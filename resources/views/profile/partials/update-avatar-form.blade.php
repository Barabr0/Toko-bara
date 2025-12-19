<p class="text-muted small">
 upload foto profile kamu. format pendukung JPG, PNG, WebP. maks 2MB.
</p>

<form action="{{ route('profile.update') }}" method="post" enctype="multipart/form-data">
@csrf
@method('patch')
<div class="d-flex align-items-center gap-4">
<div class="position-relative">
    <img id="avater-preview" class="rounded-circle object-fit-cover border"
    style="width: 200px; height: 120px;"
    src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}"
     alt="{{ $user->name }}">

     @if ($user->avatar)
            <button type="button"
                        onclick="if(confirm('Hapus foto profil?')) document.getElementById('delete-avatar-form').submit()"
                        class="btn btn-danger btn-sm rounded-circle position-absolute top-0 start-100 translate-middle p-1"
                        style="width: 24px; height: 24px; line-height: 1;"
                        title="Hapus foto">
                        &times;></button>
                    @endif
                </div>
                <div class="flex-grow-1">
                    <input type="file" name="avatar" id="avatar" accept="image/*" onchange="previewAvatar(event)" class="form-controll @error('avatar') is -invalid @enderror">
                    @error('avatar')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Simpan Foto</button>
            </div>
</form>
<form id="delete-avatar-form" action="{{ route('profile.avatar.destroy') }}" method="post">
    @csrf
    @method('DELETE')
</form>
<script>
    function previewAvatar(Event){
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload function(e){
                document.getElementById('avatar-preview').src = e.target.result;
            }
            reader.readAsDAtaURL(file);
        }
    }
</script>
