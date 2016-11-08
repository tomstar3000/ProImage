(function(window, undefined) {

// Define global Aurigma.ImageUploader namespace
var AU = (window.Aurigma || (window.Aurigma = { __namespace: true })) &&
    (window.Aurigma.ImageUploader || (window.Aurigma.ImageUploader = { __namespace: true }));

AU.language || (AU.language = { __namespace: true });
AU.ip_language || (AU.ip_language = { __namespace: true });

// Swedish
window.sv_localization = AU.language.sv = {
    "addFilesProgressDialog": {
        "cancelButtonText": "Avbryt",
        "currentFileText": "Bearbetar fil: ”[name]”",
        "titleText": "Lägger till filer i uppladdningskön",
        "totalFilesText": "redan bearbetade filer: [count]",
        "waitText": "Vänta lite..."
    },
    "authenticationDialog": {
        "cancelButtonText": "Avbryt",
        "loginText": "Inloggning:",
        "okButtonText": "OK",
        "passwordText": "Lösenord:",
        "realmText": "Miljö:",
        "text": "Värden [name] kräver autentisering"
    },
    "contextMenu": {
        "addFilesText": "Lägg till filer...",
        "addFolderText": "Lägg till mapp...",
        "arrangeByDimensionsText": "Mått",
        "arrangeByModifiedText": "Datum ändrat",
        "arrangeByNameText": "Namn",
        "arrangeByPathText": "Sökväg",
        "arrangeBySizeText": "Storlek",
        "arrangeByText": "Sortera enligt",
        "arrangeByTypeText": "Typ",
        "checkAllText": "Kontrollera alla",
        "checkText": "Kontrollera",
        "detailsViewText": "Detaljer",
        "editText": "Redigera bild...",
        "editDescriptionText": "Redigera beskrivning...",
        "listViewText": "Lista",
        "navigateToFolderText": "Navigera till mapp",
        "openText": "Öppna",
        "pasteText": "Klistra in",
        "removeAllText": "Ta bort alla",
        "removeText": "Ta bort",
        "thumbnailsViewText": "Miniatyrer",
        "tilesViewText": "Rutor",
        "uncheckAllText": "Avmarkera alla",
        "uncheckText": "Avmarkera"
    },
    "deleteFilesDialog": {
        "message": "Är du säker på att du vill radera uppladdade objekt permanent?",
        "titleText": "Ta bort fil"
    },
    "descriptionEditor": {
        "cancelHyperlinkText": "Avbryt",
        "orEscLabelText": " (eller Esc)",
        "saveButtonText": "Spara"
    },
    "detailsViewColumns": {
        "dimensionsText": "Mått",
        "fileNameText": "Namn",
        "fileSizeText": "Storlek",
        "fileTypeText": "Typ",
        "infoText": "Info",
        "lastModifiedText": "Datum ändrat"
    },
    "folderPane": {
        "filterHintText": "Sök ",
        "headerText": "<totalLink>Filer totalt: [totalCount]</totalLink>, <allowedLink>tillåtna: [allowedCount]</allowedLink>"
    },
    "imageEditor": {
        "cancelButtonText": "Avbryt",
        "cancelCropButtonText": "Avbryt beskärning",
        "cropButtonText": "Beskär",
        "descriptionHintText": "Skriv beskrivning här...",
        "rotateButtonText": "Rotera",
        "saveButtonText": "Spara och stäng"
    },
    "messages": {
        "cmykImagesNotAllowed": "CMYK-bilder tillåts inte.",
        "deletingFilesError": "Filen ”[name]” kan inte tas bort",
        "dimensionsTooLarge": "Bilden ”[name]” är för stor för att väljas.",
        "dimensionsTooSmall": "Bilden ”[name]” är för liten för att väljas.",
        "fileNameNotAllowed": "Filen ”[name]” kan inte väljas. Den här filen har ett otillåtet namn.",
        "fileSizeTooSmall": "Filen ”[name]” kan inte väljas. Den här filstorleken är mindre än gränsen.",
        "filesNotAdded": "[count] filer lades inte till på grund av begränsningar.",
        "maxFileCountExceeded": "Filen ”[name]” kan inte väljas. Antalet filer överstiger gränsen.",
        "maxFileSizeExceeded": "Filen ”[name]” kan inte väljas. Den här filstorleken överstiger gränsen.",
        "maxTotalFileSizeExceeded": "Filen ”[name]” kan inte väljas. Total uppladdningsdatastorlek överstiger gränsen.",
        "noResponseFromServer": "Inget svar från servern.",
        "serverError": "Det har inträffat ett fel på serversidan. Om du ser detta meddelande ska du kontakta din webbmaster.",
        "serverNotFound": "Server eller proxy [name] kan inte hittas.",
        "unexpectedError": "Aurigma Upload Suite stötte på problem. Om du ser detta meddelande ska du kontakta webbmaster.",
        "uploadCancelled": "Uppladdningen avbröts.",
        "uploadCompleted": "Uppladdning klar.",
        "uploadFailed": "Uppladdning misslyckades (anslutningen avbröts)."
    },
    "paneItem": {
        "descriptionEditorIconTooltip": "Redigera beskrivning",
        "imageCroppedIconTooltip": "Bilden beskärs.",
        "imageEditorIconTooltip": "Förhandsgranska / redigera bild",
        "removalIconTooltip": "Ta bort",
        "rotationIconTooltip": "Rotera"
    },
    "statusPane": {
        "clearAllHyperlinkText": "rensa alla",
        "filesToUploadText": "Filer valda för uppladdning: [count]",
        "noFilesToUploadText": "Inga filer för uppladdning",
        "progressBarText": "Laddar upp ([percents] %)"
    },
    "treePane": {
        "titleText": "Mappar",
        "unixFileSystemRootText": "Filsystem",
        "unixHomeDirectoryText": "Startmapp"
    },
    "uploadPane": {
        "dropFilesHereMacText": "Add files here...", // TODO: Translate
        "dropFilesHereText": "Släpp filer här…"
    },
    "uploadProgressDialog": {
        "cancelUploadButtonText": "Avbryt",
        "estimationText": "Beräknad återstående tid: [time]",
        "hideButtonText": "Dölj",
        "hoursText": "timmar",
        "infoText": "Filer uppladdade: [files]/[totalFiles] ([bytes] av [totalBytes])",
        "kilobytesText": "Kb",
        "megabytesText": "Mb",
        "minutesText": "minuter",
        "preparingText": "Förbereder filer för uppladdning...",
        "reconnectionText": "Uppladdningen misslyckades. Väntar på återanslutning...",
        "secondsText": "sekunder",
        "titleText": "Laddar upp filer till server"
    },
    "cancelUploadButtonText": "Stopp",
    "loadingFolderContentText": "Läser in innehåll...",
    "uploadButtonText": "Ladda upp"
};
// Swedish
AU.ip_language.sv = {
    "commonHtml": "<p>Aurigma Upload Suite-kontrollen behövs för att du ska kunna ladda upp dina filer snabbt och enkelt. Du kan välja fler bilder samtidigt i ett lättillgängligt gränssnitt och slipper alla klumpiga inmatningsfält som öppnas med knappen <strong>Bläddra</strong>.</p>",
    "progressHtml": "<p><img src=\"{0}\" /><br />Laddar Aurigma Upload Suite...</p>",
    "beforeIE6XPSP2ProgressHtml": "<p>När du vill installera Aurigma Upload Suite väntar du tills kontrollen har lästs in och klickar på <strong>Ja</strong> när installationsdialogrutan visas.</p>",
    "beforeIE6XPSP2InstructionsHtml": "<p>När du vill installera Aurigma Upload Suite laddar du om sidan och klickar på <strong>Ja</strong> när installationsdialogrutan för kontrollen visas. Kontrollera säkerhetsinställningarna om du inte kan se installationsdialogrutan.</p>",
    "IE6XPSP2ProgressHtml": "<p>Vänta tills kontrollen har laddats.</p>",
    "IE6XPSP2InstructionsHtml": "<p>När du vill installera Aurigma Upload Suite klickar du på <strong>informationsfältet</strong> och väljer <strong>Installera ActiveX-kontroll</strong> från listrutemenyn. När sidan har laddats om klickar du på <strong>Installera</strong> när du ser installationsdialogrutan för kontrollen. Ladda om sidan och/eller kontrollera säkerhetsinställningarna om du inte kan se informationsfältet.</p>",
    "IE7ProgressHtml": "<p>Vänta tills kontrollen har laddats.</p>",
    "IE7InstructionsHtml": "<p>När du vill installera Aurigma Upload Suite klickar du på <strong>informationsfältet</strong> och väljer <strong>Installera ActiveX-kontroll</strong> eller <strong>Kör ActiveX-kontroll</strong> från listrutemenyn.</p><p>Klicka sedan antingen på <strong>Kör</strong> eller, när sidan har laddats om, på <strong>Installera</strong> när installationsdialogrutan för kontrollen visas. Ladda om sidan och/eller kontrollera säkerhetsinställningarna om du inte kan se informationsfältet.</p>",
    "IE8ProgressHtml": "<p>Vänta tills kontrollen har laddats.</p>",
    "IE8InstructionsHtml": "<p>När du vill installera Aurigma Upload Suite klickar du på <strong>informationsfältet</strong> och väljer <strong>Installera tillägget</strong> eller <strong>Kör tillägget</strong> från listrutemenyn.</p><p>Klicka sedan antingen på <strong>Kör</strong> eller, när sidan har laddats om, på <strong>Installera</strong> när installationsdialogrutan för kontrollen visas. Ladda om sidan och/eller kontrollera säkerhetsinställningarna om du inte kan se informationsfältet.</p>",
    "IE9ProgressHtml": "<p>Vänta tills kontrollen har laddats.</p>",
    "IE9InstructionsHtml": "<p>När du vill installera Aurigma Upload Suite klickar du på <strong>Tillåt</strong> eller på <strong>Installera</strong> i <strong>meddelandefältet</strong> längst ned på sidan.</p><p>När sidan har laddats om klickar du på <strong>Installera</strong> i installationsdialogrutan för kontrollen. Om du inte kan se meddelandefältet försöker du ladda om sidan och/eller kontrollerar säkerhetsinställningarna (ActiveX-kontroller måste vara aktiverade).</p>",
    "chromeProgressHtml": "<p>Aurigma Upload Suite requires Java plug-in. Please, click <strong>Run this time</strong> or <strong>Always run on this site</strong> to let Java plug-in run.</p>", // TODO: translate
    "updateInstructions": "Du måste uppdatera kontrollen för Aurigma Upload Suite. Klicka på <strong>Installera</strong> eller på <strong>Kör</strong> när installationsdialogrutan för kontrollen visas. Ladda om sidan om installationsdialogrutan inte visas.",
    "macInstallJavaHtml": "<p>Använd funktionen <a href=\"http://support.apple.com/kb/HT1338\">Programuppdatering</a> (finns på Apple-menyn) om du vill kontrollera att du har den senaste Java-versionen för din Mac.</p>",
    "installJavaHtml": "<p><a href=\"http://www.java.com/getjava/\">Hämta</a> och installera Java.</p>"
};
})(window);
