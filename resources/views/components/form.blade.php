<div class="card-header">
    <h3 class="card-title">Silahkan isi data berikut dengan benar!</h3>
</div>
<div class="card-body">
    <div class="mb-3">
        <label class="form-label required">Nama Kendaraan</label>
        <div>
            <input type="email" class="form-control" aria-describedby="emailHelp" placeholder="Enter email">
            <small class="form-hint">We'll never share your email with anyone else.</small>
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Tipe Kendaraan</label>
        <div>
            <select class="form-select w-25">
                <option>Mobil</option>
                <option>Truck</option>
                <option>Motor</option>
            </select>
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Plat Nomor</label>
        <input type="text" name="plat_nomor" class="form-control" data-mask="** 0000 ***" data-mask-visible="true"
            placeholder="B 1234 XYZ" autocomplete="off">
    </div>
</div>
<div class="card-footer text-end">
    <div class="d-flex">
        <a href="{{ route('stnk-index') }}" class="btn btn-link">Kembali</a>
        <button type="submit" class="btn btn-primary ms-auto">Simpan</button>
    </div>
</div>
