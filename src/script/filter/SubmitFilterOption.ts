import FilterOption from "./FilterOption";

class SubmitFilterOption extends FilterOption {
  constructor(onClick: () => void) {
    super();

    this.setLabelTextIcon("Caută acum anunțuri", "magnifying-glass");
    this.labelElement.classList.add("label--important");
    this.element.classList.add("filter-option--submit");

    this.labelElement.onclick = onClick;
  }
}

export default SubmitFilterOption;
