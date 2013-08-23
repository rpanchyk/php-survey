/*
**
** FireToolTip - jquery tooltip plugin
** copyright: by FireTrot Studio (c) 2010
** developer: Ben
** tester: Geo
** date create: 2010-09-21
** date update: 2010-10-06
**
*/

$(document).ready(function() {
	
	// Settings
	var nShowDuration = 200; // milliseconds
	var nHideDuration = 200; // milliseconds
	var nSpacer = 50; // pixels between cursor and tip
	var bIsUseRoundCorner = false; // Use the round corners [ true | false ]
	var rcRadius = "8px"; // Radius of corner
	var rcPadding = "5px"; // Padding of corner content
	var rcBackGround = "#FFFF99";
	var bIsUseArrow = false; // Use the arrow for tooltip [ true | false ]
	var nArrowPadding = "12px"
	
	// For loading scripts
	function includeJS(url) {
	  var script = document.createElement('script');
	  script.setAttribute('type', 'text/javascript')
	  script.setAttribute('src', url);
	  document.getElementsByTagName('head').item(0).appendChild(script);
	}
	function includeCSS(url) {
	  var script = document.createElement('link');
	  script.setAttribute('rel', 'stylesheet')
	  script.setAttribute('type', 'text/css')
	  script.setAttribute('href', url);
	  document.getElementsByTagName('head').item(0).appendChild(script);
	}
	
	// Load scripts
	
	// Corner
	if (bIsUseRoundCorner){
		includeJS("js/jquery.corner.js"); // http://www.malsup.com/jquery/corner/
	}
	// Styles
	includeCSS("js/firetooltip/css/global.css");
	
	// Change DOM: add new DIV to the page
	$("*[id='fttp']").each( function(){
		// Tip content
		var strTip = '<table id="fttp_container" style="display:none; position:absolute; overflow:hidden; margin:auto; padding:0px; background:transparent;" border="0" cellpadding="0" cellspacing="0">'
			+ '<tr><td id="fttp_arrow_up">' + '<img src="js/firetooltip/images/arrow_up.gif" border="0">' + '</td></tr>'
			+ '<tr><td id="fttp_container_content">' + $(this).attr('title') + '</td></tr>'
			+ '<tr><td id="fttp_arrow_down">' + '<img src="js/firetooltip/images/arrow_down.gif" border="0">' + '</td></tr>'
			+ '</table>';
		$(this).after(strTip);
		$(this).removeAttr('title');
		$(this).css("cursor", "pointer");
	});
	
	if (!bIsUseArrow){
		$("*[id='fttp_arrow_up']").each(function(){
			$(this).hide();
		});
		$("*[id='fttp_arrow_down']").each(function(){
			$(this).hide();
		});
	}
	
	/*
	** EVENTS
	*/
	$("*[id='fttp']").each(function(){
		$(this).mousemove(function(e){
			// Set some params
			var nWindowWidth_ScrollLeft = $(window).width() + $(window).scrollLeft();
			var nWindowHeight_ScrollTop = $(window).height() + $(window).scrollTop();
			var nPageX_Spacer_ObjectWidth = e.pageX + nSpacer + $(this).next().width();
			var nPageY_Spacer_ObjectHeight = e.pageY + nSpacer + $(this).next().height();
			
			// Default position
			//SetRightBottom($(this).next(), e, bIsUseArrow);
			
			// Correct position
			if ( nWindowWidth_ScrollLeft < nPageX_Spacer_ObjectWidth && nWindowHeight_ScrollTop < nPageY_Spacer_ObjectHeight ){
				SetLeftTop($(this).next(), e, bIsUseArrow); //$("title").text("SetLeftTop: < <");
			}
			
			if ( nWindowWidth_ScrollLeft < nPageX_Spacer_ObjectWidth && nWindowHeight_ScrollTop >= nPageY_Spacer_ObjectHeight ){
				SetLeftBottom($(this).next(), e, bIsUseArrow); //$("title").text("SetLeftBottom: < >=");
			}
			
			if ( nWindowWidth_ScrollLeft > nPageX_Spacer_ObjectWidth && nWindowHeight_ScrollTop < nPageY_Spacer_ObjectHeight ){
				SetRightTop($(this).next(), e, bIsUseArrow); //$("title").text("SetRightTop: > <");
			}
			
			if ( nWindowWidth_ScrollLeft > nPageX_Spacer_ObjectWidth && nWindowHeight_ScrollTop >= nPageY_Spacer_ObjectHeight ){
				SetRightBottom($(this).next(), e, bIsUseArrow); //$("title").text("SetRightBottom: > >=");
			}
			
			// Show tip ? :)
			ShowToolTip($(this).next(), nShowDuration);
		});
		
		$(this).mouseout(function(){
			// Hide tip ? :)
			HideToolTip($(this).next(), nHideDuration);
		});
	});
	
	$("*[id='fttp_container']").each(function(){
		$(this).mousemove(function(e){
			// Set cursor as in parent (for smoothing visual effect)
			$(this).css("cursor", $(this).parent("*[id='fttp']").css("cursor"));
		});
	});
	
	/*
	** FUNCTIONS
	*/
	
	// Set object position
	function SetLeftTop(object, e, bIsUseArrow){
		if (bIsUseArrow){
			// Add arrow to tip
			$("*[id='fttp_arrow_up']", object).each(function(){
				$(this).hide();
			});
			$("*[id='fttp_arrow_down']", object).each(function(){
				$(this).css("text-align", "right").css("padding-right", nArrowPadding).show();
			});
		}
		
		// Change tip position
		object.css("left", e.pageX-10-object.width()).css("top", e.pageY-5-object.height());
	}
	function SetLeftBottom(object, e, bIsUseArrow){
		if (bIsUseArrow){
			// Add arrow to tip
			$("*[id='fttp_arrow_down']", object).each(function(){
				$(this).hide();
			});
			$("*[id='fttp_arrow_up']", object).each(function(){
				$(this).css("text-align", "right").css("padding-right", nArrowPadding).show();
			});
		}
		
		// Change tip position
		object.css("left", e.pageX-5-object.width()).css("top", e.pageY+20);
	}
	function SetRightTop(object, e, bIsUseArrow){
		if (bIsUseArrow){
			// Add arrow to tip
			$("*[id='fttp_arrow_up']", object).each(function(){
				$(this).hide();
			});
			$("*[id='fttp_arrow_down']", object).each(function(){
				$(this).css("text-align", "left").css("padding-left", nArrowPadding).show();
			});
		}
		
		// Change tip position
		object.css("left", e.pageX+5).css("top", e.pageY-5-object.height());
	}
	function SetRightBottom(object, e, bIsUseArrow){
		if (bIsUseArrow){
			// Add arrow to tip
			$("*[id='fttp_arrow_down']", object).each(function(){
				$(this).hide();
			});
			$("*[id='fttp_arrow_up']", object).each(function(){
				$(this).css("text-align", "left").css("padding-left", nArrowPadding).show();
			});
		}
		
		// Change tip position
		object.css("left", e.pageX+5).css("top", e.pageY+20);
	}
	
	// Show object
	function ShowToolTip(object, duration){
		if (bIsUseRoundCorner)
		{
			$("*[id='fttp_container_content']", object).each(function(){
				$(this).corner(rcRadius);
				$(this).css("padding", rcPadding);
				$(this).css("background", rcBackGround);
			});
		}

		object.fadeIn(duration);
	}
	// Hide object
	function HideToolTip(object, duration){
		object.fadeOut(duration);
	}
});