<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * Display the inventory management page
     */
    public function index()
    {
        $products = Product::orderBy('name')->get();
        return view('inventory.index', compact('products'));
    }

    /**
     * Store a new inventory transaction (add or use stock)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:add,use',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $product = Product::lockForUpdate()->findOrFail($validated['product_id']);

                // Check if we have enough stock for 'use' transactions
                if ($validated['type'] === 'use' && $product->quantity < $validated['quantity']) {
                    throw new \Exception('Insufficient quantity available. Current stock: ' . $product->quantity);
                }

                // Create transaction record
                ProductTransaction::create($validated);

                // Update product quantity
                if ($validated['type'] === 'add') {
                    $product->quantity += $validated['quantity'];
                } else {
                    $product->quantity -= $validated['quantity'];
                }

                $product->save();
            });

            $message = $validated['type'] === 'add'
                ? 'Stock added successfully!'
                : 'Stock removed successfully!';

            return redirect()->route('inventory.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->route('inventory.index')
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display transaction history for a specific product
     */
//     public function history($productId)
//     {
//         $product = Product::findOrFail($productId);
//         $transactions = ProductTransaction::where('product_id', $productId)
//             ->orderBy('created_at', 'desc')
//             ->paginate(20);

//         return view('inventory.history', compact('product', 'transactions'));
//     }

//     /**
//      * Display all recent transactions
//      */
//     public function transactions()
//     {
//         $transactions = ProductTransaction::with('product')
//             ->orderBy('created_at', 'desc')
//             ->paginate(50);

//         return view('inventory.transactions', compact('transactions'));
//     }
}
