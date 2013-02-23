var hrefPrint = "";


$(document).ready(function(){
    $("a, button").tooltip( {
        "placement" : "bottom"
    });
           
    $("#company-list").dataTable ({
        "sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
        "sPaginationType": "bootstrap",
        "bStateSave": true,
        "oLanguage": {
            "sProcessing":   "Bitte warten...",
            "sLengthMenu":   "_MENU_ Einträge anzeigen",
            "sZeroRecords":  "Keine Einträge vorhanden.",
            "sInfo":         "_START_ bis _END_ von _TOTAL_ Einträgen",
            "sInfoEmpty":    "0 bis 0 von 0 Einträgen",
            "sInfoFiltered": "(gefiltert von _MAX_  Einträgen)",
            "sInfoPostFix":  "",
            "sSearch":       "Suchen",
            "sUrl":          "",
            "oPaginate": {
                "sFirst":    "Erster",
                "sPrevious": "Zurück",
                "sNext":     "Nächster",
                "sLast":     "Letzter"
            }
        }
    }); 
            
    $.extend( $.fn.dataTableExt.oStdClasses, {
        "sWrapper": "dataTables_wrapper form-inline"
    });
            
    $(".companyDetails").click(function(){
        var id = $(this).data("company");
                
        if($("#details" + id).length == 0)
        {
            $.ajax({
                url: "details/" + id,
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
    
    $(".companyPrintFilter").click(function(){
        hrefPrint = $(this).data("href");
           
        $("#companyPrintFilter").modal({
            show: true
        })
        
    });
    
    $(".companyPrintFilterPrint").click(function(){
        var ids = ["#cpf-category", "#cpf-chiefs", "#cpf-classes", "#cpf-capacity"];
        var hrefAppend = "?values="
        var values = "";
        
        $.each(ids, function(index, value) {
            if($(value).prop("checked"))
            {
                values = values + $(value).data("v") + ",";
            }
        });
       
        if(values != "" && values != undefined)
        {
            var hrefFull = hrefPrint + hrefAppend + values;
        }
        else
        {
            var hrefFull = hrefPrint;
        }
        
        window.open(hrefFull);
        
        $("#companyPrintFilter").modal('hide');
    });
});
        
function showModal(id)
{
    $("#details" + id).modal({
        show: true
    })
}

