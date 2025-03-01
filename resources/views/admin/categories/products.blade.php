@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-8">
        <div class="mb-4 flex items-center">
            @if($category->image)
                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-16 h-16 mr-4 object-cover rounded">
            @endif
            <h1 class="text-2xl font-bold">Products in {{ $category->name }}</h1>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach ($products as $product)
                <div class="border p-4 rounded shadow hover:shadow-lg transition">
                    <h2 class="text-xl font-semibold">{{ $product->name }}</h2>
                    <p class="text-gray-600">{{ $product->description }}</p>
                    <p class="text-green-500 font-bold">${{ number_format($product->price, 2) }}</p>
                </div>
            @endforeach
        </div>
    </div>
@endsection
