<span class="password-control" x-data="{ visible: false }">
    <input type="password" :type="visible ? 'text' : 'password'" {{ $attributes->class(['field']) }}>
    <button type="button" class="password-toggle" @click="visible = !visible" :aria-pressed="visible" aria-label="Tampilkan kata sandi" :aria-label="visible ? 'Tutup tampilan kata sandi' : 'Tampilkan kata sandi'">
        <span x-show="!visible"><x-ui.icon name="eye" class="size-5" /></span>
        <span x-cloak x-show="visible"><x-ui.icon name="eye-off" class="size-5" /></span>
    </button>
</span>
