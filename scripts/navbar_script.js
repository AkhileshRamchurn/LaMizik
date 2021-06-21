$(document).ready(function() {
    var path = window.location.href;
    $('.navbar a').each(function(){
            if (path.startsWith(this.href)) {
                $(this).addClass('active');
            }
    });
});