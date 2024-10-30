
 /*
  *  author : venutius 
  *  website link : www.buddyuser.com  
  *  $('#publish').attr('disabled','disabled');
  */

jQuery(document).ready(function($){
   
    function bpsp_check_title_ajax(title, id) {
        var data = {
			security : ajax_object.check_nonce,
            action: 'bpsp_check_title',
            post_title: title,
            post_id: id
        };
        $.post(ajaxurl, data, function(response) {
			if ( response >= 1 ) {
				$('#submit').attr('disabled','disabled');
				$('#unique-title-message').show();
			} else {
				$('#submit').removeAttr('disabled');
				$('#unique-title-message').hide();
			}
		}); 
    };
    $('#bpsp_site_post_title').change(function() {
        var title = $('#bpsp_site_post_title').val();
        var id = $('#bpsp-our-id').val();
        bpsp_check_title_ajax(title, id);
    });

});
