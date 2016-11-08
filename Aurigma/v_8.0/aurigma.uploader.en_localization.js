(function(window, undefined) {

// Define global Aurigma.ImageUploader namespace
var AU = (window.Aurigma || (window.Aurigma = { __namespace: true })) &&
    (window.Aurigma.ImageUploader || (window.Aurigma.ImageUploader = { __namespace: true }));

AU.language || (AU.language = { __namespace: true });
AU.ip_language || (AU.ip_language = { __namespace: true });

// English
window.en_localization = AU.language.en = {
    addFilesProgressDialog: {
        cancelButtonText: "Cancel",
        currentFileText: "Processing file: '[name]'",
        titleText: "Adding files to upload queue",
        totalFilesText: "already processed files: [count]",
        waitText: "Please wait, it may take some time..."
    },
    authenticationDialog: {
        cancelButtonText: "Cancel",
        loginText: "Login:",
        okButtonText: "Ok",
        passwordText: "Password:",
        realmText: "Realm:",
        text: "Host [name] requires authentication"
    },
    contextMenu: {
        addFilesText: "Add files...",
        addFolderText: "Add folder...",
        arrangeByDimensionsText: "Dimensions",
        arrangeByModifiedText: "Date modified",
        arrangeByNameText: "Name",
        arrangeByPathText: "Path",
        arrangeBySizeText: "Size",
        arrangeByText: "Sort by",
        arrangeByTypeText: "Type",
        checkAllText: "Check All",
        checkText: "Check",
        detailsViewText: "Details",
        editDescriptionText: "Edit description...",
        editText: "Edit image...",
        listViewText: "List",
        navigateToFolderText: "Navigate to folder",
        openText: "Open",
        pasteText: "Paste",
        removeAllText: "Remove all",
        removeText: "Remove",
        thumbnailsViewText: "Thumbnails",
        tilesViewText: "Tiles",
        uncheckAllText: "Uncheck all",
        uncheckText: "Uncheck"
    },
    deleteFilesDialog: {
        message: "Are you sure you want to permanently delete uploaded items?",
        titleText: "Delete File"
    },
    descriptionEditor: {
        cancelHyperlinkText: "Cancel",
        orEscLabelText: " (or Esc)",
        saveButtonText: "Save"
    },
    detailsViewColumns: {
        dimensionsText: "Dimensions",
        fileNameText: "Name",
        fileSizeText: "Size",
        fileTypeText: "Type",
        infoText: "Info",
        lastModifiedText: "Date modified"
    },
    folderPane: {
        filterHintText: "Search ",
        headerText: "<allowedLink>Allowed [allowedCount]</allowedLink>, <totalLink>total [totalCount]</totalLink>"
    },
    imageEditor: {
        cancelButtonText: "Cancel",
        cancelCropButtonText: "Cancel crop",
        cropButtonText: "Crop",
        descriptionHintText: "Type description here...",
        rotateButtonText: "Rotate",
        saveButtonText: "Save and close"
    },
    messages: {
        cmykImagesNotAllowed: "CMYK images are not allowed.",
        deletingFilesError: "The file '[name]' cannot be deleted",
        dimensionsTooLarge: "The image '[name]' is too large to be selected.",
        dimensionsTooSmall: "The image '[name]' is too small to be selected.",
        fileNameNotAllowed: "The file '[name]' cannot be selected. This file has inadmissible name.",
        fileSizeTooSmall: "The file '[name]' cannot be selected. This file size is smaller than the limit.",
        filesNotAdded: "[name] files were not added due to restrictions.",
        maxFileCountExceeded: "The file '[name]' cannot be selected. Amount of files exceeds the limit.",
        maxFileSizeExceeded: "The file '[name]' cannot be selected. This file size exceeds the limit.",
        maxTotalFileSizeExceeded: "The file '[name]' cannot be selected. Total upload data size exceeds the limit.",
        noResponseFromServer: "No response from server.",
        serverError: "Some server-side error occurred. If you see this message, contact your Web master.",
        serverNotFound: "The server or proxy [name] cannot be found.",
        unexpectedError: "Aurigma Upload Suite encountered some problem. If you see this message, contact web master.",
        uploadCancelled: "Upload is cancelled.",
        uploadCompleted: "Upload complete.",
        uploadFailed: "Upload failed (the connection was interrupted)."
    },
    paneItem: {
        descriptionEditorIconTooltip: "Edit description",
        imageCroppedIconTooltip: "The image is cropped.",
        imageEditorIconTooltip: "Preview / edit image",
        removalIconTooltip: "Remove",
        rotationIconTooltip: "Rotate"
    },
    statusPane: {
        clearAllHyperlinkText: "clear all",
        filesToUploadText: "Files selected to upload: [count]",
        noFilesToUploadText: "No files to upload",
        progressBarText: "Uploading ([percents]%)"
    },
    treePane: {
        titleText: "Folders",
        unixFileSystemRootText: "Filesystem",
        unixHomeDirectoryText: "Home Folder"
    },
    uploadPane: {
        dropFilesHereMacText: "Add files here...",
        dropFilesHereText: "Drop files here..."
    },
    uploadProgressDialog: {
        cancelUploadButtonText: "Cancel",
        estimationText: "Estimated remaining time: [time]",
        hideButtonText: "Hide",
        hoursText: "hours",
        infoText: "Files uploaded: [files]/[totalFiles] ([bytes] of [totalBytes])",
        kilobytesText: "Kb",
        megabytesText: "Mb",
        minutesText: "minutes",
        preparingText: "Preparing files for upload...",
        "reconnectionText": "Upload failed. Waiting to reconnect...",
        secondsText: "seconds",
        titleText: "Uploading files to server"
    },
    cancelUploadButtonText: "Stop",
    loadingFolderContentText: "Loading content...",
    uploadButtonText: "Upload"
};
// English
AU.ip_language.en = {
    "commonHtml": "<p>Aurigma Upload Suite control is necessary to upload your files quickly and easily. You will be able to select multiple images in user-friendly interface instead of clumsy input fields with <strong>Browse</strong> button.</p>",
    "progressHtml": "<p><img src=\"{0}\" /><br />Loading Aurigma Upload Suite...</p>",
    "flashProgressHtml": "<p><img src=\"{0}\" /><br />Loading Aurigma Upload Suite...</p>",
    "beforeIE6XPSP2ProgressHtml": "<p>To install Aurigma Upload Suite, please wait until the control is loaded and click the <strong>Yes</strong> button when you see the installation dialog.</p>",
    "beforeIE6XPSP2InstructionsHtml": "<p>To install Aurigma Upload Suite, please reload the page and click the <strong>Yes</strong> button when you see the control installation dialog. If you don't see installation dialog, please check your security settings.</p>",
    "IE6XPSP2ProgressHtml": "<p>Please wait until the control is loaded.</p>",
    "IE6XPSP2InstructionsHtml": "<p>To install Aurigma Upload Suite, please click on the <strong>Information Bar</strong> and select <strong>Install ActiveX Control</strong> from the dropdown menu. After the page is reloaded click <strong>Install</strong> when you see the control installation dialog. If you don't see Information Bar, please try to reload the page and/or check your security settings.</p>",
    "IE7ProgressHtml": "<p>Please wait until the control is loaded.</p>",
    "IE7InstructionsHtml": "<p>To install Aurigma Upload Suite, please click on the <strong>Information Bar</strong> and select <strong>Install ActiveX Control</strong> or <strong>Run ActiveX Control</strong> from the dropdown menu.</p><p>Then either click <strong>Run</strong> or after the page is reloaded click <strong>Install</strong> when you see the control installation dialog. If you don't see Information Bar, please try to reload the page and/or check your security settings.</p>",
    "IE8ProgressHtml": "<p>Please wait until the control is loaded.</p>",
    "IE8InstructionsHtml": "<p>To install Aurigma Upload Suite, please click on the <strong>Information Bar</strong> and select <strong>Install This Add-on</strong> or <strong>Run Add-on</strong> from the dropdown menu.</p><p>Then either click <strong>Run</strong> or after the page is reloaded click <strong>Install</strong> when you see the control installation dialog. If you don't see Information Bar, please try to reload the page and/or check your security settings.</p>",
    "IE9ProgressHtml": "<p>Please wait until the control is loaded.</p>",
    "IE9InstructionsHtml": "<p>To install Aurigma Upload Suite, please click <strong>Allow</strong> or <strong>Install</strong> on the <strong>Notification Bar</strong> at the bottom of the page.</p><p>Then after the page is reloaded click <strong>Install</strong> on the control installation dialog. If you don't see Notification Bar, please try to reload the page and/or check your security settings (ActiveX controls should be enabled).</p>",
    "chromeProgressHtml": "<p>Aurigma Upload Suite requires Java plug-in. Please, click <strong>Run this time</strong> or <strong>Always run on this site</strong> to let Java plug-in run.</p>",
    "updateInstructions": "You need to update Aurigma Upload Suite control. Click <strong>Install</strong> or <strong>Run</strong> button when you see the control installation dialog. If you don't see installation dialog, please try to reload the page.",
    "macInstallJavaHtml": "<p>Use the <a href=\"http://support.apple.com/kb/HT1338\">Software Update</a> feature (available on the Apple menu) to check that you have the most up-to-date version of Java for your Mac.</p>",
    "installJavaHtml": "<p>Please <a href=\"http://www.java.com/getjava/\">download</a> and install Java.</p>",
    "installFlashPlayerHtml": "<p>You need to install Flash Player for running Aurigma Upload Suite. Download latest version from <a href='http://www.adobe.com/go/getflashplayer' title='Download Flash Player'>here</a>.</p>"
};
})(window);
