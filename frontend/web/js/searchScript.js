$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
    $('[data-toggle="tooltip"]').tooltip("show");
    $('[data-toggle="tooltip"]').find(".tooltip.fade.top").removeClass("in");
});

$(".serach-section-btn-group > .serach-section-btn").on('click', function(event) {	
	$('.serach-section-btn').removeClass('active');
    $(this).addClass("active");
});

$(".serach-section-text #btn-serach-section").on('click', function(event) {	
		var selected_letter = "All";
		if($(".serach-section-letters > .letter-serach.letter-serach-active").length > 0){
			if ($(".serach-section-letters > .letter-serach.letter-serach-active").attr('data-letter')) {
				selected_letter = $(".serach-section-letters > .letter-serach.letter-serach-active").data('letter');
	    	}
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
	    		selected_serach_text = $($(".serach-section-text #serach-term")[0]).val();
	    	}
	    }
	    
	    var search_term = "?type=" + selected_serach_btn + "&letter=" + selected_letter + "&text=" + selected_serach_text;
	    window.location.replace($('#current_base_url').text() + search_term);	    
	    
		/*console.log($('#current_base_url').text());  
		console.log(selected_letter);
		console.log(selected_serach_btn);
		console.log(selected_serach_text);*/
});

$('.serach-section-letters > .letter-serach').on('click', function(event) {	
	$('.serach-section-letters > .letter-serach').removeClass('letter-serach-active');
    $(this).addClass("letter-serach-active");
	
    /*if ($(this).attr('data-letter')) {
		selected_letter = $(this).data('letter');
	}*/
 
/*	
	$.ajax({
	    type: 'POST',
	    //contentType: 'application/json; charset=utf-8',
	    async: true,
	    url: './asynch-search-criteria',
	    data: {
	    	search_by_letter: selected_letter,
	    	search_by_entities: selected_serach_btn,
	    	search_by_text: selected_serach_text,
	    },
	    dataType: 'text',
	    success: function (successData) {
	    	if(successData != "Empty message!") {
	    		$("#article-reviewresponse-section-alert"+section_index).removeClass("hidden-div");
	    		$("#article-reviewresponse-section-alert"+section_index).addClass("alert-success");
	    		$("#article-reviewresponse-section-alert-msg"+section_index).text(successData);   		
	    	}
	    	console.log(successData);
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