(function($){
	
    $.confirmDialog = function(params){
		
        if($('#confirmOverlay').length){
            // A confirm is already shown on the page:
            return false;
        }
		
        var buttonHTML = '';
        $.each(params.buttons,function(name,obj){
			
            // Generating the markup for the buttons:
			
            buttonHTML += '<a href="#" class="button '+obj['class']+'">'+name+'<span></span></a>';
			
            if(!obj.action){
                obj.action = function(){};
            }
        });
		
        var markup = [
        '<div id="confirmOverlay">',
        '<div id="confirmBox">',
        '<h1>',params.title,'</h1>',
        '<p>',params.message,'</p>',
        '<div id="confirmButtons">',
        buttonHTML,
        '</div></div></div>'
        ].join('');
		
        $(markup).hide().appendTo('body').fadeIn();
		
        var buttons = $('#confirmBox .button'),
        i = 0;

        $.each(params.buttons,function(name,obj){
            buttons.eq(i++).click(function(){
				
                // Calling the action attribute when a
                // click occurs, and hiding the confirm.
				
                obj.action();
                $.confirmDialog.hide();
                return false;
            });
        });
    }

    $.confirmDialog.hide = function(){
        $('#confirmOverlay').fadeOut(function(){
            $(this).remove();
        });
    }
        
    $.messageDialog = function(params){
        if($('#messageOverlay').length){
            // A confirm is already shown on the page:
            return false;
        }
		
        var buttonHTML = '';
        $.each(params.buttons,function(name,obj){
			
            // Generating the markup for the buttons:
			
            buttonHTML += '<a href="#" class="button '+obj['class']+'">'+name+'<span></span></a>';
			
            if(!obj.action){
                obj.action = function(){};
            }
        });
		
        var markup = [
        '<div id="messageOverlay">',
        '<div id="messageBox">',
        '<h1>',params.title,'</h1>',
        '<p>',params.message,'</p>',
        '<div id="messageButtons">',
        buttonHTML,
        '</div></div></div>'
        ].join('');
		
        $(markup).hide().appendTo('body').fadeIn();
		
        var buttons = $('#messageBox .button'),
        i = 0;

        $.each(params.buttons,function(name,obj){
            buttons.eq(i++).click(function(){
				
                // Calling the action attribute when a
                // click occurs, and hiding the confirm.
				
                obj.action();
                $.messageDialog.hide();
                return false;
            });
        });
    }
        
    $.messageDialog.hide = function(){
        $('#messageOverlay').fadeOut(function(){
            $(this).remove();
        });
    }
        
    $.formDialog = function(params){
        
    }
        
    $.formDialog.hide = function(){
        $('#formOverlay').fadeOut(function(){
            $(this).remove();
        });
    }
        
    $.detailDialog = function(params){}
    
    $.detailDialog.hide = function(){
        $('#detailOverlay').fadeOut(function(){
            $(this).remove();
        });
    }
    
    function updateTips( t ) {
        tips
        .text( t )
        .addClass( "ui-state-highlight" );
        setTimeout(function() {
            tips.removeClass( "ui-state-highlight", 1500 );
        }, 500 );
    }

    function checkLength( o, n, min, max ) {
        if ( o.val().length > max || o.val().length < min ) {
            o.addClass( "ui-state-error" );
            updateTips( "Length of " + n + " must be between " +
                min + " and " + max + "." );
            return false;
        } else {
            return true;
        }
    }

    function checkRegexp( o, regexp, n ) {
        if ( !( regexp.test( o.val() ) ) ) {
            o.addClass( "ui-state-error" );
            updateTips( n );
            return false;
        } else {
            return true;
        }
    }
	
})(jQuery);