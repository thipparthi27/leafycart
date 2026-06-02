(function () {
    var style = document.createElement("style");
    style.textContent = [
        ".auth-logged-out #nav-profile,",
        ".auth-logged-out #nav-logout,",
        ".auth-logged-in #nav-login{display:none!important;}",
        ".auth-logged-in #nav-profile,",
        ".auth-logged-in #nav-logout{display:list-item!important;}"
    ].join("");
    document.head.appendChild(style);

    function getLoggedInUserFromStorage() {
        try {
            return JSON.parse(localStorage.getItem("loggedInUser"));
        } catch (error) {
            return null;
        }
    }

    window.updateAuthStateClass = function () {
        var user = getLoggedInUserFromStorage();
        var isLoggedIn = !!(user && user.name);

        document.documentElement.classList.toggle("auth-logged-in", isLoggedIn);
        document.documentElement.classList.toggle("auth-logged-out", !isLoggedIn);
    };

    window.updateAuthStateClass();
}());
