$(document).ready(function() {

    var limitInc = 5;

    function fetchInitialVideos() {
        $.ajax({
            url: "ajax/fetch_videoAjax_ToDelete.php",
            type: "POST",
            accepts: "application/json",
            data: {
                limit: limitInc,
                start: 0,
                videoId: videoId
            },
            cache:false,
            success:function(data) {
                $('.right-container').html('');
                $.each(data, function(i,obj) {
                    var returned_videoId = obj['Video_ID'];
                    var videoTitle = obj['Title'];
                    var uploader = obj['Uploader'];
                    var views = obj['Views'];
                    if (views == null) {
                        views=0;
                    }
                    var videoTS = moment.unix(obj['Upload_Timestamp']).fromNow();

                    var temp =  "<div class = 'mini-video'>"+
                                    "<a href='view.php?video_id="+returned_videoId+"' target='_self'><img src='video/thumbnail/"+returned_videoId+"t.jpg'></a>"+
                                    "<div class='mini-video-details-container'>"+
                                        "<h6>"+videoTitle+"</h6>"+
                                        "<p>"+uploader+"</p>"+
                                        "<p>"+videoTS+"<span class='midot3'>&#183;</span>"+views+" views</p>"+
                                    "</div>"+
                                "</div>";

                    $('.right-container').append(temp);
                });
            }
        });
    }

    fetchInitialVideos();

    var limit = limitInc;
    var start = limitInc;
    
    var timeout;
    $(window).scroll(function(){
        clearTimeout(timeout);
        timeout = setTimeout(function() {
            if ($(window).scrollTop() >= $('.right-container').offset().top + $('.right-container').outerHeight() - window.innerHeight) {
                load_video();
            }
        }, 50);
    });

    function load_video() {
        $.ajax({
            url: "ajax/fetch_videoAjax_ToDelete.php",
            type: "POST",
            accepts: "application/json",
            data: {
                limit: limit,
                start: start,
                videoId: videoId
            },
            cache:false,
            success:function(data) {
                if (data.length != 0) {
                    $.each(data, function(i,obj) {
                        var returned_videoId = obj['Video_ID'];
                        var videoTitle = obj['Title'];
                        var uploader = obj['Uploader'];
                        var views = obj['Views'];
                        if (views == null) {
                            views=0;
                        }
                        var videoTS = moment.unix(obj['Upload_Timestamp']).fromNow();

                        var temp =  "<div class = 'mini-video'>"+
                                        "<a href='view.php?video_id="+returned_videoId+"' target='_self'><img src='video/thumbnail/"+returned_videoId+"t.jpg'></a>"+
                                        "<div class='mini-video-details-container'>"+
                                            "<h6>"+videoTitle+"</h6>"+
                                            "<p>"+uploader+"</p>"+
                                            "<p>"+videoTS+"<span class='midot3'>&#183;</span>"+views+" views</p>"+
                                        "</div>"+
                                    "</div>";

                        $('.right-container').append(temp);
                    });
                    start += limit;
                }
            }
        });
    }

});