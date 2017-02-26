(function($) {
 "use strict"
 
 // Accordion Toggle Items
   var iconOpen = 'glyphicon glyphicon-minus',
       iconClose = 'glyphicon glyphicon-plus';

    $(document).on('show.bs.collapse hide.bs.collapse', '.accordion', function (e) {
        var $target = $(e.target);
          $target.siblings('.accordion-heading').find('em').toggleClass(iconOpen + ' ' + iconClose);
          if(e.type == 'show')
              $target.prev('.accordion-heading').find('.accordion-toggle').addClass('active');
          if(e.type == 'hide')
        	  $target.prev('.accordion-heading').find('.accordion-toggle').removeClass('active');
    });    
    
    var hash = window.location.hash;
    if(hash != null){
    	//console.log($(hash+':first').parent().find('.accordion-toggle'));
    	$(hash+':first').parent().find('.accordion-toggle').trigger('click');
      	//$(hash+':first').parent().find('em').toggleClass(iconOpen + ' ' + iconClose);
    	//$(hash+':first').parent().find('.accordion-toggle').addClass('active');
    }
    
})(jQuery);