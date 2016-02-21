function homeScript_changeSectionVisibility(htmlElementId, rowId){	
	var isChecked = 0;
    if ($("#" + htmlElementId).is(':checked')) {
        $("#" + htmlElementId).attr('checked', 'checked');
        isChecked = 1;
    }
    $.ajax({
        type: 'POST',
        //contentType: 'application/json; charset=utf-8',            
        async: true,
        url: 'asynch-home-section-change-visibility',
        data: { rowId: rowId, isChecked: isChecked },
        dataType: 'text',
        success: function (successData) {
        	$("#homepage-section-alert").removeClass("hidden-div");
        	$("#homepage-section-alert").removeClass("alert-danger");
        	$("#homepage-section-alert").addClass("alert-success");
        	$("#homepage-section-alert-msg").text(successData);
        },
        error: function (xhr, error) {
        	$("#homepage-section-alert").removeClass("hidden-div");
        	$("#homepage-section-alert").removeClass("alert-success");
    		$("#homepage-section-alert").addClass("alert-danger");
    		$("#homepage-section-alert-msg").text("Some error occured. Refresh the page and try again.");
    		/*console.log("error");
        	console.log(xhr);
        	console.log(error);*/
        }
    });
}

$("#sortTable").tableDnD({
    onDragClass: "myDragClass",
    onDrop: function (table, row) {
        var sortedEntityIds = [];
        var rows = table.tBodies[0].rows;

        for (var i = 0; i < rows.length; i++) {
        	sortedEntityIds.push(rows[i].id);
        }

        $.ajax({
            type: 'POST',
            //contentType: 'application/json; charset=utf-8',
            async: true,
            url: 'asynch-home-section-change-sorting',
            data: {
            	sortedEntityIds: JSON.stringify(sortedEntityIds),                
            },
            dataType: 'text',
            success: function (successData) {
            	$("#homepage-section-alert").removeClass("hidden-div");
            	$("#homepage-section-alert").removeClass("alert-danger");
            	$("#homepage-section-alert").addClass("alert-success");
            	$("#homepage-section-alert-msg").text(successData);
            },
            error: function (xhr, error) {
            	$("#homepage-section-alert").removeClass("hidden-div");
            	$("#homepage-section-alert").removeClass("alert-success");
        		$("#homepage-section-alert").addClass("alert-danger");
        		$("#homepage-section-alert-msg").text("Some error occured. Refresh the page and try again.");
        		/*console.log("error");
            	console.log(xhr);
            	console.log(error);*/
            }
        });
    },
    onDragStart: function (table, row) {

    }
});
