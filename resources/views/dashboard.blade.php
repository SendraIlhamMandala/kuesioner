<x-app-layout>
    @section('title', 'Kuesioner')
    <x-slot name="header">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

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


            @foreach ($kelas_kuesioner as $data_kelas)
                <div class=" parent w-full  my-4 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 w-full text-gray-900 dark:text-gray-100">
                        <div
                            class=" title  mb-4 w-full text-center bg-amber-500 p-4 text-base leading-5 text-white opacity-100 rounded-lg">
                            KUESIONER {{ $kelas[$data_kelas] }}
                        </div>
                        <form id = "form_{{ $data_kelas }}">


                            <table class="table w-full">
                                <thead>
                                    <tr>
                                        <td>
                                            <div>


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
                                                        name="{{ $kdkues }}" value="1" required>
                                                    <label for="1[{{ $kdkues }}][{{ $data_kelas }}]">Sangat
                                                        Tidak
                                                        Setuju</label><br>
                                                    <input type="radio"
                                                        id="2[{{ $kdkues }}][{{ $data_kelas }}]"
                                                        name="{{ $kdkues }}" value="2" required>
                                                    <label for="2[{{ $kdkues }}][{{ $data_kelas }}]">Tidak
                                                        Setuju</label><br>
                                                    <input type="radio"
                                                        id="3[{{ $kdkues }}][{{ $data_kelas }}]"
                                                        name="{{ $kdkues }}" value="3" required>
                                                    <label
                                                        for="3[{{ $kdkues }}][{{ $data_kelas }}]">Setuju</label><br>
                                                    <input type="radio"
                                                        id="4[{{ $kdkues }}][{{ $data_kelas }}]"
                                                        name="{{ $kdkues }}" value="4" required>
                                                    <label for="4[{{ $kdkues }}][{{ $data_kelas }}]">Sangat
                                                        Setuju</label>
                                                </div>

                                            </div>
                                            {{-- <td>
                                            {{ $trkuesl->where('klkues', $data_kelas)->where('kdkues', $kdkues)->first()->skor }}
                                        </td> --}}

                                        </td>
                                    </tr>
            @endforeach

            </td>

            </tr>

            </tbody>
            </table>
            <button class=" mt-4 text-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                style="width: 100%">
                Simpan
            </button>

            </form>

        </div>
    </div>
    @endforeach

    @foreach ($kelas_matakuliah as $matkul)
        <div class=" w-full parent  my-4 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="w-full p-6 text-gray-900 dark:text-gray-100">

                <div
                    class=" title  mb-4 w-full text-center bg-amber-500 p-4 text-base leading-5 text-white opacity-100">
                    KUESIONER Program Studi: {{ $matakuliah->where('kdkmk', $matkul)->first()->nakmk }}
                </div>
                <form id = "formsk_{{ $matkul }}">

                    <table class="table w-full">
                        <thead>
                            <tr>
                                <td>


                                    {{-- <div class=" title mt-2 text-2xl font-bold text-blue-600 bg-blue-100 text-center w-100 " style="width:100%">
                                    KUESIONER Program Studi: {{ $matakuliah->where('kdkmk', $matkul)->first()->nakmk }}
                                </div> --}}
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
                                    {{-- <td>

                                    {{ $trkuesk->where('kdkmk', $matkul)->where('kdkues', $data->kdkues)->first()->skor }}
                                </td> --}}


                                </tr>
                                <tr>
                                    <td>

                                        <div>
                                            <h4>Pilih salah satu :</h4>
                                            <input type="radio" id="1[{{ $data->kdkues }}][{{ $data->kdkmk }}]"
                                                name="{{ $data->kdkues }}" value="1" required>
                                            <label for="1[{{ $data->kdkues }}][{{ $data->kdkmk }}]">Sangat Tidak
                                                Setuju</label><br>
                                            <input type="radio" id="2[{{ $data->kdkues }}][{{ $data->kdkmk }}]"
                                                name="{{ $data->kdkues }}" value="2" required>
                                            <label for="2[{{ $data->kdkues }}][{{ $data->kdkmk }}]">Tidak
                                                Setuju</label><br>
                                            <input type="radio" id="3[{{ $data->kdkues }}][{{ $data->kdkmk }}]"
                                                name="{{ $data->kdkues }}" value="3" required>
                                            <label
                                                for="3[{{ $data->kdkues }}][{{ $data->kdkmk }}]">Setuju</label><br>
                                            <input type="radio" id="4[{{ $data->kdkues }}][{{ $data->kdkmk }}]"
                                                name="{{ $data->kdkues }}" value="4" required>
                                            <label for="4[{{ $data->kdkues }}][{{ $data->kdkmk }}]">Sangat
                                                Setuju</label>
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button type="submit"
                    class=" mt-4 text-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    style="width: 100%">Simpan</button>
                        
                </form>
            </div>
        </div>
    @endforeach


    <div class=" w-full parent  my-4 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg" >
        <div class="w-full p-6 text-gray-900 dark:text-gray-100">
            <div class=" title  mb-4 w-full text-center bg-amber-500 p-4 text-base leading-5 text-white opacity-100">
            KUESIONER Penerimaan Mahasiswa Baru
        </div>
        <form id = "formhasil_hasil">


                    <input type="hidden" name="'title'" value="KUESIONER Penerimaan Mahasiswa Baru">

                    <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">

                    <div class=" mb-2 mt-6">
                        <span class=" font-semibold ">
                            1.
                            Jalur penerimaan
                        </span>
                    </div>

                    <div>
                        <h4>Pilih salah satu :</h4>
                        <select id="jalur" name="jalur"
                            class="w-full p-2 border border-gray-300 rounded-md">
                            <option value="reguler">a. Reguler</option>
                            <option value="undangan">b. Undangan</option>
                            <option value="rpl">c. RPL</option>
                            <option value="kip">d. KIP</option>
                            <option value="alumni">e. Alumni</option>
                        </select>
                    </div>

                    <div class=" mb-2 mt-6">
                        <span class=" font-semibold ">
                            2.
                            Jenis Kelamin
                        </span>
                    </div>

                    <div>
                        <h4>Pilih salah satu :</h4>
                        <select id="jenisKelamin" name="jenis kelamin"
                            class="w-full p-2 border border-gray-300 rounded-md mb-2">
                            <option value="laki-laki">a. Laki laki</option>
                            <option value="perempuan">b. Perempuan</option>
                        </select>
                    </div>

                    <div id="questionsContainer" class="mb-4"></div>

                    <div class="mb-4">
                        <fieldset>
                            <legend class="block text-sm font-semibold mb-2">Isian singkat</legend>
                            <div>
                                <label for="question13">11. Darimana anda mengetahui info PMB FISIP UNIGA</label>
                                <textarea required id="question13"
                                    name="string_Darimana anda mengetahui info PMB FISIP UNIGA"
                                    class="w-full p-2 border border-gray-300 rounded-md mb-2" maxlength="500"></textarea>
                                    <div class="text-right text-xs text-gray-500">
                                        <span id="count-13"></span>/500
                                    </div>
                            </div>
                            <div>
                                <label for="question14">12. Alasan anda memilih FISIP UNIGA</label>
                                <textarea required id="question14" name="string_Alasan anda memilih FISIP UNIGA"
                                    class="w-full p-2 border border-gray-300 rounded-md mb-2" maxlength="500"></textarea>
                                    <div class="text-right text-xs text-gray-500">
                                        <span id="count-14"></span>/500
                                    </div>
                            </div>
                            <div>
                                <label for="question15">13. Saran dan Harapan Anda tentang Layanan PMB FISIP
                                    UNIGA</label>
                                <textarea required id="question15" name="string_Saran dan Harapan Anda tentang Layanan PMB FISIP UNIGA"
                                    class="w-full p-2 border border-gray-300 rounded-md resize-none" maxlength="1000"></textarea>
                                <div class="text-right text-xs text-gray-500">
                                    <span id="count-15"></span>/1000
                                </div>
                                <script>
                                    const textareaIds = ['13', '14', '15'];
                                    textareaIds.forEach(id => {
                                        const textarea = document.getElementById(`question${id}`);
                                        const countEl = document.getElementById(`count-${id}`);
                                        console.log(countEl);
                                        const updateCount = () => countEl.textContent = textarea.value.length;
                                        textarea.addEventListener('input', updateCount);
                                    });

                                    
                                </script>
                            </div>
                        </fieldset>
                    </div>

                <button type="submit" class=" mt-4 text-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                style="width: 100%">
                    Simpan
                </button>
        </form>
    </div>

    </div>


    {{-- @if ($kelas_kuesioner->count() > 0)
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Submit
        </button>
    @else --}}
        <button onclick="window.location.reload();" class=" mt-4 text-center bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
        style="width: 100%">
            Lanjut 
        </button>
    {{-- @endif --}}

    </div>
    </div>
    <div id="loading" class="fixed inset-0 w-screen bg-white z-50 flex justify-center items-center rounded-lg shadow-lg"
        style="left: 25%; top: 25%; width: 50%; height: max-content; ">
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
                    <div class="text-center mb-6">
                        Memproses . . .
                    </div>
                </div>
            </div>
        </div>
    </div>



</x-app-layout>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        const questions = [
            "Media dan Informasi Penerimaan Mahasiswa Baru (PMB)",
            "Ketersediaan media PMB (Brosur/Pamflet/Flyer/Spanduk/Baliho)",
            "Kemudahan memahami informasi PMB (Brosur/Pamflet/Flyer/Spanduk/Baliho)",
            "Desain media PMB (Brosur/Pamflet/Flyer/Spanduk/Baliho)",
            "Prosedur dan alur layanan PMB",
            "Website PMB",
            "Informasi biaya kuliah",
            "Road Show"
        ];

        const questionsContainer = document.getElementById('questionsContainer');

        // Loop through questions and create radio button sets dynamically
        questions.forEach((question, index) => {
            const fieldset = document.createElement('fieldset');
            fieldset.innerHTML = `
          <legend class="block text-sm font-semibold mb-2">${index + 3}. ${question}</legend>
          <div class="space-y-2">
            <div>
              <input required type="radio" id="${question}_sangat_baik" name="range_${question}" value="sangat_baik" class="mr-2">
              <label for="${question}_sangat_baik">Sangat Baik</label>
            </div>
            <div>
              <input required type="radio" id="${question}_cukup" name="range_${question}" value="cukup" class="mr-2">
              <label for="${question}_cukup">Cukup</label>
            </div>
            <div>
              <input required type="radio" id="${question}_kurang" name="range_${question}" value="kurang" class="mr-2">
              <label for="${question}_kurang">Kurang</label>
            </div>
          </div>
        `;
            questionsContainer.appendChild(fieldset);
        });
    });
</script>

<script>
    // Fungsi ini dieksekusi saat formulir dengan id "myForm" dikirim
    $('#myForm').submit(function() {
        $('#loading').show(); // Tampilkan elemen loading
    });
    const classProgress = @json($kelas_progress);
    const courseProgress = @json($kdkmk_progress);
    const statusProgress = @json($status_progress);
    //console.log(classProgress, courseProgress, statusProgress);

    $(document).ready(function() {
        // $('#loading').show(); // Tampilkan elemen loading

        markProgress(classProgress, 'form_');
        markProgress(courseProgress, 'formsk_');
        markProgress(statusProgress, 'formhasil_');
        // markProgress(courseProgress, 'formhasil_');
        showTitle();
        // $('#loading').show(); // Tampilkan elemen loading


});

    function markProgress(progress, selectorPrefix) {
        if (progress && progress.length > 0) {
            progress.forEach((id) => {
                const form = $(`#${selectorPrefix}${id}`);
                form.closest('.parent').find('.title').css('background-color', 'green');
                form.hide();
            });
        }
    }

    

    $('form').on('submit', function(e) {


        e.preventDefault(); // Prevent the form from submitting by default
        var formId = $(this).attr('id'); // Get the form's id

        var isValid = true;
        var formIdParts = formId.split('_');
        //console.log(formId);

        //console.log(formIdParts);
        // return false;
        var kelas = formIdParts[formIdParts.length - 1];
        $(this).find('input[required]').each(function() {
            if ($(this).val() === '') { // If a required field is empty
                isValid = false;
                $('html, body').animate({
                    scrollTop: $(this).offset().top // Scroll to the first empty required input
                }, 1000);
                return false; // Break out of the .each() loop
            }
            return false;
        });

        if (isValid) {
            $('#loading').show(); // Tampilkan elemen loading

            save_kues(formId, kelas, formIdParts[0]);
            // All required inputs are filled, you can do your AJAX call here
            return false; // Return false to prevent the form from submitting in old browsers

        } else {
            return false; // Return false to prevent the form from submitting in old browsers
        }
    });



    function save_kues(data, kelas, jenis) {
        // //console.log(data, kelas, jenis);
        // return false;
        const form = document.getElementById(data);
        // //console.log(form,form.closest('.parent'));
        const form_child_title = form.closest('.parent').querySelector(
            '.title'); // Use querySelector to directly get the title element
        // //console.log(form_child_title);
        const formData = new FormData(form);
        const kues = Object.fromEntries(formData.entries());
        const kuesJson = JSON.stringify(kues); // Convert kues object to JSON string
        //console.log(formData.entries(), kuesJson);
        // const route = jenis === 'form' ? "{{ route('save_kues_sl') }}" : "{{ route('save_kues_sk') }}";
        var route = "";
        if (jenis === 'form') {
            route = "{{ route('save_kues_sl') }}";
        } else if (jenis === 'formsk') {
            route = "{{ route('save_kues_sk') }}";
        } else if (jenis === 'formhasil') {
            route = "{{ route('save_kues_hasil') }}";
            //console.log('route 1',route);
        }
        // //console.log('route 2',route);

        $.ajax({
            type: "POST",
            url: route,
            data: {
                "_token": "{{ csrf_token() }}",
                "kues": kuesJson,
                "kelas": JSON.stringify(kelas)
            },

            success: function(response) {
                form_child_title.style.backgroundColor = 'green'; // Use style property to change the background color to green

                //console.log(response);
                // alert('Data berhasil disimpan');
                $('#loading').hide(); // Tampilkan elemen loading

                createSuccessAlert(response);



                form.style.transition =
                    ' opacity 1s ease-in-out, transform 1s ease-in-out'; // Add transition property
                form.style.transform = 'translateY(-100%)'; // Translate to the right
                form.style.opacity = '0';

                setTimeout(() => {
                    form.style.display = 'none'; // Hide the form after the transition ends
                    window.scroll({
                        top: form_child_title.offsetTop - 100,
                        left: 0,
                        behavior: 'smooth' // Smooth scroll animation
                    });
                    showTitle();


                }, 1000); // 0.5s transition duration

                if (jenis === 'formhasil') {
                    // location.reload();
                }

                // form.style.display = 'none'; // Hide the form after a successful AJAX call
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $('#loading').hide(); // Tampilkan elemen loading

                //console.log(textStatus, errorThrown);
            },
        })
    }

    function showTitle() {
        const titles = $('.title'); // Dapatkan semua elemen dengan class "title"
        const parents = $('.parent'); // Dapatkan semua elemen dengan class "parent"

        // Dapatkan jarak vertikal setiap elemen title dari atas dokumen
        var heights = titles.map(function() {
            return $(this).offset().top;
        }).get();

        // Dapatkan jarak vertikal setiap elemen parent dari atas dokumen, termasuk tingginya
        var parentHeights = parents.map(function() {
            return $(this).offset().top + $(this).outerHeight();
        }).get();


        // Buat elemen div yang berada di bagian kanan atas (fixed)
        $('body').append(
            '<div class="title  mb-4 text-center bg-amber-500 p-4 text-base leading-5 text-white opacity-100 rounded-lg shadow-lg mx-4 " id="fixed-title" style="position: fixed; top: 10px; right: 10px; z-index: 9999; display: none ">asdasdasd</div>'
        );


        $(window).scroll(function() {

            // Get the current vertical position of the scroll bar
            var windowTop = $(window)
                .scrollTop();


            // Iterate through each title element
            titles.each(function(i) {
                // Check if the current title is in the visible area
                if (windowTop > heights[i] && windowTop < parentHeights[i]) {
                    //insert title to fixed title
                    $('#fixed-title').html(titles[i].innerText);
                    $('#fixed-title').css({
                        'cssText': titles[i].style.cssText +
                            'display:inline-block;position:fixed;top:10px;right:10px;z-index:9999;'
                    });
                    $('#fixed-title').show();

                } else if (windowTop < heights[0]) {
                    $('#fixed-title').hide();

                }
                // //console.log(`windowTop: ${windowTop} > heights[${i}]: ${heights[i]} && windowTop: ${windowTop} < parentHeights[${i}]: ${parentHeights[i]}`);


            })
        })




        $('#loading').hide(); // Sembunyikan elemen loading
    }

    function createSuccessAlert(res) {
        var successElement = document.createElement('div');
        successElement.classList.add('fixed', 'inset-0', 'flex', 'items-center', 'justify-center',
            'p-4', 'rounded', 'text-'+res.color+'-800', 'bg-'+res.color+'-200', 'w-1/2', 'h-20', 'm-auto',
            'z-50');
        successElement.textContent = res.message;

        // Create a wrapper to center the success message
        var wrapper = document.createElement('div');
        wrapper.classList.add('absolute', 'w-full', 'h-full', 'flex', 'items-center',
            'justify-center');
        wrapper.appendChild(successElement);

        document.body.appendChild(wrapper);

        setTimeout(function() {
            wrapper.remove();
        }, 3000); // Remove the success element after 3 seconds
    }
    // createSuccessAlert({ message: 'Success', color: 'red' });
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
