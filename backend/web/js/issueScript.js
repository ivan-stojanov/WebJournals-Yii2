$(".optionvalue-img").on("filecleared", function(event) {
    var regexID = /^(.+?)([-\d-]{1,})(.+)$/i;
    var id = event.target.id;
    var matches = id.match(regexID);
    if (matches && matches.length === 4) {
        var identifiers = matches[2].split("-");
        $("#optionvalue-" + identifiers[1] + "-deleteimg").val("1");
    }
});

var fixHelperSortable = function(e, ui) {
    ui.children().each(function() {
        $(this).width($(this).width());
    });
    return ui;
};

$(".form-options-body").sortable({
    items: "tr",
    cursor: "move",
    opacity: 0.6,
    axis: "y",
    handle: ".sortable-handle",
    helper: fixHelperSortable,
    update: function(ev){
        $(".dynamicform_wrapper").yiiDynamicForm("updateContainer");
    }
}).disableSelection();

$("#issue-is_special_issue").change(function() {
    if(this.checked) {    	
    	$(".special-issue-div").removeClass("hidden-div");
    } else {
    	$(".special-issue-div").addClass("hidden-div");
    }
});

$('#is_special_issue').on('switchChange.bootstrapSwitch', function(event, state) {
	//console.log(this); // DOM element
	//console.log(event); // jQuery event
	//console.log(state); // true | false
	
//	var switchInput = $(this);	
	var new_state = state;
	if(new_state === true) {
		$('#special_title_container').show( "slow" );
		$('#special_editor_container').show( "slow" );			
	} else {
		$('#special_title_container').hide( "slow" );
		$('#special_editor_container').hide( "slow" );			
	}		
});
			
