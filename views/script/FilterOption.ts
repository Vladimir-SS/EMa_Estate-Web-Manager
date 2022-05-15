class FilterOption {
    public element: HTMLDivElement;
    protected labelElement: HTMLDivElement;
    protected contentBoxElement: HTMLDivElement;
    protected textNode: Text;
    protected name: String;

    private static createLabelElement = () => {
        let labelElement = document.createElement("div");
        labelElement.className = `label label--flex`;
        labelElement.setAttribute("onclick", "");

        return labelElement;
    }

    private static createContentBoxElement = () => {
        let contentBoxElement = document.createElement("div");
        contentBoxElement.className = "content-box";

        return contentBoxElement;
    }

    protected setLabelTextIcon = (text: string, iconName: string) => {
        this.textNode = document.createTextNode(text);
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


class DropdownFilterOption extends FilterOption {
    private chosenOptionItem: HTMLLIElement = null;

    private createOptionItem = (text: string) => {
        let element = document.createElement("li");
        element.innerHTML = text;
        element.setAttribute("onclick", "");
        element.onclick = () => this.optionItemHandler(element);

        return element;
    }

    private createOptionList = (optionList: string[]) => {
        let listElement = document.createElement("ul");

        optionList
            .map(this.createOptionItem)
            .forEach(e => listElement.append(e));

        return listElement;
    }

    private optionItemHandler = (optionItem: HTMLLIElement) => {
        this.textNode.textContent = optionItem.innerHTML;
        this.chosenOptionItem = optionItem;
        this.labelElement.classList.add("label--important")
        FilterOptionHandler.closeLastElement();
    }

    private static create(name: string, optionList: string[], labelText: string) {
        let instance = new DropdownFilterOption(name);
        instance.setLabelTextIcon(labelText, "down-arrow")

        instance.contentBoxElement.appendChild(instance.createOptionList(optionList));
        instance.element.classList.add("filter-option--dropdown")

        return instance;
    }

    static createWithIndex(name: string, optionList: string[], index: number) {
        let instance = this.create(name, optionList, optionList[index]);
        let child = instance.contentBoxElement.firstElementChild.children[index] as HTMLLIElement;;

        console.log(child);


        instance.optionItemHandler(child);

        return instance;
    }

    static createWithDefault(name: string, optionList: string[], defaultPlaceholder: string) {
        let instance = this.create(name, [defaultPlaceholder, ...optionList], defaultPlaceholder);
        let child = instance.contentBoxElement.firstChild.firstChild as HTMLLIElement;

        child.onclick = () => {
            instance.optionItemHandler(child);
            instance.labelElement.classList.remove("label--important")
            instance.chosenOptionItem = null;
        };

        return instance;
    }

    override  getParameters = () => {
        return this.chosenOptionItem ? `${this.name}=${this.chosenOptionItem.innerHTML}` : null;
    }
}