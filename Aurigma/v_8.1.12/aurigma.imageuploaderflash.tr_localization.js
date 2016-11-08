(function(window, undefined) {

// Define global Aurigma.ImageUploaderFlash namespace
var AU = (window.Aurigma || (window.Aurigma = { __namespace: true })) &&
    (window.Aurigma.ImageUploaderFlash || (window.Aurigma.ImageUploaderFlash = { __namespace: true }));

AU.language || (AU.language = { __namespace: true });

// Turkish
window.tr_localization = AU.language.tr = {
    "addFilesProgressDialog": {
        "text": "Dosyalar karşıya yükleme listesine ekleniyor..."
    },
    "commonDialog": {
        "cancelButtonText": "İptal",
        "okButtonText": "Tamam"
    },
    "descriptionEditor": {
        "cancelButtonText": "İptal",
        "saveButtonText": "Kaydet"
    },
    "imagePreviewWindow": {
        "closePreviewTooltip": "Kapatmak için tıklatın"
    },
    "messages": {
        "cannotReadFile": "Dosya okunamıyor: {0}.",
        "dimensionsTooLarge": "“{0}” boyutları çok büyük, dosya eklenmedi. İzin verilen maksimum resim boyutları {1} x {2} piksel.",
        "dimensionsTooSmall": "“{0}” boyutları çok küçük, dosya eklenmedi. İzin verilen minimum resim boyutları {1} x {2} piksel.",
        "fileNameNotAllowed": "“{0}” dosyası seçilemiyor. Bu dosyanın adı kabul edilemez.",
		"fileSizeTooSmall": "“{0}” boyutu eklemek için çok küçük. İzin verilen minimum boyut {1}.",
        "filesNotAdded": "Sitenin kısıtlamaları nedeniyle {0} dosya eklenmedi.",
        "maxFileCountExceeded": "Tüm dosyalar eklenmedi. {0} dosyadan fazlasını karşıya yükleme izniniz yok.",
        "maxFileSizeExceeded": "“{0}” boyutu eklemek için çok büyük. İzin verilen maksimum boyut {1}.",
        "maxTotalFileSizeExceeded": "Tüm dosyalar eklenmedi. Maksimum toplam dosya boyutu olan ({0}) aşıldı.",
        "previewNotAvailable": "Önizleme kullanılamıyor",
        "tooFewFiles": "Karşıya yüklemeyi başlatmak için en az {0} dosyanın seçilmesi gerekir."
    },
    "paneItem": {
        "descriptionEditorIconTooltip": "Açıklamayı düzenle",
        "imageTooltip": "{0}\n{1}, {3}, \nDeğiştirildi: {2}",
        "itemTooltip": "{0}\n{1}, \nDeğiştirildi: {2}",
        "removalIconTooltip": "Kaldır",
        "rotationIconTooltip": "Döndür"
    },
    "statusPane": {
        "dataUploadedText": "Veri karşıya yüklendi: {0} / {1}",
        "filesPreparedText": "Dosyalar hazırlandı: {0} / {1}",
        "filesToUploadText": "<font color=\"#7a7a7a\">Toplam dosya:</font> {0}",
        "filesUploadedText": "Dosyalar tamamlandı: {0} / {1}",
        "noFilesToUploadText": "<font color=\"#7a7a7a\">Karşıya yüklenecek dosya yok</font>",
        "preparingText": "HAZIRLANIYOR...",
        "sendingText": "KARŞIYA YÜKLENİYOR..."
    },
    "topPane": {
        "addFilesHyperlinkText": "Başka dosya ekle",
        "clearAllHyperlinkText": "tüm dosyaları kaldır",
        "orText": "veya",
        "titleText": "Karşıya yüklenecek dosyalar",
        "viewComboBox": ["Parçalar", "Küçük resimler", "Simgeler"],
        "viewComboBoxText": "Görünümü değiştir:"
    },
    "uploadErrorDialog": {
        "hideDetailsButtonText": "Ayrıntıları Gizle",
        "message": "Tüm dosyalar başarıyla karşıya yüklenemedi. Bu mesajı görüyorsanız, web yöneticisi ile temasa geçin.",
        "showDetailsButtonText": "Ayrıntıları Göster",
        "title": "Karşıya Yükleme Hatası "
    },
    "uploadPane": {
        "addFilesButtonText": "+ Başka dosya ekle"
    },
    "cancelUploadButtonText": "İptal",
    "uploadButtonText": "Karşıya Yükle"
};
})(window);
