$(document).ready(function(){
    // var url = "http://localhost/Lamizik/video/list";
    var url ="ajax/getVideoData.php";
    var user_id= $('.main-container').attr('data-id');
    var videoData;
    var videoDataLength;
    // console.log(user_id);

    /*Fetch data of videos*/
    function fetchVideoData(){
        $.ajax({
            url:url,
            accepts: "application/json",
            headers:{Accept:"application/json"},
            cache: false,
            type:"POST",
            error: function(xhr){
               alert("An error occured: " + xhr.status + " " + xhr.statusText);
            }
        })
        .done(function(data){
            console.log(data);
            videoData = data.output;
            videoDataLength=videoData.length;
            console.log(videoDataLength);
            console.log(videoData);
         
        });
    }
    fetchVideoData();
    
    
    var last_pos_rec=8;//position in video array
    setTimeout(function(){
        console.log(videoDataLength);
        if (videoDataLength < 8) {
            last_pos_rec = videoDataLength;
        }
    },100);//wait 100ms for data to be fetched

    /*Execute  displayRecommendVideos after video data has been fetched*/
    function displayRecommendVideos(){
        if(user_id) {
            var display_reVideos="<div class='recommended-videos'>";
            for(var i=0;i<last_pos_rec; i++){
                display_reVideos= display_reVideos+"<div class='video-container' >";
                display_reVideos= display_reVideos+"<a href='view.php?video_id="+videoData[i].Video_ID+"' target='_self'>";  
                display_reVideos=display_reVideos+"<img src='video/thumbnail/"+videoData[i].Video_ID+"t.jpg'>";
                display_reVideos=display_reVideos +"<div class='video-details'><h4>"+videoData[i].Title+"</h4></a><p>"+videoData[i].Username+"</p>"+"<p>"+moment.unix(videoData[i].Upload_Timestamp).fromNow()+"<span class='midot3'>&#183;</span>"+videoData[i].Views+" views</p>"+"</div>";  
                display_reVideos=display_reVideos +"</div>"; 
            }
            display_reVideos = display_reVideos+"</div><span class='separator'></span>";
            //  console.log(display_reVideos);
            $('.recommended-container').append(display_reVideos);
        }
        else{
            $('.recommended-container').empty();
        }

    }
    
    /*Displays videos when home page is first loaded*/
    setTimeout(function(){
        displayRecommendVideos();
    },200);
    
    /*load explore videos on scroll*/
    var limit=8;//number of videos to display
    var start=0;//index of first video to be displayed
    var last_pos=8;//position in video array
    var stopScrolling=false;
    
    setTimeout(function(){
        console.log(videoDataLength);
        if (videoDataLength < 8) {
            last_pos = videoDataLength;
        }
    },150);

    function loadExploreVideoOnScroll(start,last_pos){ 
        var display_exVideos="<div class='explore-videos'>";
            for(var i=start;i<last_pos; i++){
                display_exVideos= display_exVideos+"<div class='video-container' >";
                display_exVideos= display_exVideos+"<a href='view.php?video_id="+videoData[i].Video_ID+"' target='_self'>";  
                display_exVideos=display_exVideos+"<img src='video/thumbnail/"+videoData[i].Video_ID+"t.jpg'>";
                display_exVideos=display_exVideos +"<div class='video-details'><h4>"+videoData[i].Title+"</h4></a><p>"+videoData[i].Username+"</p>"+"<p>"+moment.unix(videoData[i].Upload_Timestamp).fromNow()+"<span class='midot3'>&#183;</span>"+videoData[i].Views+" views</p>"+"</div>";  
                display_exVideos=display_exVideos +"</div>"; 
            }
            display_exVideos = display_exVideos+"</div>";
            $('.explore-container').append(display_exVideos);
        
    }
    // Display intial explore videos
    setTimeout(function(){
        loadExploreVideoOnScroll(start,last_pos); 
    },200);

    // Display explore videos on scroll
    $(window).scroll(function(e){//when we are scrolling
        // console.log('working');
        setTimeout(function(){
            if($(window).scrollTop()+$(window).innerHeight() > $(".explore-videos").height() ){
                 start +=limit;
                
                //checks if there are enough videos that can be displayed
                if(last_pos+limit > videoDataLength && stopScrolling==false){
                    // console.log('working');
                    stopScrolling=true;
                    $(window).unbind('scroll'); //stops screen from scrolling 
                    last_pos = videoDataLength;
                    loadExploreVideoOnScroll(start,last_pos);  
                    $('.moreVideosMessage').html('Pena enkor video pln. to scroll em xD'); 
                }
                else{
                    last_pos +=limit;
                    loadExploreVideoOnScroll(start,last_pos);   
                }
                        
            }
        },1750);    
    })

    /*script for owl carousel*/
    $(".owl-carousel ").owlCarousel({
        loop:true,
        margin:30,
        nav:false,
        autoplay: true,
        autoplayTimeout: 2500,
        stagePadding:350,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:1
            },
            1000:{
                items:1
            }
        }
    });


});    