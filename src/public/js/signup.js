function signup() {
    let data = {
        name        : document.getElementById("name").value,
        login       : document.getElementById("login").value,
        password    : document.getElementById("password").value,
        repeat      : document.getElementById("repeat").value
    };

    fetch(url + "auth/signup", {
        method: "POST",
        headers: {
            "Content-Type": "application/json;charset=utf-8"
        },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(result => {
            if (result.data.result) {
                window.location.href = "signin";
            } else {
                document.getElementById("alert").innerHTML = result.data.message;
                document.getElementById("alert").style.display = "block";
            }
        })
}