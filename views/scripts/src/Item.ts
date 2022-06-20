interface BaseData {
    id: number
    accountID: number
    type: string
    transactionType: string
    address: string
    title: string
    description: string
    price: number
    surface: number
    imageURL: string
    imagesURLs: string[]
    lon: number,
    lat: number
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

    private static PRICE_TYPE: string = ' Ron';

    public static createInfoContainer(data: BaseData, iconsElement: HTMLElement) {


        const { price, title, address, id } = data;

        const priceElement = createSimpleElement('h2', 'price');
        priceElement.textContent = parseMoney(price) + Item.PRICE_TYPE;

        const titleElement = createSimpleElement('p', 'title text-wrap');
        titleElement.textContent = title;

        const addressElement = createSimpleElement('div', 'address icon-text');
        addressElement.appendChild(createSimpleElement('span', 'icon icon-pin'));
        const addressParagraph = createSimpleElement('p', 'text-wrap');
        addressParagraph.textContent = address;
        addressElement.appendChild(addressParagraph);

        const anchorElement = createSimpleElement('a', "important-info");
        anchorElement.href = `/item?id=${id}`;
        anchorElement.append(priceElement, titleElement, addressElement);

        const infoContainer = createSimpleElement('div', 'content__box--item__info flex-1');
        infoContainer.append(anchorElement, iconsElement);

        return infoContainer;
    }

    public static createInfoIconsElement(data: IconsData) {
        const { surface, bathrooms, rooms, parkingLots } = data;

        const spaceElementInfo = createSimpleElement("p", "surface");
        spaceElementInfo.innerHTML += surface + " m";
        const spaceElement = createSimpleElement('div', 'icon-text icon-text--surface');
        spaceElement.append(createIcon("space"), spaceElementInfo)

        const basicIconsContainer = createSimpleElement('div', 'content__box--item__info__icons__basic');
        basicIconsContainer.appendChild(spaceElement);

        const aux: [number, string][] = [
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


        const infoIconsElement = createSimpleElement('div', 'content__box--item__info__icons');
        infoIconsElement.append(basicIconsContainer, saveButton);
        return infoIconsElement;
    }

    public static create(data: ItemData) {
        const container = createSimpleElement('div', 'content__box content__box--item');


        const { imageURL, id } = data;
        const imageContainer = createSimpleElement('a', 'image-container');
        imageContainer.href = `/item?id=${id}`
        const imageElement = createSimpleElement('div', 'image');
        imageElement.style.backgroundImage = `url("${imageURL}")`;
        imageContainer.appendChild(imageElement);

        container.append(imageContainer, Item.createInfoContainer(data, Item.createInfoIconsElement(data)));
        return container;
    }


    public static createDeletable(data: ItemData, handler: () => void) {
        const container = Item.create(data);

        const deleteEl = createSimpleElement('span', 'icon icon-delete');
        deleteEl.setAttribute("onclick", "");
        deleteEl.onclick = () => {
            handler();
            container.remove();
        };

        container.appendChild(deleteEl);
        return container;
    }
}