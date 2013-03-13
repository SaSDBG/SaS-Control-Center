$(document).ready(function() {
    $('#sf').typeahead([
        {
            name: 'betriebe',
            prefetch: 'api/companies',
            remote: 'api/companies?q=%QUERY',
            limit: 10,
            template: [
                '<div class="search-suggestion">',
                '<p><span class="search-name">{{name}}</span> <span class="badge">{{id}}</span> <span class="badge badge-info">{{category}}</span></p>',
                '<div class="search-description">{{{description}}}</div>',
                '<p class="search-chiefs">{{{chiefs}}}</p>',
                '<p class="search-category">Betrieb</p>',
                '</div>'
            ].join(''),
            engine: Hogan
        }
    ]);
});
