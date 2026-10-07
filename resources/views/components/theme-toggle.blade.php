<button type="button" x-data="themeToggle" @click="toggle" class="theme-toggle grid size-10 shrink-0 place-items-center border border-border bg-surface text-text-primary" :aria-label="dark ? 'Gunakan mode terang' : 'Gunakan mode gelap'" :title="dark ? 'Gunakan mode terang' : 'Gunakan mode gelap'">
    <span x-show="!dark"><x-ui.icon name="moon" class="size-5" /></span>
    <span x-cloak x-show="dark"><x-ui.icon name="sun" class="size-5" /></span>
</button>
