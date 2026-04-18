<div class="card shadow border-0 rounded-0">
    <div class="card-header bg-white py-3 border-0">
        <h4 class="fw-bold mb-0" style="color: #0f172b;">Édition du Profil</h4>
    </div>
    <div class="card-body py-4">
        <form action="{{ $action }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Avatar Section --}}
            <div class="text-center mb-4">
                <div class="position-relative d-inline-block">
                    <div class="rounded-circle overflow-hidden mx-auto d-flex align-items-center justify-content-center bg-white shadow-sm" 
                         style="width: 120px; height: 120px; border: 4px solid #fff;">
                        <img id="profile_image_preview" 
                             src="{{ $user->avatar_url }}" 
                             alt="Avatar" 
                             class="w-100 h-100"
                             style="object-fit: cover; object-position: center;">
                    </div>
                    
                    <label for="profile_image_upload" 
                           class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle shadow-sm border border-white cursor-pointer" 
                           style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; transform: translate(-5px, -5px);">
                        <i class="bi bi-camera-fill" style="font-size: 1rem;"></i>
                        <input type="file" id="profile_image_upload" name="profile_image" class="d-none" accept="image/*">
                    </label>
                </div>
                <div class="mt-2">
                    <h5 class="mb-0 fw-bold" style="color: #0f172b;">{{ $user->name }} {{ $user->last_name }}</h5>
                </div>
                @error('profile_image') <div class="text-danger mt-1 small">{{ $message }}</div> @enderror
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label mb-1 text-muted fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">NOM</label>
                    <input type="text" name="name" class="form-control rounded-1 @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required style="padding: 0.6rem;">
                </div>

                <div class="col-md-6">
                    <label class="form-label mb-1 text-muted fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">EMAIL</label>
                    <input type="email" name="email" class="form-control rounded-1 @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required style="padding: 0.6rem;">
                </div>

                <div class="col-12">
                    <label class="form-label mb-1 text-muted fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">TÉLÉPHONE</label>
                    <input type="text" name="phone" class="form-control rounded-1 @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}" style="padding: 0.6rem;">
                </div>

                <div class="col-12 py-3">
                    <div class="d-flex align-items-center opacity-75">
                        <hr class="flex-grow-1 my-0">
                        <span class="px-3 text-muted fw-bold" style="font-size: 0.65rem; letter-spacing: 1px;">SÉCURITÉ</span>
                        <hr class="flex-grow-1 my-0">
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label mb-1 text-muted fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">MOT DE PASSE</label>
                    <input type="password" name="password" class="form-control rounded-1 @error('password') is-invalid @enderror" style="padding: 0.6rem;">
                </div>

                <div class="col-md-6">
                    <label class="form-label mb-1 text-muted fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">CONFIRMATION</label>
                    <input type="password" name="password_confirmation" class="form-control rounded-1" style="padding: 0.6rem;">
                </div>

                <div class="col-12 mt-4 pt-2">
                    <button type="submit" class="btn btn-primary w-100 fw-bold py-3 rounded-0 text-white border-0 shadow-sm" style="background-color: #ff7e21 !important; letter-spacing: 0.5px;">
                        SAUVEGARDER LES MODIFICATIONS
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .form-control:focus {
        border-color: #ff7e21;
        box-shadow: 0 0 0 0.25rem rgba(255, 126, 33, 0.1);
    }
    .btn-primary:hover {
        background-color: #e66a10 !important;
        transform: translateY(-1px);
    }
    .btn-primary:active {
        transform: translateY(0);
    }
</style>

<script>
    document.getElementById('profile_image_upload').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const preview = document.getElementById('profile_image_preview');
                if (preview) {
                    preview.src = event.target.result;
                }
            }
            reader.readAsDataURL(file);
        }
    });
</script>
