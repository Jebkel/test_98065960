@extends('layouts.app')

@section('title', 'Выбор города')

@section('content')

    <div class="container mx-auto py-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
            @foreach($arias as $area)
                <a href="{{ route('index', $area->alt_name) }}"
                   class="@if($area->id === request()->get('city')->id) font-bold @endif block px-4 py-2 bg-white shadow-md rounded-md hover:bg-gray-200 transition-colors duration-300">{{ $area->name  }}</a>
            @endforeach

        </div>
        <div class="flex justify-center mt-8">
            {{ $arias->links('vendor.pagination.simple-tailwind') }}
        </div>

    </div>

@endsection