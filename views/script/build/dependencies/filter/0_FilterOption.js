class FilterOption {
    constructor(name) {
        this.setLabelTextIcon = (text, iconName) => {
            this.textNode = document.createElement("span");
            this.textNode.innerText = text;
            this.labelElement.append(this.textNode);
            this.labelElement.appendChild(createIcon(iconName));
        };
        this.getParameters = () => {
            return {};
        };
        this.name = name;
        let element = document.createElement("div");
        element.className = "filter-option";
        let labelElement = FilterOption.createLabelElement();
        labelElement.onclick = () => { FilterOptionHandler.labelOnClickEventHandler(this); };
        let contentBoxElement = FilterOption.createContentBoxElement();
        element.appendChild(labelElement);
        element.appendChild(contentBoxElement);
        this.labelElement = labelElement;
        this.contentBoxElement = contentBoxElement;
        this.element = element;
    }
}
FilterOption.createLabelElement = () => {
    let labelElement = document.createElement("div");
    labelElement.className = `label label--flex`;
    labelElement.setAttribute("onclick", "");
    return labelElement;
};
FilterOption.createContentBoxElement = () => {
    let contentBoxElement = document.createElement("div");
    contentBoxElement.className = "content__box";
    return contentBoxElement;
};
