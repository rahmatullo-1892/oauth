function auth() {
    let data = new URLSearchParams();
    data.append("grant_type", "password");
    data.append("client_id", "myproject");
    data.append("client_secret", "abc123");
    data.append("username", document.getElementById("login").value);
    data.append("password", document.getElementById("password").value);

    fetch(url + "oauth/auth", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
            "Accept" : "1.0"
        },
        body: data
    })  .then(response => response.json())
        .then(result => {
            console.log(result);
            if (typeof result.data != "undefined") {
                document.getElementById("alert").innerHTML = "Логин и/или пароль введены неправильно";
                document.getElementById("alert").style.display = "block";
            } else {
                localStorage.setItem("Access-Token", result.access_token);
                localStorage.setItem("Refresh-Token", result.refresh_token);
                document.cookie = "Access-Token=" + result.access_token + "; path=/; domain=localhost;expires=" + result.expires_in;
                document.cookie = "Refresh-Token=" + result.refresh_token;
                window.location.href = "/";
            }

    });
}