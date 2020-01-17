jQuery().ready(function() {
	// validate signup form on keyup and submit
	jQuery("#sendEmail").validate({
		rules: {
			"member_id[]": "required",
			"doc_type[]": "required",
			"subject": "required",
			"body": "required"
		},
		messages: {
			"member_id[]": "Please select Members",
			"doc_type[]": "Please select Document Type",
			"subject": "Please enter subject of the Email",
			"body": "Please add mail body"
		}
	});
});