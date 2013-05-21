$(document).ready(function() {
    $("a, button").tooltip({
        "placement": "bottom"
    });
    
     $(".pupilDetails").click(function() {
        var id = $(this).data("pupil");

        if ($("#details" + id).length === 0)
        {
            $.ajax({
                url: id + "/details/modal",
                type: "GET",
                success: function(data) {
                    $("#pupilInfo").append(data);
                    showModal(id);
                    return;
                }
            });
        }

        showModal(id);
    });

    $("#pupil-list").dataTable({
        "sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
        "sPaginationType": "bootstrap",
        "bStateSave": true,
        "oLanguage": {
            "sProcessing": "Bitte warten...",
            "sLengthMenu": "_MENU_ Einträge anzeigen",
            "sZeroRecords": "Keine Einträge vorhanden.",
            "sInfo": "_START_ bis _END_ von _TOTAL_ Einträgen",
            "sInfoEmpty": "0 bis 0 von 0 Einträgen",
            "sInfoFiltered": "(gefiltert von _MAX_  Einträgen)",
            "sInfoPostFix": "",
            "sSearch": "Suchen",
            "sUrl": "",
            "oPaginate": {
                "sFirst": "Erster",
                "sPrevious": "Zurück",
                "sNext": "Nächster",
                "sLast": "Letzter"
            }
        }
    });

    $.extend($.fn.dataTableExt.oStdClasses, {
        "sWrapper": "dataTables_wrapper form-inline"
    });
});

function showModal(id)
{
    $("#details" + id).modal({
        show: true
    })
}
