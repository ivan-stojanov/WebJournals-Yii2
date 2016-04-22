$(".optionvalue-img").on("filecleared", function(event) {
    var regexID = /^(.+?)([-\d-]{1,})(.+)$/i;
    var id = event.target.id;
    var matches = id.match(regexID);
    if (matches && matches.length === 4) {
        var identifiers = matches[2].split("-");
        $("#optionvalue-" + identifiers[1] + "-deleteimg").val("1");
    }
});

$("#issue-is_special_issue").change(function() {
    if(this.checked) {    	
    	$(".special-issue-div").removeClass("hidden-div");
    } else {
    	$(".special-issue-div").addClass("hidden-div");
    }
});