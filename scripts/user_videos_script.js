$(document).ready(function(){
    var url ="ajax/getVideoData2.php";
    var videoData;
    var videoDataLength;

    /*Fetch data of videos*/
    function fetchVideoData(){
        $.ajax({
            url:url,
            data: {
                User_ID:userId,
            },
            accepts: "application/json",
            headers:{Accept:"application/json"},
            cache: false,
            type:"POST",
            error: function(xhr){
               alert("An error occured: " + xhr.status + " " + xhr.statusText);
            }
        })
        .done(function(data){
            if (!(Array.isArray(data.output))) {
                var noVideoHTML =   "<div class='no-video-box'>"+
                                        "<h1 class='no-videos-title'>No Videos Found</h1>"+
                                    "</div>";

                $(".main-container").append(noVideoHTML);
            }
            else {
                videoData = data.output;
                videoDataLength=videoData.length;
                $(".video-count").text(videoDataLength + " videos");
            }
        });
    }
    fetchVideoData();
    
    /*load explore videos on scroll*/
    var limit=8;//number of videos to display
    var start=0;//index of first video to be displayed
    var last_pos=8;//position in video array
    var stopScrolling=false;
    
    setTimeout(function(){
        if (videoDataLength < 8) {
            last_pos = videoDataLength;
        }
    },150);

    function loadExploreVideoOnScroll(start,last_pos){ 
        var display_exVideos="";
        var deleteButtonHTML = '';
        for(var i=start;i<last_pos; i++){
            if (sameUser || (userType == "Admin")) {
                deleteButtonHTML = "<button class='delete-btn' value='"+videoData[i].Video_ID+"vid'><i class='fas fa-trash'></i></button>";
            }
            display_exVideos= display_exVideos+"<div class='video-container' >";
            display_exVideos= display_exVideos+"<a href='view.php?video_id="+videoData[i].Video_ID+"' target='_self'>";  
            display_exVideos=display_exVideos+"<img src='video/thumbnail/"+videoData[i].Video_ID+"t.jpg'>";
            display_exVideos=display_exVideos +"<div class='video-details'><h4>"+videoData[i].Title+"</h4></a>"+"<div class='vid-details-small'><p>"+moment.unix(videoData[i].Upload_Timestamp).fromNow()+"<span class='midot3'>&#183;</span>"+videoData[i].Views+" views</p>"+deleteButtonHTML+"</div></div>";  
            display_exVideos=display_exVideos +"</div>"; 
            $('.user-videos').append(display_exVideos);
            display_exVideos="";
            deleteButtonHTML = '';
        }

        
    }
    // Display intial explore videos
    setTimeout(function(){
        loadExploreVideoOnScroll(start,last_pos); 
    },200);

    // Display explore videos on scroll
    $(window).scroll(function(e){//when we are scrolling
        setTimeout(function(){
            if($(window).scrollTop() >= $('.video-grid').offset().top + $('.video-grid').outerHeight() - window.innerHeight){
                 start +=limit;
                
                //checks if there are enough videos that can be displayed
                if(last_pos+limit > videoDataLength && stopScrolling==false){
                    stopScrolling=true;
                    $(window).unbind('scroll'); //stops screen from scrolling 
                    last_pos = videoDataLength;
                    loadExploreVideoOnScroll(start,last_pos);  
                }
                else{
                    last_pos +=limit;
                    loadExploreVideoOnScroll(start,last_pos);   
                }
                        
            }
        },1750);    
    })

    $(".btn-donate").on('click', function (e) {
        e.preventDefault();
        if (linkedCard == 1) {
            $('.donation-modal').addClass('active');
            $('.donation-modal-overlay').addClass('active');
        }
        else {
            $('.link-card-modal').addClass('active');
            $('.link-card-modal-overlay').addClass('active');
        }
    });

    $('.close-btn i, #donation-cancel-btn, .donation-modal-overlay').on('click', function (e) {
        $('.donation-modal').removeClass('active');
        $('.donation-modal-overlay').removeClass('active');
        $('#reportReason').val();
    });

    $('#donation-submit-btn').on('click', function (e) {
        e.preventDefault();
        var donoAmount = $('#donation-amount').val();
        $.ajax({
            url: "ajax/donate_Ajax.php",
            type: "POST",
            data: {
                user_id: userId,
                sender_id: currentUserId,
                dono_amount: donoAmount
            },
            cache:false,
            success: function(response){
                if (response == "success") {
                    $('.donation-modal').removeClass('active');
                    $('.donation-modal-overlay').removeClass('active');
                    $('#donation-amount').val('');
                }
                else {
                    alert("Unexpected error. Could not perform donation");
                }
            }
        });
    });

    $('.close-btn2 i, #link-card-cancel-btn, .link-card-modal-overlay').on('click', function (e) {
        $('.link-card-modal').removeClass('active');
        $('.link-card-modal-overlay').removeClass('active');
    });

    $('#link-card-submit-btn').on('click', function (e) {
        window.location.href = "user_profile.php";
    });

    $(document).on('click','.delete-btn',function(e) {
        var video_id_delete = ($(this).val()).substring(0, $(this).val().length - 3);
        $.ajax({
            url: "ajax/delete_videoAjax.php",
            type: "POST",
            data: {
                video_id: video_id_delete
            },
            cache:false,
            success: function(response){
                if (response == "success") {
                    location.reload();
                }
                else {
                    alert("Unexpected error. Could not delete video");
                }
            }
        });
    });

    $(document).on('click','.btn-ban',function(e) {
        $.ajax({
            url: "ajax/ban_userAjax.php",
            type: "POST",
            data: {
                user_id: $(this).val()
            },
            cache:false,
            success: function(response){
                if (response == "success") {
                    window.location.href = "home.php";
                }
                else {
                    alert("Unexpected error. Could not delete video");
                }
            }
        });
    });
    
    $(document).on('click','.btn-add-video',function(e) {
        window.location.href = "upload.php";
    });

});    