DocumentHandler.whenReady(() => {
    let filterElement = document.getElementById("filter");
    let lebelElement = document.createTextNode("Test");
    let aux = new FilterOption(lebelElement, "", null);
    lebelElement.after(createIcon("keyboard_arrow_down"));
    filterElement.appendChild(aux.element);
});
