class AddressMap {

    public static coordinates: { lat: number, lon: number } = null;

    private static view = new ol.View({
        center: ol.proj.fromLonLat([27.568014, 47.178197]),
        zoom: 17
    });

    private static marker = new ol.Feature({});


    private static changeCenter(lon: number, lat: number) {
        AddressMap.view.setCenter(ol.proj.fromLonLat([lon, lat]));
        AddressMap.marker.setGeometry(new ol.geom.Point(ol.proj.fromLonLat([lon, lat])));
        AddressMap.coordinates = { lat, lon };
    }

    private static searchingNow: boolean = false;
    public static lastSearch: string = null;


    public static searchAddress(address: string) {
        if (address === "" || AddressMap.searchingNow)
            return;

        AddressMap.searchingNow = true;
        AddressMap.lastSearch = address;

        const url = "https://nominatim.openstreetmap.org?format=json&limit=1&q=" + address;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data[0] != null) {
                    const { lat, lon } = data[0];
                    if (lat != null && lon != null)
                        AddressMap.changeCenter(lon, lat);
                }
                setTimeout(() => AddressMap.searchingNow = false, 3000);
            }).catch((__reason) => AddressMap.coordinates = null);
    }

    public static map = new ol.Map({
        target: 'map',
        layers: [
            new ol.layer.Tile({
                source: new ol.source.OSM()
            }),
            new ol.layer.Vector({
                source: new ol.source.Vector({
                    features: [AddressMap.marker]
                }),
                style: new ol.style.Style({
                    image: new ol.style.Icon({
                        anchor: [0.5, 46],
                        anchorXUnits: 'fraction',
                        anchorYUnits: 'pixels',
                        src: 'https://openlayers.org/en/latest/examples/data/icon.png'
                    })
                })
            })
        ],
        view: AddressMap.view
    });


    public static init() {
        navigator.geolocation.getCurrentPosition((pos) => {
            AddressMap.changeCenter(pos.coords.longitude, pos.coords.latitude);
        })

        AddressMap.map.on('click', (event) => {
            const [lon, lat] = ol.proj.toLonLat((event as any).coordinate);
            const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const { display_name } = data;
                    if (display_name == null)
                        return;

                    AddressMap.lastSearch = display_name;
                    AddressMap.marker.setGeometry(new ol.geom.Point(ol.proj.fromLonLat([lon, lat])));
                    CreateAd.addressElement.value = display_name;
                }).catch((__reason) => AddressMap.coordinates = null)
        });

    }
}

