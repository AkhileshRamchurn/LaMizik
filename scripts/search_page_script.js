    
    $(document).ready(function() {

        function truncate(str, n){
            return (str.length > n) ? str.substr(0, n-1) + '&hellip;' : str;
        };

        var searchkey = $('.form-control').val();
        var sortValue = "first";
        var filterValue = "all";
        var limitInc = 5;

        function fetchInitialVideos() {
            $.ajax({
                url: "ajax/search_videoAjax.php",
                type: "POST",
                accepts: "application/json",
                data: {
                    limit: limitInc,
                    start: 0,
                    sortValue: sortValue,
                    filterValue: filterValue,
                    searchKey: searchkey
                },
                cache:false,
                success:function(data) {
                    console.log(data);
                    $('#video-list').html('');
                    $.each(data, function(i,obj) {
                        var videoId = obj['Video_ID'];
                        var videoTitle = truncate(obj['Title'], 45);
                        var videoDescription = truncate(obj['Description'], 285);
                        var uploadDate = moment.unix(obj['Upload_TS']).fromNow();
                        var uploader = truncate(obj['Username'], 39);
                        var views = obj['Views'];
                        if (views == null) {
                            views = 0;
                        }

                        var temp =  "<div class = 'video-detail' id = "+videoId+">"+
                                        "<div class='thumbnail-container'>"+
                                            "<a href='view.php?video_id="+videoId+"' target='_self'><img src='video/thumbnail/"+videoId+"t.jpg'></a>"+
                                        "</div>"+
                                        "<div class='right-container'>"+
                                            "<div class='text-container'>"+
                                                "<h2>"+videoTitle+"</h2>"+
                                                "<h4>Uploaded By "+uploader+"</h4>"+
                                                "<h6>"+uploadDate+"</h6>"+
                                                "<h6>"+views+" views</h6>"+
                                                "<p>"+videoDescription+"</p>"+
                                            "</div>"+
                                        "</div>"+
                                    "</div>";
                        $('#video-list').append(temp);
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
                if ($(window).scrollTop() >= $('#video-list').offset().top + $('#video-list').outerHeight() - window.innerHeight - 10) {
                    load_video();
                }
            }, 50);
        });

        function load_video() {
            $.ajax({
                url: "ajax/search_videoAjax.php",
                type: "POST",
                accepts: "application/json",
                data: {
                    limit: limit,
                    start: start,
                    sortValue: sortValue,
                    filterValue: filterValue,
                    searchKey: searchkey
                },
                cache:false,
                success:function(data) {
                    if (data.length != 0) {
                        $.each(data, function(i,obj) {
                            var videoId = obj['Video_ID'];
                            var videoTitle = truncate(obj['Title'], 45);
                            var videoDescription = truncate(obj['Description'], 285);
                            var uploadDate = moment.unix(obj['Upload_TS']).fromNow();
                            var uploader = truncate(obj['Username'], 39);
                            var views = obj['Views'];
                            if (views == null) {
                                views = 0;
                            }
    
                            var temp =  "<div class = 'video-detail' id = "+videoId+">"+
                                            "<div class='thumbnail-container'>"+
                                                "<a href='view.php?video_id="+videoId+"' target='_self'><img src='video/thumbnail/"+videoId+"t.jpg'></a>"+
                                            "</div>"+
                                            "<div class='right-container'>"+
                                                "<div class='text-container'>"+
                                                    "<h2>"+videoTitle+"</h2>"+
                                                    "<h4>Uploaded By "+uploader+"</h4>"+
                                                    "<h6>"+uploadDate+"</h6>"+
                                                    "<h6>"+views+" views</h6>"+
                                                    "<p>"+videoDescription+"</p>"+
                                                "</div>"+
                                            "</div>"+
                                        "</div>";
                            $('#video-list').append(temp);
                        });
                        start += limit;
                    }
                }
            });
        }

        $('#video_filter li').click(function() {
            start = limitInc;
            filterValue = $(this).attr('value');
            fetchInitialVideos();
        });

        $('#video_sort li').click(function() {
            start = limitInc;
            sortValue = $(this).attr('value');
            fetchInitialVideos();
        });
    
    });