(function(window, undefined) {

// Define global Aurigma.ImageUploader namespace
var AU = (window.Aurigma || (window.Aurigma = { __namespace: true })) &&
    (window.Aurigma.ImageUploader || (window.Aurigma.ImageUploader = { __namespace: true }));

AU.language || (AU.language = { __namespace: true });
AU.ip_language || (AU.ip_language = { __namespace: true });

// Hebrew
window.he_localization = AU.language.he = {
    "addFilesProgressDialog": {
        "cancelButtonText": "ביטול",
        "currentFileText": "עיבוד הקובץ: \"[name]\"",
        "titleText": "הוספת קבצים לתור ההעלאות",
        "totalFilesText": "קבצים שכבר עובדו: [count]",
        "waitText": "המתן, פעולה זו עשויה להימשך זמן מה..."
    },
    "authenticationDialog": {
        "cancelButtonText": "ביטול",
        "loginText": "כניסה:",
        "okButtonText": "אישור",
        "passwordText": "סיסמה:",
        "realmText": "תחום:",
        "text": "המחשב המארח [name] דורש אימות"
    },
    "contextMenu": {
        "addFilesText": "הוסף קבצים...",
        "addFolderText": "הוסף תיקיה...",
        "arrangeByDimensionsText": "ממדים",
        "arrangeByModifiedText": "תאריך שינוי",
        "arrangeByNameText": "שם",
        "arrangeByPathText": "נתיב",
        "arrangeBySizeText": "גודל",
        "arrangeByText": "מיין לפי",
        "arrangeByTypeText": "סוג",
        "checkAllText": "סמן הכל",
        "checkText": "סמן את תיבת הסימון",
        "detailsViewText": "פרטים",
        "editDescriptionText": "ערוך תיאור...",
        "editText": "ערוך תמונה...",
        "listViewText": "רשימה",
        "navigateToFolderText": "נווט אל התיקיה",
        "openText": "פתח",
        "pasteText": "הדבק",
        "removeAllText": "הסר הכול",
        "removeText": "הסר",
        "thumbnailsViewText": "תמונות ממוזערות",
        "tilesViewText": "אריחים",
        "uncheckAllText": "מחק את כל הסימונים",
        "uncheckText": "מחק סימון מתיבת הסימון"
    },
    "deleteFilesDialog": {
        "message": "האם אתה בטוח שאתה רוצה למחוק לתמיד את התמונות שהועלו?",
        "titleText": "מחק קובץ"
    },
    "descriptionEditor": {
        "cancelHyperlinkText": "ביטול",
        "orEscLabelText": " (או Esc)",
        "saveButtonText": "שמירה"
    },
    "detailsViewColumns": {
        "dimensionsText": "ממדים",
        "fileNameText": "שם",
        "fileSizeText": "גודל",
        "fileTypeText": "סוג",
        "infoText": "מידע",
        "lastModifiedText": "תאריך שינוי"
    },
    "folderPane": {
        "filterHintText": "חפש ",
        "headerText": "סה\"כ קבצים: [totalCount], מותרים: [allowedCount]"
    },
    "imageEditor": {
        "cancelButtonText": "ביטול",
        "cancelCropButtonText": "בטל חיתוך",
        "cropButtonText": "חתוך",
        "descriptionHintText": "הקלד תיאור כאן...",
        "rotateButtonText": "סובב",
        "saveButtonText": "שמור וסגור"
    },
    "messages": {
        "cmykImagesNotAllowed": "תמונות CMYK אסורות.",
        "deletingFilesError": "לא ניתן למחוק את הקובץ \"[name]\".",
        "dimensionsTooLarge": "התמונה \"[name]\" גדולה מדי ולא ניתן לבחור אותה.",
        "dimensionsTooSmall": "התמונה \"[name]\" קטנה מדי ולא ניתן לבחור אותה.",
        "fileNameNotAllowed": "לא ניתן לבחור את הקובץ \"[name]\". שם הקובץ איננו קביל.",
        "fileSizeTooSmall": "לא ניתן לבחור את הקובץ \"[name]\". גודל הקובץ הזה קטן יותר מהמגבלה.",
        "filesNotAdded": "[count] קבצים לא נוספו בגלל ההגבלות.",
        "maxFileCountExceeded": "לא ניתן לבחור את הקובץ \"[name]\". מספר הקבצים חורג מהמגבלה.",
        "maxFileSizeExceeded": "לא ניתן לבחור את הקובץ \"[name]\". גודל הקובץ חורג מהמגבלה.",
        "maxTotalFileSizeExceeded": "לא ניתן לבחור את הקובץ \"[name]\". נפח הנתונים הכולל חורג מהמגבלה.",
        "noResponseFromServer": "אין תגובה מהשרת.",
        "serverError": "אירעה שגיאה בצד השרת. אם אתה רואה הודעה זו, צור קשר עם מנהל האתר.",
        "serverNotFound": "לא ניתן למצוא את השרת או את ה-Proxy [name].",
        "unexpectedError": "Aurigma Upload Suite נתקל בבעיה. אם אתה רואה הודעה זו, צור קשר עם מנהל האתר.",
        "uploadCancelled": "ההעלאה בוטלה.",
        "uploadCompleted": "ההעלאה הושלמה.",
        "uploadFailed": "ההעלאה נכשלה (החיבור נותק)."
    },
    "paneItem": {
        "descriptionEditorIconTooltip": "ערוך תיאור",
        "imageCroppedIconTooltip": "התמונה נחתכה.",
        "imageEditorIconTooltip": "תצוגה מקדימה / ערוך תמונה",
        "removalIconTooltip": "הסר",
        "rotationIconTooltip": "סובב"
    },
    "statusPane": {
        "clearAllHyperlinkText": "נקה הכול",
        "filesToUploadText": "קבצים שנבחרו להעלאה: [count]",
        "noFilesToUploadText": "אין קבצים להעלאה",
        "progressBarText": "העלאה ([percents]%)"
    },
    "treePane": {
        "titleText": "תיקיות",
        "unixFileSystemRootText": "מערכת הקבצים",
        "unixHomeDirectoryText": "תיקיה ראשית"
    },
    "uploadPane": {
        "dropFilesHereMacText": "Add files here...", // TODO: Translate
        "dropFilesHereText": "שחרר את הקבצים כאן..."
    },
    "uploadProgressDialog": {
        "cancelUploadButtonText": "ביטול",
        "estimationText": "הערכת הזמן שנותר: [time]",
        "hideButtonText": "הסתר",
        "hoursText": "שעות",
        "infoText": "קבצים שהועלו: [files]/[totalFiles] ([bytes] מתוך [totalBytes])",
        "kilobytesText": "KB",
        "megabytesText": "MB",
        "minutesText": "דקות",
        "preparingText": "הכנת הקבצים להעלאה...",
        "reconnectionText": "ההעלאה נכשלה. המתנה להתחברות מחדש...",
        "secondsText": "שניות",
        "titleText": "העלאת קבצים לשרת"
    },
    "cancelUploadButtonText": "ביטול",
    "loadingFolderContentText": "טעינת תוכן... ",
    "uploadButtonText": "העלה"
};
// Hebrew
AU.ip_language.he = {
    "commonHtml": "<p>הפקד Aurigma Upload Suite דרוש לצורך העלאת הקבצים שלך במהירות ובקלות. תוכל לבחור תמונות מרובות בממשק ידידותי למשתמש ולא באמצעות שדות הקלט המסורבלים של הלחצן <strong>עיון</strong>.</p>",
    "progressHtml": "<p><img src=\"{0}\" /><br />טעינת Aurigma Upload Suite...</p>",
    "beforeIE6XPSP2ProgressHtml": "<p>להתקנת Aurigma Upload Suite, המתן עד שהפקד ייטען ולחץ על הלחצן<strong>כן</strong> כשתוצג תיבת הדו-שיח של ההתקנה.</p>",
    "beforeIE6XPSP2InstructionsHtml": "<p>להתקנת Aurigma Upload Suite, טען מחדש את הדף ולחץ על הלחצן <strong>כן</strong> כשתוצג תיבת הדו-שיח של התקנת הפקד. אם אינך רואה את תיבת הדו-שיח של ההתקנה, בדוק את הגדרות האבטחה שלך.</p>",
    "IE6XPSP2ProgressHtml": "<p>המתן עד שהפקד ייטען.</p>",
    "IE6XPSP2InstructionsHtml": "<p>להתקנת Aurigma Upload Suite, לחץ על <strong>סרגל המידע</strong> ובחר <strong>התקן פקד ActiveX</strong> בתפריט הנפתח. לאחר טעינת הדף מחדש לחץ על <strong>התקן</strong> כשתוצג תיבת הדו-שיח של התקנת הפקד. אם אינך רואה את סרגל המידע, נסה לטעון מחדש את הדף ו/או לבדוק את הגדרות האבטחה.</p>",
    "IE7ProgressHtml": "<p>המתן עד שהפקד ייטען.</p>",
    "IE7InstructionsHtml": "<p>להתקנת Aurigma Upload Suite, לחץ על <strong>סרגל המידע</strong> ובחר <strong>התקן פקד ActiveX</strong> או <strong>הפעל פקד ActiveX</strong> בתפריט הנפתח.</p><p>לאחר טעינת הדף מחדש לחץ על <strong>הפעל</strong> או על <strong>התקן</strong> כשתוצג תיבת הדו-שיח של התקנת הפקד. אם אינך רואה את סרגל המידע, נסה לטעון מחדש את הדף ו/או לבדוק את הגדרות האבטחה.</p>",
    "IE8ProgressHtml": "<p>המתן עד שהפקד ייטען.</p>",
    "IE8InstructionsHtml": "<p>להתקנת Aurigma Upload Suite, לחץ על <strong>סרגל המידע</strong> ובחר <strong>התקן הרחבה זו</strong> או <strong>הפעל הרחבה ActiveX</strong> בתפריט הנפתח.</p><p>לאחר מכן לחץ על <strong>הפעל</strong> או על <strong>התקן</strong> לאחר טעינת הדף מחדש כשתוצג תיבת הדו-שיח של התקנת הפקד. אם אינך רואה את סרגל המידע, נסה לטעון מחדש את הדף ו/או לבדוק את הגדרות האבטחה.</p>",
    "IE9ProgressHtml": "<p>המתן עד שהפקד ייטען.</p>",
    "IE9InstructionsHtml": "<p>להתקנת Aurigma Upload Suite, לחץ על <strong>אפשר</strong> או על <strong>התקן</strong> ב<strong>סרגל ההודעות</strong> בתחתית הדף.</p><p>לאחר טעינת הדף מחדש לחץ על <strong>התקן</strong> בתיבת הדו-שיח של התקנת הפקד. אם אינך רואה את סרגל ההודעות, נסה לטעון מחדש את הדף ו/או בדוק את הגדרות האבטחה (יש לאפשר פקדי ActiveX).</p>",
    "chromeProgressHtml": "<p>Aurigma Upload Suite requires Java plug-in. Please, click <strong>Run this time</strong> or <strong>Always run on this site</strong> to let Java plug-in run.</p>", // TODO: translate
    "updateInstructions": "עליך לעדכן את הפקד של Aurigma Upload Suite. לחץ על <strong>התקן</strong> או על <strong>הפעל</strong> כשתוצג תיבת הדו-שיח של התקנת הפקד. אם אינך רואה את תיבת הדו-שיח של ההתקנה, נסה לטעון מחדש את הדף.",
    "macInstallJavaHtml": "<p>השתמש בתכונה <a href=\"http://support.apple.com/kb/HT1338\">עדכון תוכנה</a> (הזמינה בתפריט Apple) כדי לוודא שנמצאת בידיך גירסת Java העדכנית ביותר ל-Mac שלך.</p>",
    "installJavaHtml": "<p><a href=\"http://www.java.com/getjava/\">הורד</a> and והתקן Java.</p>"
};
})(window);
