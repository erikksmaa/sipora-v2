export default (initial) => ({
    liked: initial.liked,
    count: initial.count,
    busy: false,
    error: '',
    async toggle(form) {
        if (this.busy) return;
        this.busy = true;
        this.error = '';
        const body = new FormData(form);
        body.set('liked', this.liked ? '0' : '1');
        try {
            const response = await fetch(form.action, {
                method: 'POST', credentials: 'same-origin', body,
                headers: {Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest'},
            });
            if (response.redirected || [401, 419].includes(response.status)) {
                this.error = 'Sesi telah berakhir. Silakan masuk kembali untuk memberi suka.';
                return;
            }
            const data = await response.json();
            if (!response.ok) {
                this.error = response.status === 429 ? 'Terlalu cepat. Tunggu sebentar lalu coba lagi.' : 'Suka belum dapat diperbarui. Silakan coba lagi.';
                return;
            }
            this.liked = data.liked;
            this.count = data.likes_count;
        } catch {
            this.error = 'Suka belum dapat diperbarui. Periksa koneksi lalu coba lagi.';
        } finally {
            this.busy = false;
        }
    },
});
