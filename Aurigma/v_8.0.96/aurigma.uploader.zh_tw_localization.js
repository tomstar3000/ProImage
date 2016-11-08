(function(window, undefined) {

// Define global Aurigma.ImageUploader namespace
var AU = (window.Aurigma || (window.Aurigma = { __namespace: true })) &&
    (window.Aurigma.ImageUploader || (window.Aurigma.ImageUploader = { __namespace: true }));

AU.language || (AU.language = { __namespace: true });
AU.ip_language || (AU.ip_language = { __namespace: true });

// Traditional Chinese
window.zh_tw_localization = AU.language.zh_tw = {
    "addFilesProgressDialog": {
        "cancelButtonText": "取消",
        "currentFileText": "正在處理檔案： 「[name]」",
        "titleText": "新增檔案至上傳佇列",
        "totalFilesText": "已處理的檔案： [count]",
        "waitText": "請稍候，處理可能需要一些時間…"
    },
    "authenticationDialog": {
        "cancelButtonText": "取消",
        "loginText": "登入：",
        "okButtonText": "確定",
        "passwordText": "密碼：",
        "realmText": "範圍：",
        "text": "主機 [name] 需要驗證"
    },
    "contextMenu": {
        "addFilesText": "新增檔案...",
        "addFolderText": "新增資料夾...",
        "arrangeByDimensionsText": "尺寸",
        "arrangeByModifiedText": "修改日期",
        "arrangeByNameText": "名稱",
        "arrangeByPathText": "路徑",
        "arrangeBySizeText": "大小",
        "arrangeByText": "排序方式",
        "arrangeByTypeText": "類型",
        "checkAllText": "全選",
        "checkText": "勾選",
        "detailsViewText": "詳細資訊",
        "editText": "編輯影像…",
        "editDescriptionText": "編輯說明…",
        "listViewText": "清單",
        "navigateToFolderText": "導覽至資料夾",
        "openText": "開啟",
        "pasteText": "貼上",
        "removeAllText": "全部移除",
        "removeText": "移除",
        "thumbnailsViewText": "縮圖",
        "tilesViewText": "塊式並列影像",
        "uncheckAllText": "取消全選",
        "uncheckText": "取消勾選"
    },
    "deleteFilesDialog": {
        "message": "確定要永遠刪除上傳的項目嗎？",
        "titleText": "刪除檔案"
    },
    "descriptionEditor": {
        "cancelHyperlinkText": "取消",
        "orEscLabelText": " （或 Esc）",
        "saveButtonText": "儲存"
    },
    "detailsViewColumns": {
        "dimensionsText": "尺寸",
        "fileNameText": "名稱",
        "fileSizeText": "大小",
        "fileTypeText": "類型",
        "infoText": "資訊",
        "lastModifiedText": "修改日期"
    },
    "folderPane": {
        "filterHintText": "搜尋 ",
        "headerText": "<totalLink>檔案總計： [totalCount]</totalLink>, <allowedLink>允許： [allowedCount]</allowedLink>"
    },
    "imageEditor": {
        "cancelButtonText": "取消",
        "cancelCropButtonText": "取消裁剪",
        "cropButtonText": "裁剪",
        "descriptionHintText": "在此鍵入說明…",
        "rotateButtonText": "旋轉",
        "saveButtonText": "儲存並關閉"
    },
    "messages": {
        "cmykImagesNotAllowed": "不允許 CMYK 影像。",
        "deletingFilesError": "無法刪除檔案「[name]」",
        "dimensionsTooLarge": "影像「[name]」太大無法選擇。",
        "dimensionsTooSmall": "影像「[name]」太小無法選擇。",
        "fileNameNotAllowed": "無法選擇檔案「[name]」。 此檔案有不允許的名稱。",
        "fileSizeTooSmall": "無法選擇檔案「[name]」。 此檔案大小小於限制。",
        "filesNotAdded": "由於限制無法新增 [count] 個檔案。",
        "maxFileCountExceeded": "無法選擇檔案「[name]」。 檔案的數量超過限制。",
        "maxFileSizeExceeded": "無法選擇檔案「[name]」。 檔案的大小超過限制。",
        "maxTotalFileSizeExceeded": "無法選擇檔案「[name]」。 上傳資料大小總計超過限制。",
        "noResponseFromServer": "伺服器無回應。",
        "serverError": "發生一些伺服器端錯誤。 如果您看到此訊息，請與網站管理員聯絡。",
        "serverNotFound": "找不到伺服器或 proxy [name]。",
        "unexpectedError": "影像上傳程式遇到一些問題。 如果您看到此訊息，請與網站管理員聯絡。",
        "uploadCancelled": "取消上傳。",
        "uploadCompleted": "完成上傳。",
        "uploadFailed": "上傳失敗（連線中斷）。"
    },
    "paneItem": {
        "descriptionEditorIconTooltip": "編輯說明",
        "imageCroppedIconTooltip": "影像被裁剪。",
        "imageEditorIconTooltip": "預覽 / 編輯影像",
        "removalIconTooltip": "移除",
        "rotationIconTooltip": "旋轉"
    },
    "statusPane": {
        "clearAllHyperlinkText": "全部清除",
        "filesToUploadText": "上傳選取的檔案： [count]",
        "noFilesToUploadText": "沒有檔案上傳",
        "progressBarText": "上傳（[percents]%）"
    },
    "treePane": {
        "titleText": "資料夾",
        "unixFileSystemRootText": "檔案系統",
        "unixHomeDirectoryText": "起始檔案夾"
    },
    "uploadPane": {
        "dropFilesHereMacText": "Add files here...", // TODO: Translate
        "dropFilesHereText": "在此放下檔案…"
    },
    "uploadProgressDialog": {
        "cancelUploadButtonText": "取消",
        "estimationText": "預估剩餘時間： [time]",
        "hideButtonText": "隱藏",
        "hoursText": "小時",
        "infoText": "上傳的檔案: [files]/[totalFiles]（[bytes] 之 [totalBytes]）",
        "kilobytesText": "Kb",
        "megabytesText": "Mb",
        "minutesText": "分鐘",
        "preparingText": "正在準備要上傳的檔案...",
        "reconnectionText": "上傳失敗。 正在等待重新連接...",
        "secondsText": "秒",
        "titleText": "上傳檔案到伺服器"
    },
    "cancelUploadButtonText": "停止",
    "loadingFolderContentText": "載入內容…",
    "uploadButtonText": "上傳"
};
// Traditional Chinese
AU.ip_language.zh_tw = {
    "commonHtml": "<p>Aurigma Upload Suite 控件是輕鬆快捷上傳檔案所必需的。 您將能夠在方便使用者使用的介面中選取多個影像，而不是使用<strong>瀏覽</strong>按鈕笨拙地輸入各欄位。</p>",
    "progressHtml": "<p><img src=\"{0}\" /><br />正在載入 Aurigma Upload Suite...</p>",
    "beforeIE6XPSP2ProgressHtml": "<p>若要安裝影像上傳程式，請等待到載入控件，並在看到安裝對話方塊時按一下<strong>是</strong>按鈕。</p>",
    "beforeIE6XPSP2InstructionsHtml": "<p>若要安裝影像上傳程式，請重新載入該頁面，並在看到控件安裝對話方塊時按一下<strong>是</strong>按鈕。 如果您看不到控件安裝對話方塊，請檢查安全設定。</p>",
    "IE6XPSP2ProgressHtml": "<p>請等待到載入控件。</p>",
    "IE6XPSP2InstructionsHtml": "<p>若要安裝影像上傳程式，請按一下<strong>資訊列</strong>，然後從下拉式功能表中選取<strong>安裝 ActiveX 控件</strong>。 重新載入頁面之後，在看到控件安裝對話方塊時按一下<strong>安裝</strong>。 如果您看不到「資訊列」，請嘗試重新載入頁面及/或檢查安全設定。</p>",
    "IE7ProgressHtml": "<p>請等待到載入控件。</p>",
    "IE7InstructionsHtml": "<p>若要安裝影像上傳程式，請按一下<strong>資訊列</strong>，然後從下拉式功能表中選取<strong>安裝 ActiveX 控件</strong>或<strong>執行 ActiveX 控件</strong>。</p><p>然後按一下<strong>執行</strong>，或在重新載入頁面後，在看到控件安裝對話方塊時按一下<strong>安裝</strong>。 如果您看不到「資訊列」，請嘗試重新載入頁面及/或檢查安全設定。</p>",
    "IE8ProgressHtml": "<p>請等待到載入控件。</p>",
    "IE8InstructionsHtml": "<p>若要安裝影像上傳程式，請按一下<strong>資訊列</strong>，然後從下拉式功能表中選取<strong>安裝此附加元件</strong>或<strong>執行附加元件</strong>。</p><p>然後按一下<strong>執行</strong>，或在重新載入頁面後，在看到控件安裝對話方塊時按一下<strong>安裝</strong>。 如果您看不到「資訊列」，請嘗試重新載入頁面及/或檢查安全設定。</p>",
    "IE9ProgressHtml": "<p>請等待到載入控件。</p>",
    "IE9InstructionsHtml": "<p>若要安裝影像上傳程式，請按一下頁面底端<strong>通知列</strong>上的<strong>允許</strong>或<strong>安裝</strong>。</p><p>然後在重新載入頁面後，在看到控件安裝對話方塊時按一下<strong>安裝</strong>。 如果您看不到「通知列」，請嘗試重新載入頁面及/或檢查安全設定 (應該啟用 ActiveX 控件)。</p>",
    "chromeProgressHtml": "<p>Aurigma Upload Suite requires Java plug-in. Please, click <strong>Run this time</strong> or <strong>Always run on this site</strong> to let Java plug-in run.</p>", // TODO: translate
    "updateInstructions": "您需要更新影像更新程式控件。 在看到控件安裝對話方塊時按一下<strong>安裝</strong>或<strong>執行</strong>按鈕。 如果您看不到安裝對話方塊，請嘗試重新載入頁面。",
    "macInstallJavaHtml": "<p>使用<a href=\"http://support.apple.com/kb/HT1338\">軟體更新</a>特性 (在 Apple 功能表上提供) 來檢查您是否具有適用於您的 Mac 的 Java 的最新版本。</p>",
    "installJavaHtml": "<p>請<a href=\"http://www.java.com/getjava/\">下載</a>並安裝 Java。</p>"
};
})(window);
