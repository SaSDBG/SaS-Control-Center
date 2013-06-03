$(document).ready(function() {
    $('#sf').typeahead([
        {
            name: 'betriebe',
            prefetch: 'api/companies',
            remote: 'api/companies?q=%QUERY',
            template: [
                '<div class="search-suggestion">',
                '<p><span class="search-name">{{name}}</span> <span class="badge">{{id}}</span> <span class="badge badge-info">{{category}}</span></p>',
                '<div class="search-description">{{{description}}}</div>',
                '<div class="search-chiefs">{{{chiefs}}}</div>',
                '<p class="search-category">Betrieb</p>',
                '</div>'
            ].join(''),
            engine: Hogan,
            header: '<h3 class="search-group">Betriebe</h3>'
        },
        {
            name: 'personen',
            prefetch: 'api/persons',
            remote: 'api/persons?q=%QUERY',
            template: [
                '<div class="search-suggestion">',
                '<p><span class="search-name">{{name}}</span> <span class="badge">{{class}}</span></p>',
                '<p class="search-category">Person</p>',
                '</div>'
            ].join(''),
            engine: Hogan,
            header: '<h3 class="search-group">Personen</h3>'
        }
    ]).on('typeahead:selected', function($e) {
        var args = [].slice.call(arguments, 1);
        var type = $e.type;
        var spinner = '<div><i class="icon-spinner icon-spin icon-2x"></i></div>';

        var ajaxUrl = "";

        switch (args[0].type)
        {
            case "company":
                ajaxUrl = urlCompanyDetail.replace("IDS", args[0].id);
                break;
            case "pupil":
                ajaxUrl = urlPupilDetail.replace("IDS", args[0].id);
                break;
        }

        if (ajaxUrl != "")
        {
            $('.search-results').html(spinner);
            
            $.get(ajaxUrl, function(data) {
                $('.search-results').html(data);
                $('#search-nw').removeClass('hidden').attr('href', ajaxUrl.replace('/raw', ''));
            });
        }
    });
});
