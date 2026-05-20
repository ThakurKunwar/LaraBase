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


@extends('layouts.app')

@section('content')

<form action="{{ $brand->id ? route('brands.update', $brand->id) : route('brands.store') }}" method="POST">
    @csrf
    @if($brand->id)
        @method('PUT')
    @endif


    <label>Name:</label>
    <input class="bg-blue-200 rounded" type="text" id="name" name="name" value="{{ old('name', $brand->name) }}" placeholder="enter brand NAME">

     <label>Slug</label>
    <input type="text"  id="slug" name="slug" value="{{ old('slug', $brand->slug) }}">

     <label>Country</label>
    <input type="text" name="country" value="{{ old('country', $brand->country) }}">

    <label>Active</label>
<input type="checkbox" name="is_active" value="1" 
    {{ old('is_active', $brand->is_active) ? 'checked' : '' }}>

    
    <button type="submit" name="action" value="stay">
        {{ $brand->id ? 'Update' : 'Create' }}
    </button>
@if($brand->id)
    <button type="submit" name="action" value="back">
    Update and go back
</button>
@endif

</form>
@endsection
@push('scripts')
<script>
document.getElementById('name').addEventListener('input', function() {
    let name = this.value;
    let slug = name
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/[\s]+/g, '-');
    
    document.getElementById('slug').value = slug;
});
</script>
@endpush