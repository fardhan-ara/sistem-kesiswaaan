@extends('layouts.app')

@section('title', 'Approval Biodata Orang Tua')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Approval Biodata Orang Tua</h2>

    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Orang Tua</th>
                        <th>Siswa</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($biodatas as $b)
                    <tr>
                        <td>{{ $b->created_at->format('d/m/Y') }}</td>
                        <td>{{ $b->user->nama }}</td>
                        <td>{{ $b->siswa->nama_siswa }} - {{ $b->siswa->kelas->nama_kelas ?? '-' }}</td>
                        <td>
                            @if($b->status_approval === 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @elseif($b->status_approval === 'approved')
                                <span class="badge bg-success">Approved</span>
                            @else
                                <span class="badge bg-danger">Rejected</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('biodata-ortu.show', $b->id) }}" class="btn btn-sm btn-info">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            {{ $biodatas->links() }}
        </div>
    </div>
</div>
@endsection
