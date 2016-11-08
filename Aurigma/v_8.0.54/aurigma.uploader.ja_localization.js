(function(window, undefined) {

// Define global Aurigma.ImageUploader namespace
var AU = (window.Aurigma || (window.Aurigma = { __namespace: true })) &&
    (window.Aurigma.ImageUploader || (window.Aurigma.ImageUploader = { __namespace: true }));

AU.language || (AU.language = { __namespace: true });
AU.ip_language || (AU.ip_language = { __namespace: true });

// Japanese
window.ja_localization = AU.language.ja = {
    "addFilesProgressDialog": {
        "cancelButtonText": "キャンセル",
        "currentFileText": "ファイルを処理中: 「[name]」",
        "titleText": "アップロード キューにファイルを追加",
        "totalFilesText": "処理済みのファイル: [count]",
        "waitText": "しばらくお待ちください。"
    },
    "authenticationDialog": {
        "cancelButtonText": "キャンセル",
        "loginText": "ログイン:",
        "okButtonText": "Ok",
        "passwordText": "パスワード:",
        "realmText": "レルム:",
        "text": "ホスト [name] は認証を必要としています"
    },
    "contextMenu": {
        "addFilesText": "ファイルを追加する...",
        "addFolderText": "フォルダを追加する...",
        "arrangeByDimensionsText": "寸法",
        "arrangeByModifiedText": "変更日",
        "arrangeByNameText": "名前",
        "arrangeByPathText": "パス",
        "arrangeBySizeText": "サイズ",
        "arrangeByText": "ソートの種類",
        "arrangeByTypeText": "種類",
        "checkAllText": "すべてにチェックをつける",
        "checkText": "チェックをつける",
        "detailsViewText": "詳細",
        "editText": "画像の編集…",
        "editDescriptionText": "説明の編集…",
        "listViewText": "リスト",
        "navigateToFolderText": "フォルダに移動する",
        "openText": "開く",
        "pasteText": "貼り付け",
        "removeAllText": "すべて削除",
        "removeText": "削除",
        "thumbnailsViewText": "サムネイル",
        "tilesViewText": "タイトル",
        "uncheckAllText": "すべてチェックを解除",
        "uncheckText": "チェックを解除"
    },
    "deleteFilesDialog": {
        "message": "アップロードしたアイテムを永久に削除しますか？",
        "titleText": "ファイルを削除"
    },
    "descriptionEditor": {
        "cancelHyperlinkText": "キャンセル",
        "orEscLabelText": " (または Esc)",
        "saveButtonText": "保存"
    },
    "detailsViewColumns": {
        "dimensionsText": "寸法",
        "fileNameText": "名前",
        "fileSizeText": "サイズ",
        "fileTypeText": "種類",
        "infoText": "情報",
        "lastModifiedText": "変更日"
    },
    "folderPane": {
        "filterHintText": "検索 ",
        "headerText": "<totalLink>ファイル合計数: [totalCount]</totalLink>, <allowedLink>許可: [allowedCount]</allowedLink>"
    },
    "imageEditor": {
        "cancelButtonText": "キャンセル",
        "cancelCropButtonText": "トリミングの取消",
        "cropButtonText": "トリミング",
        "descriptionHintText": "ここに説明を入力します…",
        "rotateButtonText": "回転",
        "saveButtonText": "保存して閉じる"
    },
    "messages": {
        "cmykImagesNotAllowed": "CMYK 画像は許可されません。",
        "deletingFilesError": "ファイル「[name]」は削除できません",
        "dimensionsTooLarge": "画像「[name]」は大きすぎて選択できません。",
        "dimensionsTooSmall": "画像「[name]」は小さすぎて選択できません。",
        "fileNameNotAllowed": "ファイル「[name]」は選択できません。 このファイル名は許可されません。",
        "fileSizeTooSmall": "ファイル「[name]」は選択できません。 このファイルは最低サイズを満たしていません。",
        "filesNotAdded": "[count] 件のファイルが制限のため追加されませんでした。",
        "maxFileCountExceeded": "ファイル「[name]」は選択できません。 ファイル数が上限を超えています。",
        "maxFileSizeExceeded": "ファイル「[name]」は選択できません。 このファイルサイズは上限を超えています。",
        "maxTotalFileSizeExceeded": "ファイル「[name]」は選択できません。 アップロードデータサイズの合計が上限を超えています。",
        "noResponseFromServer": "サーバーからの応答がありません。",
        "serverError": "サーバー側でエラーが発生しました。 このメッセージが表示された場合、ウェブマスターにご連絡ください。",
        "serverNotFound": "サーバまたはプロキシ [name]が見つかりません。",
        "unexpectedError": "画像アップローダーに問題が発生しました。 このメッセージが表示されたら、ウェブマスターにご連絡ください。",
        "uploadCancelled": "アップロードがキャンセルされました。",
        "uploadCompleted": "アップロードが完了しました。",
        "uploadFailed": "アップロードに失敗しました (接続が中断されました)。"
    },
    "paneItem": {
        "descriptionEditorIconTooltip": "説明の編集",
        "imageCroppedIconTooltip": "画像がトリミングされました。",
        "imageEditorIconTooltip": "画像のプレビュー/ 編集",
        "removalIconTooltip": "削除",
        "rotationIconTooltip": "回転"
    },
    "statusPane": {
        "clearAllHyperlinkText": "すべて消去",
        "filesToUploadText": "アップロードするため選択されたファイル: [count]",
        "noFilesToUploadText": "アップロードするファイルはありません",
        "progressBarText": "アップロード中（[percents]%）"
    },
    "treePane": {
        "titleText": "フォルダ",
        "unixFileSystemRootText": "ファイルシステム",
        "unixHomeDirectoryText": "ホーム フォルダ"
    },
    "uploadPane": {
        "dropFilesHereMacText": "Add files here...", // TODO: Translate
        "dropFilesHereText": "ここにファイルをドロップ…"
    },
    "uploadProgressDialog": {
        "cancelUploadButtonText": "キャンセル",
        "estimationText": "予想残り時間: [time]",
        "hideButtonText": "非表示",
        "hoursText": "時間",
        "infoText": "アップロード済みファイル: [files]/[totalFiles] （[bytes]/[totalBytes]）",
        "kilobytesText": "Kb",
        "megabytesText": "Mb",
        "minutesText": "分",
        "preparingText": "アップロードするため、ファイルを準備中...",
        "reconnectionText": "アップロードに失敗しました。 再接続待ち...",
        "secondsText": "秒",
        "titleText": "サーバにファイルをアップロード中"
    },
    "cancelUploadButtonText": "中止",
    "loadingFolderContentText": "コンテンツをロード中です…",
    "uploadButtonText": "アップロード"
};
// Japanese
AU.ip_language.ja = {
    "commonHtml": "<p>Aurigma Upload Suite コントロールは、ファイルをすみやかに、そして簡単にアップロードするのに必要です。 <strong>[ブラウズ]</strong> ボタンによる面倒な入力フィールドではなく、使いやすいインターフェースにより、複数のイメージが選択できます</p>",
    "progressHtml": "<p><img src=\"{0}\" /><br />Aurigma Upload Suite をロード中...</p>",
    "beforeIE6XPSP2ProgressHtml": "<p>Aurigma Upload Suite をインストールするには、コントロールがロードされるのを待ち、その後、インストール ダイアログが表示されたら、 <strong>[はい]</strong> ボタンをクリックします。</p>",
    "beforeIE6XPSP2InstructionsHtml": "<p>Image Uploaderをインストールするには、ページを再ロードして、コントロールのインストレーション ダイアログが表示されたら、<strong>[はい]</strong> ボタンをクリックします。 インストール ダイアログが表示されない場合は、セキュリティ設定を確認してください。</p>",
    "IE6XPSP2ProgressHtml": "<p>コントロールがロードされるまでお待ちください。</p>",
    "IE6XPSP2InstructionsHtml": "<p>Aurigma Upload Suite をインストールするには、<strong>情報バー</strong> をクリックし、ドロップ ダウンメニューから<strong> ActiveX Control をインストールする</strong> を選択します。 ページが再ロードされ、コントロールの インストレーション ダイアログが表示されたら、<strong>インストール</strong>をクリックします。 情報バーが表示されない場合は、ページの再ロードを試みるか、またはセキュリティ設定を確認してください。</p>",
    "IE7ProgressHtml": "<p>コントロールがロードされるまでお待ちください。</p>",
    "IE7InstructionsHtml": "<p>Aurigma Upload Suite をインストールするには、 <strong>情報バー</strong>をクリックし、ドロップダウンメニューから、<strong> ActiveX Control を インストールする</strong>、または<strong>ActiveX Control を動作する</strong>を選択します。</p><p>その後、コントロールのインストレーション ダイアログが表示されたら、<strong>動作</strong>をクリックするか、またはページの再ロード後に、<strong>インストール</strong> をクリックします。 情報バーが表示されない場合は、ページの再ロードを試みるか、またはセキュリティ設定を確認してください。</p>",
    "IE8ProgressHtml": "<p>コントロールがロードされるまでお待ちください。</p>",
    "IE8InstructionsHtml": "<p>Aurigma Upload Suite をインストールするには、 <strong>情報バー</strong>をクリックし、ドロップダウンメニューから、<strong> このアドオンをインストールする</strong>、または<strong>アドオンを動作する</strong>を選択します。</p><p>その後、コントロールのインストレーション ダイアログが表示されたら、<strong>動作</strong>をクリックするか、またはページの再ロード後に、<strong>インストール</strong> をクリックします。 情報バーが表示されない場合は、ページの再ロードを試みるか、またはセキュリティ設定を確認してください。</p>",
    "IE9ProgressHtml": "<p>コントロールがロードされるまでお待ちください。</p>",
    "IE9InstructionsHtml": "<p>Aurigma Upload Suite をインストールするには、ページ下にある<strong>お知らせバー</strong>にある、<strong>許可</strong>、または<strong>インストールl</strong>をクリックします。</p><p>その後、ページが再ロードされたら、コントロールのインストレーション ダイアログの strong>インストール</strong> をクリックします。 お知らせバーが表示されない場合は、ページの再ロードを試みるか、またはセキュリティ設定を確認してください（ActiveX のコントロールが有効になっている必要があります）</p>",
    "chromeProgressHtml": "<p>Aurigma Upload Suite requires Java plug-in. Please, click <strong>Run this time</strong> or <strong>Always run on this site</strong> to let Java plug-in run.</p>", // TODO: translate
    "updateInstructions": "Aurigma Upload Suite コントロールをアップデートする必要があります。 Aurigma Upload Suite コントロールのインストレーション ダイアログが表示されたら、<strong>[インストールする]</strong>、または <strong>[動作する]</strong> ボタンをクリックします。 インストール ダイアログが表示されない場合は、ページの再ロードを試みてください。",
    "macInstallJavaHtml": "<p><a href=\"http://support.apple.com/kb/HT1338\">ソフトウェアアップデート</a> 機能 (Apple メニューにあります) を使用して、お使いの Mac に最新版のJavaがインストールされていることを確認してください。</p>",
    "installJavaHtml": "<p> <a href=\"http://www.java.com/getjava/\">をダウンロード後、</a> Java をインストールしてください。</p>"
};
})(window);
