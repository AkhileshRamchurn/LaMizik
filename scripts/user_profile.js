// /*Changing profile*/
// $(document).ready(function(){
//     var profile_container = $('.profile-container');
//     var profile_pic = $('.profile-pic img');
//     var file = $('#file');
//     var uploadBtn = $('#uploadBtn');
    
//     //if user hover on profile container, display choose photo option
//     profile_container.on({  
//         mouseenter: function(){
//             uploadBtn.css("display", "block");
//         },
//         mouseleave: function(){
//             uploadBtn.css("display", "none");
//         }
    
//     });

//     //display choosen image
//     file.change(function(event){
//        var chosenFile = this.files[0]; //Stores the file that has been chosen; that is the image from computer
//         //console.log(chosenFile);       
        
//         if(chosenFile){
//             var reader = new FileReader(); // create object of file reader
//             reader.onload = function(event) {
//                 profile_pic.attr("src", reader.result);//change source attribute of image 
//                 //console.log(reader.result);
//             };
//             reader.onerror = function(event) {
//                 alert("I AM ERROR: " + event.target.error.code);
//             };
//             reader.readAsDataURL(chosenFile);
//         }
//     });
// });


/*Displaying Contents panel */

$(document).ready(function(){
    var currentPanel = $(".personal-info-panel");
    $('#info-btn').click(function(){
        $(currentPanel).fadeOut(200, function(){
            $(".personal-info-panel").show();
            currentPanel = $(".personal-info-panel");
        }); 
    });
    $('#reset-btn').click(function(){
        $(currentPanel).fadeOut(200, function(){
            $(".resetPassword-panel").show();
            currentPanel = $(".resetPassword-panel");
        }); 
    });
    $('#wallet-btn').click(function(){
        $(currentPanel).fadeOut(200, function(){
            $(".manage-wallet-panel").show();
            currentPanel = $(".manage-wallet-panel");
        }); 
    });
});

/* Update*/

/*Reset password*/
$(document).ready(function(){
    $('#pw-btn').click(function(event){
        event.preventDefault();
        var userID = $(this).attr('data-id');
        var old_pw = $('#old_password').val();
        var new_pw = $('#new_password').val();
        var repeat_pw = $('#repeat_password').val();
        
        // alert(old_pw + new_pw + repeat_pw);
        if(userID){
            $.ajax({
                url: "user_profile.php",
                type:"POST",
                data:{
                    old_pw : old_pw,
                    new_pw : new_pw,
                    repeat_pw : repeat_pw
                },
    
                success: function(data){
                    // alert(result);
                    /*add class error/ success to response div*/
                    if (data == 'success') {
                        alert('Your password has successfully been changed');
                    }
                    else {
                        $('#change-password-response').html(data);
                    }
                    $('#password-form input').val('');
    
                }   
            });
        }
        else{
            $('#password-form input').val('');
            alert("You need to login to perform this action!");
        }
        
    });
});


$(document).ready(function(){
    $('#update-btn').on("click", function(){
    
        var userID = $(this).attr('data-id');
        var username = $('#username').val();
        var firstname = $('#first_name').val();
        var lastname = $('#last_name').val();
        var email = $('#email').val();
        var phonenum = $('#phone_num').val(); 
        var displayresponse =$('#change-personal-info-response');
        // console.log(userID);
        if(userID){
            if(!username && !firstname && !lastname && !email && !phonenum ){
                displayresponse.html('No change specified');
            }
            else{
                 //If inputs are empty, assign placeholder values to it
                if(!username){
                    username= $('#username').attr('placeholder');
                }
                if(!firstname){
                    firstname= $('#first_name').attr('placeholder');
                }
                if(!lastname){
                    lastname= $('#last_name').attr('placeholder');
                }
                if(!email){
                    email= $('#email').attr('placeholder');
                }
                if(!phonenum){
                    phonenum= $('#phone_num').attr('placeholder');
                }
                // console.log(username+ firstname + lastname + email + phonenum);
    
                $.ajax({
                    url: "user_profile.php",
                    type:"POST",
                    data: {
                        username:username,
                        firstname:firstname,
                        lastname:lastname,
                        email:email,
                        phonenum:phonenum
                    },
                    error: function(xhr){
                        alert("An error occured:" + xhr.status+" "+ xhr.statusText);
                    }
    
                })
                .done(function(data){
            
                    // console.log(responseData);
                    var checkresponseValidity= data.substr(0,7);
                    // console.log(checkresponseValidity);
    
                    //if an error occur at the server side
                     if(checkresponseValidity == 'Invalid'){
                        $('#change-personal-info-response').html("*"+data);
                     }
                     else {//else if the update was successful
                        alert("Update successful");
                        $('#change-personal-info-response').html("");
                        var responseData = data.split(" ");
                        // console.log(responseData[0]);
                        $('.personal-info-field input').val('');
                        
                        $('#username').attr('placeholder', responseData[0]);
                        $('#first_name').attr('placeholder', responseData[1]);
                        $('#last_name').attr('placeholder', responseData[2]);
                        $('#email').attr('placeholder', responseData[3]);
                        $('#phone_num').attr('placeholder', responseData[4]);
    
                     }   
                });
    
            }

        }
        else{
            $('.personal-info-field input').val('');
            alert("You need to login to perform this action!");
        }  
    });
});

/*Add/Remove card*/
$(document).ready(function(){
    $('#btn-add-card').click(function(event){
        var userID = $(this).attr('data-id');
        if(userID){
            $.ajax({
                url: "user_profile.php",
                type:"POST",
                data:{
                    card_action: "add" 
                },
    
                success: function(data){
                    if (data == 'success') {
                        alert('Card has been successfully added');
                        location.reload();
                    }
                }   
            });
        }
    });
    $('#btn-remove-card').click(function(event){
        var userID = $(this).attr('data-id');
        
        if(userID){
            $.ajax({
                url: "user_profile.php",
                type:"POST",
                data:{
                    card_action: "remove" 
                },
    
                success: function(data){
                    if (data == 'success') {
                        alert('Card has been successfully removed');
                        location.reload();
                    }
                }   
            });
        }
    });
});
