jQuery(document).ready(function($) {
	$('.error').hide();
	$('#bpsp_post_form').on('submit', function() {

		if ($("input#bpsp_quiz").val() === $("input#bpsp_quiz_hidden").val()) {
			return true;
		} else {
			$("label#quiz_error").show();  
			$("input#bpsp_quiz").focus();
			return false;
		}
	});
});
