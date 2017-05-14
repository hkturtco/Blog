$(window).bind('scroll',function(e){
    parallaxScroll();
});

function parallaxScroll(){
    var scrolled = $(window).scrollTop();
    $('#headerbg').css('background-position','0% '+ (20+(scrolled*.06)) +'%');
    $('.propiccontainer').css('margin-top', (-70+(scrolled*.3)) +'px');
}
