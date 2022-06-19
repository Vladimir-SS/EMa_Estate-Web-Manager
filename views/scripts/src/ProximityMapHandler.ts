interface PinData {
    id: number
    imageURL: string
    lat: number
    lon: number
    price: number
    type: string
}

class ProximityMapHandler {
    static curency = " Ron";
    private static overlayList: ol.Overlay[] = [];

    private static view = new ol.View({
        center: ol.proj.fromLonLat([27.568014, 47.178197]),
        zoom: 17,
        maxZoom: 18
    });

    private static typeMap = {
        land: "Teren",
        apartment: "Apartament",
        office: "Office",
        house: "Casă"
    }

    private static createPinElement(data: PinData) {
        const priceElement = createSimpleElement('p', "price");
        priceElement.textContent = parseMoney(data.price) + this.curency;
        const typeElement = createSimpleElement('p', "type");
        const typedMaped = this.typeMap[data.type];
        typeElement.textContent = typedMaped ?? data.type
        const imageElement = createSimpleElement('div', "image");
        imageElement.style.backgroundImage = `url(${data.imageURL})`
        const imageContainerElement = createSimpleElement('div', "image-container");
        imageContainerElement.appendChild(imageElement);

        const extraiInfoElement = createSimpleElement('div', "extra-info");
        extraiInfoElement.append(typeElement, imageContainerElement);

        const containerElement = createSimpleElement('div', "pin-container");
        containerElement.append(extraiInfoElement, priceElement);
        //containerElement.setAttribute("onclick", "");

        const anchorElement = document.createElement("a");
        anchorElement.href = `/item?id=${data.id}`
        anchorElement.appendChild(containerElement);

        return anchorElement;
    }


    private static createOverlay = (data: PinData) =>
        new ol.Overlay({
            position: ol.proj.fromLonLat([data.lon, data.lat]),
            positioning: 'bottom-center',
            element: this.createPinElement(data),
            stopEvent: false
        })

    private static map = new ol.Map({
        target: 'map',
        layers: [
            new ol.layer.Tile({
                source: new ol.source.OSM()
            })
        ],
        view: this.view,
    });

    public static setTo(lon: number, lat: number) {
        fetch(`/api/items/near?lon=${lon}&lat=${lat}`)
            .then(response => response.json())
            .then((data: PinData[]) => {
                this.view.setCenter(ol.proj.fromLonLat([lon, lat]));
                this.overlayList.forEach(ov => this.map.removeOverlay(ov));
                this.overlayList = data.map(p => this.createOverlay(p));
                this.overlayList.forEach(ov => this.map.addOverlay(ov));

                this.overlayList.forEach(ov => ov.setProperties({
                    className: "big-z",
                    class: "big-z",
                    classList: "big-z"
                }));
            })
    }
}