(function(window, undefined) {

// Define global Aurigma.ImageUploader namespace
var AU = (window.Aurigma || (window.Aurigma = { __namespace: true })) &&
    (window.Aurigma.ImageUploader || (window.Aurigma.ImageUploader = { __namespace: true }));

AU.language || (AU.language = { __namespace: true });
AU.ip_language || (AU.ip_language = { __namespace: true });

// Dutch
window.nl_localization = AU.language.nl = {
    "addFilesProgressDialog": {
        "cancelButtonText": "Annuleren",
        "currentFileText": "Bezig met verwerken van bestand: \"[name]\"",
        "titleText": "Bezig met toevoegen van bestanden aan uploadwachtrij",
        "totalFilesText": "reeds verwerkte bestanden: [count]",
        "waitText": "Een ogenblik geduld. Dit kan even duren..."
    },
    "authenticationDialog": {
        "cancelButtonText": "Annuleren",
        "loginText": "Aanmelding:",
        "okButtonText": "Ok",
        "passwordText": "Wachtwoord:",
        "realmText": "Realm:",
        "text": "Host [name] vereist verificatie"
    },
    "contextMenu": {
        "addFilesText": "Bestanden toevoegen...",
        "addFolderText": "Map toevoegen...",
        "arrangeByDimensionsText": "Afmetingen",
        "arrangeByModifiedText": "Wijzigingsdatum",
        "arrangeByNameText": "Naam",
        "arrangeByPathText": "Pad",
        "arrangeBySizeText": "Grootte",
        "arrangeByText": "Sorteren op",
        "arrangeByTypeText": "Type",
        "checkAllText": "Alles selecteren",
        "checkText": "Selecteren",
        "detailsViewText": "Details",
        "editText": "Afbeelding bewerken...",
        "editDescriptionText": "Omschrijving bewerken...",
        "listViewText": "Lijst",
        "navigateToFolderText": "Naar map navigeren",
        "openText": "Openen",
        "pasteText": "Plakken",
        "removeAllText": "Alles verwijderen",
        "removeText": "Verwijderen",
        "thumbnailsViewText": "Miniaturen",
        "tilesViewText": "Tegels",
        "uncheckAllText": "Alles deselecteren",
        "uncheckText": "Deselecteren"
    },
    "deleteFilesDialog": {
        "message": "Weet u zeker dat u geüploade items permanent wilt verwijderen?",
        "titleText": "Bestand verwijderen"
    },
    "descriptionEditor": {
        "cancelHyperlinkText": "Annuleren",
        "orEscLabelText": " (of Esc)",
        "saveButtonText": "Opslaan"
    },
    "detailsViewColumns": {
        "dimensionsText": "Afmetingen",
        "fileNameText": "Naam",
        "fileSizeText": "Grootte",
        "fileTypeText": "Type",
        "infoText": "Info",
        "lastModifiedText": "Wijzigingsdatum"
    },
    "folderPane": {
        "filterHintText": "Zoeken ",
        "headerText": "<totalLink>Totaal aantal bestanden: [totalCount]</totalLink>, <allowedLink>toegestaan: [allowedCount]</allowedLink>"
    },
    "imageEditor": {
        "cancelButtonText": "Annuleren",
        "cancelCropButtonText": "Bijsnijden annuleren",
        "cropButtonText": "Bijsnijden",
        "descriptionHintText": "Voer hier de omschrijving in...",
        "rotateButtonText": "Draaien",
        "saveButtonText": "Opslaan en sluiten"
    },
    "messages": {
        "cmykImagesNotAllowed": "CMYK-afbeeldingen zijn niet toegestaan.",
        "deletingFilesError": "Het bestand \"[name]\" kan niet worden verwijderd",
        "dimensionsTooLarge": "De afbeelding \"[name]\" is te groot om te worden geselecteerd.",
        "dimensionsTooSmall": "De afbeelding \"[name]\" is te klein om te worden geselecteerd.",
        "fileNameNotAllowed": "Het bestand \"[name]\" kan niet worden geselecteerd. De naam van dit bestand is niet toegestaan.",
        "fileSizeTooSmall": "Het bestand \"[name]\" kan niet worden geselecteerd. Dit bestand is te klein.",
        "filesNotAdded": "[count] bestanden zijn niet toegevoegd vanwege beperkingen.",
        "maxFileCountExceeded": "Het bestand \"[name]\" kan niet worden geselecteerd. Te veel bestanden.",
        "maxFileSizeExceeded": "Het bestand \"[name]\" kan niet worden geselecteerd. Dit bestand is te groot.",
        "maxTotalFileSizeExceeded": "Het bestand \"[name]\" kan niet worden geselecteerd. Totale grootte van uploadgegevens overschrijdt de limiet.",
        "noResponseFromServer": "Geen reactie van server.",
        "serverError": "Er is een fout bij de server opgetreden. Als dit bericht wordt weergegeven, neem dan contact op met de webmaster.",
        "serverNotFound": "Kan server of proxy [name] niet vinden.",
        "unexpectedError": "Er is een probleem opgetreden met de afbeeldingsuploader. Als dit bericht wordt weergegeven, neem dan contact op met de webmaster.",
        "uploadCancelled": "Upload is geannuleerd.",
        "uploadCompleted": "Upload voltooid.",
        "uploadFailed": "Upload mislukt (de verbinding werd onderbroken)."
    },
    "paneItem": {
        "descriptionEditorIconTooltip": "Omschrijving bewerken",
        "imageCroppedIconTooltip": "De afbeelding wordt bijgesneden.",
        "imageEditorIconTooltip": "Voorbeeld van afbeelding weergeven / Afbeelding bewerken",
        "removalIconTooltip": "Verwijderen",
        "rotationIconTooltip": "Draaien"
    },
    "statusPane": {
        "clearAllHyperlinkText": "alles wissen",
        "filesToUploadText": "Bestanden geselecteerd voor upload: [count]",
        "noFilesToUploadText": "Geen te uploaden bestanden",
        "progressBarText": "Bezig met uploaden ([percents]%)"
    },
    "treePane": {
        "titleText": "Mappen",
        "unixFileSystemRootText": "Bestandssysteem",
        "unixHomeDirectoryText": "Basismap"
    },
    "uploadPane": {
        "dropFilesHereMacText": "Add files here...", // TODO: Translate
        "dropFilesHereText": "Bestanden hier neerzetten..."
    },
    "uploadProgressDialog": {
        "cancelUploadButtonText": "Annuleren",
        "estimationText": "Geschatte resterende tijd: [time]",
        "hideButtonText": "Verbergen",
        "hoursText": "uren",
        "infoText": "Geüploade bestanden: [files]/[totalFiles] ([bytes] van [totalBytes])",
        "kilobytesText": "Kb",
        "megabytesText": "Mb",
        "minutesText": "minuten",
        "preparingText": "Bestanden voorbereiden voor uploaden...",
        "reconnectionText": "Uploaden mislukt. Wachten op opnieuw verbinding maken...",
        "secondsText": "seconden",
        "titleText": "Bezig met uploaden van bestanden naar server"
    },
    "cancelUploadButtonText": "Stoppen",
    "loadingFolderContentText": "Bezig met laden van inhoud...",
    "uploadButtonText": "Uploaden"
};
// Dutch
AU.ip_language.nl = {
    "commonHtml": "<p>De besturing van Aurigma Upload Suite is nodig om uw bestanden snel en eenvoudig te kunnen uploaden. U kunt meerdere afbeeldingen selecteren in een gebruikersvriendelijke interface in plaats van via onhandige invoervelden met een knop <strong>Bladeren</strong>.</p>",
    "progressHtml": "<p><img src=\"{0}\" /><br />Bezig met laden van Aurigma Upload Suite...</p>",
    "beforeIE6XPSP2ProgressHtml": "<p>Om de Aurigma Upload Suite te installeren, moet u wachten tot de besturing is geladen en op <strong>Ja</strong> klikken in het installatiedialoogvenster.</p>",
    "beforeIE6XPSP2InstructionsHtml": "<p>Om de Aurigma Upload Suite te installeren, moet u de pagina opnieuw laden en op <strong>Ja</strong> klikken in het installatiedialoogvenster. Controleer uw beveiligingsinstellingen als u het installatiedialoogvenster niet ziet.</p>",
    "IE6XPSP2ProgressHtml": "<p>Wacht totdat de besturing is geladen.</p>",
    "IE6XPSP2InstructionsHtml": "<p>Om de Aurigma Upload Suite te installeren, klikt u op de <strong>informatiebalk</strong> en selecteert u <strong>ActiveX-besturingselement installeren</strong> in de vervolgkeuzelijst. Nadat de pagina opnieuw is geladen, klikt u op <strong>Installeren</strong> in het installatiedialoogvenster. Als u de informatiebalk niet ziet, probeer dan de pagina opnieuw te laden en/of controleer uw beveiligingsinstellingen.</p>",
    "IE7ProgressHtml": "<p>Wacht totdat de besturing is geladen.</p>",
    "IE7InstructionsHtml": "<p>Om de Aurigma Upload Suite te installeren, moet u op de <strong>informatiebalk</strong> klikken en <strong>ActiveX-besturingselement installeren</strong> of <strong>ActiveX-besturingselement uitvoeren</strong> selecteren in het vervolgkeuzemenu.</p><p>Klik vervolgens op <strong>Uitvoeren</strong> of klik nadat de pagina opnieuw is geladen op <strong>Installeren</strong> als u het installatiedialoogvenster ziet. Als u de informatiebalk niet ziet, probeer dan de pagina opnieuw te laden en/of controleer uw beveiligingsinstellingen.</p>",
    "IE8ProgressHtml": "<p>Wacht totdat de besturing is geladen.</p>",
    "IE8InstructionsHtml": "<p>Om de Aurigma Upload Suite te installeren, moet u op de <strong>informatiebalk</strong> klikken en <strong>Deze add-on installeren</strong> of <strong>Add-on uitvoeren</strong> selecteren in het vervolgkeuzemenu.</p><p>Klik vervolgens op <strong>Uitvoeren</strong> of klik nadat de pagina opnieuw is geladen op <strong>Installeren</strong> als u het installatiedialoogvenster ziet. Als u de informatiebalk niet ziet, probeer dan de pagina opnieuw te laden en/of controleer uw beveiligingsinstellingen.</p>",
    "IE9ProgressHtml": "<p>Wacht totdat de besturing is geladen.</p>",
    "IE9InstructionsHtml": "<p>Om de Aurigma Upload Suite te installeren, klikt u op <strong>Toestaan</strong> of <strong>Installeren</strong> op de <strong>meldingsbalk</strong> onderaan de pagina.</p><p>Klik nadat de pagina opnieuw is geladen, op <strong>Installeren</strong> in het installatiedialoogvenster. Als u de meldingsbalk niet ziet, probeer dan de pagina opnieuw te laden en/of controleer uw beveiligingsinstellingen (ActiveX-besturingselementen moeten zijn ingeschakeld).</p>",
    "chromeProgressHtml": "<p>Aurigma Upload Suite requires Java plug-in. Please, click <strong>Run this time</strong> or <strong>Always run on this site</strong> to let Java plug-in run.</p>", // TODO: translate
    "updateInstructions": "U moet de besturing van de Aurigma Upload Suite bijwerken. Klik op <strong>Installeren</strong> of <strong>Uitvoeren</strong> als u het installatiedialoogvenster ziet. Als u het installatiedialoogvenster niet ziet, probeer dan de pagina opnieuw te laden.",
    "macInstallJavaHtml": "<p>Gebruik de functie <a href=\"http://support.apple.com/kb/HT1338\">Software-update</a> (beschikbaar in het Apple-menu) om te controleren of u over de laatste versie van Java voor de Mac beschikt.</p>",
    "installJavaHtml": "<p>U moet Java <a href=\"http://www.java.com/getjava/\">downloaden</a> en installeren.</p>"
};
})(window);
