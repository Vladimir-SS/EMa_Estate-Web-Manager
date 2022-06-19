
Options.onSubmit = () => {
    window.history.replaceState(null, null, Options.toGetParams());

    const items = document.getElementById('items');
    items.innerHTML = "";
    SearchHandler.getItems();
};

DocumentHandler.whenReady(() => {
    SearchHandler.resizeHandler();
    SearchHandler.getItems();
})

window.addEventListener('resize', SearchHandler.resizeHandler);
