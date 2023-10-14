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
    @foreach ($mahasiswas as $mahasiswa_data )
    <tr>
        <td>{{ $mahasiswa_data->nimhs }}</td>
        <td>{{ $mahasiswa_data->nmmhs }}</td>
    </tr>
        
    @endforeach
</tbody>
</body>
</html>