# Pemalang kecamatan boundaries

`pemalang-kecamatan.geojson` contains the 14 kecamatan polygons for Kabupaten Pemalang from Badan Informasi Geospasial's [BATAS_KECAMATAN_AR service](https://geoservices.big.go.id/rbi/rest/services/BATASWILAYAH/BATAS_KECAMATAN_AR/MapServer/0). The service describes this layer as based on its 2022 administrative boundary data.

The GeoJSON was retrieved with `KDPKAB='33.27'`, `outFields=KDCPUM,WADMKC`, `outSR=4326`, `geometryPrecision=4`, and `maxAllowableOffset=0.001`. The last two parameters generalize the source geometry for a compact web display; this is not a survey or legal boundary reference. The file contains no SIPORA user data. `KDCPUM` matches the existing `administrative_areas.code` values.
