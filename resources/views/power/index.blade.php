<!-- resources/views/power/index.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Quản trị kinh doanh') }}
        </h2>
    </x-slot>

    <div class="p-6 bg-white rounded shadow max-w-7xl mx-auto mt-4">
        <p>Chào {{ auth()->user()->name }}, đây là trang quản trị kinh doanh.</p>
    </div>
</x-app-layout>
