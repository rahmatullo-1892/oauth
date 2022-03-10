function getData() {
    fetch(url + "app/profile", {
        method: "POST",
        headers: {
            "Authorization": "Bearer " + getCookie("Access-Token")
        },
    })  .then(response => response.json())
        .then(result => {
            console.log(result.data);
            document.getElementById("name").innerText = result.data[0].name;
            document.getElementById("login").innerText = result.data[0].login;
            document.getElementById("created_at").innerText = result.data[0].created_at;
    });
}

getData();