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
                    <x-Input label="Email" id="email" name="email" type="email"
                        placeholder="Isi Email Anda Disini" class="" />

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
                    <x-Input label="Password" id="password" name="password" type="password"
                        placeholder="Isi Password Anda Disini" required=required class="" />

                    <x-Input label="Konfirmasi Password" id="password_confirmation" name="password_confirmation"
                        type="password" placeholder="Ulangi Password Anda" required=required class="" />
                </div>
                <x-cardFooter route="{{ route('management-user-index') }}" />
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            // Realtime email validation using AJAX
            document.getElementById('email').addEventListener('input', function() {
                let email = this.value;
                let emailInput = this;
                let feedback = document.getElementById('emailError');

                // Regular expression for email validation
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (email.length > 0) {
                    // Check if the email format is valid
                    if (!emailPattern.test(email)) {
                        emailInput.classList.add('is-invalid');
                        feedback.textContent = 'Format email tidak valid.';
                        feedback.style.display = 'block';
                        return; // Stop further processing
                    }

                    // Send AJAX request to check if email exists
                    fetch('{{ route('management-user-check-email') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                email: email
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.exists) {
                                // Add is-invalid class and show error message
                                emailInput.classList.add('is-invalid');
                                feedback.textContent = 'Email sudah digunakan, silakan gunakan email lain.';
                                feedback.style.display = 'block';
                            } else {
                                // Remove is-invalid class and hide error message
                                emailInput.classList.remove('is-invalid');
                                feedback.style.display = 'none';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            // Optionally handle the error visually for the user
                            emailInput.classList.add('is-invalid');
                            feedback.textContent = 'Terjadi kesalahan saat memeriksa email.';
                            feedback.style.display = 'block';
                        });
                } else {
                    // Remove is-invalid class if input is cleared
                    emailInput.classList.remove('is-invalid');
                    feedback.style.display = 'none';
                }
            });


            // Password validation
            document.getElementById('password').addEventListener('input', function() {
                let password = this.value;
                let passwordInput = this;
                let feedback = document.getElementById('passwordError');

                // Check if password is at least 8 characters
                if (password.length < 8) {
                    passwordInput.classList.add('is-invalid');
                    feedback.textContent = 'Password harus minimal 8 karakter.';
                    feedback.style.display = 'block';
                } else {
                    passwordInput.classList.remove('is-invalid');
                    feedback.style.display = 'none';
                }

                // Check password confirmation
                validatePasswordConfirmation();
            });

            document.getElementById('password_confirmation').addEventListener('input', function() {
                validatePasswordConfirmation();
            });

            function validatePasswordConfirmation() {
                let password = document.getElementById('password').value;
                let confirmPassword = document.getElementById('password_confirmation').value;
                let confirmPasswordInput = document.getElementById('password_confirmation');
                let feedback = document.getElementById('confirmPasswordError');

                // Check if passwords match
                if (confirmPassword.length > 0 && password !== confirmPassword) {
                    confirmPasswordInput.classList.add('is-invalid');
                    feedback.textContent = 'Password tidak cocok, silakan cek kembali.';
                    feedback.style.display = 'block';
                } else {
                    confirmPasswordInput.classList.remove('is-invalid');
                    feedback.style.display = 'none';
                }
            }
        </script>
    @endpush
</x-app-layout>
