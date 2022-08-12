import { createIcon } from "../shared/functions";
import FilterOptionHandler from "./FilterOptionHandler";

class FilterOption {
  public element: HTMLDivElement;
  protected labelElement: HTMLDivElement;
  protected contentBoxElement: HTMLDivElement;
  protected textNode: HTMLSpanElement;

  private static createLabelElement = () => {
    let labelElement = document.createElement("div");
    labelElement.className = `label label--flex`;
    labelElement.setAttribute("onclick", "");
    return labelElement;
  };

  private static createContentBoxElement = () => {
    let contentBoxElement = document.createElement("div");
    contentBoxElement.className = "content__box";

    return contentBoxElement;
  };

  protected setLabelTextIcon = (text: string, iconName: string) => {
    this.textNode = document.createElement("span");
    this.textNode.innerText = text;
    this.labelElement.append(this.textNode);
    this.labelElement.appendChild(createIcon(iconName));
  };

  constructor() {
    let element = document.createElement("div");
    element.className = "filter-option";

    let labelElement = FilterOption.createLabelElement();
    labelElement.onclick = () => {
      FilterOptionHandler.labelOnClickEventHandler(this);
    };

    let contentBoxElement = FilterOption.createContentBoxElement();

    element.appendChild(labelElement);
    element.appendChild(contentBoxElement);

    this.labelElement = labelElement;
    this.contentBoxElement = contentBoxElement;
    this.element = element;
  }
}

export default FilterOption;
