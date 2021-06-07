    
    $(document).ready(function() {

        function truncate(str, n){
            return (str.length > n) ? str.substr(0, n-1) + '&hellip;' : str;
        };

        var searchkey = $('.form-control').val();
        var videoType = "all";
        var videoStatus = "Pending";
        var sortValue = "first";
        var limitInc = 5;

        function fetchInitialVideos() {
            $.ajax({
                url: "ajax/fetch_admin_videoAjax.php",
                type: "POST",
                accepts: "application/json",
                data: {
                    limit: limitInc,
                    start: 0,
                    videoType: videoType,
                    videoStatus: videoStatus,
                    sortValue: sortValue,
                    searchKey: searchkey
                },
                cache:false,
                success:function(data) {
                    $('#video-list').html('');
                    $.each(data, function(i,obj) {
                        var videoId = obj['Video_ID'];
                        var videoTitle = truncate(obj['Title'], 45);
                        var videoDescription = truncate(obj['Description'], 285);
                        var uploader = truncate(obj['Username'], 39);
                        var videoStatus = obj['Status'];

                        var temp =  "<div class = 'video-detail' id = "+videoId+">"+
                                        "<div class='thumbnail-container'>"+
                                            "<a href='view.php?video_id="+videoId+"' target='_blank'><img src='video/thumbnail/"+videoId+"t.jpg'></a>"+
                                        "</div>"+
                                        "<div class='right-container'>"+
                                            "<div class='text-container'>"+
                                                "<h2>"+videoTitle+"</h2>"+
                                                "<h4>Uploaded By "+uploader+"</h4>"+
                                                "<p>"+videoDescription+"</p>"+
                                            "</div>"+
                                            "<div class='btn-container'>"+
                                                "<button name='approve' class = 'submitBtn' value="+videoId+">Approve</button>";
                        
                        if (videoStatus == "Pending") {
                            temp +=             "<button name='reject' class = 'submitBtn' value="+videoId+">Reject</button>"+
                                            "</div>"+
                                        "</div>"+
                                    "</div>";
                        }
                        else {
                            temp +=             "<button name='delete' class = 'submitBtn' value="+videoId+">Delete Permanently</button>"+
                                            "</div>"+
                                        "</div>"+
                                    "</div>";
                        }
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
                url: "ajax/fetch_admin_videoAjax.php",
                type: "POST",
                accepts: "application/json",
                data: {
                    limit: limit,
                    start: start,
                    videoType: videoType,
                    videoStatus: videoStatus,
                    sortValue: sortValue,
                    searchKey: searchkey
                },
                cache:false,
                success:function(data) {
                    if (data.length != 0) {
                        $.each(data, function(i,obj) {
                            var videoId = obj['Video_ID'];
                            var videoTitle = truncate(obj['Title'], 45);
                            var videoDescription = truncate(obj['Description'], 285);
                            var uploader = truncate(obj['Username'], 39);
                            var videoStatus = obj['Status'];
    
                            var temp =  "<div class = 'video-detail' id = "+videoId+">"+
                                            "<div class='thumbnail-container'>"+
                                                "<a href='view.php?video_id="+videoId+"' target='_blank'><img src='video/thumbnail/"+videoId+"t.jpg'></a>"+
                                            "</div>"+
                                            "<div class='right-container'>"+
                                                "<div class='text-container'>"+
                                                    "<h2>"+videoTitle+"</h2>"+
                                                    "<h4>Uploaded By "+uploader+"</h4>"+
                                                    "<p>"+videoDescription+"</p>"+
                                                "</div>"+
                                                "<div class='btn-container'>"+
                                                    "<button name='approve' class = 'submitBtn' value="+videoId+">Approve</button>";
                        
                            if (videoStatus == "Pending") {
                                temp +=             "<button name='reject' class = 'submitBtn' value="+videoId+">Reject</button>"+
                                                "</div>"+
                                            "</div>"+
                                        "</div>";
                            }
                            else {
                                temp +=             "<button name='delete' class = 'submitBtn' value="+videoId+">Delete Permanently</button>"+
                                                "</div>"+
                                            "</div>"+
                                        "</div>";
                            }
                            $('#video-list').append(temp);
                        });
                        start += limit;
                    }
                }
            });
        }

        $("#video_type li").click(function() {
            start = limitInc;
            videoType = $(this).attr('value');
            fetchInitialVideos();
        });

        $('#video_status li').click(function() {
            start = limitInc;
            videoStatus = $(this).attr('value');
            fetchInitialVideos();
        });

        $('#video_sort li').click(function() {
            start = limitInc;
            sortValue = $(this).attr('value');
            fetchInitialVideos();
        });
    
    });

//script for approving/rejecting or deleting videos
    $(document).ready(function() {

        function animateRemoveElement(element) {
            element.animate({opacity: '0'}, 150, function(){
                element.animate({height: '0px'}, 150, function(){
                    element.remove();
                });
            });
        }

        $("#video-list").on('click', '.submitBtn', function (e) {
            e.preventDefault();
            var action = $(this).attr('name');
            var videoId = $(this).val();
            $.ajax({
                url: "ajax/manage_video_statusAjax.php",
                type: "POST",
                data: {
                    videoId: videoId,
                    action: action
                },
                cache:false,
                success: function(response){
                    var element = $("#" + videoId);
                    animateRemoveElement(element);
                }
            });
        });

    });