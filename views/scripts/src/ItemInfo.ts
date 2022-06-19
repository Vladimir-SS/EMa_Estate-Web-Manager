class ItemInfo {
    private static PRICE_TYPE: string = ' Ron';
    private static airQuality: string[] = ['', 'Foarte bună', 'Bună', 'Moderată', 'Proastă', 'Foarte proastă'];

    public static createFacitilitiesContainer(data: ItemData) {
        const facilitiesContainer = createSimpleElement('div', 'content__box--property__facilities');
        const { surface, type, transactionType } = data;

        const typeElement = createSimpleElement('p', '');
        typeElement.textContent = 'Tip: ' + type;

        const transactionTypeElement = createSimpleElement('p', '');
        transactionTypeElement.textContent = 'Tip tranzacție: ' + transactionType;

        const surfaceElement = createSimpleElement('p', 'surface');
        surfaceElement.textContent = surface + ' m';

        facilitiesContainer.append(surfaceElement, typeElement, transactionTypeElement);

        return facilitiesContainer;
    }

    public static createBuildingContainer(data: BuildingData) {
        const buildingContainer = createSimpleElement('div', 'content__box--property__facilities');
        const { builtIn, parkingLots } = data;

        const builtInElement = createSimpleElement('p', '');
        builtInElement.textContent = 'Anul Construcției: ' + builtIn;

        const parkingLotsElement = createSimpleElement('p', '');
        parkingLotsElement.textContent = 'Parcări: ' + parkingLots;

        buildingContainer.append(builtInElement, parkingLotsElement);

        return buildingContainer;
    }

    public static addAditionalInfo(weatherData: WeatherData) {
        console.log(weatherData);
        const infoElement = createSimpleElement('p', '');
        infoElement.innerText = 'Calitatea aerului: ' + ItemInfo.airQuality[weatherData.list[0].main.aqi.toString()];
        return infoElement;
    }

    public static create(data: ItemData) {

        const propertyContainer = createSimpleElement('div', 'content__box content__box--property');
        const { price, title, address } = data;

        const priceElement = createSimpleElement('h2', 'accent');
        priceElement.textContent = parseMoney(price) + ItemInfo.PRICE_TYPE;

        propertyContainer.append(priceElement, ItemInfo.createFacitilitiesContainer(data));

        if (data.type !== 'land') {
            propertyContainer.appendChild(ItemInfo.createBuildingContainer(data));
        }
        //     <p>
        //         Anul Construcției: <span class="accent">2017</span> </br>
        //         Locație: <span class="accent">Strada Pacurari nr. 52</span> </br>
        //         Indice de poluare: <span class="accent">2.3</span> </br>
        //         Temperatură medie: <span class="accent">15° C</span>
        //     </p>

        return propertyContainer;
    }

}