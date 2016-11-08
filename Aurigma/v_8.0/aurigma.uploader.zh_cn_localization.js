(function(window, undefined) {

// Define global Aurigma.ImageUploader namespace
var AU = (window.Aurigma || (window.Aurigma = { __namespace: true })) &&
    (window.Aurigma.ImageUploader || (window.Aurigma.ImageUploader = { __namespace: true }));

AU.language || (AU.language = { __namespace: true });
AU.ip_language || (AU.ip_language = { __namespace: true });

// Simplified Chinese
window.zh_cn_localization = AU.language.zh_cn = {
    "addFilesProgressDialog": {
        "cancelButtonText": "取消",
        "currentFileText": "正在处理文件： “[name]”",
        "titleText": "正在将文件添加到上传队列",
        "totalFilesText": "已处理的文件： [count]",
        "waitText": "请稍候，处理可能需要一些时间"
    },
    "authenticationDialog": {
        "cancelButtonText": "取消",
        "loginText": "登录：",
        "okButtonText": "确定",
        "passwordText": "密码：",
        "realmText": "领域：",
        "text": "主机 [name] 需要验证"
    },
    "contextMenu": {
        "addFilesText": "添加文件...",
        "addFolderText": "添加文件夹...",
        "arrangeByDimensionsText": "尺寸",
        "arrangeByModifiedText": "修改日期",
        "arrangeByNameText": "名称",
        "arrangeByPathText": "路径",
        "arrangeBySizeText": "大小",
        "arrangeByText": "排序方式",
        "arrangeByTypeText": "类型",
        "checkAllText": "全选",
        "checkText": "选中",
        "detailsViewText": "详细资料",
        "editText": "编辑图像…",
        "editDescriptionText": "编辑说明…",
        "listViewText": "列表",
        "navigateToFolderText": "浏览至文件夹",
        "openText": "打开",
        "pasteText": "粘贴",
        "removeAllText": "全部删除",
        "removeText": "删除",
        "thumbnailsViewText": "缩略图",
        "tilesViewText": "平铺",
        "uncheckAllText": "取消全选",
        "uncheckText": "不选中"
    },
    "deleteFilesDialog": {
        "message": "确定要永久删除上传的项目吗？",
        "titleText": "删除文件"
    },
    "descriptionEditor": {
        "cancelHyperlinkText": "取消",
        "orEscLabelText": " （或 Esc）",
        "saveButtonText": "保存"
    },
    "detailsViewColumns": {
        "dimensionsText": "尺寸",
        "fileNameText": "名称",
        "fileSizeText": "大小",
        "fileTypeText": "类型",
        "infoText": "信息",
        "lastModifiedText": "修改日期"
    },
    "folderPane": {
        "filterHintText": "搜索 ",
        "headerText": "<totalLink>文件总计： [totalCount]</totalLink>, <allowedLink>允许的： [allowedCount]</allowedLink>"
    },
    "imageEditor": {
        "cancelButtonText": "取消",
        "cancelCropButtonText": "取消修剪",
        "cropButtonText": "修剪",
        "descriptionHintText": "类型说明在此…",
        "rotateButtonText": "旋转",
        "saveButtonText": "保存并关闭"
    },
    "messages": {
        "cmykImagesNotAllowed": "不允许 CMYK 图像。",
        "deletingFilesError": "不能删除文件 “[name]”",
        "dimensionsTooLarge": "图像 “[name]” 太大不能被选中。",
        "dimensionsTooSmall": "图像 “[name]” 太小不能被选中。",
        "fileNameNotAllowed": "不能选择文件 “[name]”。 此文件有不允许的名词。",
        "fileSizeTooSmall": "不能选择文件 “[name]”。 文件大小小于限制。",
        "filesNotAdded": "由于限制不能添加 [count] 个文件。",
        "maxFileCountExceeded": "不能选择文件 “[name]”。 文件总数超出限制。",
        "maxFileSizeExceeded": "不能选择文件 “[name]”。 文件大小超出限制。",
        "maxTotalFileSizeExceeded": "不能选择文件 “[name]”。 上传数据大小总计超出限制。",
        "noResponseFromServer": "服务器无响应。",
        "serverError": "发生了某个服务器端错误。 如果您看到此消息，请与您的网管联系。",
        "serverNotFound": "找不到服务器或代理服务器 [name]。",
        "unexpectedError": "图像上传程序遇到某些问题。 如果您看到此消息，请与网络主管联系。",
        "uploadCancelled": "取消上传。",
        "uploadCompleted": "上传完成。",
        "uploadFailed": "上传失败（连接被中断）。"
    },
    "paneItem": {
        "descriptionEditorIconTooltip": "编辑说明",
        "imageCroppedIconTooltip": "修剪图像",
        "imageEditorIconTooltip": "预览 / 编辑图像",
        "removalIconTooltip": "删除",
        "rotationIconTooltip": "旋转"
    },
    "statusPane": {
        "clearAllHyperlinkText": "全部清除",
        "filesToUploadText": "要上传所选的文件： [count]",
        "noFilesToUploadText": "没有文件要上传",
        "progressBarText": "上传（[percents]%）"
    },
    "treePane": {
        "titleText": "文件夹",
        "unixFileSystemRootText": "文件系统",
        "unixHomeDirectoryText": "起始文件夹"
    },
    "uploadPane": {
        "dropFilesHereMacText": "Add files here...", // TODO: Translate
        "dropFilesHereText": "在此放下文件"
    },
    "uploadProgressDialog": {
        "cancelUploadButtonText": "取消",
        "estimationText": "预计剩余时间： [time]",
        "hideButtonText": "隐藏",
        "hoursText": "小时",
        "infoText": "上传的文件: [files]/[totalFiles] （[bytes] 之 [totalBytes]）",
        "kilobytesText": "Kb",
        "megabytesText": "Mb",
        "minutesText": "分钟",
        "preparingText": "正在准备要上传的文件...",
        "reconnectionText": "上传失败。 等待重新连接...",
        "secondsText": "秒",
        "titleText": "将文件上传到服务器"
    },
    "cancelUploadButtonText": "停止",
    "loadingFolderContentText": "加载内容…",
    "uploadButtonText": "上传"
};
// Simplified Chinese
AU.ip_language.zh_cn = {
    "commonHtml": "<p>要迅速、轻松地上传您的文件，Aurigma 图像上传程序控件必不可少。 您可以在用户友好的界面中选择多个图像，而不是使用带有<strong>浏览</strong>按钮的简陋的输入字段进行选择。</p>",
    "progressHtml": "<p><img src=\"{0}\" /><br />正在加载 Aurigma 图像上传程序...</p>",
    "flashProgressHtml": "<p><img src=\"{0}\" /><br />正在加载 Aurigma Flash 上传程序...</p>",
    "beforeIE6XPSP2ProgressHtml": "<p>要安装图像上传程序，请等待直到控件已加载，然后在您看到安装对话框时单击<strong>是</strong>按钮。</p>",
    "beforeIE6XPSP2InstructionsHtml": "<p>要安装图像上传程序，请重新加载此页面并在看到控件安装对话框时单击<strong>是</strong>按钮。 如果您未看到安装对话框，请检查安全性设置。</p>",
    "IE6XPSP2ProgressHtml": "<p>请等待直到控件已加载。</p>",
    "IE6XPSP2InstructionsHtml": "<p>要安装图像上传程序，请单击<strong>信息栏</strong>，然后从下拉菜单中选择<strong>安装 ActiveX 控件</strong>。 在页面重新加载后，当您看到控件安装对话框时，单击<strong>安装</strong>。 如果您看不到信息栏，请尝试重新加载该页面并（或）检查安全性设置。</p>",
    "IE7ProgressHtml": "<p>请等待直到控件已加载。</p>",
    "IE7InstructionsHtml": "<p>要安装图像上传程序，请单击<strong>信息栏</strong>并从下拉菜单中选择<strong>安装 ActiveX 控件</strong>或<strong>运行 ActiveX 控件</strong>。</p><p>然后，单击<strong>运行</strong>，或者，在页面重新加载后，当您看到控件安装对话框时单击<strong>安装</strong>。 如果您看不到信息栏，请尝试重新加载该页面并（或）检查安全性设置。</p>",
    "IE8ProgressHtml": "<p>请等待直到控件已加载。</p>",
    "IE8InstructionsHtml": "<p>要安装图像上传程序，请单击<strong>信息栏</strong>并从下拉菜单中选择<strong>安装此插件</strong>或<strong>运行插件</strong>。</p><p>然后，单击<strong>运行</strong>，或者，在页面重新加载后，当您看到控件安装对话框时单击<strong>安装</strong>。 如果您看不到信息栏，请尝试重新加载该页面并（或）检查安全性设置。</p>",
    "IE9ProgressHtml": "<p>请等待直到控件已加载。</p>",
    "IE9InstructionsHtml": "<p>要安装图像上传程序，请单击页面底部<strong>通知栏</strong>上的<strong>允许</strong>或<strong>安装</strong>。</p><p>然后，在页面重新加载后，在控件安装对话框中，单击<strong>安装</strong>。 如果您未看到通知栏，请尝试重新加载该页面并（或）检查安全性设置（应启用 ActiveX 控件）。</p>",
    "chromeProgressHtml": "<p>Aurigma Upload Suite requires Java plug-in. Please, click <strong>Run this time</strong> or <strong>Always run on this site</strong> to let Java plug-in run.</p>", // TODO: translate
    "updateInstructions": "您需要更新图像上传程序控件。 当您看到控件安装对话框时，单击<strong>安装</strong>或<strong>运行</strong>按钮。 如果您未看到安装对话框，请尝试重新加载该页面。",
    "macInstallJavaHtml": "<p>使用<a href=\"http://support.apple.com/kb/HT1338\">软件更新</a>功能（在 Apple 菜单中可用）来检查您的 Mac 是否已具有最新版本的 Java。</p>",
    "installJavaHtml": "<p>请<a href=\"http://www.java.com/getjava/\">下载</a>并安装 Java。</p>",
    "installFlashPlayerHtml": "<p>要运行 Aurigma Flash 上传程序，您需要安装 Flash Player。 请从<a href='http://www.adobe.com/go/getflashplayer' title='Download Flash Player'>此处</a>下载最新版本。</p>"
};
})(window);
