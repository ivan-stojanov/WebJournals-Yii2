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