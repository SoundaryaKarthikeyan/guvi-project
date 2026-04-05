$(document).ready(function () {
    // 1. Get email from localStorage
    let email = localStorage.getItem("user");

    if (!email) {
        window.location = "login.html";
        return;
    }

    // 2. Populate age dropdown
    for (let i = 1; i <= 100; i++) {
        $("#age").append(`<option value="${i}">${i}</option>`);
    }

    // 3. Fetch profile (GET)
    $.ajax({
        url: "php/profile.php",
        type: "GET",
        data: { email: email },
        dataType: "json", // Explicitly tell jQuery to expect JSON
        success: function (data) {
            // No need for JSON.parse(res) if dataType is json
            if (data.status === "success") {
                $("#name").val(data.name);
                $("#age").val(data.age);
                $("#dob").val(data.dob);
                $("#contact").val(data.contact);

                if (data.photo) {
                    // Add timestamp to bypass browser cache for the image
                    $("#preview").attr("src", data.photo + "?t=" + new Date().getTime());
                }
            } else if (data.status === "unauthorized") {
                localStorage.removeItem("user");
                window.location = "login.html";
            }
        },
        error: function() {
            console.error("Could not fetch profile data.");
        }
    });

    // --- BUTTON LOGIC ---

    $("#editBtn").click(function () {
        $("#name, #age, #dob, #contact").prop("disabled", false);
        $("#photo").removeClass("d-none");
        $("#editBtn").hide();
        $("#saveBtn").show();
    });

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

    // --- SAVE PROFILE (POST) ---
    $("#saveBtn").click(function () {
        let name = $("#name").val();
        let age = $("#age").val();
        let dob = $("#dob").val();
        let contact = $("#contact").val();

        if (!name || !age || !dob || !/^[0-9]{10}$/.test(contact)) {
            alert("Please fill all fields correctly (Phone must be 10 digits)");
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
            success: function (res) {
                alert("Profile updated successfully!");
                $("#name, #age, #dob, #contact").prop("disabled", true);
                $("#photo").addClass("d-none");
                $("#saveBtn").hide();
                $("#editBtn").show();
            }
        });
    });

    // --- LOGOUT ---
    $("#logout").click(function () {
        $.ajax({
            url: "php/logout.php",
            type: "POST",
            data: { email: localStorage.getItem("user") },
            complete: function () {
                // We use 'complete' so even if server fails, we log out locally
                localStorage.removeItem("user");
                window.location = "login.html";
            }
        });
    });
});
