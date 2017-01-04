function userScript_clickDuplicateUser(username_user_id, email_user_id){	
	
	//alert(username_user_id);	
	//alert(email_user_id);	
	
    $.ajax({
        type: 'POST',
        //contentType: 'application/json; charset=utf-8',
        async: true,
        url: 'asynch-alert-duplicate-user',
        data: { usernameUserID: username_user_id, emailUserID: email_user_id },
        dataType: 'text',
        success: function (successData) {       	    	
        	$("#user-section-alert").removeClass("alert-warning");
        	$("#user-section-alert").removeClass("alert-danger");
        	$("#user-section-alert").removeClass("alert-success");
        	$("#user-section-alert").addClass("alert-success");
        	$("#user-section-alert-msg").text("Message successfully sent! Please check your email.");
        	//alert("success");    
        	//console.log("success");
        	//console.log(successData);
        },
        error: function (xhr, error) {
        	$("#user-section-alert").removeClass("alert-warning");
        	$("#user-section-alert").removeClass("alert-danger");
        	$("#user-section-alert").removeClass("alert-success");
        	$("#user-section-alert").addClass("alert-danger");
    		$("#user-section-alert-msg").text("Some error occured! Please check the logs.");
        	//alert("error");
        	console.log("error");
        	console.log(xhr);
        	console.log(error);
        }
    });
}