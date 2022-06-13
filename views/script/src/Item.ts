interface ItemData {
    title: string
    description: string
    imageURL: string
    rooms: number
    garages: number
}

class Item {

    public static createInfoContainer() {
        const infoContainer = createSimpleElement('div', 'content__box--item__info flex-1');

        const titleAndPrice = createSimpleElement('h2', 'accent');
        const description = createSimpleElement('p', 'flex-1');
        const address = createSimpleElement('p', 'secondary icon-text');
        address.appendChild(createSimpleElement('span', 'icon icon-pin'));

        infoContainer.append(titleAndPrice, description, address, Item.createInfoIcons());

        return infoContainer;
    }

    public static createInfoIcons() {
        const infoIcons = createSimpleElement('div', 'content__box--item__info__icons');

        let basicIcons = createSimpleElement('div', 'content__box--item__info__icons__basic');

        const surface = createSimpleElement('p', 'icon-text')
        const bathrooms = createSimpleElement('p', 'icon-text');
        const parking_lots = createSimpleElement('p', 'icon-text');
        const rooms = createSimpleElement('p', 'icon-text');

        surface.appendChild(createSimpleElement('span', 'icon icon-space'));
        bathrooms.appendChild(createSimpleElement('span', 'icon icon-bath'));
        parking_lots.appendChild(createSimpleElement('span', 'icon icon-garage'));
        rooms.appendChild(createSimpleElement('span', 'icon icon-room'));

        basicIcons.append(surface, bathrooms, parking_lots, rooms);

        const saveButton = createSimpleElement('div', 'save-button');
        saveButton.setAttribute('onclick', 'saveButtonClickHandler(this)');
        saveButton.appendChild(createSimpleElement('span', 'icon icon-save'));
        infoIcons.append(basicIcons, saveButton);

        return infoIcons;
    }

    public static create(data: ItemData) {
        const container = createSimpleElement('div', 'content__box content__box--item');
        const imageContainer = createSimpleElement('div', 'image-container image-container--animated');
        const image = createSimpleElement('div', 'image');
        image.style.backgroundImage = 'url( ' + data.imageURL + ' )';

        imageContainer.appendChild(image);

        container.append(imageContainer, Item.createInfoContainer());
    }
}