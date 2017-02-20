$(".serach-section-btn-group > .serach-section-btn").click(function() {
	$('.serach-section-btn').removeClass('active');
    $(this).addClass("active");
});

$('.serach-section-letters > .letter-serach').on('click', function(event) {	
	$('.serach-section-letters > .letter-serach').removeClass('letter-serach-active');
    $(this).addClass("letter-serach-active");
	
    var selected_letter = "All";
    if ($(this).attr('data-letter')) {
    	selected_letter = $(this).data('letter');
	}
    
    var selected_serach_btn = "volume";
    if($(".serach-section-btn-group > .serach-section-btn.active").length > 0){
    	if($($(".serach-section-btn-group > .serach-section-btn.active")[0]).val() != null &&
    	   $($(".serach-section-btn-group > .serach-section-btn.active")[0]).val() != ""){
    		selected_serach_btn = $($(".serach-section-btn-group > .serach-section-btn.active")[0]).val();
    	}
    }
    
    var selected_serach_text = "";
    if($(".serach-section-text #serach-term").length > 0){
    	if($($(".serach-section-text #serach-term")[0]).val() != null &&
    	   $($(".serach-section-text #serach-term")[0]).val() != ""){
    		selected_serach_btn = $($(".serach-section-text #serach-term")[0]).val();
    	}
    }    

	console.log(selected_letter);
	console.log(selected_serach_btn);
	console.log(selected_serach_text);	
	
/*	
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
*/
});