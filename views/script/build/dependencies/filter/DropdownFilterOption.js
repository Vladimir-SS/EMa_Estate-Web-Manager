class DropdownFilterOption extends FilterOption {
    constructor() {
        super(...arguments);
        this.chosenOptionIndex = 0;
        this.createOptionItem = (text) => {
            let element = document.createElement("li");
            element.innerHTML = text;
            element.setAttribute("onclick", "");
            return element;
        };
        this.createOptionList = (optionList) => {
            let listElement = document.createElement("ul");
            this.options = optionList.map(op => {
                let text, linked;
                if (typeof (op) === 'string') {
                    text = op;
                    linked = [];
                }
                else {
                    text = op.text;
                    linked = op.linked;
                }
                const element = this.createOptionItem(text);
                return { element, linked };
            });
            this.options.forEach((op, index) => {
                op.element.onclick = () => this.optionItemHandler(index);
                listElement.append(op.element);
                op.linked.forEach(el => el.style.display = "none");
            });
            return listElement;
        };
        this.optionItemHandler = (index) => {
            this.options[this.chosenOptionIndex]
                .linked.forEach(el => el.style.display = "none");
            this.chosenOptionIndex = index;
            const option = this.options[index];
            const { element, linked } = option;
            this.textNode.textContent = element.innerHTML;
            this.labelElement.classList.add("label--important");
            FilterOptionHandler.closeLastElement();
            linked.forEach(el => el.style.removeProperty("display"));
        };
        this.getParameters = () => {
            if (this.labelElement.classList.contains("label--important"))
                return {
                    [this.name]: this.options[this.chosenOptionIndex].element.textContent
                };
        };
    }
    static create(name, optionList) {
        const instance = new DropdownFilterOption(name);
        instance.setLabelTextIcon("", "down-arrow");
        instance.contentBoxElement.appendChild(instance.createOptionList(optionList));
        instance.element.classList.add("filter-option--dropdown");
        instance.optionItemHandler(0);
        return instance;
    }
    static createWithDefault(name, optionList, defaultPlaceholder) {
        const instance = this.create(name, [defaultPlaceholder, ...optionList]);
        let child = instance.options[0].element;
        child.onclick = () => {
            instance.optionItemHandler(0);
            instance.labelElement.classList.remove("label--important");
        };
        child.onclick(null);
        return instance;
    }
}
