$(document).ready(function() {

    var profile_dropdown = document.querySelector(".profile-dropdown");

    if (profile_dropdown != null) {
        profile_dropdown.addEventListener("click", function(){
            this.classList.toggle("active");
        });
    }

    var path = window.location.href;
    $('.navbar a').each(function(){
        if (path.startsWith(this.href) && !(path.startsWith("http://localhost/Lamizik/user_profile.php"))) {
            $(this).addClass('active');
        }
    });
});