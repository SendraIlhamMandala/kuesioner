<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mahasiswa</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <td>NIM</td>
                <td>Nama</td>
            </tr>

        </thead>
        <tbody>
            <tr>
                <td>{{ $mahasiswa->nimhs }}</td>
                <td>{{ $mahasiswa->nmmhs }}</td>
            </tr>

        </tbody>
    </table>
    <form method="POST" action="{{ route('kuesionerSk.store', $mahasiswa->nimhs) }}">
        @csrf

        @foreach ($kelas_matakuliah as $matkul)
        
        <table>
            <thead>
                <tr>
                    @dd($matakuliah, $matkul)
                    <td>{{$matakuliah->where('kdkmk',$matkul)->first()->nakmk}}</td>
                </tr>
            </thead>
            <tbody id="table">
                
                @foreach ($trkuesk->where('kdkmk', $matkul) as $data)

                <tr>
          
                        <td>{{ $kuesioner->where('kdkues', $data->kdkues)->first()->keter }}</td>
                        <td>

                            {{ $trkuesk->where('kdkmk', $matkul)->where('kdkues', $data->kdkues)->first()->skor }}
                        </td>

                        
                    </tr>
                    <tr>
                        <td>

                        <div>
                            <h4>Pilih salah satu :</h4>
                            <input type="radio" id="1[{{$data->kdkues}}][{{$data->kdkmk}}]" name="skor[{{$data->kdkues}}][{{$data->kdkmk}}]" value="1" required>
                            <label for="1[{{$data->kdkues}}][{{$data->kdkmk}}]">Sangat Tidak Setuju</label><br>
                            <input type="radio" id="2[{{$data->kdkues}}][{{$data->kdkmk}}]" name="skor[{{$data->kdkues}}][{{$data->kdkmk}}]" value="2" required>
                            <label for="2[{{$data->kdkues}}][{{$data->kdkmk}}]">Tidak Setuju</label><br>
                            <input type="radio" id="3[{{$data->kdkues}}][{{$data->kdkmk}}]" name="skor[{{$data->kdkues}}][{{$data->kdkmk}}]" value="3" required>
                            <label for="3[{{$data->kdkues}}][{{$data->kdkmk}}]">Setuju</label><br>
                            <input type="radio" id="4[{{$data->kdkues}}][{{$data->kdkmk}}]" name="skor[{{$data->kdkues}}][{{$data->kdkmk}}]" value="4" required>
                            <label for="4[{{$data->kdkues}}][{{$data->kdkmk}}]">Sangat Setuju</label>
                        </div>
                    </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
        @endforeach

 
  
    <input type="submit" value="Submit">
</form> 

</body>

</html>
