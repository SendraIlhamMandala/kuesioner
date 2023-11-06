<x-app-layout>
    @section('title', 'Kuesioner')
    <x-slot name="header">


    </x-slot>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-4">

                <a href="{{ route('tahunsemesters.create') }}"
                    class=" items-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-plus-circle mr-2"></i>
                    <span>Tambah</span>
                </a>

            </div>

            <div
            class="block my-4 mx-auto  p-6 bg-white border border-gray-200 rounded-lg shadow  dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
            
            <table id="example" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>Tahun semester</th>
                        <th>Status</th>
                        <th>Action</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($tahunsemesters as $tahunsemester)
                        <tr>
                            <td>
                                <div class="mx-4" >
                                    {{ $tahunsemester->thsms }}
                                </div>
                            </td>
                            <td>
                                <div
                                    class="flex items-center my-4 mx-4 {{ $tahunsemester->status == 'aktif' ? 'bg-green-500 hover:bg-green-700' : 'bg-red-500 hover:bg-red-700' }} text-white font-bold py-2 px-4 rounded">
                                    <span>{{ $tahunsemester->status }}</span>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('tahunsemesters.edit', $tahunsemester->id) }}"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Edit</a>
                                <form action="{{ route('tahunsemesters.destroy', $tahunsemester->id) }}" method="POST"
                                    class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Delete</button>
                                </form>
                            </td>

                        </tr>
                    @endforeach
                </tbody>

            </table>
            </div>

        </div>
    </div>




</x-app-layout>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
{{-- <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script> --}}
@vite(['resources/css/app.css', 'resources/js/app.js'])
{{-- <script src="https://cdn.tailwindcss.com"></script> --}}



<script>
    $('#example').DataTable({
        "order": [
            [0, "desc"]
        ]
    });
</script>
