
$(".checkbox-menu").on("change", "input[type='checkbox']", function() {
    $(this).closest("li").toggleClass("active", this.checked);
});

$(document).on('click', '.allow-focus', function (e) {
    e.stopPropagation();
});

$(document).ready(function() {

    $("#file-upload").change(function(){
        $(".upload-box-text").text(this.files[0].name);
    });

    $(document).on("submit", "form", function(event) {
        event.preventDefault();
        var form_data = new FormData(this);
        
        $.ajax({
            url: "ajax/upload_ajax.php",
            type: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,

            success: function(data){
                if (data == 'success') {
                    alert("Video successfully uploaded");
                    $("#myform").trigger("reset");
                }
                else {
                    alert("Unexpected error! Video could not be uploaded.");
                }
            },
            error: function (xhr, desc, err)
            {
                alert(xhr + " " + desc + " " + err);
            }   
        });
        
    });
});