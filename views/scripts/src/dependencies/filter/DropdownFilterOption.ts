
interface DropdownOption {
    index: number;
    text: string;
}

type DropdownOnChangeFunction = (index: number, text: string) => void;

class DropdownFilterOption extends FilterOption {
    private chosenOptionIndex: number = 0;
    private options: string[];
    private onChangeFunc: DropdownOnChangeFunction[] = [];

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
            element.onclick = () => this.chooseOption(index);
            listElement.append(element);
        })

        return listElement;
    }


    public chooseOption = (index: number) => {
        this.chosenOptionIndex = index;

        this.textNode.textContent = this.options[index];
        this.labelElement.classList.add("label--important")
        FilterOptionHandler.closeLastElement();

        const op = this.getOption();
        this.onChangeFunc.forEach(func => func(op.index, op.text));
    }

    public onChange = (func: DropdownOnChangeFunction) => {
        this.onChangeFunc.push(func);
        this.chooseOption(this.chosenOptionIndex);
    }

    public constructor(optionList: string[]) {
        super();
        this.options = optionList;
        this.setLabelTextIcon("", "down-arrow");

        this.contentBoxElement.appendChild(this.createOptionList());
        this.element.classList.add("filter-option--dropdown")

        this.chooseOption(0);
    }

    static createWithDefault(optionList: string[], defaultPlaceholder: string) {
        const instance = new DropdownFilterOption([defaultPlaceholder, ...optionList]);
        const child = instance.element.getElementsByTagName("ul")[0].firstChild as HTMLElement;

        child.onclick = () => {
            instance.chooseOption(0);
            instance.labelElement.classList.remove("label--important");
        };

        child.onclick(null);

        return instance;
    }

    public getOption(): DropdownOption {
        return {
            index: this.chosenOptionIndex,
            text: this.options[this.chosenOptionIndex]
        }
    }

    public isSelected() {
        return this.labelElement.classList.contains("label--important");
    }
}