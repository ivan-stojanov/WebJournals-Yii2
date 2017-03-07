$('#create-review-btn').on('click', function(event) {
	var articleid = 0;
	var reviewerid = 0;
	if ($(this).attr('data-articleid')) {
		articleid = $(this).data('articleid');
	} else {
		alert("An error occured while gettig the value for articleid");
		return;
	}
	if ($(this).attr('data-reviewerid')) {
		reviewerid = $(this).data('reviewerid');
	} else {
		alert("An error occured while gettig the value for reviewerid");
		return;
	}	

    $.ajax({
        type: 'POST',
        //contentType: 'application/json; charset=utf-8',
        async: true,
        url: './asynch-create-article-review',
        data: {
        	articleid: JSON.stringify(articleid),
        	reviewerid: JSON.stringify(reviewerid),
        	shortcomment: $("#articlereviewer-short_comment").val(),
        	longcomment: $("#articlereviewer-long_comment").val(),
        },
        dataType: 'text',
        success: function (successData) {
        	if(successData != "Empty message!") {
        		$("#articlereview-section-alert").removeClass("hidden-div");
        		$("#articlereview-section-alert").addClass("alert-success");
        		$("#articlereview-section-alert-msg").text(successData);
        		$("#myreview-section-container").addClass("hidden");
        	}
        },
        error: function (xhr, error) {
    		$("#articlereview-section-alert").removeClass("hidden-div");
    		$("#articlereview-section-alert").addClass("alert-danger");
    		$("#articlereview-section-alert-msg").text(error);
    		console.log("error");
        	console.log(xhr);
        	console.log(error);
        }
    });
});

$('#update-review-btn').on('click', function(event) {
	var articleid = 0;
	var reviewerid = 0;
	if ($(this).attr('data-articleid')) {
		articleid = $(this).data('articleid');
	} else {
		alert("An error occured while gettig the value for articleid");
		return;
	}
	if ($(this).attr('data-reviewerid')) {
		reviewerid = $(this).data('reviewerid');
	} else {
		alert("An error occured while gettig the value for reviewerid");
		return;
	}	

    $.ajax({
        type: 'POST',
        //contentType: 'application/json; charset=utf-8',
        async: true,
        url: './asynch-update-article-review',
        data: {
        	articleid: JSON.stringify(articleid),
        	reviewerid: JSON.stringify(reviewerid),
        	shortcomment: $("#articlereviewer-short_comment").val(),
        	longcomment: $("#articlereviewer-long_comment").val(),
        },
        dataType: 'text',
        success: function (successData) {
        	if(successData != "Empty message!") {
        		$("#articlereview-section-alert").removeClass("hidden-div");
        		$("#articlereview-section-alert").addClass("alert-success");
        		$("#articlereview-section-alert-msg").text(successData);        		
        		$("#myreview-section-container").addClass("hidden");
        	}        	
        },
        error: function (xhr, error) {
    		$("#articlereview-section-alert").removeClass("hidden-div");
    		$("#articlereview-section-alert").addClass("alert-danger");
    		$("#articlereview-section-alert-msg").text(error);
    		console.log("error");
        	console.log(xhr);
        	console.log(error);
        }
    });
});


$('.class-post-reviewresponse-btn').on('click', function(event) {
	var articleid = 0;
	var reviewerid = 0;
	var responsecreatorid = 0;
	var section_index = -1;
	if ($(this).attr('data-articleid')) {
		articleid = $(this).data('articleid');
	} else {
		alert("An error occured while gettig the value for articleid");
		return;
	}
	if ($(this).attr('data-reviewerid')) {
		reviewerid = $(this).data('reviewerid');
	} else {
		alert("An error occured while gettig the value for reviewerid");
		return;
	}
	if ($(this).attr('data-responsecreatorid')) {
		responsecreatorid = $(this).data('responsecreatorid');
	} else {
		alert("An error occured while gettig the value for responsecreatorid");
		return;
	}
	if ($(this).attr('data-index')) {
		section_index = $(this).data('index');
	} else {
		alert("An error occured while gettig the value for section_index");
		return;
	}
	if(section_index == -1){
		alert("An error occured while gettig the value for section_index");
		return;
	}

	var css_id_textfield = "#reviewresponse_section" + section_index + " #articlereviewresponse-long_comment"
	if($(css_id_textfield).val() == null || $(css_id_textfield).val() == '') {
			alert("You have to enter comment, in order to post it to the review!");
			return;
	}
	
	$.ajax({
	    type: 'POST',
	    //contentType: 'application/json; charset=utf-8',
	    async: true,
	    url: './asynch-article-review-response-post',
	    data: {
	    	articleid: JSON.stringify(articleid),
	    	reviewerid: JSON.stringify(reviewerid),
	    	responsecreatorid: JSON.stringify(responsecreatorid),
	    	longcomment: $(css_id_textfield).val(),
	    },
	    dataType: 'text',
	    success: function (successData) {
	    	if(successData != "Empty message!") {
	    		$("#article-reviewresponse-section-alert"+section_index).removeClass("hidden-div");
	    		$("#article-reviewresponse-section-alert"+section_index).addClass("alert-success");
	    		$("#article-reviewresponse-section-alert-msg"+section_index).text(successData);	    		
	    	}
	    },
	    error: function (xhr, error) {
			$("#article-reviewresponse-section-alert"+section_index).removeClass("hidden-div");
			$("#article-reviewresponse-section-alert"+section_index).addClass("alert-danger");
			$("#article-reviewresponse-section-alert-msg"+section_index).text(error);
			console.log("error");
	    	console.log(xhr);
	    	console.log(error);
	    }
	});
});
