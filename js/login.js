$("#btn").click(function () {
    $.ajax({
        url: "php/login.php",
        type: "POST",
        data: {
            email: $("#email").val(),
            password: $("#password").val()
        },
        success: function (res) {
            if (res === "success") {
                localStorage.setItem("user", $("#email").val());
                window.location = "profile.html";
            } else {
                alert("Invalid login");
            }
        }
    });
});