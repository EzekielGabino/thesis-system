$(document).ready(function () {
    $("#register").hide();
    $("#loginbtn").hide();
    $(document).on( 'click', '#regbtn', function (e) { 
        e.preventDefault();
        $("#login").hide();
        $("#loginbtn").show();
        $("#regbtn").hide();
        $("#register").show();
    });
    $(document).on( 'click', '#loginbtn', function (e) { 
        e.preventDefault();
        $("#register").hide();
        $("#regbtn").show();
        $("#loginbtn").hide();
        $("#login").show();

    });
    $('#registerform').on( 'submit', function (e) { 
        console.log("Form submitted");
        e.preventDefault();
        let formData = new FormData(this);

        $.ajax({
            type: "POST",
            url: "register.php",
            data: formData,
            dataType: "json",
            contentType: false,
            processData: false,
            success: function (response) {
                console.log(response);
                if(response.success){
                    alert(response.message);
                    location.reload();
                }else{
                    alert(response.message);
                }
            },
            error: function(xhr, status, error){
            alert("AJAX error: " + error);
        }
        });
    });
    $(document).on( 'submit', '#loginform', function (e) { 
        e.preventDefault();
        let formData = new FormData(this);

        $.ajax({
            type: "POST",
            url: "login.php",
            data: formData,
            dataType: "json",
            contentType: false,
            processData: false,
            success: function (response) {
                if(response.success){
                    alert(response.message);
                    if(response.role == "Student"){
                        window.location.href = "student_screen.php";
                    }else if(response.role == "Faculty"){
                        window.location.href = "faculty_screen.php";
                    }else if(response.role == "admin"){
                        window.location.href = "admin_screen.php";
                    }
                }else{
                    alert(response.message);
                }
            }
        });
    });
});