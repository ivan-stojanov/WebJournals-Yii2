function userScript_clickDuplicateUser(username_user_id, email_user_id){	
	
	alert(username_user_id);	
	alert(email_user_id);	
	
    $.ajax({
        type: 'POST',
        //contentType: 'application/json; charset=utf-8',
        async: true,
        url: 'asynch-alert-duplicate-user',
        data: { usernameUserID: username_user_id, emailUserID: email_user_id },
        dataType: 'text',
        success: function (successData) {
        	alert("success");
        	console.log("success");
        	console.log(successData);
        	/*$("#announcement-alert").removeClass("hidden-div");
        	$("#announcement-alert").removeClass("alert-danger");
        	$("#announcement-alert").addClass("alert-success");
        	$("#announcement-alert-msg").text(successData);*/
        },
        error: function (xhr, error) {
        	/*$("#announcement-alert").removeClass("hidden-div");            	
        	$("#announcement-alert").removeClass("alert-success");
    		$("#announcement-alert").addClass("alert-danger");
    		$("#announcement-alert-msg").text("Some error occured. Refresh the page and try again.");
    		*/
        	alert("error");
        	console.log("error");
        	console.log(xhr);
        	console.log(error);
        }
    });
}