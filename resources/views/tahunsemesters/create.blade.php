<x-app-layout>
    @section('title', 'Kuesioner')
    <x-slot name="header">


    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('tahunsemesters.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="thsms" class="block text-gray-700 text-sm font-bold mb-2">Tahun Semester:</label>
                    <input type="text" id="thsms" name="thsms" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Masukkan tahun semester">
                </div>
                <div class="flex items-center justify-center">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Submit</button>
                </div>
            </form>

        </div>
    </div>



</x-app-layout>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
