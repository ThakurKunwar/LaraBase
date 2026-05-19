<div>
    {{-- search and perpage --}}
    <input type="text" wire:model.live="search" placeholder="Search products...">

    <select wire:model.live="perPage">
        <option value="5">5</option>
        <option value="15">15</option>
        <option value="25">25</option>
        <option value="50">50</option>
    </select>

    {{-- table --}}
    <table>
        <thead>
            <tr>
                <th wire:click="setOrder('name')" style="cursor:pointer">
                    Name {{ $orderField === 'name' ? ($orderDirection === 'asc' ? '↑' : '↓') : '' }}
                </th>
                <th wire:click="setOrder('price')" style="cursor:pointer">
                    Price {{ $orderField === 'price' ? ($orderDirection === 'asc' ? '↑' : '↓') : '' }}
                </th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->price }}</td>
                <td>{{ $product->category->name }}</td>
                <td>
                    <a href="{{ route('products.edit', $product->id) }}">Edit</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $products->links() }}
</div>