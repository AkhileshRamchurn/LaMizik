$(document).ready(function(){
    var url = "http://localhost/Lamizik/video/list";
   
    function fetchVideosAPI(){
        $.ajax({
            url:url,
            // accepts: "application/json",
            headers:{Accept:"application/json"},
            type:"POST",
            error: function(xhr){
               alert("An error occured: " + xhr.status + " " + xhr.statusText);
            }
        })
        .done(function(data){
            // alert(JSON.stringify(data));
            // console.log(data);



        });
    }

    fetchVideosAPI();

    $(".owl-carousel ").owlCarousel({
        loop:true,
        margin:50,
        nav:false,
        autoplay: true,
        autoplayTimeout: 2500,
        stagePadding:400,
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