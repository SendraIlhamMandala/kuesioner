<x-app-layout>
    @section('title', 'Kuesioner')
    <x-slot name="header">


    </x-slot>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <div class="py-12">
        <div class="max-w-7xl flex justify-center mx-auto sm:px-6 lg:px-8">

       
<div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
 
        @php
    $isBuka = $settings->first()->is_open == 'buka';
    $buttonColor = $isBuka ? 'bg-green-500' : 'bg-red-500';
@endphp
<h5 class="mb-2 text-2xl font-semibold tracking-tight text-gray-900 dark:text-white">Status Kuesioner : <a id="button_status" href="{{ route('editKuesioner') }}" class="btn btn-primary {{ $buttonColor }} text-white  px-4 rounded">{{$settings->first()->is_open }}</a>
</h5>

    <p class="mb-3 font-normal text-gray-500 dark:text-gray-400">Buka atau tutup kuesioner untuk mahasiswa:</p>
    
<label class="relative inline-flex items-center cursor-pointer">
    <input type="checkbox" value="" class="sr-only peer" id="openCheckbox"
           @if ($settings->first()->is_open == 'buka' )
           checked
           @endif
    >
    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
    <span id="statusText" class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">{{$settings->first()->is_open == 'buka' ? 'terbuka':'tertutup' }}</span>

  </label>
  
         
       
          
</div>


       
        

        </div>
    </div>




</x-app-layout>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>
<script src="https://cdn.tailwindcss.com"></script>



<script>
    $('#example').DataTable({
        "order": [
            [0, "desc"]
        ]
    });

    const checkbox = document.getElementById('openCheckbox');
    const statusText = document.getElementById('statusText');
    const buttonStatusText = document.getElementById('button_status');

    checkbox.addEventListener('change', function() {
        const isChecked = this.checked;

        if (isChecked) {
            statusText.textContent = 'terbuka';
            buttonStatusText.classList.add('bg-green-500'); // Add a new class
            buttonStatusText.classList.remove('bg-red-500'); // Add a new class

            buttonStatusText.textContent = 'buka'; // Set the text content

        } else {
            statusText.textContent = 'tertutup';
            buttonStatusText.classList.remove('bg-green-500'); // remove a new class
            buttonStatusText.classList.add('bg-red-500'); // Add a new class

            buttonStatusText.textContent = 'tutup'; // Set the text content

        }

        // Make Axios request to update settings
        axios.post('/updateSettings', {
            is_open: isChecked ? 'buka' : 'tutup'
        })
        .then(function(response) {
            // Handle successful response
            console.log(response.data);
        })
        .catch(function(error) {
            // Handle error
            console.error(error);
        });
    });
</script>
