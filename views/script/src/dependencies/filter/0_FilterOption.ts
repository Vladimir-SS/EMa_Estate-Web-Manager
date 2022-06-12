class FilterOption {
    public element: HTMLDivElement;
    protected labelElement: HTMLDivElement;
    protected contentBoxElement: HTMLDivElement;
    protected textNode: HTMLSpanElement;
    protected name: String;

    private static createLabelElement = () => {
        let labelElement = document.createElement("div");
        labelElement.className = `label label--flex`;
        labelElement.setAttribute("onclick", "");

        return labelElement;
    }

    private static createContentBoxElement = () => {
        let contentBoxElement = document.createElement("div");
        contentBoxElement.className = "content__box";

        return contentBoxElement;
    }

    protected setLabelTextIcon = (text: string, iconName: string) => {
        this.textNode = document.createElement("span");
        this.textNode.innerText = text;
        this.labelElement.append(this.textNode);
        this.labelElement.appendChild(createIcon(iconName));
    }

    public getParameters = (): string => {
        return null;
    }

    constructor(name: string) {
        this.name = name;
        let element = document.createElement("div");
        element.className = "filter-option";

        let labelElement = FilterOption.createLabelElement();
        labelElement.onclick = () => { FilterOptionHandler.labelOnClickEventHandler(this) };

        let contentBoxElement = FilterOption.createContentBoxElement();

        element.appendChild(labelElement);
        element.appendChild(contentBoxElement);

        this.labelElement = labelElement;
        this.contentBoxElement = contentBoxElement;
        this.element = element;
    }
}
