@extends('layouts.app')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Products</h1>
    </div>

    <div class="bg-white rounded shadow p-4">
        <livewire:product-table />
    </div>
@endsection