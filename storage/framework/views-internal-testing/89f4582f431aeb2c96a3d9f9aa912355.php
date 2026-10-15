<script>
    (() => {
        try {
            const choice = localStorage.getItem('sipora.theme');
            const dark = choice === 'dark' || (!choice && matchMedia('(prefers-color-scheme: dark)').matches);
            document.documentElement.dataset.theme = dark ? 'dark' : 'light';
        } catch (_) {
            document.documentElement.dataset.theme = 'light';
        }
    })();
</script>
<?php /**PATH C:\laragon\www\projects\lomba kabupaten\sipora-v2\resources\views/components/theme-init.blade.php ENDPATH**/ ?>