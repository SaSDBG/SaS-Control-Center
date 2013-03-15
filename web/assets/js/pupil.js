$(document).ready(function() {
    var metrics = [
        ["#pupilFull_firstName", 'presence', 'Bitte Vorname angeben.'],
        ["#pupilFull_lastName", 'presence', 'Bitte Nachname angeben.'],
        ["#pupilFull_rawClass", 'presence', 'Bitte Klasse in richtigem Format angeben.'],
        ["#pupilFull_firstWish", 'integer', 'Bitte ID angeben.'],
        ["#pupilFull_secondWish", 'integer', 'Bitte ID angeben.'],
        ["#pupilFull_thirdWish", 'integer', 'Bitte ID angeben.']
    ];

    var options = {
    };

    $('#pupil-add').nod(metrics, options);

    $.each(["pupilFull_firstWish", "pupilFull_secondWish", "pupilFull_thirdWish"], function() {
        $("#" + this).typeahead([
            {
                name: 'betriebe',
                prefetch: 'add/companysuggestions',
                remote: 'add/companysuggestions?q=%QUERY',
                template: [
                    '<p><span class="badge">{{id}}</span> <em>{{name}}</em></p>'
                ].join(''),
                engine: Hogan
            }
        ]);
    });
});
