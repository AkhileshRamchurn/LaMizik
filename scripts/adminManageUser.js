    
    $(document).ready(function() {

        var searchkey = $('.form-control').val();
        var sortValue = "nameAsc";
        var limitInc = 5;

        function fetchInitialVideos() {
            $.ajax({
                url: "ajax/fetch_admin_banned_userAjax.php",
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
                    $('#user-list').html('');
                    $.each(data, function(i,obj) {
                        var userId = obj['User_ID'];
                        var username = obj['Username'];

                        var temp =  "<div class = 'user-detail' id = 'user"+userId+"'>"+
                                        "<div class='text-container'>"+
                                            "<h4>Username:<span class='bold-text'>"+username+"</span></h4>"+
                                            "<h4>User ID:<span class='bold-text'>"+userId+"</span></h4>"+
                                        "</div>"+
                                        "<div class='btn-container'>"+
                                            "<button name='view' class = 'submitBtn' value="+userId+">View Profile</button>"+
                                            "<button name='restore' class = 'submitBtn' value="+userId+">Restore Account</button>"+
                                        "</div>"+
                                    "</div>";
                        $('#user-list').append(temp);
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
                if ($(window).scrollTop() >= $('#user-list').offset().top + $('#user-list').outerHeight() - window.innerHeight - 10) {
                    load_video();
                }
            }, 50);
        });

        function load_video() {
            $.ajax({
                url: "ajax/fetch_admin_banned_userAjax.php",
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
                            var userId = obj['User_ID'];
                            var username = obj['Username'];
    
                            var temp =  "<div class = 'user-detail' id = 'user"+userId+"'>"+
                                            "<div class='text-container'>"+
                                                "<h4>Username:<span class='bold-text'>"+username+"</span></h4>"+
                                                "<h4>User ID:<span class='bold-text'>"+userId+"</span></h4>"+
                                            "</div>"+
                                            "<div class='btn-container'>"+
                                                "<button name='view' class = 'submitBtn' value="+userId+">View Profile</button>"+
                                                "<button name='restore' class = 'submitBtn' value="+userId+">Restore Account</button>"+
                                            "</div>"+
                                        "</div>";
                            $('#user-list').append(temp);
                        });
                        start += limit;
                    }
                }
            });
        }

        $('#user_sort li').click(function(e) {
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

        $("#user-list").on('click', '.submitBtn', function (e) {
            e.preventDefault();
            var action = $(this).attr('name');
            var userId = $(this).val();
            if (action=="restore") {
                $.ajax({
                    url: "ajax/manage_user_actionAjax.php",
                    type: "POST",
                    data: {
                        userId: userId
                    },
                    cache:false,
                    success: function(response){
                        var element = $("#user" + userId);
                        animateRemoveElement(element);
                    }
                });
            }
            else if (action=="view"){
                //view profile!!!
            }

        });

    });