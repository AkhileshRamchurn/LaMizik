$(document).ready(function() {

    $("#signin-btn").on('click', function () {
        window.location.href = "login.php";
    });

    $("#skip-btn").on('click', function () {
        window.location.href = "home.php";
    });



    const progressText = $(".step p");
    const progressCheck = $(".step .check");
    const bullet = $(".step .bullet");
    let current = 1;

    $(".firstNext").on("click", function(){
        const usernameValue = $('.input-username').val().trim();
        const passwordValue = $('.input-password').val().trim();
        const passwordRepeatValue = $('.input-password-repeat').val().trim();

        var usernameValid;
        var passwordValid;
        var repeatPasswordValid;
        var bothPasswordValid;

        if(usernameValue === '') {
            $('.username-error-msg').text('Username required');
            $('.input-username').addClass('invalid-field');
            usernameValid = 0;
        }
        else {
            $('.username-error-msg').text('');
            $('.input-username').removeClass('invalid-field');
            usernameValid = 1;
        }

        if(passwordValue === '') {
            $('.password-error-msg').text('Password required');
            $('.input-password').addClass('invalid-field');
            passwordValid = 0;
        }
        else {
            $('.password-error-msg').text('');
            $('.input-password').removeClass('invalid-field');
            passwordValid = 1;
        }

        if(passwordRepeatValue === '') {
            $('.password-repeat-error-msg').text('Password required');
            $('.input-password-repeat').addClass('invalid-field');
            repeatPasswordValid = 0;
        }
        else {
            $('.password-repeat-error-msg').text('');
            $('.input-password-repeat').removeClass('invalid-field');
            repeatPasswordValid = 1;
        }

        if(passwordValid == 1 && repeatPasswordValid == 1) {
            if (passwordValue != passwordRepeatValue) {
                $('.password-error-msg').text('Passwords do not match');
                $('.password-repeat-error-msg').text('');
                $('.input-password').addClass('invalid-field');
                $('.input-password').val('');
                $('.input-password-repeat').addClass('invalid-field');
                $('.input-password-repeat').val('');
                bothPasswordValid = 0;
            }
            else {
                $('.password-error-msg').text('');
                $('.input-password').removeClass('invalid-field');
                $('.input-password-repeat').removeClass('invalid-field');
                bothPasswordValid = 1;
            }
        }

        if (usernameValid == 1 && passwordValid == 1 && repeatPasswordValid == 1 && bothPasswordValid == 1) {
            $(".main-form").css("transform", "translateX(-"+100+"%)");
            bullet[current - 1].classList.add("active");
            progressCheck[current - 1].classList.add("active");
            progressText[current - 1].classList.add("active");
            current += 1;
        }
    });

    $(".next-1").on("click", function(){
        const fnameValue = $('.input-first-name').val().trim();
        const lnameValue = $('.input-last-name').val().trim();
        const emailValue = $('.input-email').val().trim();
        const phoneNumValue = $('.input-phone-number').val().trim();

        var fnameValid;
        var lnameValid;
        var emailValid;
        var phoneNumValid;

        function validateEmail(email) {
            const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(String(email).toLowerCase());
        }

        if(fnameValue === '') {
            $('.fname-error-msg').text('First name required');
            $('.input-first-name').addClass('invalid-field');
            fnameValid = 0;
        }
        else {
            $('.fname-error-msg').text('');
            $('.input-first-name').removeClass('invalid-field');
            fnameValid = 1;
        }

        if(lnameValue === '') {
            $('.lname-error-msg').text('Last name required');
            $('.input-last-name').addClass('invalid-field');
            lnameValid = 0;
        }
        else {
            $('.lname-error-msg').text('');
            $('.input-last-name').removeClass('invalid-field');
            lnameValid = 1;
        }

        if(emailValue === '') {
            $('.email-error-msg').text('Email required');
            $('.input-email').addClass('invalid-field');
            emailValid = 0;
        }
        else if(!validateEmail(emailValue)) {
            $('.email-error-msg').text('Invalid Email');
            $('.input-email').addClass('invalid-field');
            emailValid = 0;
        }
        else {
            $('.email-error-msg').text('');
            $('.input-email').removeClass('invalid-field');
            emailValid = 1;
        }

        if(phoneNumValue === '') {
            $('.phone-number-error-msg').text('Phone number required');
            $('.input-phone-number').addClass('invalid-field');
            phoneNumValid = 0;
        }
        else if(isNaN(phoneNumValue) || phoneNumValue<=50000000 || phoneNumValue>=60000000) {
            $('.phone-number-error-msg').text('Invalid Phone number');
            $('.input-phone-number').addClass('invalid-field');
            phoneNumValid = 0;
        }
        else {
            $('.phone-number-error-msg').text('');
            $('.input-phone-number').removeClass('invalid-field');
            phoneNumValid = 1;
        }

        if (fnameValid == 1 && lnameValid == 1 && emailValid == 1 && phoneNumValid == 1) {
            $(".main-form").css("transform", "translateX(-"+200+"%)");
            bullet[current - 1].classList.add("active");
            progressCheck[current - 1].classList.add("active");
            progressText[current - 1].classList.add("active");
            current += 1;
        }
    });

    $(".next-2").on("click", function(event){
        const cardNumberValue = $('.input-card-number').val().trim();
        const cardExpValue = $('.input-card-exp').val().trim();
        const cardCCVValue = $('.input-card-ccv').val().trim();

        var cardNumberValid;
        var cardExpValid;
        var cardCCVValid;

        if(cardNumberValue === '') {
            $('.card-number-error-msg').text('Card Number required');
            $('.input-card-number').addClass('invalid-field');
            cardNumberValid = 0;
        }
        else if (isNaN(cardNumberValue)) {
            $('.card-number-error-msg').text('Invalid Card Number');
            $('.input-card-number').addClass('invalid-field');
            cardNumberValid = 0;
        }
        else {
            $('.card-number-error-msg').text('');
            $('.input-card-number').removeClass('invalid-field');
            cardNumberValid = 1;
        }

        if(cardExpValue === '') {
            $('.card-exp-error-msg').text('Card expiry date required');
            $('.input-card-exp').addClass('invalid-field');
            cardExpValid = 0;
        }
        else {
            $('.card-exp-error-msg').text('');
            $('.input-card-exp').removeClass('invalid-field');
            cardExpValid = 1;
        }

        if(cardCCVValue === '') {
            $('.card-ccv-error-msg').text('Card CCV required');
            $('.input-card-ccv').addClass('invalid-field');
            cardCCVValid = 0;
        }
        else if(isNaN(cardCCVValue)) {
            $('.card-ccv-error-msg').text('Invalid Card CCV');
            $('.input-card-ccv').addClass('invalid-field');
            cardCCVValid = 0;
        }
        else {
            $('.card-ccv-error-msg').text('');
            $('.input-card-ccv').removeClass('invalid-field');
            cardCCVValid = 1;
        }

        if (cardNumberValid == 1 && cardExpValid == 1 && cardCCVValid ==1) {
            $(".main-form").css("transform", "translateX(-"+300+"%)");
            bullet[current - 1].classList.add("active");
            progressCheck[current - 1].classList.add("active");
            progressText[current - 1].classList.add("active");
            current += 1;
        }
    });

    $(".next-skip").on("click", function(event){
        $(".input-card-number").val('');
        $(".input-card-exp").val('');
        $(".input-card-ccv").val('');

        $('.card-number-error-msg').text('');
        $('.input-card-number').removeClass('invalid-field');
        $('.card-exp-error-msg').text('');
        $('.input-card-exp').removeClass('invalid-field');
        $('.card-ccv-error-msg').text('');
            $('.input-card-ccv').removeClass('invalid-field');

        $(".main-form").css("transform", "translateX(-"+300+"%)");
        bullet[current - 1].classList.add("active");
        progressCheck[current - 1].classList.add("active");
        progressText[current - 1].classList.add("active");
        current += 1;
    });

    $(".prev-1").on("click", function(){
        $(".main-form").css("transform", "translateX("+0+"%)");
        bullet[current - 2].classList.remove("active");
        progressCheck[current - 2].classList.remove("active");
        progressText[current - 2].classList.remove("active");
        current -= 1;
    });

    $(".prev-2").on("click", function(event){
        $(".main-form").css("transform", "translateX(-"+100+"%)");
        bullet[current - 2].classList.remove("active");
        progressCheck[current - 2].classList.remove("active");
        progressText[current - 2].classList.remove("active");
        current -= 1;
    });

    $(".prev-3").on("click", function(event){
        $(".main-form").css("transform", "translateX(-"+200+"%)");
        bullet[current - 2].classList.remove("active");
        progressCheck[current - 2].classList.remove("active");
        progressText[current - 2].classList.remove("active");
        current -= 1;
    });

    $(".btn-submit").on("click", function(){
        //-------------------
    });

});
