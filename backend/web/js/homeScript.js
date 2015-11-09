function homeScript_changeSectionVisibility(htmlElementId, rowId){	
    if ($("#" + htmlElementId).is(':checked')) {
        $("#" + htmlElementId).attr('checked', 'checked');
        $.ajax({
            type: 'POST',
            //contentType: 'application/json; charset=utf-8',            
            async: true,
            url: 'asynch-home-section-change-visibility',
            data: { rowId: rowId, isChecked: 1 },
            dataType: 'json',
            success: function (successData) {
            	$("#homepage-section-alert").removeClass("hidden-div");
            	$("#homepage-section-alert").removeClass("alert-danger");
            	$("#homepage-section-alert").addClass("alert-success");
            	$("#homepage-section-alert-msg").text(successData);
            },
            error: function (xhr, error) {
            	$("#homepage-section-alert").removeClass("hidden-div");
            	if(xhr != null && xhr.status != null && xhr.status == 200 && xhr.responseText != null){
                	$("#homepage-section-alert").removeClass("alert-danger");
                	$("#homepage-section-alert").addClass("alert-success");
                	$("#homepage-section-alert-msg").text(xhr.responseText);
            	} else {
            		$("#homepage-section-alert").removeClass("alert-success");
            		$("#homepage-section-alert").addClass("alert-danger");
            		$("#homepage-section-alert-msg").text("Some error occured. Refresh the page and try again.");
            	}
            }
        });
    } else {
    	$.ajax({
            type: 'POST',
            //contentType: 'application/json; charset=utf-8',            
            async: true,
            url: 'asynch-home-section-change-visibility',
            data: { rowId: rowId, isChecked: 0 },
            dataType: 'json',
            success: function (successData) {
            	$("#homepage-section-alert").removeClass("hidden-div");
            	$("#homepage-section-alert").removeClass("alert-danger");
            	$("#homepage-section-alert").addClass("alert-success");
            	$("#homepage-section-alert-msg").text(successData);
            },
            error: function (xhr, error) {
            	$("#homepage-section-alert").removeClass("hidden-div");
            	if(xhr != null && xhr.status != null && xhr.status == 200 && xhr.responseText != null){
                	$("#homepage-section-alert").removeClass("alert-danger");
                	$("#homepage-section-alert").addClass("alert-success");
                	$("#homepage-section-alert-msg").text(xhr.responseText);
            	} else {
            		$("#homepage-section-alert").removeClass("alert-success");
            		$("#homepage-section-alert").addClass("alert-danger");
            		$("#homepage-section-alert-msg").text("Some error occured. Refresh the page and try again.");
            	}
            }
        });
    }    
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
            dataType: 'json',
            success: function (successData) {
            	$("#homepage-section-alert").removeClass("hidden-div");
            	$("#homepage-section-alert").removeClass("alert-danger");
            	$("#homepage-section-alert").addClass("alert-success");
            	$("#homepage-section-alert-msg").text(successData);
            },
            error: function (xhr, error) {
            	$("#homepage-section-alert").removeClass("hidden-div");
            	if(xhr != null && xhr.status != null && xhr.status == 200 && xhr.responseText != null){
                	$("#homepage-section-alert").removeClass("alert-danger");
                	$("#homepage-section-alert").addClass("alert-success");
                	$("#homepage-section-alert-msg").text(xhr.responseText);
            	} else {
            		$("#homepage-section-alert").removeClass("alert-success");
            		$("#homepage-section-alert").addClass("alert-danger");
            		$("#homepage-section-alert-msg").text("Some error occured. Refresh the page and try again.");
            	}
            }
        });
    },
    onDragStart: function (table, row) {

    }
});
