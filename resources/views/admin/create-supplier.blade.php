@extends('layouts.app')
@section('content')
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-6">Add Supplier</h1>
        <form method="POST" action="{{ route('admin.suppliers.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700">Name</label>
                <input type="text" name="name" class="w-full p-2 border border-gray-300 rounded-lg" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" class="w-full p-2 border border-gray-300 rounded-lg" required>
            </div>
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-lg">Add Supplier</button>
        </form>
    </div>
@endsection
