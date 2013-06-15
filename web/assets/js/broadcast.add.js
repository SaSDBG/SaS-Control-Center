$(document).ready(function(){
    
    var metrics = [
        ["#pupilFull_firstName", 'presence', 'Bitte Vorname angeben.'],
        ["#pupilFull_lastName", 'presence', 'Bitte Nachname angeben.'],
        ["#pupilFull_rawClass", 'presence', 'Bitte Klasse in richtigem Format angeben.'],
        ["#pupilFull_companyRaw", 'integer', 'Bitte ID angeben.'],
    ];

    var options = {
        disableSubmitBtn: false
    };


    
    CKEDITOR.replace('broadcast[content]', ckeditorOptionsLarge);
        
    /*$('#broadcast-add').nod(metrics, options);*/
});
