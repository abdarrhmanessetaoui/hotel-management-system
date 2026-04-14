@extends('layouts.app')

@section('content')
    <div class="container py-4">
        @include('components.show-success')

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3 border-0">
                        <h4 class="fw-bold mb-0">Mon Profil Super Admin</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('superadmin.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="text-center mb-5">
                                <div class="position-relative d-inline-block">
                                    {{-- Avatar Container --}}
                                    <div class="rounded-circle overflow-hidden border shadow-sm mx-auto d-flex align-items-center justify-content-center bg-light" 
                                         style="width: 120px; height: 120px; @media (min-width: 768px) { width: 140px; height: 140px; }">
                                        
                                        @if($user->avatar)
                                            <img id="avatar_preview" 
                                                 src="{{ asset('storage/' . $user->avatar) }}" 
                                                 alt="Avatar" 
                                                 class="w-100 h-100"
                                                 style="object-fit: cover; object-position: center;">
                                        @else
                                            <div id="avatar_placeholder" class="text-primary fw-bold" style="font-size: 3.5rem;">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <img id="avatar_preview" src="#" alt="Preview" class="w-100 h-100 d-none" style="object-fit: cover; object-position: center;">
                                        @endif
                                    </div>
                                    
                                    {{-- Upload Button Overlay --}}
                                    <label for="avatar_upload" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-2 shadow-sm border border-white cursor-pointer" 
                                           style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; transform: translate(10%, 10%);">
                                        <i class="bi bi-camera-fill"></i>
                                        <input type="file" id="avatar_upload" name="avatar" class="d-none" accept="image/png, image/jpeg, image/jpg">
                                    </label>
                                </div>
                                <div class="mt-3">
                                    <h6 class="mb-0 fw-bold">{{ $user->name }}</h6>
                                    <small class="text-muted">Profil Super Admin</small>
                                </div>
                                @error('avatar') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-uppercase text-muted">Nom Complet</label>
                                    <input type="text" name="name" class="form-control py-2 @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-uppercase text-muted">Adresse Email</label>
                                    <input type="email" name="email" class="form-control py-2 @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label small fw-bold text-uppercase text-muted">Numéro de Téléphone</label>
                                    <input type="text" name="phone" class="form-control py-2 @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}">
                                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-12 my-4">
                                    <div class="d-flex align-items-center">
                                        <hr class="flex-grow-1">
                                        <span class="px-3 text-muted small fw-bold text-uppercase">Sécurité du compte</span>
                                        <hr class="flex-grow-1">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-uppercase text-muted">Nouveau mot de passe</label>
                                    <input type="password" name="password" class="form-control py-2 @error('password') is-invalid @enderror" placeholder="••••••••">
                                    <small class="text-muted" style="font-size: 0.7rem;">Laissez vide pour conserver le mot de passe actuel</small>
                                    @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-uppercase text-muted">Confirmer le mot de passe</label>
                                    <input type="password" name="password_confirmation" class="form-control py-2" placeholder="••••••••">
                                </div>

                                <div class="col-12 mt-5">
                                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold text-uppercase" style="letter-spacing: 1px;">
                                        Mettre à jour mon profil
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('avatar_upload').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const preview = document.getElementById('avatar_preview');
                    const placeholder = document.getElementById('avatar_placeholder');
                    
                    if (preview) {
                        preview.src = event.target.result;
                        preview.classList.remove('d-none');
                    }
                    if (placeholder) {
                        placeholder.classList.add('d-none');
                    }
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
