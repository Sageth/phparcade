const fixHelperModified = function (e, tr) {
        var $originals = tr.children();
        // noinspection Annotator
        var $helper = tr.clone();
        // noinspection Annotator
        $helper.children().each(function (index) {
            // noinspection Annotator
            // noinspection Annotator
            $(this).width($originals.eq(index).width());
        });
        return $helper;
    },
    updateIndex = function (e, ui) {
        // noinspection Annotator
        // noinspection Annotator
        $('td.index', ui.item.parent()).each(function (i) {
            // noinspection Annotator
            // noinspection Annotator
            $(this).html(i + 1);
        });
    };

//noinspection JSUnresolvedFunction
$('#sortable').sortable({
    helper: fixHelperModified,
    axis: "y",
    opacity: 0.5,
    crossDomain: false,
    cursor: "move",
    stop: updateIndex,
    update: saveRows
});

function saveRows(){
    /*var data = $('#sortable tr').map(function() { return {
        id: $(this).attr("rowsort") };
    });*/
    // noinspection Annotator
    // noinspection Annotator
    $(this).sortable();
    // noinspection Annotator
    // noinspection Annotator
    const tableRows = $(this).sortable('serialize');

    // POST to server using $.ajax
    // noinspection Annotator
    // noinspection Annotator
    $.ajax({
        cache: false,
        url: "/Zdmin/ajaxUpdate.php",
        type: "POST",
        data: tableRows
    });
}
