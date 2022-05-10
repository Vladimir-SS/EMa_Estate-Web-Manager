

DocumentHandler.whenReady(() => {
    let filterElement = document.getElementById("filter");

    let lebelElement = document.createTextNode("Test");

    let aux = new FilterOption(lebelElement, "", null);
    lebelElement.after(createIcon("keyboard_arrow_down"));
    filterElement.appendChild(aux.element);
});

// // class OptionHandler {
// //     static #lastElement;
// //     static #filter = {};

// //     static #closeLastElement = () => {
// //         this.#lastElement.parentElement.classList.remove("show");
// //         this.#lastElement = null;
// //     }

// //     static revealOption = (e) => {
// //         if (this.#lastElement) {
// //             let closeThis = this.#lastElement === e;

// //             this.#closeLastElement();

// //             if (closeThis)
// //                 return;

// //         }

// //         e.parentElement.classList.add("show");
// //         this.#lastElement = e;
// //     }

// //     static chooseThis = (e) => {
// //         this.#lastElement.firstChild.textContent = e.textContent;
// //         this.#filter[this.#lastElement.parentElement.id] = e.textContent;

// //         this.#closeLastElement();
// //     }

// //     static submit = () => {
// //         console.log(this.#filter);
// //     }
// // }