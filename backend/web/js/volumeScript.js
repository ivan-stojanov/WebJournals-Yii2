$(".optionvalue-img").on("filecleared", function(event) {
    var regexID = /^(.+?)([-\d-]{1,})(.+)$/i;
    var id = event.target.id;
    var matches = id.match(regexID);
    if (matches && matches.length === 4) {
        var identifiers = matches[2].split("-");
        $("#optionvalue-" + identifiers[1] + "-deleteimg").val("1");
    }
});

/*
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
*/

$(".form-options-body").tableDnD({
    onDragClass: "myDragClass",
    onDrop: function (table, row) {
        var sortedEntityIds = [];
        var rows = table.tBodies[0].rows;

        for (var i = 0; i < rows.length; i++) {
        	sortedEntityIds.push(rows[i].id);
        }
    },
    onDragStart: function (table, row) {

    }
});