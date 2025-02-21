@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-4xl font-bold mb-6">Products List</h1>
    <table class="min-w-full bg-white shadow-md rounded-lg">
        <thead>
            <tr>
                <th class="py-2 px-4 border-b">ID</th>
                <th class="py-2 px-4 border-b">Name</th>
                <th class="py-2 px-4 border-b">Description</th>
                <th class="py-2 px-4 border-b">Brand</th>
                <th class="py-2 px-4 border-b">Price</th>
                <th class="py-2 px-4 border-b">Discount</th>
                <th class="py-2 px-4 border-b">Image</th>
                <th class="py-2 px-4 border-b">Stock</th>
                <th class="py-2 px-4 border-b">Category</th>
                <th class="py-2 px-4 border-b">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td class="py-2 px-4 border-b">{{ $product->id }}</td>
                <td class="py-2 px-4 border-b">{{ $product->name }}</td>
                <td class="py-2 px-4 border-b">{{ $product->description }}</td>
                <td class="py-2 px-4 border-b">{{ $product->brand }}</td>
                <td class="py-2 px-4 border-b">{{ $product->price }}</td>
                <td class="py-2 px-4 border-b">{{ $product->discount }}</td>
                <td class="py-2 px-4 border-b">
                    @if($product->product_image)
                        <img src="{{ asset('storage/' . $product->product_image) }}"
                             alt="{{ $product->name }}" class="w-16 h-16 object-cover">
                    @endif
                </td>
                <td class="py-2 px-4 border-b">{{ $product->stock_quantity }}</td>
                <td class="py-2 px-4 border-b">{{ $product->category_id }}</td>
                <td class="py-2 px-4 border-b">
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="text-blue-500">Edit</a>
                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 ml-2">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
