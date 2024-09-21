@props(['route', 'showSubmitButton' => true])

<div class="card-footer text-end">
    <div class="d-flex">
        <a href="{{ $route }}" class="btn btn-link">Kembali</a>
        @if ($showSubmitButton)
            <!-- Kondisi jika tombol Simpan harus ditampilkan -->
            <button type="submit" class="btn btn-primary ms-auto">Simpan</button>
        @endif
    </div>
</div>
