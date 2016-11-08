(function(window, undefined) {

// Define global Aurigma.ImageUploader namespace
var AU = (window.Aurigma || (window.Aurigma = { __namespace: true })) &&
    (window.Aurigma.ImageUploader || (window.Aurigma.ImageUploader = { __namespace: true }));

AU.language || (AU.language = { __namespace: true });
AU.ip_language || (AU.ip_language = { __namespace: true });

// German
window.de_localization = AU.language.de = {
    "addFilesProgressDialog": {
        "cancelButtonText": "Abbrechen",
        "currentFileText": "Datei wird verarbeitet: „[name]“",
        "titleText": "Dateien werden der Upload-Warteschlange hinzugefügt",
        "totalFilesText": "Bereits verarbeitete Dateien: [count]",
        "waitText": "Bitte warten, es kann eine Weile dauern..."
    },
    "authenticationDialog": {
        "cancelButtonText": "Abbrechen",
        "loginText": "Anmelden:",
        "okButtonText": "OK",
        "passwordText": "Kennwort:",
        "realmText": "Bereich:",
        "text": "Host [name] erfordert Authentifizierung"
    },
    "contextMenu": {
        "addFilesText": "Dateien hinzufügen...",
        "addFolderText": "Ordner hinzufügen...",
        "arrangeByDimensionsText": "Maße",
        "arrangeByModifiedText": "Änderungsdatum",
        "arrangeByNameText": "Name",
        "arrangeByPathText": "Pfad",
        "arrangeBySizeText": "Größe",
        "arrangeByText": "Sortieren nach",
        "arrangeByTypeText": "Typ",
        "checkAllText": "Alle auswählen",
        "checkText": "Auswählen",
        "detailsViewText": "Details",
        "editText": "Bild bearbeiten...",
        "editDescriptionText": "Beschreibung bearbeiten...",
        "listViewText": "Liste",
        "navigateToFolderText": "Zu Ordner navigieren",
        "openText": "Öffnen",
        "pasteText": "Einfügen",
        "removeAllText": "Alle entfernen",
        "removeText": "Entfernen",
        "thumbnailsViewText": "Miniaturbilder",
        "tilesViewText": "Kacheln",
        "uncheckAllText": "Auswahl von allen aufheben",
        "uncheckText": "Auswahl aufheben"
    },
    "deleteFilesDialog": {
        "message": "Möchten Sie die hochgeladenen Elemente dauerhaft löschen?",
        "titleText": "Datei löschen"
    },
    "descriptionEditor": {
        "cancelHyperlinkText": "Abbrechen",
        "orEscLabelText": " (oder Esc)",
        "saveButtonText": "Speichern"
    },
    "detailsViewColumns": {
        "dimensionsText": "Maße",
        "fileNameText": "Name",
        "fileSizeText": "Größe",
        "fileTypeText": "Typ",
        "infoText": "Info",
        "lastModifiedText": "Änderungsdatum"
    },
    "folderPane": {
        "filterHintText": "Suchen ",
        "headerText": "<totalLink>Dateien insgesamt: [totalCount]</totalLink>, <allowedLink>zulässig: [allowedCount]</allowedLink>"
    },
    "imageEditor": {
        "cancelButtonText": "Abbrechen",
        "cancelCropButtonText": "Beschneiden abbrechen",
        "cropButtonText": "Beschneiden",
        "descriptionHintText": "Beschreibung hier eingeben...",
        "rotateButtonText": "Drehen",
        "saveButtonText": "Speichern und schließen"
    },
    "messages": {
        "cmykImagesNotAllowed": "CMYK-Bilder sind nicht zulässig.",
        "deletingFilesError": "Die Datei „[name]“ kann nicht gelöscht werden",
        "dimensionsTooLarge": "Das Bild „[name]“ ist zu groß, um ausgewählt zu werden.",
        "dimensionsTooSmall": "Das Bild „[name]“ ist zu klein, um ausgewählt zu werden.",
        "fileNameNotAllowed": "Die Datei „[name]“ kann nicht ausgewählt werden. Die Datei hat einen unzulässigen Namen.",
        "fileSizeTooSmall": "Die Datei „[name]“ kann nicht ausgewählt werden. Die Größe dieser Datei unterschreitet das Limit.",
        "filesNotAdded": "[count] Dateien wurden aufgrund von Beschränkungen nicht hinzugefügt.",
        "maxFileCountExceeded": "Die Datei „[name]“ kann nicht ausgewählt werden. Die Dateimenge überschreitet das Limit.",
        "maxFileSizeExceeded": "Die Datei „[name]“ kann nicht ausgewählt werden. Diese Dateigröße überschreitet das Limit.",
        "maxTotalFileSizeExceeded": "Die Datei „[name]“ kann nicht ausgewählt werden. Die Gesamtgröße der hochgeladenen Daten überschreitet das Limit.",
        "noResponseFromServer": "Keine Antwort vom Server.",
        "serverError": "Ein serverseitiger Fehler ist aufgetreten. Wenn diese Meldung erscheint, wenden Sie sich an Ihren Webmaster.",
        "serverNotFound": "Server oder Proxy [name] kann nicht gefunden werden.",
        "unexpectedError": "Aurigma Upload Suite hat ein Problem festgestellt. Wenn diese Meldung erscheint, wenden Sie sich an den Webmaster.",
        "uploadCancelled": "Hochladen wird abgebrochen.",
        "uploadCompleted": "Hochladen abgeschlossen.",
        "uploadFailed": "Fehler beim Hochladen (Verbindung wurde unterbrochen)."
    },
    "paneItem": {
        "descriptionEditorIconTooltip": "Beschreibung bearbeiten",
        "imageCroppedIconTooltip": "Das Bild ist beschnitten.",
        "imageEditorIconTooltip": "Vorschau/Bild bearbeiten",
        "removalIconTooltip": "Entfernen",
        "rotationIconTooltip": "Drehen"
    },
    "statusPane": {
        "clearAllHyperlinkText": "Alle löschen",
        "filesToUploadText": "Zum Hochladen ausgewählte Dateien: [count]",
        "noFilesToUploadText": "Keine Dateien zum Hochladen",
        "progressBarText": "Dateien werden hochgeladen ([percents] %)"
    },
    "treePane": {
        "titleText": "Ordner",
        "unixFileSystemRootText": "Dateisystem",
        "unixHomeDirectoryText": "Startordner"
    },
    "uploadPane": {
        "dropFilesHereMacText": "Add files here...", // TODO: Translate
        "dropFilesHereText": "Dateien hier ablegen…"
    },
    "uploadProgressDialog": {
        "cancelUploadButtonText": "Abbrechen",
        "estimationText": "Geschätzte verbleibende Zeit: [time]",
        "hideButtonText": "Ausblenden",
        "hoursText": "Stunden",
        "infoText": "Dateien hochgeladen: [files]/[totalFiles] ([bytes] von [totalBytes])",
        "kilobytesText": "Kb",
        "megabytesText": "Mb",
        "minutesText": "Minuten",
        "preparingText": "Dateien werden zum Hochladen vorbereitet...",
        "reconnectionText": "Hochladen fehlgeschlagen. Auf Neuverbindung wird gewartet...",
        "secondsText": "Sekunden",
        "titleText": "Dateien werden auf Server hochgeladen"
    },
    "cancelUploadButtonText": "Anhalten",
    "loadingFolderContentText": "Inhalt laden...",
    "uploadButtonText": "Hochladen"
};
// German
AU.ip_language.de = {
    "commonHtml": "<p>Das Aurigma Upload Suite-Steuerelement benötigen Sie, um Ihre Dateien schnell und einfach hochladen zu können. Sie können dann mehrere Bilder auf einer benutzerfreundlichen Oberfläche auswählen, anstatt dies über die unpraktischen Eingabefelder zu tun, die Sie über die Schaltfläche <strong>Durchsuchen</strong> aufrufen.</p>",
    "progressHtml": "<p><img src=\"{0}\" /><br />Aurigma Upload Suite wird geladen...</p>",
    "flashProgressHtml": "<p><img src=\"{0}\" /><br />Aurigma Upload Suite wird geladen...</p>",
    "beforeIE6XPSP2ProgressHtml": "<p>Zum Installieren von Aurigma Upload Suite warten Sie bitte, bis das Steuerelement geladen ist, und klicken Sie auf die Schaltfläche <strong>Ja</strong>, sobald das Installationsdialogfeld angezeigt wird.</p>",
    "beforeIE6XPSP2InstructionsHtml": "<p>Zum Installieren von Aurigma Upload Suite laden Sie die Seite neu und klicken Sie auf die Schaltfläche <strong>Ja</strong>, sobald das Dialogfeld zur Installation des Steuerelements angezeigt wird. Sollte das Installationsdialogfeld nicht angezeigt werden, überprüfen Sie Ihre Sicherheitseinstellungen.</p>",
    "IE6XPSP2ProgressHtml": "<p>Bitte warten Sie, bis das Steuerelement geladen ist.</p>",
    "IE6XPSP2InstructionsHtml": "<p>Zum Installieren von Aurigma Upload Suite klicken Sie auf die <strong>Informationsleiste</strong> und wählen Sie <strong>ActiveX-Steuerelement installieren</strong> aus dem Dropdown-Menü. Nachdem die Seite neu geladen wurde, klicken Sie auf <strong>Installieren</strong>, sobald das Dialogfeld zur Installation des Steuerelements angezeigt wird. Sollte die Informationsleiste nicht angezeigt werden, laden Sie die Seite versuchsweise erneut und/oder überprüfen Sie Ihre Sicherheitseinstellungen.</p>",
    "IE7ProgressHtml": "<p>Bitte warten Sie, bis das Steuerelement geladen ist.</p>",
    "IE7InstructionsHtml": "<p>Zum Installieren von Aurigma Upload Suite klicken Sie auf die <strong>Informationsleiste</strong> und wählen Sie <strong>ActiveX-Steuerelement installieren</strong> oder <strong>ActiveX-Steuerelement ausführen</strong> aus dem Dropdown-Menü.</p><p>Klicken Sie dann entweder auf <strong>Ausführen</strong> oder nach erneutem Laden der Seite auf <strong>Installieren</strong>, sobald das Dialogfeld zur Installation des Steuerelements angezeigt wird. Sollte die Informationsleiste nicht angezeigt werden, laden Sie die Seite versuchsweise erneut und/oder überprüfen Sie Ihre Sicherheitseinstellungen.</p>",
    "IE8ProgressHtml": "<p>Bitte warten Sie, bis das Steuerelement geladen ist.</p>",
    "IE8InstructionsHtml": "<p>Zum Installieren von Aurigma Upload Suite klicken Sie auf die <strong>Informationsleiste</strong> und wählen Sie <strong>Dieses Add-On installieren</strong> oder <strong>Add-On ausführen</strong> aus dem Dropdown-Menü.</p><p>Klicken Sie dann entweder auf <strong>Ausführen</strong> oder nach erneutem Laden der Seite auf <strong>Installieren</strong>, sobald das Dialogfeld zur Installation des Steuerelements angezeigt wird. Sollte die Informationsleiste nicht angezeigt werden, laden Sie die Seite versuchsweise erneut und/oder überprüfen Sie Ihre Sicherheitseinstellungen.</p>",
    "IE9ProgressHtml": "<p>Bitte warten Sie, bis das Steuerelement geladen ist.</p>",
    "IE9InstructionsHtml": "<p>Zum Installieren von Aurigma Upload Suite klicken Sie in der <strong>Benachrichtigungsleiste</strong> am unteren Rand der Seite auf <strong>Zulassen</strong> oder auf <strong>Installieren</strong>.</p><p>Klicken Sie dann nach erneutem Laden der Seite im Dialogfeld zur Installation des Steuerelements auf <strong>Installieren</strong>. Sollte die Benachrichtigungsleiste nicht angezeigt werden, laden Sie die Seite versuchsweise erneut und/oder überprüfen Sie Ihre Sicherheitseinstellungen (ActiveX-Steuerelemente müssen aktiviert sein).</p>",
    "chromeProgressHtml": "<p>Aurigma Upload Suite requires Java plug-in. Please, click <strong>Run this time</strong> or <strong>Always run on this site</strong> to let Java plug-in run.</p>", // TODO: translate
    "updateInstructions": "Das Aurigma Upload Suite-Steuerelement muss aktualisiert werden. Klicken Sie auf die Schaltfläche <strong>Installieren</strong> oder <strong>Ausführen</strong>, sobald das Dialogfeld zur Installation des Steuerelements angezeigt wird. Sollte das Dialogfeld nicht angezeigt werden, laden Sie die Seite versuchsweise neu.",
    "macInstallJavaHtml": "<p>Prüfen Sie mithilfe der Funktion <a href=\"http://support.apple.com/kb/HT1338\">Softwareaktualisierung</a> (im Apple-Menü), ob Sie über die aktuellste Java-Version für Ihren Mac verfügen.</p>",
    "installJavaHtml": "<p>Bitte Java <a href=\"http://www.java.com/getjava/\">herunterladen</a> und installieren.</p>",
    "installFlashPlayerHtml": "<p>Zum Ausführen von Aurigma Upload Suite müssen Sie Flash Player installieren. Laden Sie die neueste Version von <a href='http://www.adobe.com/go/getflashplayer' title='Download Flash Player'>hier</a> herunter.</p>"
};
})(window);
