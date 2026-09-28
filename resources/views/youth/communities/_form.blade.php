@php($editing = isset($organization))
@php($social = $organization->social_links ?? [])
<form method="post" action="{{ $editing ? route('youth.communities.update', $organization) : route('youth.communities.store') }}" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @if($editing) @method('PUT') @endif

    <section class="sipora-card">
        <div class="border-b border-slate-100 pb-4">
            <p class="text-xs font-extrabold uppercase tracking-[.18em] text-orange-600">Profil komunitas</p>
            <h2 class="mt-1 text-xl font-bold text-[#243378]">Informasi utama</h2>
            <p class="mt-1 text-sm text-slate-500">Data ini menjadi dasar peninjauan Verifier pada fase berikutnya.</p>
        </div>

        @if($categories->isEmpty())
            <div role="alert" class="mt-5 rounded-xl border border-amber-300 bg-amber-50 p-4 text-sm text-amber-900">
                Kategori komunitas resmi belum tersedia. Draft belum dapat dibuat sampai master kategori ditetapkan.
            </div>
        @endif

        <div class="mt-5 grid gap-5 md:grid-cols-2">
            <div class="md:col-span-2"><label class="field-label" for="name">Nama komunitas <span class="text-red-600">*</span></label><input class="field" id="name" name="name" maxlength="180" required value="{{ old('name', $organization->name ?? '') }}"><x-input-error :messages="$errors->get('name')" /></div>
            <div><label class="field-label" for="category_id">Kategori <span class="text-red-600">*</span></label><select class="field" id="category_id" name="category_id" required @disabled($categories->isEmpty())><option value="">Pilih kategori</option>@foreach($categories as $category)<option value="{{ $category->uuid() }}" @selected(old('category_id', isset($organization) ? $organization->category?->uuid() : '') === $category->uuid())>{{ $category->name }}</option>@endforeach</select><x-input-error :messages="$errors->get('category_id')" /></div>
            <div><label class="field-label" for="administrative_area_id">Wilayah domisili</label><select class="field" id="administrative_area_id" name="administrative_area_id"><option value="">Pilih wilayah (opsional)</option>@foreach($administrativeAreas as $area)<option value="{{ $area->uuid() }}" @selected(old('administrative_area_id', isset($organization) ? $organization->administrativeArea?->uuid() : '') === $area->uuid())>{{ $area->name }}</option>@endforeach</select><x-input-error :messages="$errors->get('administrative_area_id')" /></div>
            <div class="md:col-span-2"><label class="field-label" for="description">Deskripsi</label><textarea class="field min-h-36" id="description" name="description" maxlength="5000" placeholder="Ceritakan tujuan, fokus, dan kegiatan komunitas.">{{ old('description', $organization->description ?? '') }}</textarea><x-input-error :messages="$errors->get('description')" /></div>
            <div class="md:col-span-2"><label class="field-label" for="logo">Logo komunitas</label><input class="field file:mr-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-3 file:py-2 file:font-semibold file:text-[#243378]" id="logo" name="logo" type="file" accept="image/jpeg,image/png,image/webp"><p class="mt-1 text-xs text-slate-500">JPG, PNG, atau WebP. Maksimal 2 MB.</p><x-input-error :messages="$errors->get('logo')" /></div>
        </div>
    </section>

    <section class="sipora-card">
        <h2 class="text-xl font-bold text-[#243378]">Kontak dan kanal resmi</h2>
        <div class="mt-5 grid gap-5 md:grid-cols-2">
            <div><label class="field-label" for="contact_email">Email</label><input class="field" id="contact_email" name="contact_email" type="email" maxlength="254" value="{{ old('contact_email', $organization->contact_email ?? '') }}"><x-input-error :messages="$errors->get('contact_email')" /></div>
            <div><label class="field-label" for="contact_phone">Nomor telepon</label><input class="field" id="contact_phone" name="contact_phone" maxlength="32" value="{{ old('contact_phone', $organization->contact_phone ?? '') }}"><x-input-error :messages="$errors->get('contact_phone')" /></div>
            <div class="md:col-span-2"><label class="field-label" for="address_text">Alamat</label><textarea class="field min-h-24" id="address_text" name="address_text">{{ old('address_text', $organization->address_text ?? '') }}</textarea><x-input-error :messages="$errors->get('address_text')" /></div>
            <div><label class="field-label" for="website_url">Situs web</label><input class="field" id="website_url" name="website_url" type="url" placeholder="https://" value="{{ old('website_url', $organization->website_url ?? '') }}"><x-input-error :messages="$errors->get('website_url')" /></div>
            <div><label class="field-label" for="social_instagram">Instagram</label><input class="field" id="social_instagram" name="social_instagram" type="url" placeholder="https://instagram.com/..." value="{{ old('social_instagram', $social['instagram'] ?? '') }}"><x-input-error :messages="$errors->get('social_instagram')" /></div>
            <div><label class="field-label" for="social_facebook">Facebook</label><input class="field" id="social_facebook" name="social_facebook" type="url" placeholder="https://facebook.com/..." value="{{ old('social_facebook', $social['facebook'] ?? '') }}"><x-input-error :messages="$errors->get('social_facebook')" /></div>
            <div><label class="field-label" for="social_tiktok">TikTok</label><input class="field" id="social_tiktok" name="social_tiktok" type="url" placeholder="https://tiktok.com/@..." value="{{ old('social_tiktok', $social['tiktok'] ?? '') }}"><x-input-error :messages="$errors->get('social_tiktok')" /></div>
        </div>
    </section>

    <div class="flex flex-wrap justify-end gap-3"><a class="btn-secondary" href="{{ $editing ? route('youth.communities.show', $organization) : route('youth.communities.index') }}">Batal</a><button class="btn" type="submit" @disabled($categories->isEmpty())>Simpan draft</button></div>
</form>
