$(document).ready(function () {
    $(document).on( 'click', '#logoutbtn', function (e) {   
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: "logout.php",
            dataType: "json",
            success: function (response) {
                if(response.success){   
                    alert(response.message);
                    window.location.href = "loginreg.php";
                }else{
                    alert(response.message);
                }
            }
        });
    });
    //update
    $("#FormModal").hide();
    $(document).on( 'click', '.updateStudent', function (e) { 
        e.preventDefault();
        $("#FormModal").show();
        $("#formheader").text($(this).data("header"));
        $("#user").text($(this).data("user"));
        $("#user_id").val($(this).data("id"));
        $("#name").val($(this).data("name"));
        $("#email").val($(this).data("email"));
        $("#role").val($(this).data("role"));
        $("#dept_id").val($(this).data("department"));
        $("#username").val($(this).data("username"));
        $("#password").val($(this).data("password"));

        let currentprofile = $(this).data("profile");
        let currentsign = $(this).data("sign");
        if($(this).data("role") == "Student"){
            if(currentprofile){
                $("#profileImg").html(`
                    <strong>Profile</strong><br>
                    <img src="../studentUploads/${currentprofile}" alt="Profile" width="100px" height="100px">
                `);
            }
            if(currentsign){
                $("#signImg").html(`
                    <strong>Signature</strong><br>
                    <img src="../studentUploads/${currentsign}" alt="Signature" width="100px" height="100px">
                `);
            }
        }
        
    });

    $("#userForm").on( 'submit', function (e) { 
        e.preventDefault();
        let formData = new FormData(this);

        $.ajax({
            type: "POST",
            url: "updateUser.php",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (response) {
                if(response.success){
                    alert(response.message);
                    location.reload();
                }else{
                    alert(response.message);
                }
            }
        });
    });
    $("#closeForm").click(function (e) { 
        e.preventDefault();
        $("#FormModal").hide();
    });

    //updateThesis
    $("#reviewModal").hide();
    $(document).on( 'click', '.reviewThesis', function (e) { 
        e.preventDefault();
        $("#reviewModal").show();
        $("#thesis1").val($(this).data("thesis_id"));
        $("#reviewer").val($(this).data("reviewer_id"));
    });

    $(".closebtn").hide();
    $(document).on( 'click' ,'.reviewFile', function (e) { 
        e.preventDefault();
        let currentFile = $(this).data("filepath");
        let previewBox = $(this).next(".filepreview");
        let closeBtn = $(this).nextAll(".closebtn").first();
        

        if (currentFile) {
            previewBox.html(`
            PDF files open in browser and Docx Files downloads<br>
                <a href="../thesisFiles/${currentFile}" target="_blank">
                    📄 Open Thesis File: ${currentFile}
                </a>
        `);
            closeBtn.show();
        } else {
            previewBox.html("<p>No file uploaded</p>");
        }
    });

    //searchThesis
    $(document).on( 'submit', '#search_filterThesis', function (e) { 
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: "thesisSearch.php",
            data: $(this).serialize(),
            success: function (response) {
                $("#thesis_table").html(response);
            }
        });
    });
    $(document).on( 'click', '#cancelsearchThesis', function (e) { 
        e.preventDefault();
        location.reload();
    });

    $(document).on( 'click', '.closebtn', function (e) { 
        e.preventDefault();
        let previewBox = $(this).prev(".filepreview");
        previewBox.html('');
        $(this).hide();
    });

    //review
    $("#reviewModal").hide();
    $(document).on( 'click', '.reviewThesis', function (e) { 
        e.preventDefault();
        $("#reviewModal").show();
        $("#thesis1").val($(this).data("thesis_id"));
        $("#reviewer").val($(this).data("reviewer_id"));
    });
    $(document).on( 'click', '#cancelreviewbtn', function (e) { 
        e.preventDefault();
        $("#reviewModal").hide();
    });
    $(document).on( 'submit', '#reviewForm', function (e) { 
        e.preventDefault();
        let formData = new FormData(this);

        $.ajax({
            type: "POST",
            url: "review.php",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (response) {
                if(response.success){
                    alert(response.message);
                    location.reload();
                }else{
                    alert(response.message);
                }
            }
        });
    });

    //deleteuser
    $(document).on( 'click', '.deleteUser', function (e) { 
        e.preventDefault();
        let userID = $(this).data("id");
        let username = $(this).data("name");

        if(!confirm("Are you sure you want to delete this user? Name: " + username)){
            return;
        }

        $.ajax({
            type: "POST",
            url: "deleteUser.php",
            data: {id: userID},
            dataType: "json",
            success: function (response) {
                if(response.success){
                    alert(response.message);
                    location.reload();
                }else{
                    alert(response.message);
                }
            }
        });
    });

    //navbars
    $("#faculty").hide();
    $("#thesis").hide();
    $("#activity").hide();
    $("#facultybar").on('click', function (e) { 
        e.preventDefault();
        $("#faculty").show();
        $("#student").hide();
        $("#thesis").hide();
        $("#activity").hide();
    });
    $("#studentbar").on( 'click', function (e) { 
        e.preventDefault();
        $("#student").show();
        $("#faculty").hide();
        $("#thesis").hide();
        $("#activity").hide();
    });
    $("#thesisbar").on( 'click', function (e) { 
        e.preventDefault();
        $("#thesis").show();
        $("#student").hide();
        $("#faculty").hide();
        $("#activity").hide();
    });
    $("#activityLogs").on( 'click', function (e) { 
        e.preventDefault();
        $("#activity").show();
        $("#student").hide();
        $("#faculty").hide();
        $('#thesis').hide();
    });

    //searchStudent
    $(document).on( 'submit', '#search_filterStudent', function (e) { 
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: "studentSearch.php",
            data: $(this).serialize(),
            success: function (response) {
                $("#studentTable").html(response);
            }
        });
    });
    $(document).on( 'click', '#cancelsearchbtn', function (e) { 
        e.preventDefault();
        location.reload();
    });

    //searchfaculty
    $(document).on( 'submit', '#search_filterFaculty', function (e) { 
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: "facultySearch.php",
            data: $(this).serialize(),
            success: function (response) {
                $("#facultyTable").html(response);
            }
        });
    });
    $(document).on( 'click', '#cancelsearchFaculty', function (e) { 
        e.preventDefault();
        location.reload();
    });
});