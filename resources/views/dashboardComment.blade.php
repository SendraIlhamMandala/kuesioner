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

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <form id="myForm" method="POST" action="{{ route('dashboardComment.store', $mahasiswa->nimhs) }}">
                @csrf
                @foreach ($kelas_kuesioner as $data_kelas)
                    <div class=" parent w-full  my-4 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 w-full text-gray-900 dark:text-gray-100">
                            <table class="table w-full">
                                <thead>
                                    <tr>
                                        <td>
                                            <div>

                                                <div
                                                    class=" title  mb-4 w-full text-center bg-amber-500 p-4 text-base leading-5 text-white opacity-100">
                                                    Pesan dan Kritik untuk  {{ $kelas[$data_kelas] }}
                                                </div>
                                                <div class="border-2 border-amber-200">

                                                </div>
                                            </div>
                                            <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
                                        </td>
                                    </tr>

                                </thead>
                                <tbody>




                                    <tr>
                                        <td>


                                            
<label for="message['klkues'][{{ $data_kelas }}]" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pesan dan kritik</label>
<textarea id="message['klkues'][{{ $data_kelas }}]" name="klkues[{{ $data_kelas }}]" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Tulis pesan untuk {{ $kelas[$data_kelas] }} disini..."></textarea>


                                        </td>

                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach

                @foreach ($kelas_matakuliah as $matkul)
                    <div class=" w-full parent  my-4 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="w-full p-6 text-gray-900 dark:text-gray-100">
                            <table class="table w-full">
                                <thead>
                                    <tr>
                                        <td>

                                            <div
                                                class=" title  mb-4 w-full text-center bg-amber-500 p-4 text-base leading-5 text-white opacity-100">
                                                KUESIONER Program Studi:
                                                {{ $matakuliah->where('kdkmk', $matkul)->first()->nakmk }}
                                            </div>
                                            {{-- <div class=" title mt-2 text-2xl font-bold text-blue-600 bg-blue-100 text-center w-100 " style="width:100%">
                                    KUESIONER Program Studi: {{ $matakuliah->where('kdkmk', $matkul)->first()->nakmk }}
                                </div> --}}
                                            <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">

                                        </td>
                                    </tr>
                                </thead>
                                <tbody id="table">

                                 
                                    <tr>
                                        <td>


                                            
<label for="message['kdkmk'][{{ $matkul }}]" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pesan dan kritik</label>
<textarea id="message['kdkmk'][{{ $matkul }}]" name="kdkmk[{{ $matkul }}]" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Tulis pesan untuk {{ $matakuliah->where('kdkmk', $matkul)->first()->nakmk }} disini..."></textarea>


                                        </td>

                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach



                @if ($kelas_kuesioner->count() > 0)
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Submit
                    </button>
                @else
                @endif
            </form>

        </div>
    </div>
    <div id="loading" class="fixed inset-0 flex justify-center items-center">
        <div class="progress">
            <div class="flex justify-center">
                <div class="progress-bar progress-bar-striped active" role="progressbar" style="width:100%">
                    <div class="loadingio-spinner-pulse-2kkny9qh84v">
                        <div class="ldio-058sko1u4vgc flex justify-center items-center">
                            <div></div>
                            <div></div>
                            <div></div>
                        </div>
                    </div>
                    <div class="text-center">
                        Memproses . . .
                    </div>
                </div>
            </div>
        </div>
    </div>



</x-app-layout>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
    // Fungsi ini dieksekusi saat formulir dengan id "myForm" dikirim
    $('#myForm').submit(function() {
        $('#loading').show(); // Tampilkan elemen loading
    });

    $(document).ready(function() {
        var titles = $('.title'); // Dapatkan semua elemen dengan class "title"
        var parents = $('.parent'); // Dapatkan semua elemen dengan class "parent"

   

        $('#loading').hide(); // Sembunyikan elemen loading
    });
</script>


<style type="text/css">
    @keyframes ldio-058sko1u4vgc-1 {
        0% {
            top: 36px;
            height: 128px
        }

        50% {
            top: 60px;
            height: 80px
        }

        100% {
            top: 60px;
            height: 80px
        }
    }

    @keyframes ldio-058sko1u4vgc-2 {
        0% {
            top: 41.99999999999999px;
            height: 116.00000000000001px
        }

        50% {
            top: 60px;
            height: 80px
        }

        100% {
            top: 60px;
            height: 80px
        }
    }

    @keyframes ldio-058sko1u4vgc-3 {
        0% {
            top: 48px;
            height: 104px
        }

        50% {
            top: 60px;
            height: 80px
        }

        100% {
            top: 60px;
            height: 80px
        }
    }

    .ldio-058sko1u4vgc div {
        position: absolute;
        width: 38px
    }

    .ldio-058sko1u4vgc div:nth-child(1) {
        left: 31px;
        background: #93dbe9;
        animation: ldio-058sko1u4vgc-1 1s cubic-bezier(0, 0.5, 0.5, 1) infinite;
        animation-delay: -0.2s
    }

    .ldio-058sko1u4vgc div:nth-child(2) {
        left: 81px;
        background: #689cc5;
        animation: ldio-058sko1u4vgc-2 1s cubic-bezier(0, 0.5, 0.5, 1) infinite;
        animation-delay: -0.1s
    }

    .ldio-058sko1u4vgc div:nth-child(3) {
        left: 131px;
        background: #5e6fa3;
        animation: ldio-058sko1u4vgc-3 1s cubic-bezier(0, 0.5, 0.5, 1) infinite;
        animation-delay: undefineds
    }

    .loadingio-spinner-pulse-2kkny9qh84v {
        width: 200px;
        height: 200px;
        display: inline-block;
        overflow: hidden;
        background: none;
    }

    .ldio-058sko1u4vgc {
        width: 100%;
        height: 100%;
        position: relative;
        transform: translateZ(0) scale(1);
        backface-visibility: hidden;
        transform-origin: 0 0;
        /* see note above */
    }

    .ldio-058sko1u4vgc div {
        box-sizing: content-box;
    }

    /* generated by https://loading.io/ */

    .stickyx {
        position: fixed;
        top: 0;
        width: 100%;
    }
</style>
