    
    $(document).ready(function() {

        function truncate(str, n){
            return (str.length > n) ? str.substr(0, n-1) + '&hellip;' : str;
        };

        var searchkey = $('.form-control').val();
        var sortValue = "first";
        var limitInc = 5;

        function fetchInitialVideos() {
            $.ajax({
                url: "ajax/fetch_admin_commentAjax.php",
                type: "POST",
                accepts: "application/json",
                data: {
                    limit: limitInc,
                    start: 0,
                    sortValue: sortValue,
                    searchKey: searchkey
                },
                cache:false,
                success:function(data) {
                    $('#comment-list').html('');
                    $.each(data, function(i,obj) {
                        var commentId = obj['Comment_ID'];
                        var commenter = obj['poster'];
                        var reporter = obj['reporter'];
                        var reportDescription = obj['Report_Description'];
                        var commentDescription = obj['Comment_Description'];

                        var temp =  "<div class = 'comment-detail comment"+commentId+"'>"+
                                        "<div class='text-container'>"+
                                            "<h4>Posted By <span class='name'>"+commenter+"</span></h4>"+
                                            "<h4>Reported By <span class='name'>"+reporter+"</span></h4>"+
                                            "<h4 class='report-h4'>Report Reason:</h4>"+
                                            "<p>"+reportDescription+"</p>"+
                                            "<h4 class='comment-h4'>Comment:</h4>"+
                                            "<p>"+commentDescription+"</p>"+
                                        "</div>"+
                                        "<div class='btn-container'>"+
                                            "<button name='ignore' class = 'submitBtn' value="+commentId+">Mark as Read</button>"+
                                            "<button name='delete' class = 'submitBtn' value="+commentId+">Delete Comment</button>"+
                                        "</div>"+
                                    "</div>";
                        $('#comment-list').append(temp);
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
                if ($(window).scrollTop() >= $('#comment-list').offset().top + $('#comment-list').outerHeight() - window.innerHeight - 10) {
                    load_video();
                }
            }, 50);
        });

        function load_video() {
            $.ajax({
                url: "ajax/fetch_admin_commentAjax.php",
                type: "POST",
                accepts: "application/json",
                data: {
                    limit: limit,
                    start: start,
                    sortValue: sortValue,
                    searchKey: searchkey
                },
                cache:false,
                success:function(data) {
                    if (data.length != 0) {
                        $.each(data, function(i,obj) {
                            var commentId = obj['Comment_ID'];
                            var commenter = obj['poster'];
                            var reporter = obj['reporter'];
                            var reportDescription = obj['Report_Description'];
                            var commentDescription = obj['Comment_Description'];
    
                            var temp =  "<div class = 'comment-detail comment"+commentId+"'>"+
                                            "<div class='text-container'>"+
                                                "<h4>Posted By <span class='name'>"+commenter+"</span></h4>"+
                                                "<h4>Reported By <span class='name'>"+reporter+"</span></h4>"+
                                                "<h4 class='report-h4'>Report Reason:</h4>"+
                                                "<p>"+reportDescription+"</p>"+
                                                "<h4 class='comment-h4'>Comment:</h4>"+
                                                "<p>"+commentDescription+"</p>"+
                                            "</div>"+
                                            "<div class='btn-container'>"+
                                                "<button name='ignore' class = 'submitBtn' value="+commentId+">Mark as Read</button>"+
                                                "<button name='delete' class = 'submitBtn' value="+commentId+">Delete Comment</button>"+
                                            "</div>"+
                                        "</div>";
                            $('#comment-list').append(temp);
                        });
                        start += limit;
                    }
                }
            });
        }

        $('#comment_sort li').click(function(e) {
            e.preventDefault();
            start = limitInc;
            sortValue = $(this).attr('value');
            fetchInitialVideos();
        });
    
    });



    $(document).ready(function() {

        function animateRemoveElement(element) {
            element.animate({opacity: '0'}, 150, function(){
                element.animate({height: '0px'}, 150, function(){
                    element.remove();
                });
            });
        }

        $("#comment-list").on('click', '.submitBtn', function (e) {
            e.preventDefault();
            var action = $(this).attr('name');
            var commentId = $(this).val();
            $.ajax({
                url: "ajax/manage_report_actionAjax.php",
                type: "POST",
                data: {
                    commentId: commentId,
                    action: action
                },
                cache:false,
                success: function(response){
                    var element = $(".comment" + commentId);
                    console.log(element);
                    animateRemoveElement(element);
                }
            });
        });

    });