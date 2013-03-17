$(document).ready(function() {
    var metrics = [
        ["#pupilFull_firstName", 'presence', 'Bitte Vorname angeben.'],
        ["#pupilFull_lastName", 'presence', 'Bitte Nachname angeben.'],
        ["#pupilFull_rawClass", 'presence', 'Bitte Klasse in richtigem Format angeben.'],
        ["#pupilFull_firstWishRaw", 'integer', 'Bitte ID angeben.'],
        ["#pupilFull_firstWishRaw", 'presence', 'Bitte Wunsch angeben.'],
        ["#pupilFull_secondWishRaw", 'integer', 'Bitte ID angeben.'],
        ["#pupilFull_thirdWishRaw", 'integer', 'Bitte ID angeben.'],
        ["#pupilFull_pupilLinkRaw", 'integer', 'Bitte ID angeben.'],
    ];

    var options = {
        disableSubmitBtn: false
    };

    $('#pupil-add').nod(metrics, options);

    $.each(["pupilFull_firstWishRaw", "pupilFull_secondWishRaw", "pupilFull_thirdWishRaw"], function() {
        $("#" + this).typeahead([
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

    $('#pupilFull_pupilLinkRaw').typeahead([
        {
            name: 'schüler',
            prefetch: baseUrl + '/pupils/add/pupilsuggestions',
            remote: baseUrl + '/pupils/add/pupilsuggestions?q=%QUERY',
            template: [
                '<p><span class="badge">{{id}}</span> <em>{{name}}</em></p>'
            ].join(''),
            engine: Hogan
        }

    ]);
});
