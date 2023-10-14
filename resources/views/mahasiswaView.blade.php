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
    <form method="POST" action="{{ route('kuesioner.store', $mahasiswa->nimhs) }}">
        @csrf
        <table>
            <thead>
                <tr>
                    <td>Kelas kuesioner</td>
                </tr>

            </thead>
            <tbody>




                @foreach ($kelas_kuesioner as $data_kelas)
                    <tr>
                        <td>{{ $kelas[$data_kelas] }}


                            @foreach ($kuesioner->where('klkues', $data_kelas)->pluck('keter', 'kdkues') as $kdkues => $keter)
                            <tr>
                                <td>

                                    <h2>
                                        {{ $loop->index + 1 }}
                                        {{ $keter }}
                                    </h2>
                                    <div>
                                        <h4>Pilih salah satu :</h4>
                                        <input type="radio" id="1[{{ $kdkues }}][{{ $data_kelas }}]"
                                            name="skor[{{ $kdkues }}][{{ $data_kelas }}]" value="1" required>
                                        <label for="1[{{ $kdkues }}][{{ $data_kelas }}]">Sangat Tidak
                                            Setuju</label><br>
                                        <input type="radio" id="2[{{ $kdkues }}][{{ $data_kelas }}]"
                                            name="skor[{{ $kdkues }}][{{ $data_kelas }}]" value="2" required>
                                        <label for="2[{{ $kdkues }}][{{ $data_kelas }}]">Tidak Setuju</label><br>
                                        <input type="radio" id="3[{{ $kdkues }}][{{ $data_kelas }}]"
                                            name="skor[{{ $kdkues }}][{{ $data_kelas }}]" value="3" required>
                                        <label for="3[{{ $kdkues }}][{{ $data_kelas }}]">Setuju</label><br>
                                        <input type="radio" id="4[{{ $kdkues }}][{{ $data_kelas }}]"
                                            name="skor[{{ $kdkues }}][{{ $data_kelas }}]" value="4" required>
                                        <label for="4[{{ $kdkues }}][{{ $data_kelas }}]">Sangat Setuju</label>
                                    </div>
                                </td>
                                <td>
                                    {{ $trkuesl->where('klkues', $data_kelas)->where('kdkues', $kdkues)->first()->skor }}
                                </td>

                            </tr>
                            @endforeach

                      </td>

                     </tr>
                @endforeach

            </tbody>
        </table>

        <input type="submit" value="Submit">
    </form>

</body>

</html>
