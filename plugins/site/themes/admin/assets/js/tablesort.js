var fixHelperModified = function(e, tr) {
        var $originals = tr.children();
        var $helper = tr.clone();
        $helper.children().each(function(index) {
            $(this).width($originals.eq(index).width())
        });
        return $helper;
    },
    updateIndex = function(e, ui) {
        $('td.index', ui.item.parent()).each(function (i) {
            $(this).html(i + 1);
        });
    };

//noinspection JSUnresolvedFunction
$('#sortable').sortable({
    helper: fixHelperModified,
    axis: 'y',
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
    $(this).sortable();
    var tableRows = $(this).sortable('serialize');

    // POST to server using $.ajax
    $.ajax({
        cache: false,
        url: "/plugins/site/themes/admin/ajaxUpdate.php",
        type: "POST",
        data: tableRows
    });
}
