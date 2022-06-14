interface ItemData {
    ID: string
    ACCOUNT_ID: string
    ADDRESS: string
    TITLE: string
    PRICE: string
    SURFACE: string
    DESCRIPTION: string
    IMAGE: ImageData

    BUILDING: BuildingData
}

interface BuildingData {
    BATHROOMS: string
    PARKING_LOTS: string
    ROOMS: string
}

interface ImageData {
    IMAGE: string
    NAME: string
    TYPE: string
}

class Item {

    protected data: ItemData;
    private static PRICE_TYPE: string = ' RON';

    private container: HTMLDivElement;
    private imageContainer: HTMLDivElement;
    private image: HTMLDivElement;

    private price: HTMLHeadingElement;
    private title: HTMLParagraphElement;
    private addressParagraph: HTMLParagraphElement;

    private surfaceText: Text;
    private bathroomsText: Text;
    private parkingLotsText: Text;
    private roomsText: Text;

    public constructor(data: ItemData) {
        this.data = data;
        this.create();
    }

    public createInfoContainer() {
        const infoContainer = createSimpleElement('div', 'content__box--item__info flex-1');

        this.price = createSimpleElement('h2', 'accent');
        this.price.innerHTML = this.data.PRICE + Item.PRICE_TYPE;
        this.title = createSimpleElement('p', 'text-wrap');
        this.title.innerText = this.data.TITLE;
        const address = createSimpleElement('p', 'secondary icon-text');

        address.appendChild(createSimpleElement('span', 'icon icon-pin'));

        this.addressParagraph = createSimpleElement('p', 'text-wrap');
        this.addressParagraph.innerText = this.data.ADDRESS;

        address.appendChild(this.addressParagraph);
        infoContainer.append(this.price, this.title, address, this.createInfoIcons(this.data));

        return infoContainer;
    }

    public createInfoIcons(data: ItemData) {
        const infoIcons = createSimpleElement('div', 'content__box--item__info__icons');

        let basicIcons = createSimpleElement('div', 'content__box--item__info__icons__basic');

        const surface = createSimpleElement('p', 'icon-text');
        const bathrooms = createSimpleElement('p', 'icon-text');
        const parkingLots = createSimpleElement('p', 'icon-text');
        const rooms = createSimpleElement('p', 'icon-text');

        surface.appendChild(createSimpleElement('span', 'icon icon-space'));
        bathrooms.appendChild(createSimpleElement('span', 'icon icon-bath'));
        parkingLots.appendChild(createSimpleElement('span', 'icon icon-garage'));
        rooms.appendChild(createSimpleElement('span', 'icon icon-room'));

        this.surfaceText = document.createTextNode(data.SURFACE);
        this.bathroomsText = document.createTextNode(data.BUILDING.BATHROOMS);
        this.parkingLotsText = document.createTextNode(data.BUILDING.PARKING_LOTS);
        this.roomsText = document.createTextNode(data.BUILDING.ROOMS);

        surface.appendChild(this.surfaceText);
        bathrooms.appendChild(this.bathroomsText);
        parkingLots.appendChild(this.parkingLotsText);
        rooms.appendChild(this.roomsText);

        basicIcons.append(surface, bathrooms, parkingLots, rooms);

        const saveButton = createSimpleElement('div', 'save-button');
        saveButton.setAttribute('onclick', 'saveButtonClickHandler(this)');
        saveButton.appendChild(createSimpleElement('span', 'icon icon-save'));
        infoIcons.append(basicIcons, saveButton);

        return infoIcons;
    }

    public create() {
        this.container = createSimpleElement('div', 'content__box content__box--item');
        this.imageContainer = createSimpleElement('div', 'image-container image-container--animated');
        this.image = createSimpleElement('div', 'image');
        if (typeof this.data.IMAGE !== 'undefined' && this.data.IMAGE !== null) {
            this.image.style.backgroundImage = 'url( "data: ' + this.data.IMAGE.TYPE + '; base64, ' + this.data.IMAGE.IMAGE + '" ) ';
            this.imageContainer.appendChild(this.image);
        }

        this.container.append(this.imageContainer, this.createInfoContainer());

        return this.container;
    }

    public changeData(data: ItemData) {
        this.data = data;

        if (typeof data.IMAGE !== 'undefined' && data.IMAGE !== null) {
            this.image.style.backgroundImage = 'url( "data: ' + data.IMAGE.TYPE + '; base64, ' + data.IMAGE.IMAGE + '" ) ';
        } else {
            this.image.style.backgroundImage = null;
        }

        this.surfaceText.textContent = data.SURFACE;
        this.bathroomsText.textContent = data.BUILDING.BATHROOMS;
        this.parkingLotsText.textContent = data.BUILDING.PARKING_LOTS;
        this.roomsText.textContent = data.BUILDING.ROOMS;

        this.addressParagraph.innerText = data.ADDRESS;
    }

    public getContainer() {
        return this.container;
    }
}