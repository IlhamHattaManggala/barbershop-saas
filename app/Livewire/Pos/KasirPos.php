<?php

namespace App\Livewire\Pos;

use App\Models\Product;
use App\Models\Reservation;
use App\Models\Service;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class KasirPos extends Component
{
    use WithFileUploads;

    public $cart = []; // ['id', 'type', 'name', 'price', 'qty', 'subtotal', 'barber_id']

    public $customer_name = 'Pelanggan Umum';

    public $customer_phone = '';

    public $selected_barber_id = null;

    public $reservation_id = null;

    public $payment_method = 'cash';

    public $cash_paid = 0;

    public $payment_proof_photo;

    public $base64_payment_proof = null;

    public $discount = 0;

    public $tax = 0;

    public $search = '';

    public $catalog_filter = 'all'; // 'all', 'services', 'products'

    public $showReceiptModal = false;

    public $success_message = '';

    public $last_transaction = null;

    public $discount_amount = 0;

    public $is_split_payment = false;

    public $second_payment_method = 'qris';

    public $second_payment_amount = 0;

    public function closeReceiptModal()
    {
        $this->showReceiptModal = false;
    }

    public function mount()
    {
        if (request()->has('reservation_id')) {
            $reservationId = request()->get('reservation_id');
            $reservation = Reservation::find($reservationId);
            if ($reservation) {
                $this->reservation_id = $reservation->id;
                $this->customer_name = $reservation->customer_name ?: 'Pelanggan Umum';
                $this->customer_phone = $reservation->customer_phone ?: '';
                $this->selected_barber_id = $reservation->barber_user_id;

                if ($reservation->service_id) {
                    $this->addServiceToCart($reservation->service_id);
                }

                if (in_array($reservation->status, ['pending', 'confirmed'])) {
                    $reservation->update(['status' => 'in_service']);
                }
            }
        }
    }

    public function getManualWaLinkProperty()
    {
        if (! $this->last_transaction || empty($this->last_transaction->customer_phone)) {
            return null;
        }

        $phone = preg_replace('/[^0-9]/', '', $this->last_transaction->customer_phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62'.substr($phone, 1);
        }

        $tenantName = auth()->user()->tenant->name ?? 'Barbershop';
        $message = "Halo {$this->last_transaction->customer_name},\n\nTerima kasih telah berkunjung ke {$tenantName}.\nNota: {$this->last_transaction->transaction_number}\nTotal Pembayaran: Rp ".number_format($this->last_transaction->total_amount, 0, ',', '.')."\nMetode: ".strtoupper($this->last_transaction->payment_method)."\n\nSampai jumpa di kunjungan berikutnya!";

        return 'https://wa.me/'.$phone.'?text='.urlencode($message);
    }

    public function saveBase64PaymentProof($base64Data)
    {
        $this->base64_payment_proof = $base64Data;
    }

    public function removePaymentProof()
    {
        $this->reset('payment_proof_photo', 'base64_payment_proof');
    }

    public function setCatalogFilter($filter)
    {
        $this->catalog_filter = in_array($filter, ['all', 'services', 'products']) ? $filter : 'all';
    }

    public function dismissSuccessAlert()
    {
        $this->success_message = '';
        $this->last_transaction = null;
    }

    public function addServiceToCart($serviceId)
    {
        $this->success_message = '';
        $service = Service::find($serviceId);
        if (! $service) {
            return;
        }

        $cartKey = 'service_'.$service->id;

        if (isset($this->cart[$cartKey])) {
            $this->cart[$cartKey]['qty'] += 1;
            $this->cart[$cartKey]['subtotal'] = $this->cart[$cartKey]['qty'] * $this->cart[$cartKey]['price'];
        } else {
            $this->cart[$cartKey] = [
                'id' => $service->id,
                'type' => 'service',
                'name' => $service->name,
                'price' => (float) $service->price,
                'qty' => 1,
                'subtotal' => (float) $service->price,
                'barber_id' => $this->selected_barber_id,
            ];
        }
    }

    public function addProductToCart($productId)
    {
        $this->success_message = '';
        $product = Product::find($productId);
        if (! $product || $product->stock < 1) {
            return;
        }

        $cartKey = 'product_'.$product->id;

        if (isset($this->cart[$cartKey])) {
            if ($this->cart[$cartKey]['qty'] + 1 > $product->stock) {
                return;
            }
            $this->cart[$cartKey]['qty'] += 1;
            $this->cart[$cartKey]['subtotal'] = $this->cart[$cartKey]['qty'] * $this->cart[$cartKey]['price'];
        } else {
            $this->cart[$cartKey] = [
                'id' => $product->id,
                'type' => 'product',
                'name' => $product->name,
                'price' => (float) $product->price,
                'qty' => 1,
                'subtotal' => (float) $product->price,
                'barber_id' => null,
            ];
        }
    }

    public function updateQty($cartKey, $newQty)
    {
        if ($newQty <= 0) {
            unset($this->cart[$cartKey]);

            return;
        }

        if (isset($this->cart[$cartKey])) {
            $this->cart[$cartKey]['qty'] = (int) $newQty;
            $this->cart[$cartKey]['subtotal'] = $this->cart[$cartKey]['qty'] * $this->cart[$cartKey]['price'];
        }
    }

    public function removeFromCart($cartKey)
    {
        unset($this->cart[$cartKey]);
    }

    public function clearCart()
    {
        $this->cart = [];
        $this->cash_paid = 0;
        $this->discount = 0;
        $this->discount_amount = 0;
        $this->is_split_payment = false;
        $this->second_payment_amount = 0;
        $this->reset('payment_proof_photo', 'base64_payment_proof');
    }

    public function checkout()
    {
        $this->resetErrorBag();

        if (empty($this->cart)) {
            $this->addError('cart', 'Keranjang belanja masih kosong.');

            return;
        }

        $tenantId = auth()->user()->tenant_id ?? 1;
        $subtotal = array_sum(array_column($this->cart, 'subtotal'));
        $effectiveDiscount = (float) ($this->discount_amount > 0 ? $this->discount_amount : $this->discount);
        $totalAmount = max(0, $subtotal - $effectiveDiscount + $this->tax);

        // Split payment validation
        if ($this->is_split_payment) {
            $firstPayment = (float) $this->cash_paid;
            $secondPayment = (float) $this->second_payment_amount;

            if (($firstPayment + $secondPayment) < $totalAmount) {
                $this->addError('cash_paid', 'Total kombinasi 2 pembayaran (Rp '.number_format($firstPayment + $secondPayment, 0, ',', '.').') kurang dari tagihan (Rp '.number_format($totalAmount, 0, ',', '.').').');

                return;
            }
            $cashPaid = $firstPayment;
        } else {
            if ($this->payment_method === 'cash') {
                $cashInput = (float) $this->cash_paid;
                if ($cashInput < $totalAmount) {
                    $this->addError('cash_paid', 'Uang tunai yang diterima (Rp '.number_format($cashInput, 0, ',', '.').') kurang dari total tagihan (Rp '.number_format($totalAmount, 0, ',', '.').').');

                    return;
                }
                $cashPaid = $cashInput;
            } else {
                $cashPaid = $totalAmount;
            }
        }

        if (in_array($this->payment_method, ['qris', 'transfer']) || ($this->is_split_payment && in_array($this->second_payment_method, ['qris', 'transfer']))) {
            if (empty($this->base64_payment_proof) && empty($this->payment_proof_photo)) {
                $this->addError('payment_proof', 'Bukti pembayaran (Foto Kamera/Upload File) wajib disertakan untuk metode transfer/QRIS.');

                return;
            }
        }

        $changeDue = $this->is_split_payment ? 0 : max(0, $cashPaid - $totalAmount);

        $proofPath = null;
        if ($this->base64_payment_proof) {
            $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $this->base64_payment_proof);
            $decoded = base64_decode($imageData);
            $filename = 'uploads/payment_proofs/proof_'.time().'_'.Str::random(6).'.jpg';
            Storage::disk('public')->put($filename, $decoded);
            $proofPath = 'storage/'.$filename;
        } elseif ($this->payment_proof_photo) {
            $stored = $this->payment_proof_photo->store('uploads/payment_proofs', 'public');
            $proofPath = 'storage/'.$stored;
        }

        $transaction = Transaction::create([
            'tenant_id' => $tenantId,
            'transaction_number' => 'TRX-'.date('YmdHis'),
            'customer_name' => $this->customer_name ?: 'Pelanggan Umum',
            'customer_phone' => $this->customer_phone,
            'cashier_user_id' => auth()->id(),
            'subtotal' => $subtotal,
            'tax' => $this->tax,
            'discount' => $effectiveDiscount,
            'discount_amount' => $effectiveDiscount,
            'total_amount' => $totalAmount,
            'cash_paid' => $cashPaid,
            'change_due' => $changeDue,
            'payment_method' => $this->payment_method,
            'second_payment_method' => $this->is_split_payment ? $this->second_payment_method : null,
            'second_payment_amount' => $this->is_split_payment ? $this->second_payment_amount : 0,
            'payment_proof' => $proofPath,
            'status' => 'paid',
        ]);

        foreach ($this->cart as $item) {
            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'item_type' => $item['type'],
                'service_id' => $item['type'] === 'service' ? $item['id'] : null,
                'product_id' => $item['type'] === 'product' ? $item['id'] : null,
                'barber_user_id' => $item['barber_id'] ?? $this->selected_barber_id,
                'item_name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['qty'],
                'subtotal' => $item['subtotal'],
            ]);

            // Deduct product stock & log StockMovement
            if ($item['type'] === 'product') {
                $product = Product::find($item['id']);
                if ($product) {
                    $product->decrement('stock', $item['qty']);

                    \App\Models\StockMovement::create([
                        'tenant_id' => $tenantId,
                        'product_id' => $product->id,
                        'type' => 'sale',
                        'quantity' => -$item['qty'],
                        'notes' => 'Penjualan POS Nota '.$transaction->transaction_number,
                        'created_by' => auth()->id(),
                    ]);
                }
            }
        }

        // If this transaction was generated from an Online Reservation, mark reservation as completed
        if ($this->reservation_id) {
            $reservation = Reservation::find($this->reservation_id);
            if ($reservation) {
                $reservation->update(['status' => 'completed']);
            }
        }

        $tenant = auth()->user()->tenant;
        $rSettings = $tenant ? ($tenant->receipt_settings ?? []) : [];
        $enablePrint = isset($rSettings['enable_print']) ? (bool) $rSettings['enable_print'] : true;

        $this->last_transaction = $transaction->load(['items.barber', 'cashier', 'tenant']);
        $this->showReceiptModal = $enablePrint;
        $this->success_message = 'Transaksi '.$transaction->transaction_number.' Berhasil Dibuat!';
        $this->clearCart();
    }

    public function render()
    {
        $tenantId = auth()->user()->tenant_id ?? 1;

        $services = Service::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->when($this->search, fn ($q) => $q->where('name', 'like', '%'.$this->search.'%'))
            ->get();

        $products = Product::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->when($this->search, fn ($q) => $q->where('name', 'like', '%'.$this->search.'%'))
            ->get();

        $barbers = User::where('tenant_id', $tenantId)
            ->whereIn('role', ['barber', 'owner'])
            ->get();

        $subtotal = array_sum(array_column($this->cart, 'subtotal'));
        $totalAmount = max(0, $subtotal - $this->discount + $this->tax);

        return view('livewire.pos.kasir-pos', [
            'services' => $services,
            'products' => $products,
            'barbers' => $barbers,
            'subtotal' => $subtotal,
            'totalAmount' => $totalAmount,
        ])->layout('layouts.pos');
    }
}
