<x-app-layout>
    <x-PageHeader header="Form User" classcontainer="col-lg-8" />
    <div class="page-body">
        <div class="col-12 col-lg-8 container-xl">
            {{-- Form Create STNK --}}
            <form action="{{ route('management-user-store') }}" method="POST" class="card">
                @csrf
                <x-cardHeader titleHeader="Silahkan isi data dibawah ini dengan benar!" />
                <div class="card-body">
                    <x-Input label="Nama" name="name" type="text" placeholder="Isi Nama Anda Disini"
                        class="" required=required />
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <x-Input label="Email" name="email" type="email" placeholder="Isi Email Anda Disini"
                        class="" />
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="mb-3 w-100 w-lg-50"">
                        <label class="form-label">Role</label>
                        <select class="form-select @error('role') is-invalid @enderror" name="role_id">
                            <option value="" selected>Pilih Role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <x-Input label="Password" name="password" type="password" placeholder="Isi Password Anda Disini"
                        required=required class="" />
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <x-cardFooter route="{{ route('management-user-index') }}" />
            </form>
        </div>
    </div>
</x-app-layout>
