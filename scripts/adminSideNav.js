$(document).ready(function() {
    var path = window.location.href;
    $('.menu a').each(function(){
            if (path.startsWith(this.href)) {
                $(this).addClass('active');
            }
    });
});