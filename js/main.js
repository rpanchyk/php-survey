$(document).ready(function(){
    var sliderHeight = parseInt($('.slider_container').css('height'));
    $('.slider_container').css('top', $(window).height()/2 - sliderHeight/2 + 'px');
    
    var buttonHeight = parseInt($('#slider_button_prev').css('height'));
    $('#slider_button_prev').css('top', $(document).height()/2 - buttonHeight/2 + 'px');
    $('#slider_button_next').css('top', $(document).height()/2 - buttonHeight/2 + 'px');
    
    $('.slider_container').firetrotSlider();
});
