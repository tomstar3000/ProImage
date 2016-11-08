(function(window, undefined) {

// Define global Aurigma.ImageUploader namespace
var AU = (window.Aurigma || (window.Aurigma = { __namespace: true })) &&
    (window.Aurigma.ImageUploader || (window.Aurigma.ImageUploader = { __namespace: true }));

AU.language || (AU.language = { __namespace: true });
AU.ip_language || (AU.ip_language = { __namespace: true });

// Turkish
window.tr_localization = AU.language.tr = {
    "addFilesProgressDialog": {
        "cancelButtonText": "İptal",
        "currentFileText": "Dosya işleniyor: “[name]”",
        "titleText": "Dosyalar yükleme kuyruğuna ekleniyor",
        "totalFilesText": "zaten işlenmiş dosyalar: [count]",
        "waitText": "Lütfen bekleyin, biraz sürebilir..."
    },
    "authenticationDialog": {
        "cancelButtonText": "İptal",
        "loginText": "Kullanıcı Adı:",
        "okButtonText": "Tamam",
        "passwordText": "Parola:",
        "realmText": "Bölge:",
        "text": "Ana bilgisayar [name] yetkilendirme gerektiriyor"
    },
    "contextMenu": {
        "addFilesText": "Dosyaları ekle...",
        "addFolderText": "Klasör ekle...",
        "arrangeByDimensionsText": "Ölçüler",
        "arrangeByModifiedText": "Değiştirildiği tarih",
        "arrangeByNameText": "Ad",
        "arrangeByPathText": "Yol",
        "arrangeBySizeText": "Boyut",
        "arrangeByText": "Sırala",
        "arrangeByTypeText": "Tür",
        "checkAllText": "Tümünü İşaretle",
        "checkText": "İşaretle",
        "detailsViewText": "Ayrıntılar",
        "editText": "Görüntüyü düzenle...",
        "editDescriptionText": "Açıklamayı düzenle...",
        "listViewText": "Liste",
        "navigateToFolderText": "Klasöre git",
        "openText": "Aç",
        "pasteText": "Yapıştır",
        "removeAllText": "Tümünü kaldır",
        "removeText": "Kaldır",
        "thumbnailsViewText": "Küçük resimler",
        "tilesViewText": "Parçalar",
        "uncheckAllText": "Tümünün işaretlerini kaldır",
        "uncheckText": "İşaretini kaldır"
    },
    "deleteFilesDialog": {
        "message": "Yüklenen öğeleri kalıcı olarak silmek istediğinizden emin misiniz?",
        "titleText": "Dosyayı Sil"
    },
    "descriptionEditor": {
        "cancelHyperlinkText": "İptal",
        "orEscLabelText": " (veya Esc)",
        "saveButtonText": "Kaydet"
    },
    "detailsViewColumns": {
        "dimensionsText": "Ölçüler",
        "fileNameText": "Ad",
        "fileSizeText": "Boyut",
        "fileTypeText": "Tür",
        "infoText": "Bilgi",
        "lastModifiedText": "Değiştirildiği tarih"
    },
    "folderPane": {
        "filterHintText": "Ara ",
        "headerText": "<totalLink>Toplam dosya: [totalCount]</totalLink>, <allowedLink>izin verilen: [allowedCount]</allowedLink>"
    },
    "imageEditor": {
        "cancelButtonText": "İptal",
        "cancelCropButtonText": "Kırpmayı iptal et",
        "cropButtonText": "Kırp",
        "descriptionHintText": "Açıklamayı buraya yazın...",
        "rotateButtonText": "Döndür",
        "saveButtonText": "Kaydet ve kapat"
    },
    "messages": {
        "cmykImagesNotAllowed": "CMYK görüntülere izin verilmez.",
        "deletingFilesError": "“[name]” dosyası silinemiyor",
        "dimensionsTooLarge": "“[name]” görüntüsü seçilmek için çok büyük.",
        "dimensionsTooSmall": "“[name]” görüntüsü seçilmek için çok küçük.",
        "fileNameNotAllowed": "“[name]” dosyası seçilemiyor. Bu dosyanın adı kabul edilemez.",
        "fileSizeTooSmall": "“[name]” dosyası seçilemiyor. Bu dosyanın boyutu sınırdan küçük.",
        "filesNotAdded": "[count] dosya kısıtlamalardan dolayı eklenmedi.",
        "maxFileCountExceeded": "“[name]” dosyası seçilemiyor. Dosya miktarı sınırı aşıyor.",
        "maxFileSizeExceeded": "“[name]” dosyası seçilemiyor. Bu dosyanın boyutu sınırı aşıyor.",
        "maxTotalFileSizeExceeded": "“[name]” dosyası seçilemiyor. Toplam karşıya yükleme veri boyutu sınırı aşıyor.",
        "noResponseFromServer": "Sunucudan yanıt yok.",
        "serverError": "Sunucu tarafında bazı hatalar oluştu. Bu mesajı görüyorsanız, Web yöneticiniz ile temasa geçin.",
        "serverNotFound": "Sunucu veya vekil [name] bulunamadı.",
        "unexpectedError": "Görüntü Yükleyicisi bazı sorunlarla karşılaştı. Bu mesajı görüyorsanız, web yöneticiniz ile temasa geçin.",
        "uploadCancelled": "Karşıya yükleme iptal edildi.",
        "uploadCompleted": "Karşıya yükleme tamamlandı.",
        "uploadFailed": "Karşıya yükleme yapılamadı (Bağlantı kesildi)."
    },
    "paneItem": {
        "descriptionEditorIconTooltip": "Açıklamayı düzenle",
        "imageCroppedIconTooltip": "Görüntü kırpılıyor.",
        "imageEditorIconTooltip": "Görüntüyü önizle/düzenle",
        "removalIconTooltip": "Kaldır",
        "rotationIconTooltip": "Döndür"
    },
    "statusPane": {
        "clearAllHyperlinkText": "tümünü sil",
        "filesToUploadText": "Karşıya yüklemek için seçilen dosyalar: [count]",
        "noFilesToUploadText": "Karşıya yüklenecek dosya yok",
        "progressBarText": "Karşıya yükleniyor (%[percents])"
    },
    "treePane": {
        "titleText": "Klasörler",
        "unixFileSystemRootText": "Dosya sistemi",
        "unixHomeDirectoryText": "Ana Klasör"
    },
    "uploadPane": {
        "dropFilesHereMacText": "Add files here...", // TODO: Translate
        "dropFilesHereText": "Dosyaları buraya bırakın…"
    },
    "uploadProgressDialog": {
        "cancelUploadButtonText": "İptal",
        "estimationText": "Tahmini kalan süre: [time]",
        "hideButtonText": "Gizle",
        "hoursText": "saat",
        "infoText": "Karşıya yüklenen dosyalar: [files]/[totalFiles] ([bytes]/[totalBytes])",
        "kilobytesText": "Kb",
        "megabytesText": "Mb",
        "minutesText": "dakika",
        "preparingText": "Karşıya yüklenecek dosyalar hazırlanıyor...",
        "reconnectionText": "Karşıya yüklenemedi. Yeniden bağlanma bekleniyor...",
        "secondsText": "saniye",
        "titleText": "Dosyalar sunucuya yükleniyor"
    },
    "cancelUploadButtonText": "Durdur",
    "loadingFolderContentText": "İçerik yükleniyor...",
    "uploadButtonText": "Karşıya Yükle"
};
// Turkish
AU.ip_language.tr = {
    "commonHtml": "<p>Aurigma Görüntü Yükleyicisi denetimi dosyalarınızı hızlı bir şekilde ve kolayca karşıya yüklemeniz için gereklidir. <strong>Gözat</strong> düğmesiyle birden fazla resmi hantal giriş alanları yerine kullanıcı dostu bir arabirimde seçebilirsiniz.</p>",
    "progressHtml": "<p><img src=\"{0}\" /><br />Aurigma Görüntü Yükleyicisi Yükleniyor...</p>",
    "beforeIE6XPSP2ProgressHtml": "<p>Görüntü Yükleyicisi'ni yüklemek için, lütfen denetim yüklenene kadar bekleyin ve yükleme iletişim kutusunu gördüğünüzde <strong>Evet</strong> düğmesini tıklatın.",
    "beforeIE6XPSP2InstructionsHtml": "<p>Görüntü Yükleyicisi'ni yüklemek için, lütfen sayfayı tekrar yükleyin ve denetim yükleme iletişim kutusunu gördüğünüzde <strong>Evet</strong> düğmesini tıklatın. Yükleme iletişim kutusunu görmezseniz, lütfen güvenlik ayarlarınızı kontrol edin.</p>",
    "IE6XPSP2ProgressHtml": "<p>Lütfen denetim yüklenene kadar bekleyin.</p>",
    "IE6XPSP2InstructionsHtml": "<p>Görüntü Yükleyicisi'ni yüklemek için, lütfen <strong>Bilgi Çubuğu</strong>'nu tıklatın ve açılan menüden <strong>ActiveX Denetimini Yükle</strong>'yi seçin. Sayfa tekrar yüklendikten sonra denetim yükleme iletişim kutusunda <strong>Yükle</strong>'yi tıklatın. Bilgi Çubuğu'nu görmezseniz, lütfen sayfayı tekrar yüklemeyi ve/veya güvenlik ayarlarınızı kontrol etmeyi deneyin.</p>",
    "IE7ProgressHtml": "<p>Lütfen denetim yüklenene kadar bekleyin.</p>",
    "IE7InstructionsHtml": "<p>Görüntü Yükleyicisi'ni yüklemek için, lütfen <strong>Bilgi Çubuğu</strong>'nu tıklatın ve açılan menüden <strong>ActiveX Denetimini Yükle</strong>'yi veya <strong>ActiveX Denetimini Çalıştır</strong>'ı seçin.</p><p>Sonra, <strong>Çalıştır</strong>'ı tıklatın veya denetim yükleme iletişim kutusunu gördüğünüzde sayfa tekrar yüklendikten sonra <strong>Yükle</strong>'yi tıklatın. Bilgi Çubuğu'nu görmezseniz, lütfen sayfayı tekrar yüklemeyi ve/veya güvenlik ayarlarınızı kontrol etmeyi deneyin.</p>",
    "IE8ProgressHtml": "<p>Lütfen denetim yüklenene kadar bekleyin.</p>",
    "IE8InstructionsHtml": "<p>Görüntü Yükleyicisi'ni yüklemek için, lütfen <strong>Bilgi Çubuğu</strong>'nu tıklatın ve açılan menüden <strong>Bu Eklentiyi Yükle</strong>'yi veya <strong>Eklentiyi Çalıştır</strong>'ı seçin.</p><p>Sonra, <strong>Çalıştır</strong>'ı tıklatın veya denetim yükleme iletişim kutusunu gördüğünüzde sayfa tekrar yüklendikten sonra <strong>Yükle</strong>'yi tıklatın. Bilgi Çubuğu'nu görmezseniz, lütfen sayfayı tekrar yüklemeyi ve/veya güvenlik ayarlarınızı kontrol etmeyi deneyin.</p>",
    "IE9ProgressHtml": "<p>Lütfen denetim yüklenene kadar bekleyin.</p>",
    "IE9InstructionsHtml": "<p>Görüntü Yükleyicisi'ni yüklemek için, lütfen sayfanın altındaki <strong>Bildirim Çubuğu</strong>'nda <strong>İzin Ver</strong>'i veya <strong>Yükle</strong>'yi tıklatın.</p><p>Sonra, sayfa tekrar yüklendikten sonra denetim yükleme iletişim kutusunda <strong>Yükle</strong>'yi tıklatın. Bildirim Çubuğu'nu görmezseniz, lütfen sayfanızı tekrar yüklemeyi ve/veya güvenlik ayarlarınızı kontrol etmeyi deneyin (ActiveX denetimleri etkin olmalıdır).</p>",
    "chromeProgressHtml": "<p>Aurigma Upload Suite requires Java plug-in. Please, click <strong>Run this time</strong> or <strong>Always run on this site</strong> to let Java plug-in run.</p>", // TODO: translate
    "updateInstructions": "Görüntü Yükleyicisi denetimini güncellemeniz gerekir. Denetim yükleme iletişim kutusunu gördüğünüzde <strong>Yükle</strong> veya <strong>Çalıştır</strong> düğmesini tıklatın. Yükleme iletişim kutusunu görmezseniz, sayfayı tekrar yüklemeyi deneyin.",
    "macInstallJavaHtml": "<p>Mac'iniz için en güncel Java sürümüne sahip olup olmadığınızı kontrol etmek için <a href=\"http://support.apple.com/kb/HT1338\">Yazılım Güncelleme</a> özelliğini kullanın (Apple menüsünde bulunur).</p>",
    "installJavaHtml": "<p>Lütfen Java'yı <a href=\"http://www.java.com/getjava/\">indirin</a> ve yükleyin.</p>"
};
})(window);
