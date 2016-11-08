(function(window, undefined) {

// Define global Aurigma.ImageUploader namespace
var AU = (window.Aurigma || (window.Aurigma = { __namespace: true })) &&
    (window.Aurigma.ImageUploader || (window.Aurigma.ImageUploader = { __namespace: true }));

AU.language || (AU.language = { __namespace: true });
AU.ip_language || (AU.ip_language = { __namespace: true });

// Norwegian
window.no_localization = AU.language.no = {
    "addFilesProgressDialog": {
        "cancelButtonText": "Avbryt",
        "currentFileText": "Behandler fil: [name]",
        "titleText": "Legger til filer i opplastingskøen",
        "totalFilesText": "allerede behandlede filer: [count]",
        "waitText": "Det kan ta en stund. Vent litt..."
    },
    "authenticationDialog": {
        "cancelButtonText": "Avbryt",
        "loginText": "Logg på:",
        "okButtonText": "OK",
        "passwordText": "Passord:",
        "realmText": "Område:",
        "text": "Verten [name] krever autentisering"
    },
    "contextMenu": {
        "addFilesText": "Legg til filer...",
        "addFolderText": "Legg til mappe...",
        "arrangeByDimensionsText": "Mål",
        "arrangeByModifiedText": "Endringsdato",
        "arrangeByNameText": "Navn",
        "arrangeByPathText": "Bane",
        "arrangeBySizeText": "Størrelse",
        "arrangeByText": "Sorter etter",
        "arrangeByTypeText": "Type",
        "checkAllText": "Merk av for alle",
        "checkText": "Merk av",
        "detailsViewText": "Detaljer",
        "editText": "Rediger bilde...",
        "editDescriptionText": "Rediger beskrivelse...",
        "listViewText": "Liste",
        "navigateToFolderText": "Naviger til en mappe",
        "openText": "Åpne",
        "pasteText": "Lim inn",
        "removeAllText": "Fjern alle",
        "removeText": "Fjern",
        "thumbnailsViewText": "Miniatyrer",
        "tilesViewText": "Side ved side",
        "uncheckAllText": "Fjern all merking",
        "uncheckText": "Fjern merking"
    },
    "deleteFilesDialog": {
        "message": "Er du sikker på at du vil slette opplastede elementer permanent?",
        "titleText": "Slett fil"
    },
    "descriptionEditor": {
        "cancelHyperlinkText": "Avbryt",
        "orEscLabelText": " (eller Esc)",
        "saveButtonText": "Lagre"
    },
    "detailsViewColumns": {
        "dimensionsText": "Mål",
        "fileNameText": "Navn",
        "fileSizeText": "Størrelse",
        "fileTypeText": "Type",
        "infoText": "Info",
        "lastModifiedText": "Endringsdato"
    },
    "folderPane": {
        "filterHintText": "Søk ",
        "headerText": "<totalLink>Totalt antall filer: [totalCount]</totalLink>, <allowedLink>tillatt: [allowedCount]</allowedLink>"
    },
    "imageEditor": {
        "cancelButtonText": "Avbryt",
        "cancelCropButtonText": "Avbryt beskjæring",
        "cropButtonText": "Beskjær",
        "descriptionHintText": "Skriv inn beskrivelse her...",
        "rotateButtonText": "Roter",
        "saveButtonText": "Lagre og lukk"
    },
    "messages": {
        "cmykImagesNotAllowed": "CMYK-bilder er ikke tillatt.",
        "deletingFilesError": "Filen [name] kan ikke slettes",
        "dimensionsTooLarge": "Bildet [name] er for stort til å bli valgt.",
        "dimensionsTooSmall": "Bildet [name] er for lite til å bli valgt.",
        "fileNameNotAllowed": "Filen [name] kan ikke velges. Filen har et ugyldig navn.",
        "fileSizeTooSmall": "Filen [name] kan ikke velges. Filstørrelsen er mindre enn grensen.",
        "filesNotAdded": "[count] filer ble ikke lagt til på grunn av begrensninger.",
        "maxFileCountExceeded": "Filen [name] kan ikke velges. Filantallet overskrider grensen.",
        "maxFileSizeExceeded": "Filen [name] kan ikke velges. Filstørrelsen overskrider grensen.",
        "maxTotalFileSizeExceeded": "Filen [name] kan ikke velges. Total dataopplastingsstørrelse overskrider grensen.",
        "noResponseFromServer": "Ikke noe svar fra serveren.",
        "serverError": "En feil har inntruffet på tjenersiden. Ta kontakt med Deres nettadministrator, om De ser denne beskjeden.",
        "serverNotFound": "Finner ikke serveren eller proxyen [name].",
        "unexpectedError": "Aurigma Upload Suite støtte på et problem. Kontakt webadministrator hvis du får denne meldingen.",
        "uploadCancelled": "Opplastingen er avbrutt.",
        "uploadCompleted": "Opplastingen er fullført.",
        "uploadFailed": "Opplastingen mislyktes (tilkoblingen ble brutt)."
    },
    "paneItem": {
        "descriptionEditorIconTooltip": "Rediger beskrivelse",
        "imageCroppedIconTooltip": "Bildet beskjæres.",
        "imageEditorIconTooltip": "Forhåndsvis/rediger bilde",
        "removalIconTooltip": "Fjern",
        "rotationIconTooltip": "Roter"
    },
    "statusPane": {
        "clearAllHyperlinkText": "tøm alle",
        "filesToUploadText": "Filer valgt for opplasting: [count]",
        "noFilesToUploadText": "Ingen filer å laste opp",
        "progressBarText": "Laster opp ([percents] %)"
    },
    "treePane": {
        "titleText": "Mapper",
        "unixFileSystemRootText": "Filsystem",
        "unixHomeDirectoryText": "Hjemmemappe"
    },
    "uploadPane": {
        "dropFilesHereMacText": "Add files here...", // TODO: Translate
        "dropFilesHereText": "Slipp filer her…"
    },
    "uploadProgressDialog": {
        "cancelUploadButtonText": "Avbryt",
        "estimationText": "Anslått gjenstående tid: [time]",
        "hideButtonText": "Skjul",
        "hoursText": "timer",
        "infoText": "Filer lastet opp: [files]/[totalFiles] ([bytes] av [totalBytes])",
        "kilobytesText": "Kb",
        "megabytesText": "Mb",
        "minutesText": "minutter",
        "preparingText": "Forbereder filene for opplastning...",
        "reconnectionText": "Opplastingen gikk feil. Venter på at ny kontakt etableres...",
        "secondsText": "sekunder",
        "titleText": "Laster opp filer til server"
    },
    "cancelUploadButtonText": "Stopp",
    "loadingFolderContentText": "Laster inn innhold...",
    "uploadButtonText": "Last opp"
};
// Norwegian
AU.ip_language.no = {
    "commonHtml": "<p>Aurigma Bildeopplaster -kontrollen behøves for å laste opp Deres bilder raskt og enkelt. De vil kunne velge mange bilder i et brukervennlig grensesnitt, i stedet for tungvinte felt for inndata med en <strong>Søk</strong> -knapp.</p>",
    "progressHtml": "<p><img src=\"{0}\" /><br />Laster inn Aurigma Bildeopplasteren...</p>",
    "beforeIE6XPSP2ProgressHtml": "<p>For å installere Bildeopplasteren, vennligst vent til kontrollen er lastet ned og klikk på <strong>Ja</strong> -knappen når De ser installasjonsdialogen.",
    "beforeIE6XPSP2InstructionsHtml": "<p>For å installere Bildeopplasteren, vennligst last inn siden på nytt og klikk på <strong>Ja</strong> -knappen når De ser kontrollinstallasjons -dialogen. Om De ikke ser installasjonsdialogen, vennligst kontroller Deres sikkerhetsinnstillinger.</p>",
    "IE6XPSP2ProgressHtml": "<p>Vennligst vent til kontrollen er lastet inn.</p>",
    "IE6XPSP2InstructionsHtml": "<p>For å installere Bildeopplasteren, vennligst klikk på <strong>Informasjonslinjen</strong> og velg <strong>Installer ActiveX kontroll</strong> fra rullegardin-menyen. Etter at siden er lastet inn på nytt, klikk på <strong>Installer</strong> når De ser kontrollinstallasjons-dialogen. Om De ikke ser Informasjonslinjen, vennligst last inn siden på nytt og/eller kontroller Deres sikkerhetsinnstillinger.</p>",
    "IE7ProgressHtml": "<p>Vennligst vent til kontrollen er lastet inn.</p>",
    "IE7InstructionsHtml": "<p>For å installere Bildeopplasteren, vennligst klikk på <strong>Informasjonslinjen</strong> og velg <strong>Installer ActiveX kontroll</strong> eller <strong>Kjør ActiveX kontroll</strong> fra rullegardinmenyen.</p><p>Klikk deretter enten på <strong>Kjør</strong> eller -etter at siden er lastet inn på nytt - klikk på <strong>Installer</strong> når De ser kontrollinstallasjonsdialogen. Om De ikke ser Informasjonslinjen, vennligst last inn siden på nytt og/eller kontroller Deres sikkerhetsinnstillinger.</p>",
    "IE8ProgressHtml": "<p>Vennligst vent til kontrollen er lastet inn.</p>",
    "IE8InstructionsHtml": "<p>For å installere Bildeopplasteren, vennligst klikk på <strong>Informasjonslinjen</strong> og velg <strong>Installer denne Add-on </strong> eller <strong>Kjør Add-on</strong> fra rullegardinmenyen.</p><p>Klikk deretter enten på <strong>Kjør</strong> eller -etter at siden er lastet inn på nytt - klikk på <strong>Installer</strong> når De ser kontrollinstallasjons dialogen. Om De ikke ser Informasjonslinjen, vennligst last inn siden på nytt og/eller kontroller Deres sikkerhetsinnstillinger.</p>",
    "IE9ProgressHtml": "<p>Vennligst vent til kontrollen er lastet inn.</p>",
    "IE9InstructionsHtml": "<p>For å installere Bildeopplasteren, vennligst klikk på <strong>Tillat</strong> eller <strong>Installer</strong> på <strong>Informasjonslinjen</strong> på bunnen av siden.</p><p>Deretter - etter at siden er lastet opp på nytt - klikk på <strong>Installer</strong> på kontrollinstallasjons dialogen. Om De ikke ser Informasjonslinjen, vennligst last inn siden på nytt og/eller kontroller Deres sikkerhetsinnstillinger (ActiveX kontroller må være mulige å kjøre).</p>",
    "chromeProgressHtml": "<p>Aurigma Upload Suite requires Java plug-in. Please, click <strong>Run this time</strong> or <strong>Always run on this site</strong> to let Java plug-in run.</p>", // TODO: translate
    "updateInstructions": "De må oppdatere Bildeopplasteren -kontrollen. Klikk på <strong>Installer</strong> eller <strong>Kjør</strong> -knappen når De ser kontrollinstallasjons dialogen. Om De ikke ser installasjonsdialogen, vennligst last inn siden på nytt.",
    "macInstallJavaHtml": "<p>Benytt <a href=\"http://support.apple.com/kb/HT1338\">Software Update</a> funksjonen (tilgjengelig på Apple -menyen) for å kontrollere at De har den mest oppdaterte versjonen av Java for Deres Mac.</p>",
    "installJavaHtml": "<p>Vennligst <a href=\"http://www.java.com/getjava/\">last ned </a> og installer Java.</p>"
};
})(window);
