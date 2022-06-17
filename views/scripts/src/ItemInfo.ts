class ItemInfo {
    private static PRICE_TYPE: string = ' RON';

    public static create(data: ItemData) {

        const propertyContainer = createSimpleElement('div', 'content__box content__box--property');
        const { price, surface, title, address } = data;
        const { bathrooms, rooms, parkingLots } = data as IconsData;

        const priceElement = createSimpleElement('h2', 'accent');
        priceElement.textContent = price + ItemInfo.PRICE_TYPE;

        const facilitiesContainer = createSimpleElement('div', 'content__box--property__facilities');

        const surfaceElement = createSimpleElement('p', '');
        surfaceElement.textContent = surface + ' m';

        const roomsElement = createSimpleElement('p', '');
        roomsElement.textContent = rooms + ' camere';

        const bathroomsElement = createSimpleElement('p', '');
        bathroomsElement.textContent = bathrooms + '  băi';

        const parkingLotsElement = createSimpleElement('p', '');
        parkingLotsElement.textContent = parkingLots + '  parcari';

        facilitiesContainer.append(surfaceElement, roomsElement, bathroomsElement, parkingLotsElement);

        propertyContainer.append(facilitiesContainer);
        //     <p>
        //         Anul Construcției: <span class="accent">2017</span> </br>
        //         Locație: <span class="accent">Strada Pacurari nr. 52</span> </br>
        //         Indice de poluare: <span class="accent">2.3</span> </br>
        //         Temperatură medie: <span class="accent">15° C</span>
        //     </p>

        return propertyContainer;
    }

}