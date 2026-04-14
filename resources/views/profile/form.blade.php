<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-2 border-0">
        <h5 class="fw-bold mb-0">Édition du Profil</h5>
    </div>
    <div class="card-body py-3">
        <form action="{{ $action }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Avatar Section - Compact --}}
            <div class="text-center mb-3">
                <div class="position-relative d-inline-block">
                    <div class="rounded-circle overflow-hidden border mx-auto d-flex align-items-center justify-content-center bg-light" 
                         style="width: 100px; height: 100px;">
                        
                        @if($user->profile_image)
                            <img id="profile_image_preview" 
                                 src="{{ asset('storage/' . $user->profile_image) }}" 
                                 alt="Image" 
                                 class="w-100 h-100"
                                 style="object-fit: cover; object-position: center;">
                        @else
                            <div id="profile_image_placeholder" class="text-primary fw-bold" style="font-size: 2.5rem;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <img id="profile_image_preview" src="#" alt="Preview" class="w-100 h-100 d-none" style="object-fit: cover; object-position: center;">
                        @endif
                    </div>
                    
                    <label for="profile_image_upload" class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-1 shadow-sm border border-white cursor-pointer" 
                           style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-camera-fill" style="font-size: 0.8rem;"></i>
                        <input type="file" id="profile_image_upload" name="profile_image" class="d-none" accept="image/png, image/jpeg, image/jpg">
                    </label>
                </div>
                <div class="mt-1">
                    <h6 class="mb-0 small fw-bold">{{ $user->name }}</h6>
                </div>
                @error('profile_image') <div class="text-danger" style="font-size: 0.7rem;">{{ $message }}</div> @enderror
            </div>

            <div class="row g-2">
                <div class="col-md-6">
                    <label class="form-label mb-1" style="font-size: 0.75rem; font-weight: 700;">NOM</label>
                    <input type="text" name="name" class="form-control form-control-sm @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label mb-1" style="font-size: 0.75rem; font-weight: 700;">EMAIL</label>
                    <input type="email" name="email" class="form-control form-control-sm @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="col-12">
                    <label class="form-label mb-1" style="font-size: 0.75rem; font-weight: 700;">TÉLÉPHONE</label>
                    <input type="text" name="phone" class="form-control form-control-sm @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}">
                </div>

                <div class="col-12 py-1">
                    <div class="d-flex align-items-center opacity-50">
                        <hr class="flex-grow-1 my-0">
                        <span class="px-2" style="font-size: 0.65rem; font-weight: 800; letter-spacing: 0.5px;">SÉCURITÉ</span>
                        <hr class="flex-grow-1 my-0">
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label mb-1" style="font-size: 0.75rem; font-weight: 700;">MOT DE PASSE</label>
                    <input type="password" name="password" class="form-control form-control-sm @error('password') is-invalid @enderror">
                </div>

                <div class="col-md-6">
                    <label class="form-label mb-1" style="font-size: 0.75rem; font-weight: 700;">CONFIRMATION</label>
                    <input type="password" name="password_confirmation" class="form-control form-control-sm">
                </div>

                <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold py-2">
                        SAUVEGARDER LES MODIFICATIONS
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('profile_image_upload').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const preview = document.getElementById('profile_image_preview');
                const placeholder = document.getElementById('profile_image_placeholder');
                
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
