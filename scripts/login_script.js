$(document).ready(function() {

    $("#signup-btn").on('click', function () {
        window.location.href = "register.php";
    });

    $("#skip-btn").on('click', function () {
        window.location.href = "home.php";
    });

    $(".log-in-btn").on('click', function (e) {
        checkInputs(e);
    });



    function checkInputs(e) {
        // trim to remove the whitespaces
        const usernameValue = $('.input-username').val().trim();
        const passwordValue = $('.input-password').val().trim();
        
        if(usernameValue === '') {
            $('#username-error-msg').text('Username/Email required');
            $('.input-username').addClass('invalid-field');
            e.preventDefault();
        } else {
            $('#username-error-msg').text('');
            $('.input-username').removeClass('invalid-field');
        }
        
        if(passwordValue === '') {
            $('#password-error-msg').text('Password required');
            $('.input-password').addClass('invalid-field');
            e.preventDefault();
        } else {
            $('#password-error-msg').text('');
            $('.input-password').removeClass('invalid-field');
        }
    }

});