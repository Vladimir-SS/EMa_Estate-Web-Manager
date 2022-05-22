DocumentHandler.ready();

const createIcon = (name: string) => {
    let iconElement = document.createElement("span");
    iconElement.className = `icon icon-${name}`;

    return iconElement;
}

const hamburgerClickHandler = () => {
    document.getElementById("nav-list").classList.toggle("show");
}