class SubmitFilterOption extends FilterOption {
    constructor() {
        super("submit");
        this.setLabelTextIcon("Caută acum anunțuri", "magnifying-glass");
        this.labelElement.classList.add("label--important");
        this.element.classList.add("filter-option--submit");
        this.labelElement.onclick = FilterOptionHandler.submit;
    }
}
