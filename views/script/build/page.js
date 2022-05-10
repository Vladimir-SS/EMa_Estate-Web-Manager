DocumentHandler.ready();
const createIcon = (name) => {
    let iconElement = document.createElement("span");
    iconElement.className = `icon icon-${name}`;
    return iconElement;
};
