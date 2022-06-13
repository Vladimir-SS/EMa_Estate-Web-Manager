function createSimpleElement<K extends keyof HTMLElementTagNameMap>(tagName: K, classes: string): HTMLElementTagNameMap[K] {
    const container = document.createElement(tagName);
    container.setAttribute('class', classes);
    return container;
}

const createIcon = (name: string) => {
    return createSimpleElement("span", `icon icon-${name}`);
}

const hamburgerClickHandler = () => {
    document.getElementById("nav-list").classList.toggle("show");
}

const getCookie = (cookieName: string) => {
    let cookie = {};
    document.cookie.split(';').forEach(function (el) {
        let [key, value] = el.split('=');
        cookie[key.trim()] = value;
    })
    return cookie[cookieName];
}

function deleteCookie(name, path, domain) {
    if (getCookie(name)) {
        document.cookie = name + "=" +
            ((path) ? ";path=" + path : "") +
            ((domain) ? ";domain=" + domain : "") +
            ";expires=Thu, 01 Jan 1970 00:00:01 GMT";
    }
}

const createLi = (text: string, href: string, liClass: string): HTMLLIElement => {
    let li = document.createElement("li");
    let a = document.createElement("a");
    a.appendChild(document.createTextNode(text));
    a.href = href;
    li.appendChild(a);
    li.setAttribute("class", liClass);

    return li;
}

const logout = () => {
    deleteCookie("user", "/", "");
}

const loggedIn = () => {
    if (getCookie('user')) {
        document.getElementById('login').remove();
        document.getElementById('register').remove();

        let liProfile = createLi("Profil", "/profile", "right");
        var ul = document.getElementById("nav-list");
        ul.appendChild(liProfile);

        let liLogout = createLi("Deconectează-te", "/login", "");
        liLogout.setAttribute("onclick", "logout()");

        ul.appendChild(liLogout);
    }
}
DocumentHandler.whenReady(loggedIn);
DocumentHandler.ready();