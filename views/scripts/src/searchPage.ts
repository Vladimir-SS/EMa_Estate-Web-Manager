const itemsElement = document.getElementById('items');

const resizeHandler = () => {
    if (window.innerWidth <= 1220)
        itemsElement.classList.add('chubby-items');
    else
        itemsElement.classList.remove('chubby-items');

}

DocumentHandler.whenReady(() => {
    resizeHandler();

})

window.addEventListener('resize', resizeHandler);