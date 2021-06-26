$(document).ready(function() {

    var limitInc = 10;
    var recommender_limit = 8;

    $.ajax({
        url: "recommended_videos.php?user_id="+userId,
        type: "POST",
        accepts: "application/json",
        cache:false,
        success:function(data) {
            video_arr = (data[0]['Recommended_Videos']);
            fetchRecommendedVideos();
            video_pagination();
        }
    });

    function fetchRecommendedVideos() {
        $('.right-container').html('');
        $.each(video_arr, function(i,obj) {
            var returned_videoId = obj['Video_ID'];
            var videoTitle = obj['Title'];
            var uploader = obj['Username'];
            var views = obj['views'];
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

    function video_pagination() {

        var queryCondition = videoId;

        $.each(video_arr, function(i,obj) {
            queryCondition += ', ' + obj['Video_ID'];
        });

        function fetchInitialVideos() {
            $.ajax({
                url: "ajax/fetch_viewVideoAjax.php",
                type: "POST",
                accepts: "application/json",
                data: {
                    limit: recommender_limit-video_arr.length,
                    start: 0,
                    condition: queryCondition
                },
                cache:false,
                success:function(data) {
                    shuffle(data);
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

        var start = recommender_limit-video_arr.length;
        var limit = limitInc;

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
                url: "ajax/fetch_viewVideoAjax.php",
                type: "POST",
                accepts: "application/json",
                data: {
                    limit: limit,
                    start: start,
                    condition: queryCondition
                },
                cache:false,
                success:function(data) {
                    if (data.length != 0) {
                        shuffle(data);
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
    }

    function shuffle(array) {
        var currentIndex = array.length,  randomIndex;
      
        // While there remain elements to shuffle...
        while (0 !== currentIndex) {
      
          // Pick a remaining element...
          randomIndex = Math.floor(Math.random() * currentIndex);
          currentIndex--;
      
          // And swap it with the current element.
          [array[currentIndex], array[randomIndex]] = [
            array[randomIndex], array[currentIndex]];
        }
      
        return array;
      }

});