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