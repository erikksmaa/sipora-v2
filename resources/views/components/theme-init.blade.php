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
