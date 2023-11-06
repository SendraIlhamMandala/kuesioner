<x-app-layout>
    @section('title', 'Kuesioner')
    <x-slot name="header">

        <a href="#"
            class="block mx-auto max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Nama:
                {{ Auth()->user()->nmmhs }}</h5>
            <p class="font-normal text-gray-700 dark:text-gray-400">

                <div>
                    NIM: {{ Auth()->user()->nimhs }}
                </div>
                <div>
                    Email: {{ Auth()->user()->email }}
                </div>
            </p>
        </a>



    </x-slot>
@vite(['resources/css/app.css', 'resources/js/app.js'])
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-yellow-500 text-white px-4 py-2 rounded">
                Kuesioner ditutup
            </div>
          
        </div>
    </div>


</x-app-layout>
{{-- <script src="https://cdn.tailwindcss.com/3.3.3"></script> --}}
