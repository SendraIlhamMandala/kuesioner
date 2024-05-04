<x-app-layout>
    @section('title', 'Kuesioner')
 

    <div class="py-12">
        <div class="max-w-7xl  justify-center mx-auto sm:px-6 lg:px-8">


            <div class="max-w-7xl p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
              
                @foreach ($matkul as $item)
                
                <a href="{{ route('exportMatkul', $item->kdkmk)}}">
                    <div class="block my-4 mx-auto  p-6 bg-white border border-gray-200 rounded-lg shadow  dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                            <h5 class=" font-bold tracking-tight text-gray-900 dark:text-white">
                                
                                {{$item->nakmk}}
                            </h5>
                        </div>
                    </a>

                @endforeach


            </div>
             

            <div class="max-w-7xl p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
              
                @foreach ($layanan as $key => $item)
                
                <a href="{{ route('exportLayanan', $key)}}">
                    <div class="block my-4 mx-auto  p-6 bg-white border border-gray-200 rounded-lg shadow  dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                            <h5 class=" font-bold tracking-tight text-gray-900 dark:text-white">
                                
                                {{$item}}
                            </h5>
                        </div>
                    </a>

                @endforeach


            </div>

            @if (App\Models\Hasil::where('tahunsemester_id', App\Models\Tahunsemester::where('status', 'aktif')->first()->id)->exists())
                
            <div class="max-w-7xl p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                
                
                <a href="{{ route('exportHasilSurvey', App\Models\Tahunsemester::where('status', 'aktif')->first()->id)}}">
                    <div class="block my-4 mx-auto  p-6 bg-white border border-gray-200 rounded-lg shadow  dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                        <h5 class=" font-bold tracking-tight text-gray-900 dark:text-white">
                            
                            Survey Penerimaan Mahasiswa Baru
                        </h5>
                    </div>
                </a>
                
                
                
                
            </div>
            @endif
             

        </div>
    </div>

  


</x-app-layout>
