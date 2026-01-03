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

    //file
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

    $(document).on( 'click', '.closebtn', function (e) { 
        e.preventDefault();
        let previewBox = $(this).prev(".filepreview");
        previewBox.html('');
        $(this).hide();
    });

    

    //searchThesis
    $("#search_filter").hide();
    $(document).on( 'click', '#searchbtn', function (e) { 
        e.preventDefault();
        $("#search_filter").show();
        $("#searchbtn").hide();
    });
    $(document).on( 'submit', '#search', function (e) { 
        e.preventDefault();

        $.ajax({
            type: "POST",
            url: "thesisSearch.php",
            data: $(this).serialize(),
            success: function (response) {
                $("#thesis").html(response);
            }
        });
    });
    $(document).on( 'click', '#cancelsearchbtn', function (e) { 
        e.preventDefault();
        location.reload();
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
});