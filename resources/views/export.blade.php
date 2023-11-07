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
             
        </div>
    </div>

  


</x-app-layout>
