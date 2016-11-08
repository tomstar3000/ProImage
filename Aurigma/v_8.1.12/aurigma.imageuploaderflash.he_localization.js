(function(window, undefined) {

// Define global Aurigma.ImageUploaderFlash namespace
var AU = (window.Aurigma || (window.Aurigma = { __namespace: true })) &&
    (window.Aurigma.ImageUploaderFlash || (window.Aurigma.ImageUploaderFlash = { __namespace: true }));

AU.language || (AU.language = { __namespace: true });

// Hebrew
window.he_localization = AU.language.he = {
    "locale": "he",
    "addFilesProgressDialog": {
        "text": "הוספת קבצים לרשימת ההעלאות..."
    },
    "commonDialog": {
        "cancelButtonText": "ביטול",
        "okButtonText": "אישור"
    },
    "descriptionEditor": {
        "cancelButtonText": "ביטול",
        "saveButtonText": "שמירה"
    },
    "imagePreviewWindow": {
        "closePreviewTooltip": "לחץ לסגירה"
    },
    "messages": {
        "cannotReadFile": "לא ניתן לקרוא את הקובץ: {0}.",
        "dimensionsTooLarge": "ממדיו של \"{0}\" גדולים מדי, הקובץ לא נוסף. ממדי התמונה המרביים המותרים הם {1} x {2} פיקסלים.",
        "dimensionsTooSmall": "ממדיו של \"{0}\" קטנים מדי, הקובץ לא נוסף. ממדי התמונה המזעריים המותרים הם {1} x {2} פיקסלים.",
        "fileNameNotAllowed": "לא ניתן לבחור את הקובץ \"{0}\". שם הקובץ איננו קביל.",
		"fileSizeTooSmall": "גודלו של \"{0}\" קטן מדי, ולא ניתן להוסיף אותו. הגודל המזערי המותר הוא {1}.",
        "filesNotAdded": "{0} קבצים לא נוספו בגלל הגבלות האתר.",
        "maxFileCountExceeded": "לא כל הקבצים נוספו. מותר לך להעלות {0} קבצים לכל היותר.",
        "maxFileSizeExceeded": "גודלו של \"{0}\" גדול מדי, ולא ניתן להוסיף אותו. הגודל המרבי המותר הוא {1}.",
        "maxTotalFileSizeExceeded": "לא כל הקבצים נוספו. נפח הקבצים הכולל חורג מהגודל המרבי המותר ({0}).",
        "previewNotAvailable": "תצוגה מקדימה אינה זמינה",
        "tooFewFiles": "יש לבחור לפחות {0} קבצים כדי להתחיל העלאה."
    },
    "paneItem": {
        "descriptionEditorIconTooltip": "ערוך תיאור",
        "imageTooltip": "{0}\n{1}, {3}, \nהשתנה: {2}",
        "itemTooltip": "{0}\n{1}, \nהשתנה: {2}",
        "removalIconTooltip": "הסר",
        "rotationIconTooltip": "סובב"
    },
    "statusPane": {
        "dataUploadedText": "נתונים שהועלו: {0} / {1}",
        "filesPreparedText": "קבצים שהוכנו: {0} / {1}",
        "filesToUploadText": "<font color=\"#7a7a7a\">סה\"כ קבצים:</font> {0}",
        "filesUploadedText": "קבצים שהושלמו: {0} / {1}",
        "noFilesToUploadText": "<font color=\"#7a7a7a\">אין קבצים להעלאה</font>",
        "preparingText": "הכנה...",
        "sendingText": "העלאה..."
    },
    "topPane": {
        "addFilesHyperlinkText": "הוסף עוד קבצים",
        "clearAllHyperlinkText": "הסר את כל הקבצים",
        "orText": "או",
        "titleText": "קבצים להעלאה",
        "viewComboBox": ["אריחים", "תמונות ממוזערות", "סמלים"],
        "viewComboBoxText": "שנה תצוגה:"
    },
    "uploadErrorDialog": {
        "hideDetailsButtonText": "הסתר פרטים",
        "message": "לא כל הקבצים הועלו בהצלחה. אם אתה רואה הודעה זו, צור קשר עם מנהל האתר.",
        "showDetailsButtonText": "הצג פרטים",
        "title": "שגיאת העלאה "
    },
    "uploadPane": {
        "addFilesButtonText": "+ הוסף עוד קבצים"
    },
    "cancelUploadButtonText": "ביטול",
    "uploadButtonText": "העלה"
};
})(window);
