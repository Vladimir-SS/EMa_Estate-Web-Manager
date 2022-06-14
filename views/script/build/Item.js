class Item {
    static createInfoContainer(data) {
        const infoContainer = createSimpleElement('div', 'content__box--item__info flex-1');
        const price = createSimpleElement('h2', 'accent');
        price.innerHTML = data.PRICE + Item.PRICE_TYPE;
        const title = createSimpleElement('p', 'text-wrap');
        title.innerText = data.TITLE;
        const address = createSimpleElement('p', 'secondary icon-text');
        address.appendChild(createSimpleElement('span', 'icon icon-pin'));
        var addr = createSimpleElement('p', 'text-wrap');
        addr.innerText = data.ADDRESS;
        address.appendChild(addr);
        infoContainer.append(price, title, address, Item.createInfoIcons(data));
        return infoContainer;
    }
    static createInfoIcons(data) {
        const infoIcons = createSimpleElement('div', 'content__box--item__info__icons');
        let basicIcons = createSimpleElement('div', 'content__box--item__info__icons__basic');
        const surface = createSimpleElement('p', 'icon-text');
        const bathrooms = createSimpleElement('p', 'icon-text');
        const parking_lots = createSimpleElement('p', 'icon-text');
        const rooms = createSimpleElement('p', 'icon-text');
        surface.appendChild(createSimpleElement('span', 'icon icon-space'));
        bathrooms.appendChild(createSimpleElement('span', 'icon icon-bath'));
        parking_lots.appendChild(createSimpleElement('span', 'icon icon-garage'));
        rooms.appendChild(createSimpleElement('span', 'icon icon-room'));
        surface.appendChild(document.createTextNode(data.SURFACE));
        bathrooms.appendChild(document.createTextNode(data.BUILDING.BATHROOMS));
        parking_lots.appendChild(document.createTextNode(data.BUILDING.PARKING_LOTS));
        rooms.appendChild(document.createTextNode(data.BUILDING.ROOMS));
        basicIcons.append(surface, bathrooms, parking_lots, rooms);
        const saveButton = createSimpleElement('div', 'save-button');
        saveButton.setAttribute('onclick', 'saveButtonClickHandler(this)');
        saveButton.appendChild(createSimpleElement('span', 'icon icon-save'));
        infoIcons.append(basicIcons, saveButton);
        return infoIcons;
    }
    static create(data) {
        const container = createSimpleElement('div', 'content__box content__box--item');
        const imageContainer = createSimpleElement('div', 'image-container image-container--animated');
        const image = createSimpleElement('div', 'image');
        if (typeof data.IMAGE !== 'undefined' && data.IMAGE !== null) {
            image.style.backgroundImage = 'url( "data: ' + data.IMAGE.TYPE + '; base64, ' + data.IMAGE.IMAGE + '" ) ';
            imageContainer.appendChild(image);
        }
        container.append(imageContainer, Item.createInfoContainer(data));
        return container;
    }
}
Item.PRICE_TYPE = ' RON';
