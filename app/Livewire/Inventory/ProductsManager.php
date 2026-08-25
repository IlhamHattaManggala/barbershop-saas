<?php

namespace App\Livewire\Inventory;

use App\Models\Product;
use App\Models\StockMovement;
use Livewire\Component;

class ProductsManager extends Component
{
    public $name = '';

    public $category = 'Pomade';

    public $price = 0;

    public $stock = 10;

    public $min_stock = 3;

    public $description = '';

    public $search = '';

    public $success_message = '';

    // Edit Product State
    public $editing_product_id = null;

    public $edit_name = '';

    public $edit_category = 'Pomade';

    public $edit_price = 0;

    public $edit_stock = 10;

    public $edit_min_stock = 3;

    public $edit_description = '';

    // Delete Product State
    public $deleting_product_id = null;

    public $deleting_product_name = '';

    // Stock Adjustment State
    public $adj_product_id = null;
    public $adj_product_name = '';
    public $adj_type = 'purchase'; // purchase, adjustment, return
    public $adj_qty = 1;
    public $adj_notes = '';

    public function createProduct()
    {
        $this->validate([
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $tenantId = auth()->user()->tenant_id ?? 1;

        $product = Product::create([
            'tenant_id' => $tenantId,
            'name' => $this->name,
            'category' => $this->category,
            'price' => $this->price,
            'stock' => $this->stock,
            'min_stock' => $this->min_stock,
            'description' => $this->description,
            'is_active' => true,
        ]);

        if ($this->stock > 0) {
            StockMovement::create([
                'tenant_id' => $tenantId,
                'product_id' => $product->id,
                'type' => 'purchase',
                'quantity' => $this->stock,
                'notes' => 'Stok awal produk baru',
                'created_by' => auth()->id(),
            ]);
        }

        $this->success_message = "Produk '{$this->name}' Berhasil Ditambahkan!";
        $this->reset(['name', 'price', 'stock', 'description']);
    }

    public function editProduct($id)
    {
        $product = Product::findOrFail($id);
        $this->editing_product_id = $product->id;
        $this->edit_name = $product->name;
        $this->edit_category = $product->category;
        $this->edit_price = $product->price;
        $this->edit_stock = $product->stock;
        $this->edit_min_stock = $product->min_stock;
        $this->edit_description = $product->description ?? '';
    }

    public function updateProduct()
    {
        $this->validate([
            'edit_name' => 'required|string',
            'edit_price' => 'required|numeric|min:0',
            'edit_stock' => 'required|integer|min:0',
        ]);

        $product = Product::findOrFail($this->editing_product_id);
        $oldStock = $product->stock;
        $newStock = (int) $this->edit_stock;

        $product->update([
            'name' => $this->edit_name,
            'category' => $this->edit_category,
            'price' => $this->edit_price,
            'stock' => $newStock,
            'min_stock' => $this->edit_min_stock,
            'description' => $this->edit_description,
        ]);

        if ($newStock !== $oldStock) {
            $diff = $newStock - $oldStock;
            StockMovement::create([
                'tenant_id' => $product->tenant_id,
                'product_id' => $product->id,
                'type' => 'adjustment',
                'quantity' => $diff,
                'notes' => 'Penyesuaian stok via edit produk',
                'created_by' => auth()->id(),
            ]);
        }

        $this->success_message = "Produk '{$product->name}' Berhasil Diperbarui!";
        $this->reset(['editing_product_id', 'edit_name', 'edit_price', 'edit_stock', 'edit_description']);
    }

    public function openStockAdjustmentModal($productId)
    {
        $product = Product::findOrFail($productId);
        $this->adj_product_id = $product->id;
        $this->adj_product_name = $product->name;
        $this->adj_type = 'purchase';
        $this->adj_qty = 1;
        $this->adj_notes = '';
    }

    public function submitStockAdjustment()
    {
        $this->validate([
            'adj_qty' => 'required|integer|not_in:0',
        ]);

        $product = Product::findOrFail($this->adj_product_id);
        $qtyChange = (int) $this->adj_qty;

        if ($this->adj_type === 'return' && $qtyChange > 0) {
            $qtyChange = -$qtyChange;
        }

        $newStock = max(0, $product->stock + $qtyChange);
        $product->update(['stock' => $newStock]);

        StockMovement::create([
            'tenant_id' => $product->tenant_id,
            'product_id' => $product->id,
            'type' => $this->adj_type,
            'quantity' => $qtyChange,
            'notes' => $this->adj_notes ?: 'Penyesuaian stok manual',
            'created_by' => auth()->id(),
        ]);

        $this->success_message = "Stok '{$product->name}' Berhasil Diperbarui ({$qtyChange})!";
        $this->reset(['adj_product_id', 'adj_product_name', 'adj_type', 'adj_qty', 'adj_notes']);
    }

    public function confirmDeleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $this->deleting_product_id = $product->id;
        $this->deleting_product_name = $product->name;
    }

    public function deleteProduct()
    {
        if (! $this->deleting_product_id) {
            return;
        }
        $product = Product::findOrFail($this->deleting_product_id);
        $name = $product->name;
        $product->delete();
        $this->success_message = "Produk '{$name}' Berhasil Dihapus!";
        $this->reset(['deleting_product_id', 'deleting_product_name']);
    }

    public function updateStock($productId, $newStock)
    {
        $product = Product::find($productId);
        if ($product) {
            $oldStock = $product->stock;
            $updatedStock = max(0, $newStock);
            $diff = $updatedStock - $oldStock;
            
            $product->update(['stock' => $updatedStock]);

            if ($diff !== 0) {
                StockMovement::create([
                    'tenant_id' => $product->tenant_id,
                    'product_id' => $product->id,
                    'type' => 'adjustment',
                    'quantity' => $diff,
                    'notes' => 'Quick adjustment (+/-)',
                    'created_by' => auth()->id(),
                ]);
            }
        }
    }

    public function render()
    {
        $tenantId = auth()->user()->tenant_id ?? 1;

        $products = Product::where('tenant_id', $tenantId)
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->latest()
            ->get();

        $stockMovements = StockMovement::with(['product', 'creator'])
            ->where('tenant_id', $tenantId)
            ->latest()
            ->take(15)
            ->get();

        return view('livewire.inventory.products-manager', [
            'products' => $products,
            'stockMovements' => $stockMovements,
        ])->layout('layouts.app');
    }
}
