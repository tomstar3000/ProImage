(function(window, undefined) {

// Define global Aurigma.ImageUploader namespace
var AU = (window.Aurigma || (window.Aurigma = { __namespace: true })) &&
    (window.Aurigma.ImageUploader || (window.Aurigma.ImageUploader = { __namespace: true }));

AU.language || (AU.language = { __namespace: true });
AU.ip_language || (AU.ip_language = { __namespace: true });

// Italian
window.it_localization = AU.language.it = {
    "addFilesProgressDialog": {
        "cancelButtonText": "Annulla",
        "currentFileText": "Elaborazione file: \"[name]\"",
        "titleText": "Aggiunta fila a coda caricamento",
        "totalFilesText": "file già elaborati: [count]",
        "waitText": "Attendere qualche istante..."
    },
    "authenticationDialog": {
        "cancelButtonText": "Annulla",
        "loginText": "Login:",
        "okButtonText": "OK",
        "passwordText": "Password:",
        "realmText": "Area autenticazione:",
        "text": "L'host [name] richiede autenticazione"
    },
    "contextMenu": {
        "addFilesText": "Aggiungi file...",
        "addFolderText": "Aggiungi cartella...",
        "arrangeByDimensionsText": "Dimensioni",
        "arrangeByModifiedText": "Data modifica",
        "arrangeByNameText": "Nome",
        "arrangeByPathText": "Percorso",
        "arrangeBySizeText": "Dimensioni",
        "arrangeByText": "Ordina per",
        "arrangeByTypeText": "Tipo",
        "checkAllText": "Seleziona tutto",
        "checkText": "Seleziona",
        "detailsViewText": "Dettagli",
        "editText": "Modifica immagine...",
        "editDescriptionText": "Modifica descrizione...",
        "listViewText": "Elenco",
        "navigateToFolderText": "Sposta sulla cartella",
        "openText": "Apri",
        "pasteText": "Incolla",
        "removeAllText": "Rimuovi tutti",
        "removeText": "Rimuovi",
        "thumbnailsViewText": "Miniature",
        "tilesViewText": "Titoli",
        "uncheckAllText": "Deseleziona tutto",
        "uncheckText": "Deseleziona"
    },
    "deleteFilesDialog": {
        "message": "Eliminare per sempre gli elementi caricati?",
        "titleText": "Elimina file"
    },
    "descriptionEditor": {
        "cancelHyperlinkText": "Annulla",
        "orEscLabelText": " (o Esc)",
        "saveButtonText": "Salva"
    },
    "detailsViewColumns": {
        "dimensionsText": "Dimensioni",
        "fileNameText": "Nome",
        "fileSizeText": "Dimensioni",
        "fileTypeText": "Tipo",
        "infoText": "Informazioni",
        "lastModifiedText": "Data modifica"
    },
    "folderPane": {
        "filterHintText": "Cerca ",
        "headerText": "<totalLink>File totali: [totalCount]</totalLink>, <allowedLink>consentito: [allowedCount]</allowedLink>"
    },
    "imageEditor": {
        "cancelButtonText": "Annulla",
        "cancelCropButtonText": "Annulla rilascio",
        "cropButtonText": "Ritaglia",
        "descriptionHintText": "Descrizione tipo qui...",
        "rotateButtonText": "Ruota",
        "saveButtonText": "Salva e chiudi"
    },
    "messages": {
        "cmykImagesNotAllowed": "Immagini CMYK non consentite.",
        "deletingFilesError": "Il file \"[name]\" non può essere eliminato",
        "dimensionsTooLarge": "L'immagine \"[name]\" è troppo grande per essere selezionata.",
        "dimensionsTooSmall": "L'immagine \"[name]\" è troppo piccola per essere selezionata.",
        "fileNameNotAllowed": "Il file \"[name]\" non può essere selezionato. Questo file ha un nome inammissibile.",
        "fileSizeTooSmall": "Il file \"[name]\" non può essere selezionato. Queste dimensioni di file sono inferiori al limite.",
        "filesNotAdded": "[count] file non sono stati aggiunti a causa delle limitazioni.",
        "maxFileCountExceeded": "Il file \"[name]\" non può essere selezionato. La quantità di file supera il limite.",
        "maxFileSizeExceeded": "Il file \"[name]\" non può essere selezionato. Le dimensioni di questo file superano il limite.",
        "maxTotalFileSizeExceeded": "Il file \"[name]\" non può essere selezionato. Le dimensioni totali dei dati da caricare superano il limite.",
        "noResponseFromServer": "Nessuna risposta dal server.",
        "serverError": "Si sono verificati alcuni errori dal lato del server. Se viene visualizzato questo messaggio, contattare il Web master.",
        "serverNotFound": "Impossibile trovare server o proxy [name].",
        "unexpectedError": "Aurigma Upload Suite ha riscontrato alcuni problemi. Se viene visualizzato questo messaggio, contattare il Web master.",
        "uploadCancelled": "Caricamento annullato.",
        "uploadCompleted": "Caricamento completato.",
        "uploadFailed": "Caricamento non riuscito (la connessione è stata interrotta)."
    },
    "paneItem": {
        "descriptionEditorIconTooltip": "Modifica descrizione",
        "imageCroppedIconTooltip": "L'immagine è ritagliata.",
        "imageEditorIconTooltip": "Anteprima/modifica immagine",
        "removalIconTooltip": "Rimuovi",
        "rotationIconTooltip": "Ruota"
    },
    "statusPane": {
        "clearAllHyperlinkText": "Cancella tutto",
        "filesToUploadText": "File selezionati da caricare: [count]",
        "noFilesToUploadText": "Nessun file da caricare",
        "progressBarText": "Caricamento ([percents]%)"
    },
    "treePane": {
        "titleText": "Cartelle",
        "unixFileSystemRootText": "File system",
        "unixHomeDirectoryText": "Cartella iniziale"
    },
    "uploadPane": {
        "dropFilesHereMacText": "Add files here...", // TODO: Translate
        "dropFilesHereText": "Rilascia file qui…"
    },
    "uploadProgressDialog": {
        "cancelUploadButtonText": "Annulla",
        "estimationText": "Tempo rimasto stimato: [time]",
        "hideButtonText": "Nascondi",
        "hoursText": "ore",
        "infoText": "File caricati: [files]/[totalFiles] ([bytes] di [totalBytes])",
        "kilobytesText": "Kb",
        "megabytesText": "Mb",
        "minutesText": "minuti",
        "preparingText": "Preparazione file per il caricamento...",
        "reconnectionText": "Caricamento non riuscito. Attesa riconnessione...",
        "secondsText": "secondi",
        "titleText": "Caricamento file sul server"
    },
    "cancelUploadButtonText": "Interrompi",
    "loadingFolderContentText": "Caricamento contenuto...",
    "uploadButtonText": "Carica"
};
// Italian
AU.ip_language.it = {
    "commonHtml": "<p>Il controllo Aurigma Upload Suite è necessario per caricare i file in modo rapido e facile. Sarà possibile selezionare più immagini in un'interfaccia di facile uso, invece che in astrusi campi di immissione con il pulsante <strong>Sfoglia</strong>.</p>",
    "progressHtml": "<p><img src=\"{0}\" /><br />Caricamento di Aurigma Upload Suite in corso...</p>",
    "flashProgressHtml": "<p><img src=\"{0}\" /><br />Caricamento di Aurigma Upload Suite in corso...</p>",
    "beforeIE6XPSP2ProgressHtml": "<p>Per installare Aurigma Upload Suite, attendere il caricamento del controllo e fare clic sul pulsante <strong>Sì</strong> quando viene visualizzata la finestra di dialogo di installazione.",
    "beforeIE6XPSP2InstructionsHtml": "<p>Per installare Aurigma Upload Suite, ricaricare la pagina e fare clic sul pulsante <strong>Sì</strong> quando viene visualizzata la finestra di dialogo di installazione del controllo. Se la finestra di dialogo di installazione non viene visualizzata, controllare le impostazioni di sicurezza.</p>",
    "IE6XPSP2ProgressHtml": "<p>Attendere il caricamento del controllo.</p>",
    "IE6XPSP2InstructionsHtml": "<p>Per installare Aurigma Upload Suite, fare clic su <strong>Barra informazioni</strong> e selezionare <strong>Installa controllo ActiveX</strong> dal menu a discesa. Dopo che la pagina è stata ricaricata, fare clic su <strong>Installa</strong> quando viene visualizzata la finestra di dialogo dell'installazione del controllo. Se la barra informazioni non viene visualizzata, provare a ricaricare la pagina e/o controllare le impostazioni di sicurezza.</p>",
    "IE7ProgressHtml": "<p>Attendere il caricamento del controllo.</p>",
    "IE7InstructionsHtml": "<p>Per installare Aurigma Upload Suite, fare clic su <strong>Barra informazioni</strong> e selezionare <strong>Installa controllo ActiveX</strong> o <strong>Esegui controllo ActiveX</strong> dal menu a discesa.</p><p>Dopodiché, fare clic su <strong>Esegui</strong> oppure, dopo che la pagina è stata ricaricata, fare clic su <strong>Installa</strong> quando viene visualizzata la finestra di dialogo di installazione del controllo. Se la barra informazioni non viene visualizzata, provare a ricaricare la pagina e/o controllare le impostazioni di sicurezza.</p>",
    "IE8ProgressHtml": "<p>Attendere il caricamento del controllo.</p>",
    "IE8InstructionsHtml": "<p>Per installare Aurigma Upload Suite, fare clic su <strong>Barra informazioni</strong> e selezionare <strong>Installa questo componente aggiuntivo</strong> o <strong>Esegui componente aggiuntivo</strong> dal menu a discesa.</p><p>Dopodiché, fare clic su <strong>Esegui</strong> oppure, dopo che la pagina è stata ricaricata, fare clic su <strong>Installa</strong> quando viene visualizzata la finestra di dialogo di installazione del controllo. Se la barra informazioni non viene visualizzata, provare a ricaricare la pagina e/o controllare le impostazioni di sicurezza.</p>",
    "IE9ProgressHtml": "<p>Attendere il caricamento del controllo.</p>",
    "IE9InstructionsHtml": "<p>Per installare Aurigma Upload Suite, fare clic su <strong>Consenti</strong> o <strong>Installa</strong> nella <strong>barra di notifica</strong> nella parte inferiore della pagina.</p><p>Dopo che la pagina è stata ricaricata, fare clic su <strong>Installa</strong> nella finestra di dialogo di installazione del controllo. Se la barra di notifica non viene visualizzata, provare a ricaricare la pagina e/o controllare le impostazioni di sicurezza (i controlli ActiveX dovrebbero essere attivati).</p>",
    "chromeProgressHtml": "<p>Aurigma Upload Suite requires Java plug-in. Please, click <strong>Run this time</strong> or <strong>Always run on this site</strong> to let Java plug-in run.</p>", // TODO: translate
    "updateInstructions": "È necessario aggiornare il controllo Aurigma Upload Suite. Fare clic sul pulsante <strong>Installa</strong> o <strong>Esegui</strong> quando viene visualizzata la finestra di dialogo dell'installazione del controllo. Se la finestra di dialogo non viene visualizzata, provare a ricaricare la pagina.",
    "macInstallJavaHtml": "<p>Utilizzare la funzionalità <a href=\"http://support.apple.com/kb/HT1338\">Aggiornamento software</a> (disponibile nel menu Apple) per verificare di disporre della versione più aggiornata di Java per Mac.</p>",
    "installJavaHtml": "<p><a href=\"http://www.java.com/getjava/\">Scaricare</a> e installare Java.</p>",
    "installFlashPlayerHtml": "<p>Per eseguire Aurigma Upload Suite è necessario installare Flash Player. Scaricare la versione più recente da <a href='http://www.adobe.com/go/getflashplayer' title='Download Flash Player'>qui</a>.</p>"
};
})(window);
