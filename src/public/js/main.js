function getCookie(name) {
    let matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, "\\$1") + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}

function refreshToken() {
    let data = new URLSearchParams();
    data.append("grant_type", "refresh_token");
    data.append("client_id", "myproject");
    data.append("client_secret", "abc123");
    data.append("refresh_token", getCookie("Refresh-Token"));
    fetch(url + "oauth/refresh", {
        method: "POST",
        headers: {
            "Authorization": "Bearer " + getCookie("Access-Token")
        },
        body: data
    })  .then(response => response.json())
        .then(result => {
            console.log(result);
            if ((typeof result.data != "undefined" && result.data.result == false ||
                (typeof result.result != "undefined" && result.result == false))) {
                window.location.href = "signin";
            } else if (typeof result.access_token != "undefined") {
                document.cookie = "Access-Token=" + result.access_token + "; path=/; domain=localhost;expires=" + result.expires_in;
                document.cookie = "Refresh-Token=" + result.refresh_token + "; path=/; domain=localhost;expires=" + result.expires_in;
            }
            setTimeout(refreshToken, 300000);
        });
}

if (typeof isRefresh != "undefined") refreshToken();

function logout() {
    fetch(url + "oauth/logout", {
        method: "POST",
        headers: {
            "Authorization": "Bearer " + getCookie("Access-Token")
        },
    })  .then(response => response.json())
        .then(result => {
            console.log(result);
            window.location.href = "signin";
            // document.cookie = "Access-Token=''";
            // document.cookie = "Refresh-Token=''";
        });
}