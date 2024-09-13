<x-app-layout>
    <x-PageHeader header="Notifikasi" classcontainer="" />

    <div class="page-body">
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3>Daftar Notifikasi</h3>
                        </div>
                        <div class="card-body">
                            <!-- Tampilkan pesan sukses jika ada -->
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <!-- Tabel Notifikasi -->
                            @foreach ($notifications as $notification)
                                <div class="alert alert-info d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $notification->type }}:</strong> {{ $notification->pesan }}
                                        <ul>
                                            @foreach ($notification->data_kendaraan as $data)
                                                <li>
                                                    Nomor Polisi: {{ $data['nomor_polisi'] }} - 
                                                    Tanggal Perpanjangan/Expired: {{ $data['tanggal_perpanjangan'] ?? $data['tanggal_expired_kir'] }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <div>
                                        @if (!$notification->diproses)
                                            <form action="{{ route('notifikasi.updateStatus', $notification->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-sm btn-primary">Tandai sebagai diproses</button>
                                            </form>
                                        @else
                                            <span class="badge bg-success">Diproses</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
