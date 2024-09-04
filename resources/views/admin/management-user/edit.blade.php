<x-app-layout>
    <x-PageHeader header="Form User" classcontainer="col-lg-8" />
    <div class="page-body">
        <div class="col-12 col-lg-8 container-xl">
            {{-- Form Create STNK --}}
            <form action="{{ route('management-user-update', $user->id) }}" method="POST" class="card">
                @csrf
                @method('PUT')
                <x-cardHeader titleHeader="Silahkan isi data dibawah ini dengan benar!" />
                <div class="card-body">
                    <x-Input label="Name" name="name" type="text" value="{{ $user->name }}" class="" />
                    <x-Input label="Email" name="email" type="email" value="{{ $user->email }}" class="" />
        
                    <x-Input label="Password" name="password" type="password" placeholder="Isi Password Anda Disini" class="" />
                </div>
                <x-cardFooter route="{{ route('management-user-index') }}" />
            </form>
        </div>
    </div>
</x-app-layout>
