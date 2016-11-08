(function(window, undefined) {

// Define global Aurigma.ImageUploader namespace
var AU = (window.Aurigma || (window.Aurigma = { __namespace: true })) &&
    (window.Aurigma.ImageUploader || (window.Aurigma.ImageUploader = { __namespace: true }));

AU.language || (AU.language = { __namespace: true });
AU.ip_language || (AU.ip_language = { __namespace: true });

// Czech
window.cs_localization = AU.language.cs = {
    "addFilesProgressDialog": {
        "cancelButtonText": "Zrušit",
        "currentFileText": "Zpracovávání souboru: „[name]“",
        "titleText": "Přidávání souborů k frontě nahrávání",
        "totalFilesText": "již zpracované soubory: [count]",
        "waitText": "Prosíme čekejte, operace může chvíli trvat..."
    },
    "authenticationDialog": {
        "cancelButtonText": "Zrušit",
        "loginText": "Přihlásit se:",
        "okButtonText": "Ok",
        "passwordText": "Heslo:",
        "realmText": "Stránková oblast:",
        "text": "Hostitelské [name] vyžaduje autorizaci"
    },
    "contextMenu": {
        "addFilesText": "Přidat soubory...",
        "addFolderText": "Přidat složku...",
        "arrangeByDimensionsText": "Rozměry",
        "arrangeByModifiedText": "Datum úprav",
        "arrangeByNameText": "Název",
        "arrangeByPathText": "Cesta",
        "arrangeBySizeText": "Formát",
        "arrangeByText": "Seřadit podle",
        "arrangeByTypeText": "Typ",
        "checkAllText": "Vybrat vše",
        "checkText": "Ověřit",
        "detailsViewText": "Podrobnosti",
        "editText": "Upravit snímek...",
        "editDescriptionText": "Upravit popis...",
        "listViewText": "Seznam",
        "navigateToFolderText": "Navigovat do složky",
        "openText": "Otevřít",
        "pasteText": "Vložit",
        "removeAllText": "Odstranit vše",
        "removeText": "Odstranit",
        "thumbnailsViewText": "Náhledy",
        "tilesViewText": "Názvy",
        "uncheckAllText": "Zrušit zaškrtnutí u všech",
        "uncheckText": "Zrušit zaškrtnutí"
    },
    "deleteFilesDialog": {
        "message": "Opravdu chcete trvale smazat nahrané položky?",
        "titleText": "Smazat soubor"
    },
    "descriptionEditor": {
        "cancelHyperlinkText": "Zrušit",
        "orEscLabelText": " (nebo Esc)",
        "saveButtonText": "Uložit"
    },
    "detailsViewColumns": {
        "dimensionsText": "Rozměry",
        "fileNameText": "Název",
        "fileSizeText": "Formát",
        "fileTypeText": "Typ",
        "infoText": "Informace",
        "lastModifiedText": "Datum úprav"
    },
    "folderPane": {
        "filterHintText": "Hledat... ",
        "headerText": "<totalLink>Celkový počet souborů: [totalCount]</totalLink>, <allowedLink>povoleno: [allowedCount]</allowedLink>"
    },
    "imageEditor": {
        "cancelButtonText": "Zrušit",
        "cancelCropButtonText": "Zrušit oříznutí",
        "cropButtonText": "Oříznout",
        "descriptionHintText": "Sem zadejte popist...",
        "rotateButtonText": "Otočit",
        "saveButtonText": "Uložit a uzavřít"
    },
    "messages": {
        "cmykImagesNotAllowed": "CMYK snímky nejsou povoleny.",
        "deletingFilesError": "Soubor „[name]“ nelze smazat",
        "dimensionsTooLarge": "Snímek „[name]“ je příliš velký, aby byl zvolen.",
        "dimensionsTooSmall": "Snímek „[name]“ je příliš malý, aby byl zvolen.",
        "fileNameNotAllowed": "Soubor „[name]“ nelze zvolit. Tento soubor má nepřípustný název.",
        "fileSizeTooSmall": "Soubor „[name]“ nelze zvolit. Tento soubor je menší než je limit.",
        "filesNotAdded": "[count] souborů nebylo přidáno z důvodu omezení..",
        "maxFileCountExceeded": "Soubor „[name]“ nelze zvolit. Počet souborů překračuje limit.",
        "maxFileSizeExceeded": "Soubor „[name]“ nelze zvolit. Tato velikost souborů překračuje limit.",
        "maxTotalFileSizeExceeded": "Soubor „[name]“ nelze zvolit. Celková velikost načtených dat překračuje limit.",
        "noResponseFromServer": "Žádná odpověď serveru.",
        "serverError": "Došlo k chybě na straně serveru. Pokud se vám zobrazí toto hlášení, obraťte se na správce webu.",
        "serverNotFound": "Server nebo proxy [name] nelze nalézt.",
        "unexpectedError": "Program Aurigma Upload Suite se setkal s nějakým problémem. Pokud uvidíte hlášení, obraťte se na správce webu.",
        "uploadCancelled": "Načítání je zrušeno.",
        "uploadCompleted": "Načítání dokončeno.",
        "uploadFailed": "Načítání selhalo (spojení bylo přerušeno)."
    },
    "paneItem": {
        "descriptionEditorIconTooltip": "Upravit popis",
        "imageCroppedIconTooltip": "Snímek je oříznut.",
        "imageEditorIconTooltip": "Zobrazit/upravit snímek",
        "removalIconTooltip": "Odstranit",
        "rotationIconTooltip": "Otočit"
    },
    "statusPane": {
        "clearAllHyperlinkText": "vymazat vše",
        "filesToUploadText": "Soubory zvoleny k načtení: [count]",
        "noFilesToUploadText": "Žádné soubory k načtení",
        "progressBarText": "Načítání ([percents] %)"
    },
    "treePane": {
        "titleText": "Složky",
        "unixFileSystemRootText": "Systém souborů",
        "unixHomeDirectoryText": "Výchozí složka"
    },
    "uploadPane": {
        "dropFilesHereMacText": "Add files here...", // TODO: Translate
        "dropFilesHereText": "Přetáhnout soubory sem..."
    },
    "uploadProgressDialog": {
        "cancelUploadButtonText": "Zrušit",
        "estimationText": "Odhadovaná zbývající doba: [time]",
        "hideButtonText": "Skrýt",
        "hoursText": "hodin",
        "infoText": "Načtené soubory: [files]/[totalFiles] ([bytes] z [totalBytes])",
        "kilobytesText": "Kb",
        "megabytesText": "Mb",
        "minutesText": "minut",
        "preparingText": "Příprava souborů k načtení...",
        "reconnectionText": "Načtení selhalo. Čeká se na opětovné připojení...",
        "secondsText": "sekund",
        "titleText": "Načítání souborů na server"
    },
    "cancelUploadButtonText": "Stop",
    "loadingFolderContentText": "Načítání obsahu...",
    "uploadButtonText": "Načíst"
};
// Czech
AU.ip_language.cs = {
    "commonHtml": "<p>Ovládací prvek Aurigma Upload Suite vám umožňuje rychle a snadno nahrát vaše soubory. Místo nepohodlných zadávacích polí tlačítka <strong>Procházet</strong> můžete hromadně vybírat obrázky v uživatelsky přívětivém rozhraní.</p>",
    "progressHtml": "<p><img src=\"{0}\" /><br />Načítání aplikace Aurigma Upload Suite...</p>",
    "flashProgressHtml": "<p><img src=\"{0}\" /><br />Načítání aplikace Aurigma Upload Suite...</p>",
    "beforeIE6XPSP2ProgressHtml": "<p>Chcete-li nainstalovat aplikaci Aurigma Upload Suite, počkejte, dokud se nenačte ovládací prvek, a až se vám zobrazí dialogové okno, klepněte na tlačítko <strong>Ano</strong>.</p>",
    "beforeIE6XPSP2InstructionsHtml": "<p>Chcete-li nainstalovat aplikaci Aurigma Upload Suite, načtěte stránku znovu. Až se vám zobrazí dialogové okno instalace ovládacího prvku, klepněte na tlačítko <strong>Ano</strong>. Pokud se vám dialogové okno nezobrazí, prosíme zkontrolujte svá nastavení zabezpečení.</p>",
    "IE6XPSP2ProgressHtml": "<p>Prosíme počkejte, dokud se ovládací prvek nenačte.</p>",
    "IE6XPSP2InstructionsHtml": "<p>Chcete-li nainstalovat aplikaci Aurigma Upload Suite, klikněte na <strong>Informační lištu</strong> a v rozevírací nabídce zvolte <strong>Instalovat prvek ActiveX</strong>. Po načtení stránky, až se zobrazí dialogové okno instalace ovládacího prvku, klikněte na <strong>Instalovat</strong>. Pokud se vám Informační lišta nezobrazuje, zkuste znovu načíst stránku a/nebo zkontrolovat vaše nastavení zabezpečení.</p>",
    "IE7ProgressHtml": "<p>Prosíme počkejte, dokud se ovládací prvek nenačte.</p>",
    "IE7InstructionsHtml": "<p>Chcete-li nainstalovat aplikaci Aurigma Upload Suite, klikněte na <strong>Informační lištu</strong> a v rozevírací nabídce zvolte <strong>Instalovat prvek ActiveX</strong> nebo <strong>Spustit prvek ActiveX</strong>.</p><p>Poté buď klikněte na <strong>Spustit</strong>, nebo znovu načtěte stránku a až se zobrazí dialogové okno instalace ovládacího prvku, klepněte na <strong>Instalovat</strong>. Pokud se vám Informační lišta nezobrazuje, zkuste znovu načíst stránku a/nebo zkontrolovat vaše nastavení zabezpečení.</p>",
    "IE8ProgressHtml": "<p>Prosíme počkejte, dokud se ovládací prvek nenačte.</p>",
    "IE8InstructionsHtml": "<p>Chcete-li nainstalovat aplikaci Aurigma Upload Suite, klikněte na <strong>Informační lištu</strong> a v rozevírací nabídce zvolte <strong>Instalovat tento doplněk Add-on</strong> nebo <strong>Spustit doplněk Add-on</strong>.</p><p>Poté buď klikněte na <strong>Spustit</strong>, nebo znovu načtěte stránku a až se zobrazí dialogové okno instalace ovládacího prvku, klepněte na <strong>Instalovat</strong>. Pokud se vám Informační lišta nezobrazuje, zkuste znovu načíst stránku a/nebo zkontrolovat vaše nastavení zabezpečení.</p>",
    "IE9ProgressHtml": "<p>Prosíme počkejte, dokud se ovládací prvek nenačte.</p>",
    "IE9InstructionsHtml": "<p>Chcete-li nainstalovat aplikaci Aurigma Upload Suite, klikněte na <strong>Povolit</strong> nebo <strong>Instalovat</strong> v <strong>Liště s upozorněními</strong> ve spodní části stránky.</p><p>Po opětovném načtení stránky klepněte na <strong>Instalovat</strong> v dialogovém okně instalace ovládacího prvku. Jestliže se vám Lišta s upozorněními nezobrazuje, zkuste znovu načíst stránku a/nebo zkontrolujte svá nastavení zabezpečení (musíte mít povoleny prvky ActiveX).</p>",
    "chromeProgressHtml": "<p>Aurigma Upload Suite requires Java plug-in. Please, click <strong>Run this time</strong> or <strong>Always run on this site</strong> to let Java plug-in run.</p>", // TODO: translate
    "updateInstructions": "Musíte ovládací prvek Aurigma Upload Suite aktualizovat. Až se vám zobrazí dialogové okno instalace ovládacího prvku, klepněte na tlačítko <strong>Instalovat</strong> nebo <strong>Spustit</strong>. Pokud se vám dialogové okno instalace nezobrazuje, zkuste znovu načíst stránku.",
    "macInstallJavaHtml": "<p>Zkontrolujte pomocí funkce <a href=\"http://support.apple.com/kb/HT1338\">Aktualizace softwaru</a> (dostupná v nabídce Apple), zda váš Mac obsahuje tu nejaktuálnější verzi Javy.</p>",
    "installJavaHtml": "<p>Prosíme <a href=\"http://www.java.com/getjava/\">stáhněte</a> a nainstalujte si Javu.</p>",
    "installFlashPlayerHtml": "<p>Ke spuštění aplikace Aurigma Upload Suite musíte mít nainstalovaný program Flash Player. Stáhněte si nejnovější verzi programu pomocí odkazu <a href='http://www.adobe.com/go/getflashplayer' title='Download Flash Player'>zde</a>.</p>"
};
})(window);
