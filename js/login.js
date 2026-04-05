$("#btn").click(function () {
    // Collect values
    let email = $("#email").val();
    let password = $("#password").val();

    // Basic validation before sending to server
    if (!email || !password) {
        alert("Please enter both email and password");
        return;
    }

    $.ajax({
        url: "php/login.php",
        type: "POST",
        data: {
            email: email,
            password: password
        },
        success: function (res) {
            /**
             * Using .trim() is the secret fix here. 
             * It removes any accidental white space or newlines 
             * sent by the PHP server.
             */
            if (res.trim() === "success") {
                // Save to localStorage using the key "user" 
                // to match your profile.js logic
                localStorage.setItem("user", email);
                
                // Redirect to profile
                window.location = "profile.html";
            } else {
                alert("Invalid login. Please check your credentials.");
            }
        },
        error: function() {
            alert("Server error. Please check your Railway connection.");
        }
    });
});
