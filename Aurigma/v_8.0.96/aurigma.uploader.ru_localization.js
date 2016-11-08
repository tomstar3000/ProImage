(function(window, undefined) {

// Define global Aurigma.ImageUploader namespace
var AU = (window.Aurigma || (window.Aurigma = { __namespace: true })) &&
    (window.Aurigma.ImageUploader || (window.Aurigma.ImageUploader = { __namespace: true }));

AU.language || (AU.language = { __namespace: true });
AU.ip_language || (AU.ip_language = { __namespace: true });

// Russian
window.ru_localization = AU.language.ru = {
    addFilesProgressDialog: {
        cancelButtonText: "Отмена",
        currentFileText: "Обрабатывается файл: '[name]'",
        titleText: "Файлы добавляются в очередь загрузки.",
        totalFilesText: "уже обработано файлов: [count]",
        waitText: "Пожалуйста, подождите, это может занять некоторое время..."
    },
    authenticationDialog: {
        cancelButtonText: "Отмена",
        loginText: "Логин:",
        okButtonText: "Ок",
        passwordText: "Пароль:",
        realmText: "Домен:",
        text: "Сервер [name] запрашивает аутентификацию"
    },
    contextMenu: {
        addFilesText: "Добавить файлы...",
        addFolderText: "Добавить папки...",
        arrangeByDimensionsText: "Разрешение",
        arrangeByModifiedText: "Дата изменения",
        arrangeByNameText: "Имя",
        arrangeByPathText: "Путь",
        arrangeBySizeText: "Размер",
        arrangeByText: "Сортировать по",
        arrangeByTypeText: "Тип",
        checkAllText: "Выбрать все",
        checkText: "Выбрать",
        detailsViewText: "Таблица",
        editDescriptionText: "Редактировать описание...",
        editText: "Редактировать изображение...",
        listViewText: "Список",
        navigateToFolderText: "Перейти в папку с файлом",
        openText: "Открыть",
        pasteText: "Вставить",
        removeAllText: "Удалить все",
        removeText: "Удалить",
        thumbnailsViewText: "Эскизы",
        tilesViewText: "Детальный",
        uncheckAllText: "Снять выделение со всех",
        uncheckText: "Снять выделение"
    },
    deleteFilesDialog: {
        message: "Вы уверены, что хотите окончательно удалить загруженные файлы?",
        titleText: "Удалить файл"
    },
    descriptionEditor: {
        cancelHyperlinkText: "Отмена",
        orEscLabelText: " (или Esc)",
        saveButtonText: "Сохранить"
    },
    detailsViewColumns: {
        dimensionsText: "Разрешение",
        fileNameText: "Имя",
        fileSizeText: "Размер",
        fileTypeText: "Тип",
        infoText: "Дополнительно",
        lastModifiedText: "Дата изменения"
    },
    folderPane: {
        filterHintText: "Поиск ",
        headerText: "<totalLink>Всего файлов: [totalCount]</totalLink>, <allowedLink>можно загрузить: [allowedCount]</allowedLink>"
    },
    imageEditor: {
        cancelButtonText: "Отмена",
        cancelCropButtonText: "Отменить кадрирование",
        cropButtonText: "Кадрировать",
        descriptionHintText: "Добавьте сюда описание...",
        rotateButtonText: "Повернуть",
        saveButtonText: "Сохранить и закрыть"
    },
    messages: {
        cmykImagesNotAllowed: "CMYK изображения запрещены.",
        deletingFilesError: "Файл '[name]' не может быть удален",
        dimensionsTooLarge: "Невозможно выбрать '[name]'. Слишком большой размер изображения.",
        dimensionsTooSmall: "Невозможно выбрать '[name]'. Слишком маленький размер изображения.",
        fileNameNotAllowed: "Невозможно выбрать '[name]'. Недопустимое имя файла.",
        fileSizeTooSmall: "Невозможно выбрать '[name]'. Слишком маленький размер файла.",
        filesNotAdded: "[name] файл(ов) не было добавлено из-за установленных ограничений.",
        maxFileCountExceeded: "Невозможно выбрать '[name]'. Уже выбрано максимальное количество файлов.",
        maxFileSizeExceeded: "Невозможно выбрать '[name]'. Слишком большой размер файла.",
        maxTotalFileSizeExceeded: "Невозможно выбрать '[name]'. Общий размер загружаемых файлов будет больше разрешенного.",
        noResponseFromServer: "Сервер не отвечает.",
        serverError: "Произошла серверная ошибка во время загрузки. Если вы видите это сообщение, пожалуйста, обратитесь к веб-мастеру.", // TODO: translate
        serverNotFound: "Сервер или прокси [name] не найден.",
        unexpectedError: "Произошла ошибка во время загрузки. Если вы видите это сообщение, пожалуйста, обратитесь к веб-мастеру.",
        uploadCancelled: "Загрузка прервана.",
        uploadCompleted: "Загрузка завершена.",
        uploadFailed: "Загрузка не удалась (соединение было разорвано)."
    },
    paneItem: {
        descriptionEditorIconTooltip: "Редактировать описание",
        imageCroppedIconTooltip: "Изображение кадрировано.",
        imageEditorIconTooltip: "Смотреть/редактировать изображение",
        removalIconTooltip: "Удалить",
        rotationIconTooltip: "Повернуть"
    },
    statusPane: {
        clearAllHyperlinkText: "очистить",
        filesToUploadText: "Выбрано [count] файл(ов)",
        noFilesToUploadText: "Файлы не выбраны",
        progressBarText: "Загрузка ([count]%)"
    },
    treePane: {
        titleText: "Папки",
        unixFileSystemRootText: "Файловая система",
        unixHomeDirectoryText: "Домашняя папка"
    },
    uploadPane: {
        dropFilesHereMacText: "Добавьте файл(ы) сюда...",
        dropFilesHereText: "Перенесите файл(ы) сюда..."
    },
    uploadProgressDialog: {
        cancelUploadButtonText: "Отмена",
        estimationText: "Оставшееся время загрузки: [time]",
        hideButtonText: "Скрыть",
        hoursText: "час(а)",
        infoText: "Загружено файлов: [files]/[totalFiles] ([bytes] из [totalBytes])",
        kilobytesText: "Кб",
        megabytesText: "Мб",
        minutesText: "минут(ы)",
        preparingText: "Подготовка файлов для загрузки...",
        reconnectionText: "Загрузка не удалась. Ожидание повторной попытки...",
        secondsText: "секунд(ы)",
        titleText: "Файлы загружаются на сервер"
    },
    cancelUploadButtonText: "Остановить",
    loadingFolderContentText: "Чтение файлов...",
    uploadButtonText: "Загрузить"
};
// Russian
AU.ip_language.ru = {
    "commonHtml": "<p>Aurigma Upload Suite - компонент, позволяющий легко и быстро загружать файлы. Вы сможете выбирать изображения для загрузки с помощью понятного и простого интерфейса вместо неудобных полей с кнопкой <strong>Обзор</strong>.</p>",
    "progressHtml": "<p><img src=\"{0}\" /><br />Загружается Aurigma Upload Suite...</p>",
    "beforeIE6XPSP2ProgressHtml": "<p>Чтобы установить Aurigma Upload Suite, щелкните по информационной строке вверху (Information Bar). После перезагрузки страницы нажмите <strong>Да</strong> в диалоге установки элемента управления.</p>",
    "beforeIE6XPSP2InstructionsHtml": "<p>Чтобы установить Aurigma Upload Suite, обновите страницу и нажмите кнопку \"Да\" в диалоге установки элемента управления. Если диалоговое окно не появилось проверьте настройки безопасности.</p>",
    "IE6XPSP2ProgressHtml": "<p>Пожалуйста, дождитесь загрузки контрола.</p>",
    "IE6XPSP2InstructionsHtml": "<p>Чтобы установить Aurigma Upload Suite, щелкните по информационной строке вверху (Information Bar) и выберете пункт <strong>Install ActiveX Control</strong> из выпадающего меню. После перезагрузки страницы нажмите <strong>Install</strong> в диалоге установки элемента управления.</p>",
    "IE7ProgressHtml": "<p>Пожалуйста, дождитесь загрузки контрола.</p>",
    "IE7InstructionsHtml": "Чтобы установить Aurigma Upload Suite, щелкните по информационной строке вверху (Information Bar) и выберете пункт <strong>Install ActiveX Control</strong> или <strong>Run ActiveX Control</strong> из выпадающего меню. Затем нажмите <strong>Run</strong> или после перезагрузки страницы нажмите <strong>Install</strong>.",
    "IE8ProgressHtml": "<p>Пожалуйста, дождитесь загрузки контрола.</p>",
    "IE8InstructionsHtml": "<p>Чтобы установить Aurigma Upload Suite, щелкните по информационной строке вверху (Information Bar) и выберите <strong>Install This Add-on</strong> или <strong>Run Add-on</strong> из выпадающего меню.</p><p>Затем нажмите <strong>Run</strong> или после перезагрузки страницы нажмите <strong>Install</strong>.</p>",
    "IE9ProgressHtml": "<p>Пожалуйста, дождитесь загрузки контрола.</p>",
    "IE9InstructionsHtml": "<p>Чтобы установить Aurigma Upload Suite, щелкните <strong>Allow</strong> или <strong>Install</strong> на <strong>Notification Bar</strong> внизу страницы.</p><p>Затем нажмите <strong>Install</strong> после перезагрузки страницы.</p>",
    "chromeProgressHtml": "<p>Для работы Aurigma Upload Suite требуется Java плагин. Щелкните <strong>Запустить один раз</strong> или <strong>Всегда запускать на этом сайте</strong>, чтобы разрешить Java на этой странице.</p>",
    "updateInstructions": "Необходимо обновить Aurigma Upload Suite компонент. Щелкните <strong>Install</strong> или <strong>Run</strong> в появивщемся диалоге установки элемента управления. Если диалоговое окно не появилось попробуйте обновить страницу.",
    "macInstallJavaHtml": "<p>Используйте <a href=\"http://support.apple.com/kb/HT1338\">Software Update</a> (доступно из Apple меню), чтобы убедиться, что на вашем компьютере установлена последняя версия Java.</p>",
    "installJavaHtml": "<p>Необходимо установить Java для запуска Aurigma Upload Suite.</p>"
};
})(window);
