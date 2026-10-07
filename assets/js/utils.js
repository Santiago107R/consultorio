window.redirect = function (url, replace = false) {
    if (replace) {
        window.location.replace(url);
    } else {
        window.location.assign(url);
    }
};