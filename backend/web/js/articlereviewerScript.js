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
	//alert(articleid + reviewerid);

    $.ajax({
        type: 'POST',
        //contentType: 'application/json; charset=utf-8',
        async: true,
        url: '../asynch-create-article-review',
        data: {
        	articleid: JSON.stringify(articleid),
        	reviewerid: JSON.stringify(reviewerid),
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
	//alert(articleid + reviewerid);

    $.ajax({
        type: 'POST',
        //contentType: 'application/json; charset=utf-8',
        async: true,
        url: '../asynch-update-article-review',
        data: {
        	articleid: JSON.stringify(articleid),
        	reviewerid: JSON.stringify(reviewerid),
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