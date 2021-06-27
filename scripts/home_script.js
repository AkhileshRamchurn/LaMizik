$(document).ready(function(){
   
    var user_id= $('.main-container').attr('data-id');
    // console.log(user_id);

    /************** Fetch data of all videos********************/
    var videoData;
    var videoDataLength;
    var url ="ajax/getVideoData.php";
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
            // console.log(data);
            videoData = data.output;
            videoDataLength=videoData.length;
            // console.log(videoDataLength);
            // console.log(videoData);
         
        });
    }
    fetchVideoData();

    setTimeout(function(){
        // console.log(videoDataLength);
        if (videoDataLength < 8) {
            last_pos = videoDataLength;
        }
    },150);


     /************************ Load explore videos on scroll**************/
     var limit=8;//number of videos to display
     var start=0;//index of first video to be displayed
     var last_pos=8;//position in video array
     var stopScrolling=false;
     
    
     function loadExploreVideoOnScroll(start,last_pos){ 
         var display_exVideos="";
         for(var i=start;i<last_pos; i++){
             display_exVideos= display_exVideos+"<div class='video-container' >";
             display_exVideos= display_exVideos+"<a href='view.php?video_id="+videoData[i].Video_ID+"' target='_self'>";  
             display_exVideos=display_exVideos+"<img src='video/thumbnail/"+videoData[i].Video_ID+"t.jpg'>";
             display_exVideos=display_exVideos +"<div class='video-details'><h4>"+videoData[i].Title+"</h4></a><p>"+videoData[i].Username+"</p>"+"<p>"+moment.unix(videoData[i].Upload_Timestamp).fromNow()+"<span class='midot3'>&#183;</span>"+videoData[i].Views+" views</p>"+"</div>";  
             display_exVideos=display_exVideos +"</div>"; 
             $('.explore-videos').append(display_exVideos);
             display_exVideos="";
         }
 
         
     }
     // Display intial explore videos
     setTimeout(function(){
         loadExploreVideoOnScroll(start,last_pos); 
     },250);
 
     // Display explore videos on scroll
     $(window).scroll(function(e){//when we are scrolling
         // console.log('working');
         setTimeout(function(){
             if($(window).scrollTop() >= $('.explore-container').offset().top + $('.explore-container').outerHeight() - window.innerHeight){
                  start +=limit;
                 
                 //checks if there are enough videos that can be displayed
                 if(last_pos+limit > videoDataLength && stopScrolling==false){
                     // console.log('working');
                     stopScrolling=true;
                     $(window).unbind('scroll'); //stops screen from scrolling 
                     last_pos = videoDataLength;
                     loadExploreVideoOnScroll(start,last_pos);  
                    //  $('.moreVideosMessage').html('Pena enkor video pln. to scroll em xD'); 
                 }
                 else{
                     last_pos +=limit;
                     loadExploreVideoOnScroll(start,last_pos);   
                 }
                         
             }
         },1750);    
     })

    /************************ END Load explore videos on scroll***********/

    /*************************  Carousel VIDEOS **************************/
    function displayCarouselVideos(){
        //copying videoData to a new array
        var videoDataCopy = videoData.slice();
        
        //shuffling the new array
        shuffle(videoDataCopy);

        //trimming the array to contain the first 5 elements only
        videoDataCopy.splice(5);

        var randomVideoData;
        var display_carouselVideos="<h2>Picks of the day</h2><div class='owl-carousel owl-theme'>";
        for(var i=0;i<5; i++){
           
            randomVideoData = videoDataCopy[i]; 
            // console.log(randomVideoData);
            display_carouselVideos= display_carouselVideos+"<div class='item' >";
            display_carouselVideos= display_carouselVideos+"<a href='view.php?video_id="+randomVideoData.Video_ID+"' target='_self'>";  
            display_carouselVideos=display_carouselVideos+"<img src='video/thumbnail/"+randomVideoData.Video_ID+"t.jpg'>";
            display_carouselVideos=display_carouselVideos +"<h4>"+randomVideoData.Title+"<h4><p>"+randomVideoData.Username+"</p></a>";
            display_carouselVideos=display_carouselVideos +"</div>"; 
        }
        display_carouselVideos = display_carouselVideos+"</div>";
    
        $('.carousel-container').append(display_carouselVideos);
        
    }   
    
    setTimeout(function(){
        displayCarouselVideos();
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
    },250);

    //function to shuffle an array
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

    
    

    /************************* END Carousel VIDEOS ************************/
 

   /************************* RECOMMENDED VIDEOS ************************/
   
    //Moved to PHP (home.php) Consumed at PhP level

    // var recommendedVideos;
    // var NumRecommendedVideos; 
    // var url2='recommended_videos.php?user_id='+user_id;
    //  /*Fetch recommended videos */
    //  function fetchRecommededVideos(){
    //      $.ajax({
    //          url:url2,
    //          accepts:"application/json",
    //          cache:false,
    //          type:"GET",
    //          error:function(xhr){
    //              alert("An error occured: " + xhr.status + " " + xhr.statusText);
    //          }
    //      })
 
    //      .done(function(data){
    //         // console.log(data[0].Recommended_Videos);
    //         recommendedVideos =data[0].Recommended_Videos;
    //         NumRecommendedVideos = recommendedVideos.length;
    //         // console.log(recommendedVideos);
    //         // console.log(recommendedVideosNum);
         
    //      });
    //  }
    //  fetchRecommededVideos();

    //  var last_pos_rec=8;//position in video recommended array
    //  setTimeout(function(){   
    //     if (NumRecommendedVideos< 8) {
    //         last_pos_rec =NumRecommendedVideos;
    //     }
    // },150);//wait 100ms for data to be fetched

    //  /*Display Recommend Videos after videos data have been fetched*/
    // function displayRecommendVideos(){
    //     if(user_id) {
    //         var display_reVideos="<h2>Recommended for you</h2><div class='recommended-videos'>";
    //         for(var i=0;i<last_pos_rec; i++){
    //             display_reVideos= display_reVideos+"<div class='video-container' >";
    //             display_reVideos= display_reVideos+"<a href='view.php?video_id="+recommendedVideos[i].Video_ID+"' target='_self'>";  
    //             display_reVideos=display_reVideos+"<img src='video/thumbnail/"+recommendedVideos[i].Video_ID+"t.jpg'>";
    //             display_reVideos=display_reVideos +"<div class='video-details'><h4>"+recommendedVideos[i].Title+"</h4></a><p>"+recommendedVideos[i].Username+"</p>"+"<p>"+moment.unix(recommendedVideos[i].Upload_Timestamp).fromNow()+"<span class='midot3'>&#183;</span>"+recommendedVideos[i].views+" views</p>"+"</div>";  
    //             display_reVideos=display_reVideos +"</div>"; 
    //         }
    //         display_reVideos = display_reVideos+"</div><span class='separator'></span>";
    //         //  console.log(display_reVideos);
    //         $('.recommended-container').append(display_reVideos);
    //     }
    //     else{
    //         $('.recommended-container').empty();
    //     }

    // }
    
    // /*Displays videos when home page is first loaded*/
    // setTimeout(function(){
    //     displayRecommendVideos();
    // },250);

    $('.timestamp').each(function() {
        var ts = $(this).text();
        console.log(ts);
        var newTs = moment.unix(ts).fromNow();
        $(this).text(newTs);
    });

    /************************* END OF RECOMMENDED VIDEOS ************************/ 
    
   


});    