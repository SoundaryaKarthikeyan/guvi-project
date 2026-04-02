$(document).ready(function () {

    let email = localStorage.getItem("user");

    if (!email) {
        window.location = "login.html";
        return;
    }

    // Populate age dropdown
    for (let i = 1; i <= 100; i++) {
        $("#age").append(`<option value="${i}">${i}</option>`);
    }

    // Fetch profile
    $.ajax({
        url: "php/profile.php",
        type: "GET",
        data: { email: email },
        success: function (res) {

            let data = JSON.parse(res);

            if (data.status === "success") {

                $("#name").val(data.name);
                $("#age").val(data.age);
                $("#dob").val(data.dob);
                $("#contact").val(data.contact);

                if (data.photo) {
                    $("#preview").attr("src", data.photo + "?t=" + new Date().getTime());
                } else {
                    $("#preview").attr("src", "assets/Profile_avatar_placeholder_large.png");
                }
            }
        }
    });

});


// ENABLE EDIT MODE
$("#editBtn").click(function () {

    $("#name, #age, #dob, #contact").prop("disabled", false);

    // show upload option
    $("#photo").removeClass("d-none");

    $("#editBtn").hide();
    $("#saveBtn").show();
});


// CLICK IMAGE TO UPLOAD
$("#preview").click(function () {
    if (!$("#photo").hasClass("d-none")) {
        $("#photo").click();
    }
});


// IMAGE PREVIEW BEFORE SAVE
$("#photo").change(function () {

    let file = this.files[0];

    if (file) {
        let reader = new FileReader();

        reader.onload = function (e) {
            $("#preview").attr("src", e.target.result);
        };

        reader.readAsDataURL(file);
    }
});


// SAVE PROFILE
$("#saveBtn").click(function () {

    let email = localStorage.getItem("user");

    let name = $("#name").val();
    let age = $("#age").val();
    let dob = $("#dob").val();
    let contact = $("#contact").val();

    // VALIDATION
    if (!name) {
        alert("Name is required");
        return;
    }

    if (!age) {
        alert("Please select your age");
        return;
    }

    if (!dob) {
        alert("Please select your date of birth");
        return;
    }

    if (!/^[0-9]{10}$/.test(contact)) {
        alert("Phone number must be exactly 10 digits");
        return;
    }

    let formData = new FormData();

    formData.append("email", email);
    formData.append("name", name);
    formData.append("age", age);
    formData.append("dob", dob);
    formData.append("contact", contact);

    let file = $("#photo")[0].files[0];
    if (file) {
        formData.append("photo", file);
    }

    $.ajax({
        url: "php/profile.php",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function () {

            alert("Profile updated successfully!");

            // disable again
            $("#name, #age, #dob, #contact").prop("disabled", true);

            $("#photo").addClass("d-none");

            $("#saveBtn").hide();
            $("#editBtn").show();
        }
    });

});


// LOGOUT
$("#logout").click(function () {

    $.ajax({
        url: "php/logout.php",
        type: "POST",
        data: {
            email: localStorage.getItem("user")
        },
        success: function () {
            localStorage.removeItem("user");
            window.location = "login.html";
        }
    });

});