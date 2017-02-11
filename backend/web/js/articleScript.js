$('.article_attribute__file_attach').on('fileclear', function(event) {
	
	var clickedElementID = (this.id).replace("article_attribute__file_attach-", "");;
	
    $.ajax({
        type: 'POST',
        //contentType: 'application/json; charset=utf-8',
        async: true,
        url: '../asynch-remove-article-file',
        data: {
        	clickedElementID: JSON.stringify(clickedElementID),                
        },
        dataType: 'text',
        success: function (successData) {
        	if(successData != "Empty message!") {
        		alert(successData);
        	}        	
        },
        error: function (xhr, error) {
    		console.log("error");
        	console.log(xhr);
        	console.log(error);
        }
    });
});

$('#accept-article-btn').on('click', function(event) {
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

	if($("#articlereviewer-short_comment").val() == null || $("#articlereviewer-short_comment").val() == '' ||
		$("#articlereviewer-long_comment").val() == null || $("#articlereviewer-long_comment").val() == '') {
		return;
	}
	
	$.ajax({
	    type: 'POST',
	    //contentType: 'application/json; charset=utf-8',
	    async: true,
	    url: './asynch-article-status-accept',
	    data: {
	    	articleid: JSON.stringify(articleid),
	    	reviewerid: JSON.stringify(reviewerid),
	    	shortcomment: $("#articlereviewer-short_comment").val(),
	    	longcomment: $("#articlereviewer-long_comment").val(),
	    },
	    dataType: 'text',
	    success: function (successData) {
	    	if(successData != "Empty message!") {
	    		$("#article-section-alert").removeClass("hidden-div");
	    		$("#article-section-alert").addClass("alert-success");
	    		$("#article-section-alert-msg").text(successData);
	    	}
	    },
	    error: function (xhr, error) {
			$("#article-section-alert").removeClass("hidden-div");
			$("#article-section-alert").addClass("alert-danger");
			$("#article-section-alert-msg").text(error);
			console.log("error");
	    	console.log(xhr);
	    	console.log(error);
	    }
	});
});

$('#reject-article-btn').on('click', function(event) {
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

	if($("#articlereviewer-short_comment").val() == null || $("#articlereviewer-short_comment").val() == '' ||
		$("#articlereviewer-long_comment").val() == null || $("#articlereviewer-long_comment").val() == '') {
		return;
	}
	
	$.ajax({
	    type: 'POST',
	    //contentType: 'application/json; charset=utf-8',
	    async: true,
	    url: './asynch-article-status-reject',
	    data: {
	    	articleid: JSON.stringify(articleid),
	    	reviewerid: JSON.stringify(reviewerid),
	    	shortcomment: $("#articlereviewer-short_comment").val(),
	    	longcomment: $("#articlereviewer-long_comment").val(),
	    },
	    dataType: 'text',
	    success: function (successData) {
	    	if(successData != "Empty message!") {
	    		$("#article-section-alert").removeClass("hidden-div");
	    		$("#article-section-alert").addClass("alert-success");
	    		$("#article-section-alert-msg").text(successData);
	    	}
	    },
	    error: function (xhr, error) {
			$("#article-section-alert").removeClass("hidden-div");
			$("#article-section-alert").addClass("alert-danger");
			$("#article-section-alert-msg").text(error);
			console.log("error");
	    	console.log(xhr);
	    	console.log(error);
	    }
	});
});