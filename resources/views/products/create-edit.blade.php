@if($errors->any())
    @foreach($errors->all() as $error)
        <p>{{ $error }}</p>
    @endforeach
@endif

@if(session('success'))
    <p>{{ session('success') }}</p>
@endif

@if(session('error'))
    <p>{{ session('error') }}</p>
@endif

<form action="{{ $product->id ? route('products.update', $product->id) : route('products.store') }}" method="POST">
    @csrf
    @if($product->id)
        @method('PUT')
    @endif

    <label>Name</label>
    <input type="text" name="name" value="{{ old('name', $product->name) }}">

    <label>Price</label>
    <input type="number" name="price" value="{{ old('price', $product->price) }}">

    <label>Category</label>
    <select name="category_id">
        @foreach($categories as $category)
            <option value="{{ $category->id }}"
                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>

    <button type="submit" name="action" value="stay">
        {{ $product->id ? 'Update' : 'Create' }}
    </button>
@if($product->id)
    <button type="submit" name="action" value="back">
    Save and go back
</button>
@endif

</form>
