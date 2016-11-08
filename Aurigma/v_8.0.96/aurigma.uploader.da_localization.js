(function(window, undefined) {

// Define global Aurigma.ImageUploader namespace
var AU = (window.Aurigma || (window.Aurigma = { __namespace: true })) &&
    (window.Aurigma.ImageUploader || (window.Aurigma.ImageUploader = { __namespace: true }));

AU.language || (AU.language = { __namespace: true });
AU.ip_language || (AU.ip_language = { __namespace: true });

(function(window, undefined) {

// Define global Aurigma.ImageUploader namespace
var AU = (window.Aurigma || (window.Aurigma = { __namespace: true })) &&
    (window.Aurigma.ImageUploader || (window.Aurigma.ImageUploader = { __namespace: true }));

AU.language || (AU.language = { __namespace: true });
AU.ip_language || (AU.ip_language = { __namespace: true });

// Danish
window.da_localization = AU.language.da = {
    "addFilesProgressDialog": {
        "cancelButtonText": "Afbryd",
        "currentFileText": "Behandler fil: ”[name]”",
        "titleText": "Tilføjer filer til upload køen",
        "totalFilesText": "allerede behandlede filer: [count]",
        "waitText": "vent et øjeblik..."
    },
    "authenticationDialog": {
        "cancelButtonText": "Afbryd",
        "loginText": "Login:",
        "okButtonText": "OK",
        "passwordText": "Kodeord:",
        "realmText": "Miljø:",
        "text": "Værten [name] kræver autentisering"
    },
    "contextMenu": {
        "addFilesText": "Tilføj filer...",
        "addFolderText": "Tilføj folder...",
        "arrangeByDimensionsText": "Dimensioner",
        "arrangeByModifiedText": "Dato ændret",
        "arrangeByNameText": "Navn",
        "arrangeByPathText": "Sti",
        "arrangeBySizeText": "Størrelse",
        "arrangeByText": "Sorter efter",
        "arrangeByTypeText": "Type",
        "checkAllText": "Marker alle",
        "checkText": "Marker",
        "detailsViewText": "Detaljer",
        "editText": "Rediger billede...",
        "editDescriptionText": "Ændre beskrivelse...",
        "listViewText": "Liste",
        "navigateToFolderText": "Naviger til sti",
        "openText": "Åben",
        "pasteText": "Sæt ind",
        "removeAllText": "Fjern alle",
        "removeText": "Fjern",
        "thumbnailsViewText": "Thumbnails",
        "tilesViewText": "Tiles",
        "uncheckAllText": "Fjern alle markeringer",
        "uncheckText": "Fjern markering"
    },
    "deleteFilesDialog": {
        "message": "Er du sikker på du vil fjerne uploadede objekter permanent?",
        "titleText": "Slet fil"
    },
    "descriptionEditor": {
        "cancelHyperlinkText": "Afbryd",
        "orEscLabelText": " (eller Esc)",
        "saveButtonText": "Gem"
    },
    "detailsViewColumns": {
        "dimensionsText": "Dimensioner",
        "fileNameText": "Navn",
        "fileSizeText": "Størrelse",
        "fileTypeText": "Type",
        "infoText": "Info",
        "lastModifiedText": "Ændret dato"
    },
    "folderPane": {
        "filterHintText": "Søg ",
        "headerText": "<totalLink>Filer totalt: [totalCount]</totalLink>, <allowedLink>tilladt: [allowedCount]</allowedLink>"
    },
    "imageEditor": {
        "cancelButtonText": "Afbryd",
        "cancelCropButtonText": "Avbryd beskæring",
        "cropButtonText": "Beskær",
        "descriptionHintText": "Skriv beskrivelse her...",
        "rotateButtonText": "Roter",
        "saveButtonText": "Gem og luk"
    },
    "messages": {
        "cmykImagesNotAllowed": "CMYK-billeder er ikke tilladt.",
        "deletingFilesError": "Filen ”[name]” kan ikke slettes",
        "dimensionsTooLarge": "Billedet ”[name]” er for stor til at blive valgt.",
        "dimensionsTooSmall": "Billedet ”[name]” er for lille til at blive valgt.",
        "fileNameNotAllowed": "Filen ”[name]” kan ikke vælges. Filer af denne type er ikke tilladt.",
        "fileSizeTooSmall": "Filen ”[name]” kan ikke vælges. Filstørrelsen er under den tilladte grænse.",
        "filesNotAdded": "[count] filer blev ikke tilføjet på grund af begrænsninger.",
        "maxFileCountExceeded": "Filen ”[name]” kan ikke vælges. Antallet af filer overstiger det tilladte.",
        "maxFileSizeExceeded": "Filen ”[name]” kan ikke vælges. Filstørrelsen overstiger det tilladte.",
        "maxTotalFileSizeExceeded": "Filen ”[name]” kan ikke vælges. Total uploadstørrelse overstiger det tilladte.",
        "noResponseFromServer": "Intet svar fra serveren.",
        "serverError": "Der er opstået en fejl på serveren. Hvis du ser denne fejl skal du kontakte webmaster eller support.",
        "serverNotFound": "Server eller proxy [name] kan ikke kontaktes.",
        "unexpectedError": "Der er sket en uventet fejl. Hvis du ser denne fejl skal du kontakte webmaster eller support.",
        "uploadCancelled": "Upload blev afbrudt.",
        "uploadCompleted": "Upload færdig.",
        "uploadFailed": "Upload mislykkedes."
    },
    "paneItem": {
        "descriptionEditorIconTooltip": "Rediger beskrivelse",
        "imageCroppedIconTooltip": "Billedet beskæres.",
        "imageEditorIconTooltip": "Preview / rediger billede",
        "removalIconTooltip": "Fjern",
        "rotationIconTooltip": "Roter"
    },
    "statusPane": {
        "clearAllHyperlinkText": "fjern alle",
        "filesToUploadText": "Filer valgt til upload: [count]",
        "noFilesToUploadText": "Intet at uploade",
        "progressBarText": "Sender ([percents] %)"
    },
    "treePane": {
        "titleText": "Foldere",
        "unixFileSystemRootText": "Filsystem",
        "unixHomeDirectoryText": "Startfolder"
    },
    "uploadPane": {
        "dropFilesHereMacText": "Tilføj filer her...", // TODO: Translate
        "dropFilesHereText": "Drop filer her…"
    },
    "uploadProgressDialog": {
        "cancelUploadButtonText": "Afbryd",
        "estimationText": "Beregnet tid tilbage: [time]",
        "hideButtonText": "Gem",
        "hoursText": "timer",
        "infoText": "Filer sendt: [files]/[totalFiles] ([bytes] af [totalBytes])",
        "kilobytesText": "KB",
        "megabytesText": "MB",
        "minutesText": "minutter",
        "preparingText": "Förbereder filer for upload...",
        "reconnectionText": "Upload mislykkedes. Venter på tilslutning til server...",
        "secondsText": "sekunder",
        "titleText": "Sender filer til server"
    },
    "cancelUploadButtonText": "Stop",
    "loadingFolderContentText": "Indlæser indhold...",
    "uploadButtonText": "Send"
};
// Danish
AU.ip_language.da = {
    "commonHtml": "<p>Aurigma Upload Suite-kontrollen er nødvendig for at du skal kunne uploade dine filer hurtigt og enkelt. Du kan vælge flere filer samtidig i et let tilgængeligt grænsesnit i stedet for besværelige input felter som åbnes med <strong>Browse</strong> knappen.</p>",
    "progressHtml": "<p><img src=\"{0}\" /><br />Indlæser Aurigma Upload Suite...</p>",
    "beforeIE6XPSP2ProgressHtml": "<p>For at installere Aurigma Upload Suite så vent indtil kontrollen er indlæst og klik derefter på <strong>Ja</strong> når installationsvinduet bliver vist.</p>",
    "beforeIE6XPSP2InstructionsHtml": "<p>For at installere Aurigma Upload Suite skal du genindlæse siden og klikke på <strong>Ja</strong> när installationsdialogvinduet for kontrollen vises. Kontroller sikkerhedsindstillingerne hvis du ikke kan se installationsvinduet.</p>",
    "IE6XPSP2ProgressHtml": "<p>Vent indtil kontrollen er indlæst.</p>",
    "IE6XPSP2InstructionsHtml": "<p>Når du skal installere Aurigma Upload Suite klikker du på <strong>informationsfeltet</strong> og vælger <strong>Installer ActiveX-kontrol</strong> fra menuen. Når siden er indlæst igen klikker du på <strong>Installer</strong> når du ser installationsvinduet for kontrollen. Genindlæs side og/eller kontroller sikkerhedsindstillingerne hvis du ikke kan se informationsfeltet.</p>",
    "IE7ProgressHtml": "<p>Vent indtil kontrollen er indlæst.</p>",
    "IE7InstructionsHtml": "<p>Når du skal installere Aurigma Upload Suite klikker du på <strong>informationsfeltet</strong> og vælger <strong>Installer ActiveX-kontrol</strong> eller <strong>Kør ActiveX-kontroll</strong> fra informationsmenuen.</p><p>Klik derefter enten på <strong>Kør</strong> eller, når siden er genindlæst, på <strong>Installer</strong> når installationsvinduet for kontrollen vises. Genindlæs siden og/eller kontroller sikkerhedsindstillingerne hvis du ikke kan se informationsfeltet.</p>",
    "IE8ProgressHtml": "<p>Vent indtil kontrollen er indlæst.</p>",
    "IE8InstructionsHtml": "<p>Når du skal installere Aurigma Upload Suite klikker du på <strong>informationsfeltet</strong> og vælger <strong>Installer AddOn</strong> eller <strong>Kør AddOn</strong> fra informationsmenuen.</p><p>Klik derefter enten på <strong>Kør</strong> eller, når siden er genindlæst, på <strong>Installer</strong> når installationsvinduet for kontrollen vises. Genindlæs siden og/eller kontroller sikkerhedsindstillingerne hvis du ikke kan se informationsfeltet.</p>",
    "IE9ProgressHtml": "<p>Vent indtil kontrollen er indlæst.</p>",
    "IE9InstructionsHtml": "<p>Når du skal installere Aurigma Upload Suite klikker du på <strong>Tillad</strong> eller på <strong>Installer</strong> i <strong>meddelelsesfeltet</strong> længst nederst på siden.</p><p>Når siden er genindlæst klikker du på <strong>Installer</strong> i kontrollens installationsvindue. Hvis du ikke kan se meddelelsesfeltet skal du genindlæse siden og/eller kontrollere sikkerhedsindstillingerne (ActiveX-kontroller skal være aktiverede).</p>",
    "chromeProgressHtml": "<p>Aurigma Upload Suite kræver Java plug-in. Klik <strong>Kør denne gang</strong> eller <strong>Kør altid på denne side</strong> for at køre Java plug-in run.</p>",
    "updateInstructions": "Du skal opdatere Aurigma Upload Suite kontrollen. Klik på <strong>Installer</strong> eller på <strong>Kør</strong> når installationsvinduet for kontrollen vises. Genindlæs siden hvis installationsvinduet ikke vises.",
    "macInstallJavaHtml": "<p>Anvend funktionen <a href=\"http://support.apple.com/kb/HT1338\">Programopdatering</a> (findes på Apple-menuen) hvis du vil kontrollere at du har den seneste Java-version.</p>",
    "installJavaHtml": "<p><a href=\"http://www.java.com/getjava/\">Hent</a> og installer Java.</p>"
};
})(window);

})(window);
