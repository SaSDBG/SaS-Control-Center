$(document).ready(function() {
    var metrics = [
        ["#pupilFull_firstName", 'presence', 'Bitte Vorname angeben.'],
        ["#pupilFull_lastName", 'presence', 'Bitte Nachname angeben.'],
        ["#pupilFull_rawClass", 'presence', 'Bitte Klasse in richtigem Format angeben.'],
        ["#pupilFull_companyRaw", 'integer', 'Bitte ID angeben.'],
    ];

    var options = {
        disableSubmitBtn: false
    };

    $('#pupil-add').nod(metrics, options);

    $("#pupilFull_companyRaw").typeahead([
        {
            name: 'betriebe',
            prefetch: baseUrl + '/pupils/add/companysuggestions',
            remote: baseUrl + '/pupils/add/companysuggestions?q=%QUERY',
            template: [
                '<p><span class="badge">{{id}}</span> <em>{{name}}</em></p>'
            ].join(''),
            engine: Hogan
        }
    ]);
});
