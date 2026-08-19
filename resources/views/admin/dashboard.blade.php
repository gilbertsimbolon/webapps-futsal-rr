@extends('admin.layouts.app')

@section('title', 'Dashboard | bkngftsl.')

@section('content')
    <!-- Card Angka Ringkasan -->
    <div class="card p-6 bg-white rounded-lg shadow">
        <h4 class="text-gray-500 text-sm">Total Owner</h4>
        <p class="text-3xl font-bold">{{ $owners }}</p>
    </div>
@endsection
