@extends('layouts.app')
@section('content')
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-4">Products in {{ $category->name }}</h1>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach ($products as $product)
                <div class="border p-4 rounded shadow">
                    <h2 class="text-xl font-semibold">{{ $product->name }}</h2>
                    <p class="text-gray-600">{{ $product->description }}</p>
                    <p class="text-green-500 font-bold">${{ $product->price }}</p>
                </div>
            @endforeach
        </div>
    </div>
@endsection
