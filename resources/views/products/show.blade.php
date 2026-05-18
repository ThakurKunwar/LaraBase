@if(session('success'))
    <p>{{ session('success') }}</p>
@endif

@if(session('error'))
    <p>{{ session('error') }}</p>
@endif

<a href="{{ route('products.index') }}">Back</a>

<p>{{ $product->name }}</p>
<p>{{ $product->price }}</p>
<p>{{ $product->category->name }}</p>

<a href="{{ route('products.edit', $product->id) }}">Edit</a>