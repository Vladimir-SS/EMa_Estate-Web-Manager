class DropdownFilterOption extends FilterOption {
    constructor(name, optionList) {
        super(name);
        this.chosenOptionIndex = 0;
        this.onChange = (__index, __text) => { };
        this.createOptionList = () => {
            let listElement = document.createElement("ul");
            this.options.forEach((text, index) => {
                const element = DropdownFilterOption.createOptionItem(text);
                element.onclick = () => this.optionItemHandler(index);
                listElement.append(element);
            });
            return listElement;
        };
        this.optionItemHandler = (index) => {
            this.chosenOptionIndex = index;
            this.textNode.textContent = this.options[index];
            this.labelElement.classList.add("label--important");
            FilterOptionHandler.closeLastElement();
            const op = this.getCurrentOption();
            this.onChange(op.index, op.text);
        };
        this.getParameters = () => {
            if (this.labelElement.classList.contains("label--important"))
                return this.getCurrentOption().text;
            return {};
        };
        this.options = optionList;
        this.setLabelTextIcon("", "down-arrow");
        this.contentBoxElement.appendChild(this.createOptionList());
        this.element.classList.add("filter-option--dropdown");
        this.optionItemHandler(0);
    }
    static createWithDefault(name, optionList, defaultPlaceholder) {
        const instance = new DropdownFilterOption(name, [defaultPlaceholder, ...optionList]);
        const child = instance.element.getElementsByTagName("ul")[0].firstChild;
        child.onclick = () => {
            instance.optionItemHandler(0);
            instance.labelElement.classList.remove("label--important");
        };
        child.onclick(null);
        return instance;
    }
    getCurrentOption() {
        return {
            index: this.chosenOptionIndex,
            text: this.options[this.chosenOptionIndex]
        };
    }
}
DropdownFilterOption.createOptionItem = (text) => {
    let element = document.createElement("li");
    element.innerHTML = text;
    element.setAttribute("onclick", "");
    return element;
};
