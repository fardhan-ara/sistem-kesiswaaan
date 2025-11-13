@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')
<h3>Data Siswa</h3>
<table class="table table-bordered">
    <tr>
        <th>NIS</th>
        <th>Nama</th>
        <th>Kelas</th>
    </tr>
    @foreach($siswa as $s)
        <tr>
            <td>{{ $s->nis }}</td>
            <td>{{ $s->nama_siswa }}</td>
            <td>{{ $s->kelas_id }}</td>
        </tr>
    @endforeach
</table>
@endsection
