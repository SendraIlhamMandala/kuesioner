<x-app-layout>
    @section('title', 'Dashboard Kuesioner')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <form id="myForm" method="POST" action="{{ route('kuesionerDashboard.store', $mahasiswa->nimhs) }}">
                @csrf
                @foreach ($kelas_kuesioner as $data_kelas)
                    <div class="    my-4 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <table>
                                <thead>
                                    <tr>
                                        <td>
                                            <div  class=" title mt-2 text-2xl font-bold text-blue-600 bg-blue-100 text-center w-100 " style="width:100%">
                                                KUESIONER {{ $kelas[$data_kelas] }}
                                            </div>
                                            <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">
                                        </td>
                                    </tr>

                                </thead>
                                <tbody>




                                    <tr>
                                        <td>


                                            @foreach ($kuesioner->where('klkues', $data_kelas)->pluck('keter', 'kdkues') as $kdkues => $keter)
                                    <tr>
                                        <td>


                                            <div class=" mb-2 mt-6">

                                                <span class=" font-semibold ">
                                                    {{ $loop->index + 1 }}.
                                                    {{ $keter }}
                                                </span>
                                            </div>
                                            <div>
                                                <h4>Pilih salah satu :</h4>
                                                <div>

                                                    <input type="radio"
                                                        id="1[{{ $kdkues }}][{{ $data_kelas }}]"
                                                        name="skor_sl[{{ $kdkues }}][{{ $data_kelas }}]"
                                                        value="1" required>
                                                    <label for="1[{{ $kdkues }}][{{ $data_kelas }}]">Sangat
                                                        Tidak
                                                        Setuju</label><br>
                                                    <input type="radio"
                                                        id="2[{{ $kdkues }}][{{ $data_kelas }}]"
                                                        name="skor_sl[{{ $kdkues }}][{{ $data_kelas }}]"
                                                        value="2" required>
                                                    <label for="2[{{ $kdkues }}][{{ $data_kelas }}]">Tidak
                                                        Setuju</label><br>
                                                    <input type="radio"
                                                        id="3[{{ $kdkues }}][{{ $data_kelas }}]"
                                                        name="skor_sl[{{ $kdkues }}][{{ $data_kelas }}]"
                                                        value="3" required>
                                                    <label
                                                        for="3[{{ $kdkues }}][{{ $data_kelas }}]">Setuju</label><br>
                                                    <input type="radio"
                                                        id="4[{{ $kdkues }}][{{ $data_kelas }}]"
                                                        name="skor_sl[{{ $kdkues }}][{{ $data_kelas }}]"
                                                        value="4" required>
                                                    <label for="4[{{ $kdkues }}][{{ $data_kelas }}]">Sangat
                                                        Setuju</label>
                                                </div>

                                            </div>
                                        <td>
                                            {{ $trkuesl->where('klkues', $data_kelas)->where('kdkues', $kdkues)->first()->skor }}
                                        </td>

                                        </td>
                                    </tr>
                @endforeach

                </td>

                </tr>

                </tbody>
                </table>
        </div>
    </div>
    @endforeach

    @foreach ($kelas_matakuliah as $matkul)
        <div class="    my-4 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <table>
                    <thead>
                        <tr>
                            <td>


                                <div class=" title mt-2 text-2xl font-bold text-blue-600 bg-blue-100 text-center w-100 " style="width:100%">
                                    KUESIONER Program Studi: {{ $matakuliah->where('kdkmk', $matkul)->first()->nakmk }}
                                </div>
                                <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">

                            </td>
                        </tr>
                    </thead>
                    <tbody id="table">

                        @foreach ($trkuesk->where('kdkmk', $matkul) as $data)
                            <tr>

                                <td>

                                    <div class=" mb-2 mt-6">

                                        <span class=" font-semibold ">
                                            {{ $loop->index + 1 }}.
                                            {{ $kuesionerA->where('kdkues', $data->kdkues)->first()->keter }}
                                        </span>
                                    </div>
                                </td>
                                <td>

                                    {{ $trkuesk->where('kdkmk', $matkul)->where('kdkues', $data->kdkues)->first()->skor }}
                                </td>


                            </tr>
                            <tr>
                                <td>

                                    <div>
                                        <h4>Pilih salah satu :</h4>
                                        <input type="radio" id="1[{{ $data->kdkues }}][{{ $data->kdkmk }}]"
                                            name="skor_sk[{{ $data->kdkues }}][{{ $data->kdkmk }}]" value="1"
                                            required>
                                        <label for="1[{{ $data->kdkues }}][{{ $data->kdkmk }}]">Sangat Tidak
                                            Setuju</label><br>
                                        <input type="radio" id="2[{{ $data->kdkues }}][{{ $data->kdkmk }}]"
                                            name="skor_sk[{{ $data->kdkues }}][{{ $data->kdkmk }}]" value="2"
                                            required>
                                        <label for="2[{{ $data->kdkues }}][{{ $data->kdkmk }}]">Tidak
                                            Setuju</label><br>
                                        <input type="radio" id="3[{{ $data->kdkues }}][{{ $data->kdkmk }}]"
                                            name="skor_sk[{{ $data->kdkues }}][{{ $data->kdkmk }}]" value="3"
                                            required>
                                        <label for="3[{{ $data->kdkues }}][{{ $data->kdkmk }}]">Setuju</label><br>
                                        <input type="radio" id="4[{{ $data->kdkues }}][{{ $data->kdkmk }}]"
                                            name="skor_sk[{{ $data->kdkues }}][{{ $data->kdkmk }}]" value="4"
                                            required>
                                        <label for="4[{{ $data->kdkues }}][{{ $data->kdkmk }}]">Sangat Setuju</label>
                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach



    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Submit
    </button>
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
    $('#myForm').submit(function() {
        $('#loading').show();
    });

    $(document).ready(function() {
        $('#loading').hide();
    });

    var titles = $('.title');
var heights = titles.map(function() {
    return $(this).offset().top-150;
}).get();

$(window).scroll(function() {
    var windowTop = $(window).scrollTop();
    titles.each(function(i) {
        if (windowTop > heights[i]) {
            $(this).addClass('fixed top-0 left-0');
        } else {
            $(this).removeClass('fixed top-0 left-0');
        }
    });
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
