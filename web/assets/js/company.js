var hrefPrint = "";


$(document).ready(function() {
    $("a, button, input").tooltip({
        "placement": "top"
    });

    $(".companyDetails").click(function() {
        var id = $(this).data("company");

        if ($("#details" + id).length == 0)
        {
            $.ajax({
                url: id + "/details/modal",
                type: "GET",
                success: function(data) {
                    $("#companyInfo").append(data);
                    showModal(id);
                    return;
                }
            });
        }

        showModal(id);
    });

    $(".companyPrintFilter").click(function() {
        hrefPrint = $(this).data("href");

        $("#companyPrintFilter").modal({
            show: true
        })

    });

    $("#company-list").dataTable({
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

    $(".companyPrintFilterPrint").click(function() {
        var ids = ["#cpf-dtl", "#cpf-chiefs", "#cpf-members", "#cpf-descr"];
        var hrefAppend = "?values="
        var values = "";
        var layout = $("#layout-selection .active").data("value");

        $.each(ids, function(index, value) {
            if ($(value).prop("checked"))
            {
                values = values + $(value).data("v") + ",";
            }
        });

        if (values != "" && values != undefined)
        {
            var hrefFull = hrefPrint + hrefAppend + values + "&layout=" + layout;
        }
        else
        {
            var hrefFull = hrefPrint + "?layout=" + layout; 
        }

        window.open(hrefFull);

        $("#companyPrintFilter").modal('hide');
    });

    $(".companyToggleDelete").click(function() {
        var id = $(this).data("id");
        var marked = $(this).data("marked");

        $.ajax({
            url: id + "/delete/mark",
            type: "GET",
            success: function(data)
            {
                $(".to-delete-badge" + id).toggle('slow');
            }
        });
    });
});



function showModal(id)
{
    $("#details" + id).modal({
        show: true
    })
}
