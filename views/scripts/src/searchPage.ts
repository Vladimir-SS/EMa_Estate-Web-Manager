const itemsElement = document.getElementById('items');

const resizeHandler = () => {
    if (window.innerWidth <= 1220)
        itemsElement.classList.add('chubby-items');
    else
        itemsElement.classList.remove('chubby-items');
}

const toApiParams = (): string => {

    //ce?? Nu e cod duplicat...
    const dropdownParams = Object.entries(Options.dropdown)
        .filter(([__key, op]) => op.element.style.display != "none" && op.isSelected())
        .map(([key, op]) => `${key}=${op.getOption().index}`);

    const sliderParams = Object.entries(Options.slider)
        .filter(([__key, op]) => op.element.style.display != "none" && op.isSelected())
        .map(([key, op]) => Object.entries(op.getOption()).map(([minmax, value]) => `${key}${minmax}=${value}`).join("&")
        );

    return "?" + [...dropdownParams, ...sliderParams].join("&");
}

const getItems = () => {
    console.log(toApiParams());
    window.history.replaceState(null, null, Options.toGetParams());
}

Options.onSubmit = getItems;

DocumentHandler.whenReady(() => {
    resizeHandler();
    getItems();
})

window.addEventListener('resize', resizeHandler);