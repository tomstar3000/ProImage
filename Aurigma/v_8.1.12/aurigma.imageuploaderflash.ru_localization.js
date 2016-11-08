(function(window, undefined) {

// Define global Aurigma.ImageUploaderFlash namespace
var AU = (window.Aurigma || (window.Aurigma = { __namespace: true })) &&
    (window.Aurigma.ImageUploaderFlash || (window.Aurigma.ImageUploaderFlash = { __namespace: true }));

AU.language || (AU.language = { __namespace: true });

// Russian
window.ru_localization = AU.language.ru = {
    addFilesProgressDialog: {
        text: "Добавление файлов..."
    },
    commonDialog: {
        cancelButtonText: "Отмена",
        okButtonText: "OK"
    },
    descriptionEditor: {
        cancelButtonText: "Отмена",
        saveButtonText: "Сохранить"
    },
    imagePreviewWindow: {
        closePreviewTooltip: "Кликните, чтобы закрыть."
    },
    messages: {
        cannotReadFile: "Файл {0} недоступен.",
        dimensionsTooLarge: 'Файл {0} не может быть выбран для загрузки. Размер изображения не должен превышать {1}x{2} пикселей.',
        dimensionsTooSmall: 'Файл {0} не может быть выбран для загрузки. Размер изображения должен быть не менее {1}x{2} пикселей.',
        fileNameNotAllowed: "Файл {0} не может быть выбран для загрузки. Недопустимое имя файла.",
		fileSizeTooSmall: "Файл {0} не может быть выбран для загрузки. Размер файла меньше допустимого значения ({1}).",
        filesNotAdded: "Не все файлы были добавлены. Файлов пропущено: {0}.",
        maxFileCountExceeded: "Не все файлы были добавлены. Максимальное количество файлов для загрузки: {0}.",
        maxFileSizeExceeded: "Файл {0} не может быть выбран для загрузки. Размер файла первышает {1}.",
        maxTotalFileSizeExceeded: "Не все файлы были добавлены. Максимальный общий размер файлов: {0}.",
        previewNotAvailable: "Предпросмотр недоступен",
        tooFewFiles: "Необходимо выбрать не менее {0} файлов для загрузки."
    },
    paneItem: {
        descriptionEditorIconTooltip: "Редактировать описание",
        imageTooltip: "{0}\n{1}, {3}, \nДата: {2}",
        itemTooltip: "{0}\n{1}, \nДата: {2}",
        removalIconTooltip: "Удалить",
        rotationIconTooltip: "Повернуть"
    },
    statusPane: {
        dataUploadedText: "Отправлено данных: {0} / {1}",
        filesPreparedText: "Подготовлено: {0}",
        filesToUploadText: 'Выбрано файлов: {0}',
        filesUploadedText: "Загружено файлов: {0} / {1}",
        noFilesToUploadText: 'Выберите файлы для загрузки',
        preparingText: "Подготовка к загрузке",
        sendingText: "Загрузка"
    },
    topPane: {
        addFilesHyperlinkText: "Добавить файлы",
        clearAllHyperlinkText: "очистить",
        orText: "или",
        titleText: "Выбор файлов для загрузки",
        viewComboBox: ["Детальный", "Эскизы", "Список файлов"],
        viewComboBoxText: "Вид:"
    },
    uploadErrorDialog: {
        hideDetailsButtonText: "Скрыть...",
        message: "Произошла ошибка во время загрузки. Если вы видите это сообщение, обратитесь к веб мастеру.",
        showDetailsButtonText: "Показать...",
        title: "Ошибка при загрузке"
    },
    uploadPane: {
        addFilesButtonText: "Добавить файлы"
    },
    cancelUploadButtonText: "Остановить",
    uploadButtonText: "Загрузить"
};
})(window);
