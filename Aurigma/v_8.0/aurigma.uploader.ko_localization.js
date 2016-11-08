(function(window, undefined) {

// Define global Aurigma.ImageUploader namespace
var AU = (window.Aurigma || (window.Aurigma = { __namespace: true })) &&
    (window.Aurigma.ImageUploader || (window.Aurigma.ImageUploader = { __namespace: true }));

AU.language || (AU.language = { __namespace: true });
AU.ip_language || (AU.ip_language = { __namespace: true });

// Korean
window.ko_localization = AU.language.ko = {
    "addFilesProgressDialog": {
        "cancelButtonText": "취소",
        "currentFileText": "파일을 처리중: “[name]”",
        "titleText": "파일을 업로드 대기열에 추가",
        "totalFilesText": "이미 처리된 파일: [count]",
        "waitText": "기다려 주십시오. 시간이 걸릴 수 있습니다…"
    },
    "authenticationDialog": {
        "cancelButtonText": "취소",
        "loginText": "로그인:",
        "okButtonText": "확인",
        "passwordText": "암호:",
        "realmText": "영역:",
        "text": "호스트[name]가 인증을 요구합니다"
    },
    "contextMenu": {
        "addFilesText": "파일 추가...",
        "addFolderText": "폴더 추가...",
        "arrangeByDimensionsText": "치수",
        "arrangeByModifiedText": "수정된 일자",
        "arrangeByNameText": "이름",
        "arrangeByPathText": "경로",
        "arrangeBySizeText": "크기",
        "arrangeByText": "정렬 기준:",
        "arrangeByTypeText": "유형",
        "checkAllText": "모두 확인",
        "checkText": "확인",
        "detailsViewText": "세부 사항",
        "editText": "이미지 편집…",
        "editDescriptionText": "설명 편집…",
        "listViewText": "목록",
        "navigateToFolderText": "폴더로 이동",
        "openText": "열기",
        "pasteText": "붙여넣기",
        "removeAllText": "모두 제거",
        "removeText": "제거",
        "thumbnailsViewText": "축소판 그림",
        "tilesViewText": "타일",
        "uncheckAllText": "모두 선택취소",
        "uncheckText": "선택 취소"
    },
    "deleteFilesDialog": {
        "message": "업로드된 항목을 영구히 삭제하겠습니까?",
        "titleText": "파일 삭제"
    },
    "descriptionEditor": {
        "cancelHyperlinkText": "취소",
        "orEscLabelText": " (또는 Esc)",
        "saveButtonText": "저장"
    },
    "detailsViewColumns": {
        "dimensionsText": "치수",
        "fileNameText": "이름",
        "fileSizeText": "크기",
        "fileTypeText": "유형",
        "infoText": "정보",
        "lastModifiedText": "수정된 일자"
    },
    "folderPane": {
        "filterHintText": "검색 ",
        "headerText": "<totalLink>총 파일: [totalCount]</totalLink>, <allowedLink>허용됨: [allowedCount]</allowedLink>"
    },
    "imageEditor": {
        "cancelButtonText": "취소",
        "cancelCropButtonText": "자르기 취소",
        "cropButtonText": "자르기",
        "descriptionHintText": "설명을 여기에 입력하십시오…",
        "rotateButtonText": "회전",
        "saveButtonText": "저장 및 종료"
    },
    "messages": {
        "cmykImagesNotAllowed": "CMYK 이미지가 허용되지 않습니다.",
        "deletingFilesError": "파일“[name]”을 삭제할 수 없습니다",
        "dimensionsTooLarge": "이미지“[name]”가 선택하기에 너무 큽니다.",
        "dimensionsTooSmall": "이미지“[name]”가 선택하기에 너무 작습니다.",
        "fileNameNotAllowed": "파일“[name]”을 선택할 수 없습니다. 이 파일의 이름을 허용할 수 없습니다.",
        "fileSizeTooSmall": "파일“[name]”을 선택할 수 없습니다. 이 파일 크기가 한도보다 작습니다.",
        "filesNotAdded": "[count] 파일이 제한 때문에 추가되지 못했습니다.",
        "maxFileCountExceeded": "파일“[name]”을 선택할 수 없습니다. 파일의 양이 한도를 초과했습니다.",
        "maxFileSizeExceeded": "파일“[name]”을 선택할 수 없습니다. 이 파일의 크기가 한도를 초과했습니다.",
        "maxTotalFileSizeExceeded": "파일“[name]”을 선택할 수 없습니다. 총 업로드 데이터 크기가 한도를 초과했습니다.",
        "noResponseFromServer": "서버로부터 응답 없음.",
        "serverError": "서버 쪽 오류가 발생했습니다. 이 메시지가 표시되면 웹 마스터에게 문의하십시오.",
        "serverNotFound": "서버나 프록시[name]를 찾을 수 없습니다.",
        "unexpectedError": "이미지 업로더에 문제가 생겼습니다. 이 메시지가 나타나면, 웹 마스터에게 연락하십시오.",
        "uploadCancelled": "업로드가 취소되었습니다.",
        "uploadCompleted": "업로드 완료.",
        "uploadFailed": "업로드 실패함(접속이 중단되었습니다)."
    },
    "paneItem": {
        "descriptionEditorIconTooltip": "설명 편집",
        "imageCroppedIconTooltip": "이미지가 잘려 있습니다.",
        "imageEditorIconTooltip": "이미지 미리보기 / 편집",
        "removalIconTooltip": "제거",
        "rotationIconTooltip": "회전"
    },
    "statusPane": {
        "clearAllHyperlinkText": "모두 지우기",
        "filesToUploadText": "업로드하기 위해 선택된 파일들: [count]",
        "noFilesToUploadText": "업로드할 파일 없음",
        "progressBarText": "업로딩 ([percents]%)"
    },
    "treePane": {
        "titleText": "폴더",
        "unixFileSystemRootText": "파일 시스템",
        "unixHomeDirectoryText": "홈 폴더"
    },
    "uploadPane": {
        "dropFilesHereMacText": "Add files here...", // TODO: Translate
        "dropFilesHereText": "파일을 여기에 놓으십시오…"
    },
    "uploadProgressDialog": {
        "cancelUploadButtonText": "취소",
        "estimationText": "추정 잔여 시간: [time]",
        "hideButtonText": "숨기기",
        "hoursText": "시간",
        "infoText": "업로드된 파일: [files]/[totalFiles] ([bytes]/[totalBytes])",
        "kilobytesText": "Kb",
        "megabytesText": "Mb",
        "minutesText": "분",
        "preparingText": "업로드할 파일 준비중...",
        "reconnectionText": "업로드 실패함. 업로드하지 못했습니다.",
        "secondsText": "초",
        "titleText": "파일을 서버로 업로딩"
    },
    "cancelUploadButtonText": "중지",
    "loadingFolderContentText": "내용 로딩중…",
    "uploadButtonText": "업로드"
};
// Korean
AU.ip_language.ko = {
    "commonHtml": "<p>Aurigma Upload Suite 컨트롤은 파일을 쉽고 편하게 업로드하는 데 필요합니다. <strong>찾아보기</strong> 단추를 사용하여 불편한 입력 필드 대신 사용자의 입장을 고려한 인터페이스로 여러 이미지를 한꺼번에 선택할 수 있습니다.</p>",
    "progressHtml": "<p><img src=\"{0}\" /><br />Aurigma Upload Suite 로딩...</p>",
    "flashProgressHtml": "<p><img src=\"{0}\" /><br />Aurigma Upload Suite 로딩...</p>",
    "beforeIE6XPSP2ProgressHtml": "<p>Image Uploader를 설치하려면 컨트롤이 로딩될 때까지 기다렸다가, 설치 대화가 표시되면 <strong>예</strong> 단추를 클릭하십시오.</p>",
    "beforeIE6XPSP2InstructionsHtml": "<p>Image Uploader를 설치하려면 페이지를 다시 로딩하고 컨트롤 설치 대화 상자가 표시되면 <strong>예</strong> 단추를 누르십시오. 설치 대화가 표시되지 않으면, 보안 설정을 확인하십시오.</p>",
    "IE6XPSP2ProgressHtml": "<p>컨트롤이 로딩될 때까지 기다리십시오.</p>",
    "IE6XPSP2InstructionsHtml": "<p>Image Uploader를 설치하려면 <strong>알림 표시줄</strong>을 클릭하고 <strong>ActiveX 컨트롤 설치</strong>를 드롭다운 메뉴에서 선택하십시오. 페이지가 다시 로딩된 다음, 컨트롤 설치 대화가 표시되면 <strong>설치</strong>를 클릭하십시오. 알림 표시줄이 보이지 않으면 페이지를 다시 로딩하고 또는 보안 설정을 확인하십시오.</p>",
    "IE7ProgressHtml": "<p>컨트롤이 로딩될 때까지 기다리십시오.</p>",
    "IE7InstructionsHtml": "<p>Image Uploader를 설치하려면 <strong>알림 표시줄</strong>을 클릭하고 <strong>ActiveX 컨트롤 설치</strong> 또는 <strong>ActiveX 컨트롤 실행</strong>을 드롭다운 메뉴에서 선택하십시오.</p><p>그런 다음 <strong>실행</strong>을 클릭하거나 페이지가 다시 로딩된 다음 컨트롤 설치 대화가 표시되면 <strong>설치</strong>를 클릭하십시오. 알림 표시줄이 보이지 않으면 페이지를 다시 로딩하고 또는 보안 설정을 확인하십시오.</p>",
    "IE8ProgressHtml": "<p>컨트롤이 로딩될 때까지 기다리십시오.</p>",
    "IE8InstructionsHtml": "<p>Image Uploader를 설치하려면 <strong>알림 표시줄</strong>을 클릭하고 <strong>이 애드온 설치</strong> 또는 <strong>이 애드온 실행</strong>을 드롭다운 메뉴에서 선택하십시오.</p><p>그런 다음<strong>실행</strong>을 클릭하거나 페[이지가 다시 로딩된 다음 컨트롤 설치 대화가 표시되면<strong>설치</strong>를 클릭하십시오. 알림 표시줄이 보이지 않으면 페이지를 다시 로딩하고 또는 보안 설정을 확인하십시오.</p>",
    "IE9ProgressHtml": "<p>컨트롤이 로딩될 때까지 기다리십시오.</p>",
    "IE9InstructionsHtml": "<p>Image Uploader를 설치하려면 페이지 하단에 있는 <strong>알림 표시줄</strong>에서 <strong>허용</strong> 또는 <strong>설치</strong>를 클릭하십시오.</p><p>그런 다음 페이지가 다시 로딩되면 컨트롤 설치 대화 상자에서 <strong>설치</strong>를 클릭하십시오. 알림 표시줄이 보이지 않으면, 페이지를 다시 로딩하거나 또는 보안 설정을 확인하십시오 (ActiveX 컨트롤이 사용 상태여야 합니다).</p>",
    "chromeProgressHtml": "<p>Aurigma Upload Suite requires Java plug-in. Please, click <strong>Run this time</strong> or <strong>Always run on this site</strong> to let Java plug-in run.</p>", // TODO: translate
    "updateInstructions": "Aurigma Upload Suite 컨트롤을 업데이트하셔야 합니다 . 컨트롤 설치 대화가 표시되면 <strong>설치</strong> 또는 <strong>실행</strong> 단추를 클릭하십시오. 설치 대화가 표시되지 않으면, 페이지를 다시 로딩하십시오.",
    "macInstallJavaHtml": "<p><a href=\"http://support.apple.com/kb/HT1338\">소프트웨어 업데이트</a> 기능( Apple 메뉴에서 사용 가능)을 사용하여 여러분의 Mac에 Java 최신 버전을 설치했는지 확인하십시오.</p>",
    "installJavaHtml": "<p><a href=\"http://www.java.com/getjava/\">다운로드</a>를 하고 Java를 설치하십시오.</p>",
    "installFlashPlayerHtml": "<p>Aurigma Flash Uploader를 사용하려면 Flash Player를 설치해야 합니다. <a href='http://www.adobe.com/go/getflashplayer' title='Download Flash Player'>여기</a>에서 최신 버전을 다운로드하십시오.</p>"
};
})(window);
