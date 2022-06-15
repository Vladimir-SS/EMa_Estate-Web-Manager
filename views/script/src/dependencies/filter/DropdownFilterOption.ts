
interface DropdownOption {
    index: number;
    text: string;
}

class DropdownFilterOption extends FilterOption {
    private chosenOptionIndex: number = 0;
    private options: string[];
    public onChange: (index: number, text: string) => void = (__index, __text) => { };

    private static createOptionItem = (text: string) => {
        let element = document.createElement("li");
        element.innerHTML = text;
        element.setAttribute("onclick", "");

        return element;
    }

    private createOptionList = () => {
        let listElement = document.createElement("ul");

        this.options.forEach((text, index) => {
            const element = DropdownFilterOption.createOptionItem(text);
            element.onclick = () => this.optionItemHandler(index);
            listElement.append(element);
        })

        return listElement;
    }


    private optionItemHandler = (index: number) => {
        this.chosenOptionIndex = index;

        this.textNode.textContent = this.options[index];
        this.labelElement.classList.add("label--important")
        FilterOptionHandler.closeLastElement();

        const op = this.getCurrentOption();
        this.onChange(op.index, op.text);
    }

    public constructor(name: string, optionList: string[]) {
        super(name);
        this.options = optionList;
        this.setLabelTextIcon("", "down-arrow");

        this.contentBoxElement.appendChild(this.createOptionList());
        this.element.classList.add("filter-option--dropdown")

        this.optionItemHandler(0);
    }

    static createWithDefault(name: string, optionList: string[], defaultPlaceholder: string) {
        const instance = new DropdownFilterOption(name, [defaultPlaceholder, ...optionList]);
        const child = instance.element.getElementsByTagName("ul")[0].firstChild as HTMLElement;

        child.onclick = () => {
            instance.optionItemHandler(0);
            instance.labelElement.classList.remove("label--important");
        };

        child.onclick(null);

        return instance;
    }

    public getCurrentOption(): DropdownOption {
        return {
            index: this.chosenOptionIndex,
            text: this.options[this.chosenOptionIndex]
        }
    }

    override  getParameters = () => {
        if (this.labelElement.classList.contains("label--important"))
            return this.getCurrentOption().text;

        return {}
    }
}