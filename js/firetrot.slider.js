/**
* author: Ben
* date: 2013-12-30
*/
$.fn.firetrotSlider = function(options){
    // Default settings
    var settings = $.extend({
        duration: 600,
        loop: false,
        tapeSelector: '.slider_tape',
        itemSelector: '.slider_item',
        nextButtonSelector: '#slider_button_next',
        prevButtonSelector: '#slider_button_prev',
        isShowButtonsOnAnimation: false,
    }, options);
    
    // Get dynamic params
    var isCanAnimate = true;
    var itemsCount = $(settings['itemSelector']).length;
    var itemWidth = parseInt($(settings['itemSelector']).css('width'));
    var tapeWidth = itemWidth * itemsCount;
    //alert('itemsCount: ' + itemsCount + ', itemWidth: ' + itemWidth + ', tapeWidth: ' + tapeWidth);
    
    // Initial elements configuration
    $(settings['tapeSelector']).css('width', tapeWidth + 'px');
    $(settings['itemSelector']).css('width', itemWidth + 'px');
    
    var left = parseInt($(settings['tapeSelector']).css('left'));
    if (left == 0) {
        $(settings['prevButtonSelector']).hide();
    }
    
    
    var slideNext = function(){
        if (!isCanAnimate) return false;
        var left = parseInt($(settings['tapeSelector']).css('left'));
        var defaultPosition = settings['loop'] ? 0 : left;
        left = left - itemWidth == -tapeWidth ? defaultPosition : left - itemWidth;
        animate(left + 'px');
        return false;
    };
    var slidePrev = function(){
        if (!isCanAnimate) return false;
        var left = parseInt($(settings['tapeSelector']).css('left'));
        var defaultPosition = settings['loop'] ? -(tapeWidth - itemWidth) : 0;
        left = left == 0 ? defaultPosition : left + itemWidth;
        animate(left + 'px');
        return false;
    };
    
    var animate = function(offset){
        $(settings['tapeSelector']).animate({left: offset}, {
            duration: settings['duration'],
            start: function(){
                isCanAnimate = false;
                if (!settings['isShowButtonsOnAnimation']) {
                    $(settings['nextButtonSelector']).hide();
                    $(settings['prevButtonSelector']).hide();
                }
            },
            done: function(){
                isCanAnimate = true;
                if (!settings['isShowButtonsOnAnimation']) {
                    $(settings['nextButtonSelector']).show();
                    $(settings['prevButtonSelector']).show();
                }
                // Edge processing
                var left = parseInt($(settings['tapeSelector']).css('left'));
                if (left == 0) {
                    $(settings['prevButtonSelector']).hide();
                }
                if (left - itemWidth == -tapeWidth) {
                    $(settings['nextButtonSelector']).hide();
                }
            }
        });
    }
    
    // Bind buttons
    $(settings['nextButtonSelector']).click(slideNext);
    $(settings['prevButtonSelector']).click(slidePrev);
    return this;
}
