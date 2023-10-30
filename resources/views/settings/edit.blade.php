<x-app-layout>
    @section('title', 'Kuesioner')
    <x-slot name="header">


    </x-slot>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('tahunsemesters.update', $tahunsemester->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label for="thsms" class="block text-gray-700 text-sm font-bold mb-2">Tahun Semester:</label>
                    <input type="number" id="thsms" name="thsms"
                        value="{{ $tahunsemester->thsms }}"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        placeholder="Masukkan tahun semester">
                </div>
                <div class="mb-4">
                    <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Status:</label>
                    <select id="status" name="status"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="aktif" 
                        @if ($tahunsemester->status == 'aktif') 
                        selected    
                        @endif >Aktif</option>
                        <option @if ($tahunsemester->status != 'aktif') 
                            selected    
                            @endif  value="tidak_aktif">Tidak Aktif</option>
                    </select>
                </div>
                @if (session('pesan'))
                

                <div class=" items-center bg-yellow-200 text-yellow-800 font-bold py-2 px-4 rounded">
<i class="fas fa-exclamation-circle text-yellow-500 mr-2"></i>                    
            <span>{{ session('pesan') }}. Nonaktifkan terlebih dahulu</span>
                  </div>
    
            @endif
                
                
                <div class="flex items-center justify-center">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update</button>
                </div>
            </form>

        </div>
    </div>



</x-app-layout>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
