

const hamburgerClickHandler = () => {
    document.getElementById("nav-list").classList.toggle("show");
}

const saveButtonClickHandler = (element) => {
    element.classList.toggle("save-button--is-saved")
}