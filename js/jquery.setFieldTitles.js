/**
 * jQuery.setFieldTitles
 * Copyright (c) 2012 Ryan Bagwell ryan(at)ryanbagwell(dot)com | http://www.ryanbagwell.com
 * Dual licensed under MIT and GPL.
  *
 * @projectDescription Sets input fields to the element's "title" attribute, clears it on focus, and restores it on blur if the use doesn't add it.
 *
 * @author Ryan Bagwell
 * @version 1.0
 */
 
(function( $ ) {
  
    var methods = {
        showTitle: function() {
          
         if ($(this).attr('type') == 'password' && $(this).val() == '') {
          $(this).hide().next().show();
          methods.showTitle.apply($(this).next());
          }
          
          if ($(this).val() == '')
             $(this).val($(this).attr('title'));
        },
        
        clearTitle: function() {
         
          if ($(this).attr('role') == 'password-cleartext') {
            $(this).hide().prev().show().focus();
          }
            
         if ($(this).val() == $(this).attr('title'))
             $(this).val('');
         
        }
    };
    
  $.fn.setFieldTitles = function() {
       
      this.each(function() {
        
        if ($(this).attr('type') == 'password') {
            $('<input />').attr({
              'type': 'text',
              'class': $(this).attr('class'),
              'title': $(this).attr('title'),
              'id': $(this).attr('id') + '-cleartext',
              'role': 'password-cleartext',
            }).css('display', 'none').setFieldTitles().insertAfter(this);
        }
  
         methods.showTitle.apply(this);

      });
          
      this.on('focus', function() {
          methods.clearTitle.apply(this);
      });
      
      this.on('blur', function() {
          methods.showTitle.apply(this);
      });
      
      return this;
  };
  
})( jQuery );