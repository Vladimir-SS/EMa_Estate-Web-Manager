interface BaseData {
    id: number
    /**
     * Type
     * 
     * 0 - "Apartament"
     * 1 - "Casă"
     * 2 - "Office"
     * 3 - "Teren"
     */
    accountID: number
    type: number
    transactionType: string
    address: string
    title: string
    description: string
    price: string
    surface: number
    imageURL: string
    imagesURLs: string[]

}

interface BuildingData extends BaseData {
    bathrooms: number
    parkingLots: number
    builtIn: number
}

interface ResidentialData extends BuildingData {
    rooms: number
    floor: number
}

interface ApartmentData extends ResidentialData {
    type: "apartment"
    apartmentType: string
}

interface HouseData extends BuildingData {
    type: "house"
    floors: number
}

interface OfficeData extends BuildingData {
    type: "office"
}

interface LandData extends BaseData {
    type: "land"
}

type ItemData = ApartmentData | HouseData | OfficeData | LandData;

interface IconsData {
    bathrooms?: number
    surface: number
    rooms?: number
    parkingLots?: number
}

class Item {

    private static PRICE_TYPE: string = ' RON';

    public static createInfoContainer(data: BaseData, iconsElement: HTMLElement) {

        const infoContainer = createSimpleElement('div', 'content__box--item__info flex-1');
        const { price, title, address, id } = data;

        const priceElement = createSimpleElement('h2', 'accent');
        priceElement.textContent = price + Item.PRICE_TYPE;

        const titleElement = createSimpleElement('a', 'hlink text-wrap') as HTMLAnchorElement;
        titleElement.href = `/item?id=${id}`;
        titleElement.textContent = title;

        const addressElement = createSimpleElement('p', 'secondary icon-text');
        addressElement.appendChild(createSimpleElement('span', 'icon icon-pin'));
        const addressParagraph = createSimpleElement('p', 'text-wrap');
        addressParagraph.textContent = address;
        addressElement.appendChild(addressParagraph);

        infoContainer.append(priceElement, titleElement, addressElement, iconsElement);

        return infoContainer;
    }

    public static createInfoIconsElement(data: IconsData) {
        const infoIconsElement = createSimpleElement('div', 'content__box--item__info__icons');
        const basicIconsContainer = createSimpleElement('div', 'content__box--item__info__icons__basic');
        const { surface, bathrooms, rooms, parkingLots } = data;

        const aux: [number, string][] = [
            [surface, 'space'],
            [bathrooms, 'bath'],
            [rooms, 'room'],
            [parkingLots, 'garage']
        ];

        aux.forEach(([val, iconName]) => {
            const el = createSimpleElement('p', 'icon-text');
            if (val != null) {
                el.appendChild(createIcon(iconName));
                el.innerHTML += val;
            }

            basicIconsContainer.appendChild(el);
        });

        const saveButton = createSimpleElement('div', 'save-button');
        saveButton.onclick = () => saveButtonClickHandler(saveButton);
        saveButton.appendChild(createSimpleElement('span', 'icon icon-save'));
        infoIconsElement.append(basicIconsContainer, saveButton);

        return infoIconsElement;
    }

    public static create(data: ItemData) {
        const container = createSimpleElement('div', 'content__box content__box--item');
        const imageContainer = createSimpleElement('div', 'image-container image-container--animated');
        const imageElement = createSimpleElement('div', 'image');

        const { imageURL } = data;
        imageElement.style.backgroundImage = `url("${imageURL}")`;
        imageContainer.appendChild(imageElement);

        container.append(imageContainer, Item.createInfoContainer(data, Item.createInfoIconsElement(data)));
        return container;
    }
}