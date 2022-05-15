class FilterOption {
    constructor(name) {
        this.setLabelTextIcon = (text, iconName) => {
            this.textNode = document.createTextNode(text);
            this.labelElement.append(this.textNode);
            this.labelElement.appendChild(createIcon(iconName));
        };
        this.getParameters = () => {
            return null;
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
    contentBoxElement.className = "content-box";
    return contentBoxElement;
};
class DropdownFilterOption extends FilterOption {
    constructor() {
        super(...arguments);
        this.chosenOptionItem = null;
        this.createOptionItem = (text) => {
            let element = document.createElement("li");
            element.innerHTML = text;
            element.setAttribute("onclick", "");
            element.onclick = () => this.optionItemHandler(element);
            return element;
        };
        this.createOptionList = (optionList) => {
            let listElement = document.createElement("ul");
            optionList
                .map(this.createOptionItem)
                .forEach(e => listElement.append(e));
            return listElement;
        };
        this.optionItemHandler = (optionItem) => {
            this.textNode.textContent = optionItem.innerHTML;
            this.chosenOptionItem = optionItem;
            this.labelElement.classList.add("label--important");
            FilterOptionHandler.closeLastElement();
        };
        this.getParameters = () => {
            return this.chosenOptionItem ? `${this.name}=${this.chosenOptionItem.innerHTML}` : null;
        };
    }
    static create(name, optionList, labelText) {
        let instance = new DropdownFilterOption(name);
        instance.setLabelTextIcon(labelText, "down-arrow");
        instance.contentBoxElement.appendChild(instance.createOptionList(optionList));
        instance.element.classList.add("filter-option--dropdown");
        return instance;
    }
    static createWithIndex(name, optionList, index) {
        let instance = this.create(name, optionList, optionList[index]);
        let child = instance.contentBoxElement.firstElementChild.children[index];
        ;
        console.log(child);
        instance.optionItemHandler(child);
        return instance;
    }
    static createWithDefault(name, optionList, defaultPlaceholder) {
        let instance = this.create(name, [defaultPlaceholder, ...optionList], defaultPlaceholder);
        let child = instance.contentBoxElement.firstChild.firstChild;
        child.onclick = () => {
            instance.optionItemHandler(child);
            instance.labelElement.classList.remove("label--important");
            instance.chosenOptionItem = null;
        };
        return instance;
    }
}
