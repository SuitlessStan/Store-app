@extends('layouts.app')
@section('content')
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>
        <div class="grid grid-cols-2 gap-4">
            <div class="p-6 bg-white shadow rounded-lg">
                <h2 class="text-xl font-semibold">Total Products</h2>
                <p class="text-3xl">{{ $totalProducts }}</p>
            </div>
            <div class="p-6 bg-white shadow rounded-lg">
                <h2 class="text-xl font-semibold">Total Suppliers</h2>
                <p class="text-3xl">{{ $totalSuppliers }}</p>
            </div>
        </div>
    </div>
@endsection
