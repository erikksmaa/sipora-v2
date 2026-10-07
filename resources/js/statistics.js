import {
    Chart, LineController, LineElement, PointElement, BarController, BarElement,
    DoughnutController, ArcElement, CategoryScale, LinearScale, Tooltip,
} from 'chart.js';

Chart.register(LineController, LineElement, PointElement, BarController, BarElement, DoughnutController, ArcElement, CategoryScale, LinearScale, Tooltip);

const source = document.getElementById('statistics-data');
if (source) {
    const statistics = JSON.parse(source.textContent);
    const number = new Intl.NumberFormat('id-ID');
    const charts = [];
    let growthMode = 'monthly';
    const genderLabels = { male: 'Laki-laki', female: 'Perempuan', other: 'Lainnya', prefer_not_to_say: 'Memilih tidak menyebutkan', unknown: 'Belum diisi' };
    const statusLabels = { scheduled: 'Terjadwal', ongoing: 'Berlangsung', completed: 'Selesai', cancelled: 'Dibatalkan', active: 'Aktif', inactive: 'Tidak aktif', planned: 'Direncanakan', running: 'Berjalan' };
    const labelFor = (key, label) => key === 'gender' ? (genderLabels[label] ?? label) : key.startsWith('ecosystem-') ? (statusLabels[label] ?? label) : label;
    const colors = () => {
        const dark = document.documentElement.dataset.theme === 'dark';
        return {
            primary: dark ? '#9db7c6' : '#273f4f',
            accent: '#fe7743',
            muted: dark ? '#bbcad2' : '#536b79',
            grid: dark ? 'rgba(215,228,235,.14)' : 'rgba(39,63,79,.13)',
            donut: ['#fe7743', dark ? '#9db7c6' : '#273f4f', '#6389a1', '#b9c8d1', '#d7a88f'],
        };
    };
    const values = (rows) => rows.map(row => row.count);
    const visibleRows = (rows) => rows.filter(row => row.count !== null);
    const tooltip = {
        callbacks: { label: context => {
            const value = typeof context.parsed === 'number' ? context.parsed
                : context.chart.options.indexAxis === 'y' ? context.parsed.x : context.parsed.y;
            return `${context.label || context.dataset.label || ''}: ${number.format(value ?? 0)}`;
        } },
    };
    const render = () => {
        charts.forEach(chart => chart.destroy());
        charts.length = 0;
        const palette = colors();
        document.querySelectorAll('canvas[data-chart]').forEach(canvas => {
            const key = canvas.dataset.chart;
            const sourceRows = key === 'growth' ? statistics[growthMode === 'monthly' ? 'growth_monthly' : 'growth_yearly']
                : key.startsWith('ecosystem-') ? statistics.ecosystem[key.slice(10)] : statistics[key];
            const isLine = key === 'growth';
            const isDonut = key === 'gender';
            const rows = isLine ? (sourceRows ?? []) : visibleRows(sourceRows ?? []);
            if (rows.length === 0 || rows.every(row => row.count === null)) { canvas.parentElement.hidden = true; return; }
            canvas.parentElement.hidden = false;
            const horizontal = ['districts', 'interests', 'skills'].includes(key) || key.startsWith('ecosystem-');
            const chart = new Chart(canvas, {
                type: isLine ? 'line' : isDonut ? 'doughnut' : 'bar',
                data: {
                    labels: rows.map(row => labelFor(key, row.label)),
                    datasets: [{
                        data: values(rows),
                        borderColor: isLine ? palette.accent : isDonut ? 'transparent' : palette.primary,
                        backgroundColor: isDonut ? palette.donut : isLine ? palette.accent : palette.primary,
                        borderWidth: isLine ? 3 : 0,
                        pointBackgroundColor: palette.accent,
                        pointRadius: isLine ? 4 : 0,
                        tension: isLine ? .2 : 0,
                        borderRadius: isDonut ? 0 : 3,
                        maxBarThickness: horizontal ? 26 : 45,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: horizontal ? 'y' : 'x',
                    cutout: isDonut ? '64%' : undefined,
                    plugins: { legend: { display: false }, tooltip },
                    scales: isDonut ? {} : {
                        x: { grid: { color: horizontal ? palette.grid : 'transparent' }, ticks: { color: palette.muted, maxRotation: isLine ? 50 : 0, autoSkip: true, precision: horizontal ? 0 : undefined }, beginAtZero: horizontal },
                        y: { grid: { color: horizontal ? 'transparent' : palette.grid }, ticks: { color: palette.muted, precision: horizontal ? undefined : 0, autoSkip: true }, beginAtZero: !horizontal },
                    },
                },
            });
            charts.push(chart);
        });
    };
    document.querySelectorAll('[data-growth-mode]').forEach(button => button.addEventListener('click', () => {
        growthMode = button.dataset.growthMode;
        document.querySelectorAll('[data-growth-mode]').forEach(item => item.setAttribute('aria-pressed', String(item === button)));
        render();
    }));
    window.addEventListener('sipora:theme', render);
    render();
}
