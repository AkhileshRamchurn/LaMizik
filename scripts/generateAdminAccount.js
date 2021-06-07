$(document).ready(function() {

    $('.btn-generate').click(function() {
        var username = generateString(10, alphaLower+numbers);
        var password = generateString(10, alphaLower+alphaUpper+numbers+symbols);
        
        $.ajax({
            url: "ajax/generate_adminAjax.php",
            type: "POST",
            data: {
                username: username,
                password: password
            },
            cache:false,
            success: function(){
                $('#username').val(username);
                $('#password').val(password);
                alert("Account Generated");
            }
        });

    });

    const alphaLower = "abcdefghijklmnopqrstuvwxyz";
    const alphaUpper = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    const numbers = "0123456789";
    const symbols = "!@#$%^&*_-+=";

    function generateString(length, characters) {
        let randomString = "";
        for (let i = 0; i < length; i++) {
            randomString += characters.charAt(
                Math.floor(Math.random() * characters.length)
            );
        }
        return randomString;
    };



    $('#btn-copy-username').click(function() {
        navigator.clipboard.writeText($('#username').val());
    });

    $('#btn-copy-password').click(function() {
        navigator.clipboard.writeText($('#password').val());
    });

});