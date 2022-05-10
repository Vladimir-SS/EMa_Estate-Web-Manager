class FilterOption {
    public element: Element;

    static #createLabel = (content: string | Node) => {
        let lebelElement = document.createElement("div");
        lebelElement.className = `label label--flex`;
        lebelElement.append(content);


        //     <div class="label icon-field" onclick="OptionHandler.revealOption(this)">
        //     <?php
        //     echo $option_name;
        //     echo View::render_vector($option_vector);
        //     ?>
        // </div>

        return lebelElement;
    }

    constructor(labelContent: string | Node, className: string, content: Node) {
        this.element = document.createElement("div");
        this.element.className = `filter-option ${className}`;

        this.element.appendChild(FilterOption.#createLabel(labelContent));
    }
}