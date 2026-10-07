import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

const source = document.getElementById('eco-map-data');
const canvas = document.getElementById('eco-map');

if (source && canvas) {
    const { districts, admin, threshold } = JSON.parse(source.textContent);
    const byCode = new Map(districts.map(row => [row.code, row]));
    const formatter = new Intl.NumberFormat('id-ID');
    const labels = { youth: 'Youth terdaftar', participated: 'Youth ikut Activity', community: 'Community aktif', activity: 'Activity terbit', program: 'Program publik' };
    const districtPicker = document.getElementById('eco-map-district');
    const resetButton = document.getElementById('eco-map-reset');
    const detailTitle = document.getElementById('eco-map-selected-title');
    const detailCopy = document.getElementById('eco-map-selected-copy');
    const detailValues = document.getElementById('eco-map-selected-values');
    let activeMetric = 'youth';
    let selectedCode = null;

    const display = count => count === null ? `<${threshold}` : formatter.format(count);
    const darkMode = () => document.documentElement.dataset.theme === 'dark';
    const maximum = () => Math.max(1, ...districts.map(row => row[activeMetric] ?? 0));

    const map = L.map(canvas, {
        attributionControl: false,
        zoomControl: false,
        scrollWheelZoom: false,
        preferCanvas: true,
    });
    L.control.zoom({ position: 'bottomright' }).addTo(map);
    const shapes = new Map();
    let boundaries;
    let fullBounds;

    const styleFor = code => {
        const row = byCode.get(code);
        const value = row?.[activeMetric];
        const selected = code === selectedCode;
        const dark = darkMode();
        return {
            color: selected ? '#fe7743' : dark ? '#d4e3e8' : '#294454',
            weight: selected ? 3 : 1.5,
            fillColor: value === null ? (dark ? '#65727a' : '#b6bab8') : value === 0 ? (dark ? '#314854' : '#d9dfdf') : (dark ? '#a4bfd0' : '#345d75'),
            fillOpacity: value === null || value === 0 ? .7 : Math.min(.9, .27 + .63 * value / maximum()),
        };
    };

    const renderDetail = () => {
        const row = selectedCode ? byCode.get(selectedCode) : null;
        detailTitle.textContent = row?.district ?? 'Semua kecamatan';
        detailCopy.textContent = row
            ? 'Angka berdasarkan record SIPORA yang terkait dengan kecamatan ini.'
            : 'Pilih wilayah di peta atau daftar untuk melihat angka agregat.';
        detailValues.replaceChildren();
        if (row) {
            const keys = admin ? ['youth', 'participated', 'community', 'activity', 'program'] : ['youth', 'community', 'activity', 'program'];
            keys.forEach(key => {
                const pair = document.createElement('div');
                const term = document.createElement('dt');
                const value = document.createElement('dd');
                term.textContent = labels[key];
                value.textContent = display(row[key]);
                pair.append(term, value);
                detailValues.append(pair);
            });
        }
        resetButton.hidden = !row;
        districtPicker.value = selectedCode ?? '';
        document.querySelectorAll('[data-map-row]').forEach(item => {
            item.classList.toggle('eco-map-row-active', item.dataset.mapRow === selectedCode);
        });
    };

    const select = code => {
        selectedCode = code && byCode.has(code) ? code : null;
        boundaries?.setStyle(feature => styleFor(feature.properties.KDCPUM));
        if (selectedCode && shapes.has(selectedCode)) {
            map.fitBounds(shapes.get(selectedCode).getBounds(), { padding: [24, 24], maxZoom: 11 });
        } else if (fullBounds) {
            map.fitBounds(fullBounds, { padding: [20, 20] });
        }
        renderDetail();
    };

    document.querySelectorAll('input[name="map-layer"]').forEach(input => input.addEventListener('change', () => {
        if (!input.checked) return;
        activeMetric = input.value;
        boundaries?.setStyle(feature => styleFor(feature.properties.KDCPUM));
    }));
    districtPicker.addEventListener('change', () => select(districtPicker.value));
    resetButton.addEventListener('click', () => select(null));
    document.querySelectorAll('[data-map-select]').forEach(button => button.addEventListener('click', () => {
        select(button.dataset.mapSelect);
        canvas.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }));
    window.addEventListener('sipora:theme', () => boundaries?.setStyle(feature => styleFor(feature.properties.KDCPUM)));

    fetch(canvas.dataset.geometryUrl)
        .then(response => { if (!response.ok) throw new Error('Geometry unavailable'); return response.json(); })
        .then(geojson => {
            if (geojson.type !== 'FeatureCollection' || geojson.features.length !== 14) throw new Error('Incomplete geometry');
            boundaries = L.geoJSON(geojson, {
                style: feature => styleFor(feature.properties.KDCPUM),
                onEachFeature: (feature, shape) => {
                    const row = byCode.get(feature.properties.KDCPUM);
                    if (!row) return;
                    shapes.set(row.code, shape);
                    shape.bindTooltip(row.district, { sticky: true });
                    shape.on('click', () => select(row.code));
                },
            }).addTo(map);
            fullBounds = boundaries.getBounds();
            map.fitBounds(fullBounds, { padding: [20, 20] });
            map.setMaxBounds(fullBounds.pad(.25));
            map.setMinZoom(Math.max(1, map.getZoom() - 1));
            if (selectedCode) select(selectedCode);
        })
        .catch(() => { document.getElementById('eco-map-error').hidden = false; });
    renderDetail();
}
