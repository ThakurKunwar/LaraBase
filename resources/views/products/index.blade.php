{{-- resources/views/products/index.blade.php --}}
<h1>Products List</h1>

@foreach($products as $product)
    <p>{{ $product->name }}</p>
    <p>{{$product->price}}</p>
    <p>{{$product->category->name}}</p>
@endforeach