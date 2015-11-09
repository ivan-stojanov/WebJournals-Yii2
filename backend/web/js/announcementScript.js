$('[data-toggle="tooltip"]').tooltip();

function announcementScript_changeAnnouncementVisibility(htmlElementId, rowId){	
    if ($("#" + htmlElementId).is(':checked')) {
        $("#" + htmlElementId).attr('checked', 'checked');
        $.ajax({
            type: 'POST',
            //contentType: 'application/json; charset=utf-8',
            async: true,
            url: 'asynch-announcement-change-visibility',
            data: { rowId: rowId, isChecked: 1 },
            dataType: 'json',
            success: function (successData) {
            	$("#announcement-alert").removeClass("hidden-div");
            	$("#announcement-alert").removeClass("alert-danger");
            	$("#announcement-alert").addClass("alert-success");
            	$("#announcement-alert-msg").text(successData);
            },
            error: function (xhr, error) {
            	$("#announcement-alert").removeClass("hidden-div");
            	if(xhr != null && xhr.status != null && xhr.status == 200 && xhr.responseText != null){
                	$("#announcement-alert").removeClass("alert-danger");
                	$("#announcement-alert").addClass("alert-success");
                	$("#announcement-alert-msg").text(xhr.responseText);
            	} else {
            		$("#announcement-alert").removeClass("alert-success");
            		$("#announcement-alert").addClass("alert-danger");
            		$("#announcement-alert-msg").text("Some error occured. Refresh the page and try again.");
            	}
            }
        });
    } else {
    	$.ajax({
            type: 'POST',
            //contentType: 'application/json; charset=utf-8',
            async: true,
            url: 'asynch-announcement-change-visibility',
            data: { rowId: rowId, isChecked: 0 },
            dataType: 'json',
            success: function (successData) {
            	$("#announcement-alert").removeClass("hidden-div");
            	$("#announcement-alert").removeClass("alert-danger");
            	$("#announcement-alert").addClass("alert-success");
            	$("#announcement-alert-msg").text(successData);
            },
            error: function (xhr, error) {
            	$("#announcement-alert").removeClass("hidden-div");
            	if(xhr != null && xhr.status != null && xhr.status == 200 && xhr.responseText != null){
                	$("#announcement-alert").removeClass("alert-danger");
                	$("#announcement-alert").addClass("alert-success");
                	$("#announcement-alert-msg").text(xhr.responseText);
            	} else {
            		$("#announcement-alert").removeClass("alert-success");
            		$("#announcement-alert").addClass("alert-danger");
            		$("#announcement-alert-msg").text("Some error occured. Refresh the page and try again.");
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
            url: 'asynch-announcement-change-sorting',
            data: {
            	sortedEntityIds: JSON.stringify(sortedEntityIds),                
            },
            dataType: 'json',
            success: function (successData) {
            	$("#announcement-alert").removeClass("hidden-div");
            	$("#announcement-alert").removeClass("alert-danger");
            	$("#announcement-alert").addClass("alert-success");
            	$("#announcement-alert-msg").text(successData);
            },
            error: function (xhr, error) {
            	$("#announcement-alert").removeClass("hidden-div");
            	if(xhr != null && xhr.status != null && xhr.status == 200 && xhr.responseText != null){
                	$("#announcement-alert").removeClass("alert-danger");
                	$("#announcement-alert").addClass("alert-success");
                	$("#announcement-alert-msg").text(xhr.responseText);
            	} else {
            		$("#announcement-alert").removeClass("alert-success");
            		$("#announcement-alert").addClass("alert-danger");
            		$("#announcement-alert-msg").text("Some error occured. Refresh the page and try again.");
            	}
            }
        });
    },
    onDragStart: function (table, row) {

    }
});

$('.deleteAnnouncementBtn').each(function(){
    $(this).click(function(){
        if (confirm("Are you sure you want to delete this announcement?") == true) {
        	$("#announcement-alert").removeClass("hidden-div");
        	$("#announcement-alert").removeClass("alert-danger");
        	$("#announcement-alert").addClass("alert-success");
        	$("#announcement-alert-msg").text("Announcementn has been successfully deleted.");
        	return true;
        }
        return false;
    });
 });
