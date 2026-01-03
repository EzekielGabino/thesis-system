$(document).ready(function () {
    let currentFIle = "";

    $("#thesis_submission").hide();
    $("#modal").hide();
    $("#chooseFile").hide();
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

    $(document).on( 'submit', '#form', function (e) { 
        e.preventDefault();
        let formData = new FormData(this);

        $.ajax({
            type: "POST",
            url: "thesisUpload.php",
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

    $(document).on( 'click', '.editThesis', function (e) { 
        e.preventDefault();

        $("#modal").show();
        $("#id").val($(this).data("id"));
        $("#title1").val($(this).data("title"));
        $("#abstract1").val($(this).data("abstract"));
        $("#keywords1").val($(this).data("keywords"));
        $("#year").val($(this).data("year"));

        let date_approved = $(this).data("date_approved") ? $(this).data("date_approved") : "Not Yet Approved";
        $("#approved").val(date_approved);

        currentFIle = $(this).data("thesis");

        $("#fileOptions").show();
        $("#chooseFile").hide();
    });

    $(document).on( 'click', '#reviewfile', function (e) { 
        e.preventDefault();

        if (currentFIle) {
            $("#filePreview").html(`
                <p><strong>Current File:</strong></p>
                <a href="../thesisFiles/${currentFIle}" target="_blank">
                    📄 Open Thesis File
                </a>
            `);
        } else {
            $("#filePreview").html("<p>No file uploaded</p>");
        }
    });

    $(document).on( 'submit', '#updateForm', function (e) { 
        e.preventDefault();
        let formData = new FormData(this);

        $.ajax({
            type: "POST",
            url: "updateThesis.php",
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

    //tracker
    $("#approved").hide();
    $("#pending").hide();
    $("#rejected").hide();
    $(document).on( 'click', '#submittedbtn', function (e) { 
        e.preventDefault();
        $("#submitted").show();
        $("#approved").hide();
        $("#pending").hide();
        $("#rejected").hide();
    });
    $(document).on( 'click', '#approvedbtn', function (e) { 
        e.preventDefault();
        $("#approved").show();
        $("#submitted").hide();
        $("#pending").hide();
        $("#rejected").hide();
    });
    $(document).on( 'click', '#pendingbtn', function (e) { 
        e.preventDefault();
        $("#approved").hide();
        $("#submitted").hide();
        $("#pending").show();
        $("#rejected").hide();
    });
    $(document).on( 'click', '#rejectedbtn', function (e) { 
        e.preventDefault();
        $("#approved").hide();
        $("#submitted").hide();
        $("#pending").hide();
        $("#rejected").show();
    });

    $(document).on( 'click', '#yesbtn', function (e) { 
        e.preventDefault();
        $("#chooseFile").show();
        $("#fileOptions").hide();
    });

    $(document).on( 'click', '#nobtn', function (e) { 
        e.preventDefault();
        $("#fileOptions").hide();
    });
    $(document).on( 'click', '#cancel', function (e) { 
        e.preventDefault();
        $("#modal").hide();
    });

    $(document).on( 'click', '#SubmittedThesis', function (e) { 
        e.preventDefault();
        $("#thesis_tracker").show();
        $("#thesis_submission").hide();
    });
    $(document).on( 'click', '#SubmissionForm', function (e) { 
        e.preventDefault();
        $("#thesis_tracker").hide();
        $("#trackersbtn ").hide();
        $("#thesis_submission").show();
    });
    
});