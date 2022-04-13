{
    let navListElement = document.getElementById("nav-list");

    document.getElementById("hamburger-icon")
        .addEventListener("click", () => {
            navListElement.classList.toggle("show")
        })
}