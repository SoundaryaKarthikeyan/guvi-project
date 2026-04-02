$("#btn").click(function () {
    $.ajax({
        url: "php/register.php",
        type: "POST",
        data: {
            email: $("#email").val(),
            password: $("#password").val()
        },
        success: function (res) {
            alert(res);
            window.location = "login.html";
        }
    });
});