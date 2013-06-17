$(document).ready(function() {
    $("a, button, i").tooltip({"placement": "bottom"});

    $(".broadcastToggleVisible").click(function() {
        var toggleUrl = $(this).data("url");
        var el = $(this);
        $.ajax({
            url: toggleUrl,
            type: "GET",
            success: function(data)
            {
                if (data === "0")
                {
                    $(el).removeClass("icon-eye-open");
                    $(el).addClass("icon-eye-close");
                }
                else
                {
                    $(el).removeClass("icon-eye-close");
                    $(el).addClass("icon-eye-open");
                }
            }
        });
    });
    
    $(".broadcastToggleActive").click(function() {
        var toggleUrl = $(this).data("url");
        var el = $(this);
        $.ajax({
            url: toggleUrl,
            type: "GET",
            success: function(data)
            {
                if (data === "0")
                {
                    $(el).removeClass("icon-ok");
                    $(el).addClass("icon-remove");
                }
                else
                {
                    $(el).removeClass("icon-remove");
                    $(el).addClass("icon-ok");
                }
            }
        });
    });
});