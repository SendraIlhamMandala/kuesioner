<x-app-layout>
    @section('title', 'Kuesioner')
    <x-slot name="header">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />




    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            <div class="grid">
                <div>

                    <div
                        class="block my-4 mx-auto  p-6 bg-white border border-gray-200 rounded-lg shadow  dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                        <h5 class=" font-bold tracking-tight text-gray-900 dark:text-white">
                            Mahasiswa yang sudah mengisi kuesioner
                        </h5>
                    </div>

                    <div
                        class="block my-4 mx-auto  p-6 bg-white border border-gray-200 rounded-lg shadow  dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">

                        <table id="example" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    


                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dosen as $data)
                                    <tr onclick="location.href='{{ route('dosen.matkul', $data->nmdosen) }}';" >
                                        <td>
    
    
          {{ $data->nmdosen }}
    
</td>



                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
              
            </div>


        </div>
    </div>



</x-app-layout>
@vite(['resources/css/app.css', 'resources/js/app.js'])
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
{{-- <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script> --}}
{{-- <script src="https://cdn.tailwindcss.com"></script> --}}



<script>
    $('#example').DataTable({
        "order": [
            [0, "desc"]
        ]
    });
 
</script>
