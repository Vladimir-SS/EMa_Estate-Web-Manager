class MapHandler {
    private static view = new ol.View({
        center: ol.proj.fromLonLat([27.568014, 47.178197]),
        zoom: 17
    });

    private static map = new ol.Map({
        target: 'map',
        layers: [
            new ol.layer.Tile({
                source: new ol.source.OSM()
            })
        ],
        view: MapHandler.view
    });
}