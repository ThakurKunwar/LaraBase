{{-- show errors --}}
@if($errors->any())
    @foreach($errors->all() as $error)
        <p>{{ $error }}</p>
    @endforeach
@endif

<form action="{{ $product->id 
    ? route('products.update', $product->id) 
    : route('products.store') }}" method="POST">

    @csrf
    @if($product->id)
        @method('PUT')
    @endif

    <input type="text" name="name" value="{{ old('name', $product->name) }}">
    
    <input type="number" name="price" value="{{ old('price', $product->price) }}">

    {{-- category dropdown --}}
    <select name="category_id">
        @foreach($categories as $category)
            <option value="{{ $category->id }}" 
                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>

    <button type="submit">
        {{ $product->id ? 'Update' : 'Create' }}
    </button>
</form>