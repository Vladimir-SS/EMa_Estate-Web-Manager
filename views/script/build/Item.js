class Item {
    createItemTemplate() {
        this.container = this.createItemElement('div', 'content__box content__box--item');
        this.imageContainer = this.createItemElement('div', 'image-container image-container--animated');
        this.image = this.createItemElement('div', 'image');
        this.setImage(this.imgURL);
        this.imageContainer.appendChild(this.image);
        this.createInfoContainer();
        this.container.append(this.imageContainer, this.infoContainer);
    }
    createInfoContainer() {
        this.infoContainer = this.createItemElement('div', 'content__box--item__info flex-1');
        this.titleAndPrice = this.createItemElement('h2', 'accent');
        this.description = this.createItemElement('p', 'flex-1');
        this.address = this.createItemElement('p', 'secondary icon-text');
        this.address.appendChild(this.createItemElement('span', 'icon icon-pin'));
        this.createInfoIcons();
        this.infoContainer.append(this.titleAndPrice, this.description, this.address, this.infoIcons);
    }
    createInfoIcons() {
        this.infoIcons = this.createItemElement('div', 'content__box--item__info__icons');
        let basicIcons = this.createItemElement('div', 'content__box--item__info__icons__basic');
        this.surface = this.createItemElement('p', 'icon-text');
        this.bathrooms = this.createItemElement('p', 'icon-text');
        this.parking_lots = this.createItemElement('p', 'icon-text');
        this.rooms = this.createItemElement('p', 'icon-text');
        this.surface.appendChild(this.createItemElement('span', 'icon icon-space'));
        this.bathrooms.appendChild(this.createItemElement('span', 'icon icon-bath'));
        this.parking_lots.appendChild(this.createItemElement('span', 'icon icon-garage'));
        this.rooms.appendChild(this.createItemElement('span', 'icon icon-room'));
        basicIcons.append(this.surface, this.bathrooms, this.parking_lots, this.rooms);
        let saveButton = this.createItemElement('div', 'save-button');
        saveButton.setAttribute('onclick', 'saveButtonClickHandler(this)');
        saveButton.appendChild(this.createItemElement('span', 'icon icon-save'));
        this.infoIcons.append(basicIcons, saveButton);
    }
    fillItemTemplate(data) {
    }
    setImage(imgURL) {
        this.image.style.backgroundImage = 'url( ' + imgURL + ' )';
    }
    createItemElement(tag, containerClass) {
        let container = document.createElement(tag);
        container.setAttribute('class', containerClass);
        return container;
    }
}
