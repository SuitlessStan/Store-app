@extends('layouts.app')
@section('content')
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-6">Add Product</h1>
        <form method="POST" action="{{ route('admin.products.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700">Name</label>
                <input type="text" name="name" class="w-full p-2 border border-gray-300 rounded-lg" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Price</label>
                <input type="number" name="price" class="w-full p-2 border border-gray-300 rounded-lg" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Quantity</label>
                <input type="number" name="quantity" class="w-full p-2 border border-gray-300 rounded-lg" required>
            </div>
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-lg">Add Product</button>
        </form>
    </div>
@endsection
