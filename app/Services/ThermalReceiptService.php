<?php

namespace App\Services;

use App\Models\Transaction;
use Mike42\Escpos\PrintConnectors\DummyPrintConnector;
use Mike42\Escpos\Printer;

class ThermalReceiptService
{
    /**
     * Generate raw ESC/POS formatted receipt text using mike42/escpos-php package.
     */
    public function generateEscposBuffer(Transaction $transaction): string
    {
        $connector = new DummyPrintConnector;
        $printer = new Printer($connector);

        $tenant = $transaction->tenant;
        $settings = $tenant->receipt_settings ?? [];
        $paperSize = $settings['paper_size'] ?? '58mm';
        $width = $paperSize === '80mm' ? 48 : 32;

        try {
            // Header
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->setEmphasis(true);
            $printer->text(($tenant->name ?? 'BARBERSHOP')."\n");
            $printer->setEmphasis(false);

            if (! empty($tenant->address)) {
                $printer->text($tenant->address."\n");
            }
            if (! empty($tenant->phone)) {
                $printer->text('Telp/WA: '.$tenant->phone."\n");
            }

            if (! empty($settings['header_text'])) {
                $printer->text($settings['header_text']."\n");
            }

            $printer->text(str_repeat('-', $width)."\n");

            // Transaction Info
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text('Nota   : '.$transaction->transaction_number."\n");
            $printer->text('Waktu  : '.$transaction->created_at->format('d/m/Y H:i')." WIB\n");
            $printer->text('Kasir  : '.($transaction->cashier?->name ?? 'Kasir')."\n");
            $printer->text('Pelanggan: '.($transaction->customer_name ?? 'Umum')."\n");
            $printer->text(str_repeat('-', $width)."\n");

            // Items
            foreach ($transaction->items as $item) {
                $itemName = $item->item_name;
                $barberName = $item->barber?->name ? ' (Barber: '.$item->barber->name.')' : '';
                $printer->text($itemName.$barberName."\n");

                $qtyPrice = ' '.$item->quantity.' x '.number_format($item->price, 0, ',', '.');
                $subtotalStr = 'Rp '.number_format($item->subtotal, 0, ',', '.');

                $padLen = max(1, $width - strlen($qtyPrice) - strlen($subtotalStr));
                $printer->text($qtyPrice.str_repeat(' ', $padLen).$subtotalStr."\n");
            }

            $printer->text(str_repeat('-', $width)."\n");

            // Totals
            $this->printTotalLine($printer, 'Subtotal:', 'Rp '.number_format($transaction->subtotal, 0, ',', '.'), $width);
            if ($transaction->discount > 0) {
                $this->printTotalLine($printer, 'Diskon:', '-Rp '.number_format($transaction->discount, 0, ',', '.'), $width);
            }
            $printer->setEmphasis(true);
            $this->printTotalLine($printer, 'TOTAL:', 'Rp '.number_format($transaction->total_amount, 0, ',', '.'), $width);
            $printer->setEmphasis(false);

            $this->printTotalLine($printer, 'Bayar ('.strtoupper($transaction->payment_method).'):', 'Rp '.number_format($transaction->cash_paid, 0, ',', '.'), $width);
            $this->printTotalLine($printer, 'Kembalian:', 'Rp '.number_format($transaction->change_due, 0, ',', '.'), $width);

            $printer->text(str_repeat('-', $width)."\n");

            // Footer
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $footerText = $settings['footer_text'] ?? 'Terima kasih atas kunjungan Anda. Harap simpan struk ini sebagai bukti pembayaran resmi.';
            $printer->text($footerText."\n");
            $printer->feed(2);

            $printer->cut();

            $data = $connector->getData();
            $printer->close();

            return $data;
        } catch (\Throwable $e) {
            return '';
        }
    }

    private function printTotalLine(Printer $printer, string $label, string $value, int $width): void
    {
        $spaceLen = max(1, $width - strlen($label) - strlen($value));
        $printer->text($label.str_repeat(' ', $spaceLen).$value."\n");
    }
}
