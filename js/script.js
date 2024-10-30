function ProcessFormAjax() {

	var errorNotice = jQuery('#error'),
		successNotice = jQuery('#success')
		refresher = jQuery('#refresher')
		submit = jQuery('#submit');
	
	var theLanguage = jQuery('html').attr('lang');
	var btnMsg = 'Update';
	

	jQuery("label#quiz_error").hide();
	if (jQuery("input#bpsp_quiz").val() !== jQuery("input#bpsp_quiz_hidden").val()) {
		jQuery("label#quiz_error").show();
		jQuery("input#bpsp_quiz").focus();
		return false;
	}

	var ed = tinyMCE.get('bpspsitepostcontent');

	ed.setProgressState(1);
	tinyMCE.get('bpspsitepostcontent').save();

	var newPostForm = jQuery(this).serialize();

	
	jQuery('#loading').show;
	jQuery.ajax({
		type:"POST",
		url: jQuery(this).attr('action'),
		data: newPostForm,
		success:function(response){
			ed.setProgressState(0);
			jQuery('#loading').hide;
            if(response == "success") {
				successNotice.show();
				refresher.show();
				submit.html(btnMsg);
			} else {
				errorNotice.show();
			}
		}
	});

	return false;
}
