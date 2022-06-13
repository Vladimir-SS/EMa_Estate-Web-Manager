interface DropdownStringOption {
    text: string;
    linked: HTMLElement[];
}

type DropdownOption = string | DropdownStringOption;

interface DropdownElementOption {
    element: HTMLElement;
    linked: HTMLElement[];
}

class DropdownFilterOption extends FilterOption {
    private chosenOptionIndex: number = 0;
    private options: DropdownElementOption[];

    private createOptionItem = (text: string) => {
        let element = document.createElement("li");
        element.innerHTML = text;
        element.setAttribute("onclick", "");

        return element;
    }

    private createOptionList = (optionList: DropdownOption[]) => {
        let listElement = document.createElement("ul");

        this.options = optionList.map(op => {
            let
                text: string,
                linked: HTMLElement[];

            if (typeof (op) === 'string') {
                text = op;
                linked = [];
            } else {
                text = op.text;
                linked = op.linked;
            }

            const element = this.createOptionItem(text);
            return { element, linked };
        })

        this.options.forEach((op, index) => {
            op.element.onclick = () => this.optionItemHandler(index);
            listElement.append(op.element);
            op.linked.forEach(el => el.style.display = "none");
        })

        return listElement;
    }

    private optionItemHandler = (index: number) => {
        this.options[this.chosenOptionIndex]
            .linked.forEach(el => el.style.display = "none");
        this.chosenOptionIndex = index;
        const option = this.options[index];
        const { element, linked } = option;

        this.textNode.textContent = element.innerHTML;
        this.labelElement.classList.add("label--important")
        FilterOptionHandler.closeLastElement();

        linked.forEach(el => el.style.removeProperty("display"));
    }

    public static create(name: string, optionList: DropdownOption[]) {
        const instance = new DropdownFilterOption(name);
        instance.setLabelTextIcon("", "down-arrow");

        instance.contentBoxElement.appendChild(instance.createOptionList(optionList));
        instance.element.classList.add("filter-option--dropdown")

        instance.optionItemHandler(0);

        return instance;
    }

    static createWithDefault(name: string, optionList: DropdownOption[], defaultPlaceholder: string) {
        const instance = this.create(name, [defaultPlaceholder, ...optionList]);
        let child = instance.options[0].element;

        child.onclick = () => {
            instance.optionItemHandler(0);
            instance.labelElement.classList.remove("label--important");
        };

        child.onclick(null);

        return instance;
    }

    override  getParameters = () => {

        if (this.labelElement.classList.contains("label--important"))
            return {
                [this.name]: this.options[this.chosenOptionIndex].element.textContent
            }
    }
}