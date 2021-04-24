

$(document).ready(function(){
    var action="";
    var video_id="";
        
    //if user clicks on the like button
    $('.like-btn').on('click',function(){
        video_id = $(this).data('id'); //receives data from data-id 
        $clicked_btn = $(this);
          
        //if user likes the video
        if($clicked_btn.hasClass('fa-thumbs-o-up')){
            action ='like';
        }
        //else if user unlike video
        else if($clicked_btn.hasClass('fa-thumbs-up')){
            action='unlike';
        }


        //using ajax to make changes without reloading
        $.ajax({
            url: 'view.php',
            type: 'post',
        
            data: {
                'action': action,
                'video_id': video_id
            },
            dataType:'JSON', 

            //if the response is successful from server
            success: function(data){
            
            res = JSON.parse(JSON.stringify(data)); 
           

            if(action == 'like'){
                $clicked_btn.removeClass('fa-thumbs-o-up');
                $clicked_btn.addClass('fa-thumbs-up');
            }else if(action == "unlike"){
                $clicked_btn.removeClass('fa-thumbs-up');
                $clicked_btn.addClass('fa-thumbs-o-up');
            }

            //display the number of likes and dislikes
            
            $clicked_btn.siblings('span.likes').text(res.likes);
            $clicked_btn.siblings('span.dislikes').text(res.dislikes);

            //change button styling of the other button if the user is reacting the second time to video
            $clicked_btn.siblings('i.fa-thumbs-down').removeClass('fa-thumbs-down').addClass('fa-thumbs-o-down')
            }
        });

       



    });

    //if user clicks on the dislike button
    $('.dislike-btn').on('click',function(){
        video_id = $(this).data('id'); //receives data from data-id (line 195)
        $clicked_btn = $(this);

        //if user likes the video
        if($clicked_btn.hasClass('fa-thumbs-o-down')){
            action ='dislike';
        }
        //else if user unlike video
        else if($clicked_btn.hasClass('fa-thumbs-down')){
            action='undislike';
        }

        //using ajax to make changes without reloading
     $.ajax({
        url: 'view.php',
        type: 'post',
       
        data: {
            'action': action,
            'video_id': video_id
        },
        dataType:'JSON', 

        //if the response is successful from server
        success: function(data){
            
           res = JSON.parse(JSON.stringify(data)); 
             
           if(action == 'dislike'){
               $clicked_btn.removeClass('fa-thumbs-o-down');
               $clicked_btn.addClass('fa-thumbs-down');
           }else if(action == "undislike"){
               $clicked_btn.removeClass('fa-thumbs-down');
               $clicked_btn.addClass('fa-thumbs-o-down');
           }

           //display the number of likes and dislikes
          $clicked_btn.siblings('span.likes').text(res.likes);
           $clicked_btn.siblings('span.dislikes').text(res.dislikes);

           //change button styling of the other button if the user is reacting the second time to video
           $clicked_btn.siblings('i.fa-thumbs-up').removeClass('fa-thumbs-up').addClass('fa-thumbs-o-up')
        }
    });


    });


    


    

    
});