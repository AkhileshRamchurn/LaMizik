
$(document).ready(function(){

    //Converting video timestamp to a user-frinedly format
    var unix_ts = $('.video-timestamp').text();
    $('.video-timestamp').text(moment.unix(unix_ts).fromNow());

    //LIKE AND DISLIKE-------------------------------------------------------------------------------------------------------
    var action="";
    var video_id="";
    var user_id= $('#user_id').val(); //receive data from hidden input field
    
    
    //if user clicks on the like button
    $('.like-btn').on('click',function(){
        if(user_id != null){
            var video_id = $(this).attr('data-id'); //receives data from data-id
            var clicked_btn = $(this);
            
            //check if like button is unselected
            if(clicked_btn.hasClass('fa-thumbs-o-up')){
                action ='like';
            }
            //else if user unlike video
            else if(clicked_btn.hasClass('fa-thumbs-up')){
                action='unlike';
            }
    
            $.ajax({
                url: 'ajax/user_ratingAjax.php',
                type: 'post',
            
                data: {
                    'action': action,
                    'video_id': video_id
                },
                dataType:'JSON', 
    
                //if the response is successful from server
                success: function(data){

               // alert(JSON.stringify(data.likes+" "+data.dislikes));    
                    if(action == 'like'){
                        clicked_btn.removeClass('fa-thumbs-o-up');
                        clicked_btn.addClass('fa-thumbs-up');
                    }else if(action == "unlike"){
                        clicked_btn.removeClass('fa-thumbs-up');
                        clicked_btn.addClass('fa-thumbs-o-up');
                    }
    
                    //display updated number of likes and dislikes
                    $('span.likes').text(data.likes);
                    $('span.dislikes').text(data.dislikes);
        
                    //change button styling of the other button if the user is reacting the second time to video
                    //make sure that dislike button is unselected
                    $('i.fa-thumbs-down').removeClass('fa-thumbs-down').addClass('fa-thumbs-o-down');
                }
            });   

        }
        else{
            $('.my-modal2').addClass('active');
            $('.my-modal-overlay2').addClass('active');
        }
        
    });

    //if user clicks on the dislike button
    $('.dislike-btn').on('click',function(){

        if(user_id != null){
            video_id = $(this).data('id'); //receives data from data-id (line 195)
                clicked_btn = $(this);

                //check if dislike button is unselected
                if(clicked_btn.hasClass('fa-thumbs-o-down')){
                    action ='dislike';
                }
                //else if button has already been selected
                else if(clicked_btn.hasClass('fa-thumbs-down')){
                    action='undislike';
                }
        
        
            $.ajax({
                url: 'ajax/user_ratingAjax.php',
                type: 'post',
            
                data: {
                    'action': action,
                    'video_id': video_id
                },
                dataType:'JSON', 
        
                //if the response is successful from server
                success: function(data){
                    
            
                //alert(JSON.stringify(data.likes+" "+data.dislikes)); 
                        
                    if(action == 'dislike'){
                        clicked_btn.removeClass('fa-thumbs-o-down');
                        clicked_btn.addClass('fa-thumbs-down');
                    }else if(action == "undislike"){
                        clicked_btn.removeClass('fa-thumbs-down');
                        clicked_btn.addClass('fa-thumbs-o-down');
                    }
        
                    //display the updated number of likes and dislikes
                    $('span.likes').text(data.likes);
                    $('span.dislikes').text(data.dislikes);
            
                    //change button styling of the other button if the user is reacting the second time to video
                    //make sure that dislike button is unselected
                    $('i.fa-thumbs-up').removeClass('fa-thumbs-up').addClass('fa-thumbs-o-up');
                }
            });

        }
        else{
            $('.my-modal2').addClass('active');
            $('.my-modal-overlay2').addClass('active');
        }
        

    });
    //END LIKE AND DISLIKE-------------------------------------------------------------------------------------------------------


    //COMMENT--------------------------------------------------------------------------------------------------------------------
    //script for inserting comments
    $('#submit').click(function(e) {
        e.preventDefault();

        var comment = $('#commentDesc').val();
        console.log(comment.trim());
        if (userId == null) {
            $('.my-modal2').addClass('active');
            $('.my-modal-overlay2').addClass('active');
        }
        else if (comment.trim() != "") {
            $.ajax({
                url: "ajax/insert_commentAjax.php",
                type: "POST",
                data: {
                    videoId: videoId,
                    userId: userId,
                    comment: comment,
                },
                cache:false,
                success: function(inserted_commentId){

                    var temp = "<div class='main-comment' id = '"+inserted_commentId+"'>"+
                                    "<div class='comment-header'>"+
                                        "<p class = 'username'>"+username+"</p>"+
                                        "<span class='midot2'>&#183;</span>"+
                                        "<p class = 'time'>Just now</p>"+
                                        "<button class = 'inner-delete-btn' value = '"+inserted_commentId+"'><i class='fas fa-trash'></i></button>"+
                                    "</div>"+
                                    "<div class='comment-description'>"+
                                        "<p>"+comment+"</p>"+
                                    "</div>"+
                                "</div>"
                                ;

                    $(temp).hide().prependTo("#comment-container").css('opacity', 0).slideDown('fast').animate({ opacity: 1 },{ queue: false, duration: 'slow' });
                    $('#commentDesc').val("");
                    var commentCount = $(".comment-count").text();
                    commentCount++;
                    $(".comment-count").text(commentCount);

                }
            });
        }
        else {
            alert('Empty comment cannot be posted.');
            $('#commentDesc').val('');
        }
    });


    //script for fetching comments
    var sortValue = "first";
    var limitInc = 15;

    function fetchInitialComment() {
        $.ajax({
            url: "ajax/fetch_commentAjax.php",
            type: "POST",
            accepts: "application/json",
            data: {
                limit: limitInc,
                start: 0,
                videoId: videoId,
                sortValue: sortValue
            },
            cache:false,
            success:function(data) {
                $('#comment-container').html('');
                $.each(data, function(i,obj) {
                    var commenter_username = obj['Username'];
                    var comment_ts = moment.unix(obj['Comment_Timestamp']).fromNow();
                    var commentDescription = obj['Comment_Description'];
                    var commenter_id = obj['User_ID'];
                    var comment_id = obj['Comment_ID'];

                    var tempButton;

                    if (userType == null) {
                        tempButton = '';
                    }

                    if (userType == "Admin") {
                        tempButton = "<button class = 'inner-delete-btn' value = '"+comment_id+"'><i class='fas fa-trash'></i></button>";
                    }
                    else if (userType == "NormalUser") {
                        if (userId == commenter_id) {
                            tempButton = "<button class = 'inner-delete-btn' value = '"+comment_id+"'><i class='fas fa-trash'></i></button>";
                        }
                        else {
                            tempButton = "<button class = 'inner-report-btn' name = '"+userId+"' value = '"+comment_id+"'><i class='fas fa-flag'></i></button>";
                        } 
                    }

                    var temp = "<div class='main-comment' id = '"+comment_id+"'>"+
                                    "<div class='comment-header'>"+
                                        "<p class = 'username'>"+commenter_username+"</p>"+
                                        "<span class='midot2'>&#183;</span>"+
                                        "<p class = 'time'>"+comment_ts+"</p>"+
                                        tempButton+
                                    "</div>"+
                                    "<div class='comment-description'>"+
                                        "<p>"+commentDescription+"</p>"+
                                    "</div>"+
                                "</div>"
                                ;

                    $('#comment-container').append(temp);
                });
            }
        });
    }

    fetchInitialComment();
    
    $(document).ready(function() {
        
        var limit = limitInc;
        var start = limitInc;
        
        var timeout;
        $(window).scroll(function(){
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                if ($(window).scrollTop() >= $('#comment-container').offset().top + $('#comment-container').outerHeight() - window.innerHeight) {
                    load_comment();
                }
            }, 50);
        });

        function load_comment() {
            $.ajax({
                url: "ajax/fetch_commentAjax.php",
                type: "POST",
                accepts: "application/json",
                data: {
                    limit: limit,
                    start: start,
                    videoId: videoId,
                    sortValue: sortValue
                },
                cache:false,
                success:function(data) {
                    if (data.length != 0) {
                        $.each(data, function(i,obj) {
                            var commenter_username = obj['Username'];
                            var comment_ts = moment.unix(obj['Comment_Timestamp']).fromNow();
                            var commentDescription = obj['Comment_Description'];
                            var commenter_id = obj['User_ID'];
                            var comment_id = obj['Comment_ID'];

                            var tempButton;

                            if (userType == null) {
                                tempButton = '';
                            }

                            if (userType == "Admin") {
                                tempButton = "<button class = 'inner-delete-btn' value = '"+comment_id+"'><i class='fas fa-trash'></i></button>";
                            }
                            else if (userType == "NormalUser") {
                                if (userId == commenter_id) {
                                    tempButton = "<button class = 'inner-delete-btn' value = '"+comment_id+"'><i class='fas fa-trash'></i></button>";
                                }
                                else {
                                    tempButton = "<button class = 'inner-report-btn' value = '"+comment_id+"'><i class='fas fa-flag'></i></button>";
                                } 
                            }

                            var temp = "<div class='main-comment' id = '"+comment_id+"'>"+
                                            "<div class='comment-header'>"+
                                                "<p class = 'username'>"+commenter_username+"</p>"+
                                                "<span class='midot2'>&#183;</span>"+
                                                "<p class = 'time'>"+comment_ts+"</p>"+
                                                tempButton+
                                            "</div>"+
                                            "<div class='comment-description'>"+
                                                "<p>"+commentDescription+"</p>"+
                                            "</div>"+
                                        "</div>"
                                ;

                            $('#comment-container').append(temp);
                        });
                        start += limit;
                    }
                }
            });
        }

        function animateRemoveElement(element) {
            element.animate({opacity: '0'}, 150, function(){
                element.animate({height: '0px'}, 150, function(){
                    element.remove();
                });
            });
        }

        $('#comment_Sort li').click(function(e) {
            e.preventDefault()
            start = limitInc;
            sortValue = $(this).attr('value');
            fetchInitialComment();
        });

        $("#comment-container").on('click', '.inner-delete-btn', function (e) {
            e.preventDefault();
            var deleteId = $(this).val();
            $.ajax({
                url: "ajax/delete_commentAjax.php",
                type: "POST",
                data: {
                    comment_id: deleteId,
                },
                cache:false,
                success: function(response){
                    if (response == "success") {
                        var element = $("#" + deleteId);
                        animateRemoveElement(element);
                        var commentCount = $(".comment-count").text();
                        commentCount--;
                        $(".comment-count").text(commentCount);
                    }
                    else {
                        alert("Unexpected error. Comment could not be deleted");
                    }
                }
            });
        });

        var report_commentID;
        $("#comment-container").on('click', '.inner-report-btn', function (e) {
            e.preventDefault();
            $('.my-modal').addClass('active');
            $('.my-modal-overlay').addClass('active');

            report_commentID = $(this).val();

        });

        $('.close-btn i, #report-cancel-btn, .my-modal-overlay').on('click', function (e) {
            $('.my-modal').removeClass('active');
            $('.my-modal-overlay').removeClass('active');
            $('#reportReason').val();
        });

        $('#report-submit-btn').on('click', function (e) {
            e.preventDefault();
            var report_reason = $('#reportReason').val();
            $.ajax({
                url: "ajax/report_commentAjax.php",
                type: "POST",
                data: {
                    comment_id: report_commentID,
                    user_id: userId,
                    report_reason: report_reason
                },
                cache:false,
                success: function(response){
                    if (response == "success") {
                        $('.my-modal').removeClass('active');
                        $('.my-modal-overlay').removeClass('active');
                        $('#reportReason').val('');
                    }
                    else {
                        alert("Unexpected error. Comment could not be reported");
                    }
                }
            });
        });

        $('.close-btn2 i, #login-cancel-btn, .my-modal-overlay2').on('click', function (e) {
            $('.my-modal2').removeClass('active');
            $('.my-modal-overlay2').removeClass('active');
        });

        $('#login-submit-btn').on('click', function (e) {
            window.location.href = "login.php";
        });
    
    });
    //END COMMENT---------------------------------------------------------------------------------------------------------------

});