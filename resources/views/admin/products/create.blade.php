@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-8">
        <!-- Header -->
        <h1 class="text-4xl font-bold text-white mb-6">Add Product</h1>

        <!-- Form Container -->
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-lg mx-auto">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name:</label>
                    <input type="text" name="name" required
                        class="w-full mt-1 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Description Field -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description:</label>
                    <textarea name="description" rows="4"
                        class="w-full mt-1 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <!-- Category Field -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700">Category:</label>
                    <select name="category_id" required
                        class="w-full mt-1 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select a Category</option>
                        @foreach($categories as $category)
                          <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Brand Field -->
                <div>
                    <label for="brand" class="block text-sm font-medium text-gray-700">Brand:</label>
                    <input type="text" name="brand"
                        class="w-full mt-1 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Stock Quantity Field -->
                <div>
                    <label for="stock_quantity" class="block text-sm font-medium text-gray-700">Stock Quantity:</label>
                    <input type="number" name="stock_quantity" required
                        class="w-full mt-1 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Price Field -->
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700">Price:</label>
                    <input type="number" step="0.01" name="price" required
                        class="w-full mt-1 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Discount Field -->
                <div class="form-group">
                    <label for="discount" class="block text-sm font-medium text-gray-700">Discount (%):</label>
                    <input type="number" step="0.01" name="discount" value="0"
                        class="w-full mt-1 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Image Upload Field -->
                <div>
                    <label for="product_image" class="block text-sm font-medium text-gray-700">Image:</label>
                    <input type="file" name="product_image"
                        class="w-full mt-1 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                        class="w-full bg-green-500 text-white py-3 px-6 rounded-lg shadow-md hover:bg-green-600 transition duration-300">
                        Add Product
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
