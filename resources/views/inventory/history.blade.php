<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transaction History for: ') . $product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Product Details</h3>
                <div class="mb-6">
                    <p><strong>SKU:</strong> {{ $product->sku ?? '-' }}</p>
                    <p><strong>Available Quantity:</strong> {{ $product->quantity }}</p>
                    <p><strong>Price:</strong> ${{ number_format($product->price, 2) }}</p>
                </div>

                <h3 class="text-lg font-semibold mb-4">Transaction History</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($transactions as $transaction)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap font-semibold {{ $transaction->type === 'add' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ ucfirst($transaction->type) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->quantity }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->notes ?? '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                        {{ $transaction->created_at->format('Y-m-d H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                        No transactions found for this product.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $transactions->links() }}
                </div>

                <div class="mt-6">
                    <a href="{{ route('inventory.index') }}"
                       class="inline-block bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        ← Back to Inventory
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
