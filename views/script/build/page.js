DocumentHandler.ready();
const createIcon = (name) => {
    let iconElement = document.createElement("span");
    iconElement.className = `icon icon-${name}`;
    return iconElement;
};
const hamburgerClickHandler = () => {
    document.getElementById("nav-list").classList.toggle("show");
};
const getCookie = (cookieName) => {
    let cookie = {};
    document.cookie.split(';').forEach(function (el) {
        let [key, value] = el.split('=');
        cookie[key.trim()] = value;
    });
    return cookie[cookieName];
};
function deleteCookie(name, path, domain) {
    if (getCookie(name)) {
        document.cookie = name + "=" +
            ((path) ? ";path=" + path : "") +
            ((domain) ? ";domain=" + domain : "") +
            ";expires=Thu, 01 Jan 1970 00:00:01 GMT";
    }
}
const createLi = (text, href, liClass) => {
    let li = document.createElement("li");
    let a = document.createElement("a");
    a.appendChild(document.createTextNode(text));
    a.href = href;
    li.appendChild(a);
    li.setAttribute("class", liClass);
    return li;
};
const logout = () => {
    deleteCookie("user", "/", "");
};
const loggedIn = () => {
    if (getCookie('user')) {
        document.getElementById('login').remove();
        document.getElementById('register').remove();
        let liProfile = createLi("Profil", "/profile", "right");
        var ul = document.getElementById("nav-list");
        ul.appendChild(liProfile);
        let liLogout = createLi("Deconectează-te", "/login", "right");
        liLogout.setAttribute("onclick", "logout()");
        ul.appendChild(liLogout);
    }
};
DocumentHandler.whenReady(loggedIn);
