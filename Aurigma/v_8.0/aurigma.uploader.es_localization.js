(function(window, undefined) {

// Define global Aurigma.ImageUploader namespace
var AU = (window.Aurigma || (window.Aurigma = { __namespace: true })) &&
    (window.Aurigma.ImageUploader || (window.Aurigma.ImageUploader = { __namespace: true }));

AU.language || (AU.language = { __namespace: true });
AU.ip_language || (AU.ip_language = { __namespace: true });

// Spanish
window.es_localization = AU.language.es = {
    "addFilesProgressDialog": {
        "cancelButtonText": "Cancelar",
        "currentFileText": "Archivo de procesamiento: “[name]”",
        "titleText": "Incorporación de archivos a la cola de carga",
        "totalFilesText": "archivos procesados: [count]",
        "waitText": "Espere unos momentos mientras termina el proceso..."
    },
    "authenticationDialog": {
        "cancelButtonText": "Cancelar",
        "loginText": "Inicio de sesión:",
        "okButtonText": "Aceptar",
        "passwordText": "Contraseña:",
        "realmText": "Territorio:",
        "text": "El host [name] requiere autenticación"
    },
    "contextMenu": {
        "addFilesText": "Agregar archivos...",
        "addFolderText": "Agregar carpeta...",
        "arrangeByDimensionsText": "Dimensiones",
        "arrangeByModifiedText": "Fecha de modificación",
        "arrangeByNameText": "Nombre",
        "arrangeByPathText": "Ruta",
        "arrangeBySizeText": "Tamaño",
        "arrangeByText": "Ordenar por",
        "arrangeByTypeText": "Tipo",
        "checkAllText": "Marcar todos",
        "checkText": "Marcar",
        "detailsViewText": "Detalles",
        "editText": "Editar imagen...",
        "editDescriptionText": "Editar descripción...",
        "listViewText": "Lista",
        "navigateToFolderText": "Ir a la carpeta",
        "openText": "Abrir",
        "pasteText": "Pegar",
        "removeAllText": "Eliminar todo",
        "removeText": "Eliminar",
        "thumbnailsViewText": "Miniaturas",
        "tilesViewText": "Mosaicos",
        "uncheckAllText": "Desmarcar todo",
        "uncheckText": "Desmarcar"
    },
    "deleteFilesDialog": {
        "message": "¿Está seguro de que desea eliminar de forma permanente los elementos cargados?",
        "titleText": "Eliminar archivo"
    },
    "descriptionEditor": {
        "cancelHyperlinkText": "Cancelar",
        "orEscLabelText": " (o Esc)",
        "saveButtonText": "Guardar"
    },
    "detailsViewColumns": {
        "dimensionsText": "Dimensiones",
        "fileNameText": "Nombre",
        "fileSizeText": "Tamaño",
        "fileTypeText": "Tipo",
        "infoText": "Información",
        "lastModifiedText": "Fecha de modificación"
    },
    "folderPane": {
        "filterHintText": "Búsqueda ",
        "headerText": "<totalLink>Archivos totales: [totalCount]</totalLink>, <allowedLink>permitidos: [allowedCount]</allowedLink>"
    },
    "imageEditor": {
        "cancelButtonText": "Cancelar",
        "cancelCropButtonText": "Cancelar recorte",
        "cropButtonText": "Recortar",
        "descriptionHintText": "Escriba la descripción aquí...",
        "rotateButtonText": "Girar",
        "saveButtonText": "Guardar y cerrar"
    },
    "messages": {
        "cmykImagesNotAllowed": "No se permiten las imágenes CMYK.",
        "deletingFilesError": "El archivo “[name]” no se puede eliminar",
        "dimensionsTooLarge": "La imagen “[name]” es demasiado grande para seleccionarla.",
        "dimensionsTooSmall": "La imagen “[name]” es demasiado pequeña para seleccionarla.",
        "fileNameNotAllowed": "El archivo “[name]” no se puede eliminar. El nombre de este archivo no es válido.",
        "fileSizeTooSmall": "El archivo “[name]” no se puede eliminar. El tamaño de este archivo es inferior al límite.",
        "filesNotAdded": "Debido a las restricciones, [count] archivos no se han añadido.",
        "maxFileCountExceeded": "El archivo “[name]” no se puede eliminar. El número de archivos supera el límite.",
        "maxFileSizeExceeded": "El archivo “[name]” no se puede eliminar. El tamaño de este archivo supera el límite.",
        "maxTotalFileSizeExceeded": "El archivo “[name]” no se puede eliminar. El tamaño total de datos cargados supera el límite.",
        "noResponseFromServer": "Sin respuesta del servidor.",
        "serverError": "Se ha producido algún error en el servidor. Si ve este mensaje, contacte con el administrador del sitio web.",
        "serverNotFound": "El servidor o proxy [name] no se ha encontrado.",
        "unexpectedError": "El cargador de imágenes ha detectado un problema. Si ve este mensaje, contacte con el administrador del sitio web.",
        "uploadCancelled": "La carga se ha cancelado.",
        "uploadCompleted": "Carga completa.",
        "uploadFailed": "Error en la carga (la conexión se ha interrumpido)."
    },
    "paneItem": {
        "descriptionEditorIconTooltip": "Editar descripción",
        "imageCroppedIconTooltip": "La imagen está recortada.",
        "imageEditorIconTooltip": "Previsualizar/editar imagen",
        "removalIconTooltip": "Eliminar",
        "rotationIconTooltip": "Girar"
    },
    "statusPane": {
        "clearAllHyperlinkText": "borrar todo",
        "filesToUploadText": "Archivos seleccionados para cargar: [count]",
        "noFilesToUploadText": "No hay archivos para cargar",
        "progressBarText": "Cargando ([percents]%)"
    },
    "treePane": {
        "titleText": "Carpetas",
        "unixFileSystemRootText": "Filesystem",
        "unixHomeDirectoryText": "Carpeta de inicio"
    },
    "uploadPane": {
        "dropFilesHereMacText": "Add files here...", // TODO: Translate
        "dropFilesHereText": "Soltar archivos aquí…"
    },
    "uploadProgressDialog": {
        "cancelUploadButtonText": "Cancelar",
        "estimationText": "Tiempo restante estimado: [time]",
        "hideButtonText": "Ocultar",
        "hoursText": "horas",
        "infoText": "Archivos cargados: [files]/[totalFiles] ([bytes] de [totalBytes])",
        "kilobytesText": "Kb",
        "megabytesText": "Mb",
        "minutesText": "minutos",
        "preparingText": "Preparando archivos para cargar...",
        "reconnectionText": "Error al cargar. Esperando para volver a conectar...",
        "secondsText": "segundos",
        "titleText": "Cargar archivos al servidor"
    },
    "cancelUploadButtonText": "Detener",
    "loadingFolderContentText": "Cargando contenido...",
    "uploadButtonText": "Cargar"
};
// Spanish
AU.ip_language.es = {
    "commonHtml": "<p>Necesita el control del cargador de imágenes de Aurigma para cargar archivos de forma rápida y sencilla. Podrá seleccionar varias imágenes desde una interfaz de fácil manejo en lugar de usar campos de entrada desorganizados con el botón <strong>Examinar</strong>.</p>",
    "progressHtml": "<p><img src=\"{0}\" /><br />Carga del cargador de imágenes de Aurigma...</p>",
    "flashProgressHtml": "<p><img src=\"{0}\" /><br />Carga del cargador de Flash de Aurigma...</p>",
    "beforeIE6XPSP2ProgressHtml": "<p>Para instalar el cargador de imágenes, espere mientras se carga el control y haga clic en el botón <strong>Sí</strong> cuando aparezca el cuadro de diálogo de instalación.</p>",
    "beforeIE6XPSP2InstructionsHtml": "<p>Para instalar el cargador de imágenes, vuelva a cargar la página y haga clic en el botón <strong>Sí</strong> cuando aparezca el cuadro de diálogo de instalación del control. Si no aparece el cuadro de diálogo de instalación, compruebe la configuración de seguridad.</p>",
    "IE6XPSP2ProgressHtml": "<p>Espere hasta que se cargue el control.</p>",
    "IE6XPSP2InstructionsHtml": "<p>Para instalar el cargador de imágenes, haga clic en la <strong>Barra de información</strong> y seleccione <strong>Instalar control ActiveX</strong> en el menú desplegable. Una vez recargada la página, haga clic en <strong>Instalar</strong> cuando aparezca el cuadro de diálogo de instalación del control. Si no ve la Barra de información, intente volver a cargar la página o compruebe la configuración de seguridad.</p>",
    "IE7ProgressHtml": "<p>Espere hasta que se cargue el control.</p>",
    "IE7InstructionsHtml": "<p>Para instalar el cargador de imágenes, haga clic en la <strong>Barra de información</strong> y seleccione <strong>Instalar control ActiveX</strong> o <strong>Ejecutar control ActiveX</strong> en el menú desplegable.</p><p>A continuación, haga clic en <strong>Ejecutar</strong> o, una vez recargada la página, haga clic en <strong>Instalar</strong> cuando aparezca el cuadro de diálogo de instalación del control. Si no ve la Barra de información, intente volver a cargar la página o compruebe la configuración de seguridad.</p>",
    "IE8ProgressHtml": "<p>Espere hasta que se cargue el control.</p>",
    "IE8InstructionsHtml": "<p>Para instalar el cargador de imágenes, haga clic en la <strong>Barra de información</strong> y seleccione <strong>Instalar este complemento</strong> o <strong>Ejecutar este complemento</strong> desde el menú desplegable.</p><p>A continuación, haga clic en <strong>Ejecutar</strong> o vuelva a cargar la página y haga clic en <strong>Instalar</strong> cuando aparezca el cuadro de diálogo de instalación del control. Si no ve la Barra de información, intente volver a cargar la página o compruebe la configuración de seguridad.</p>",
    "IE9ProgressHtml": "<p>Espere hasta que se cargue el control.</p>",
    "IE9InstructionsHtml": "<p>Para instalar el cargador de imágenes, haga clic en <strong>Permitir</strong> o en <strong>Instalar</strong> en la <strong>Barra de notificaciones</strong> de la parte inferior de la página.</p><p>A continuación, vuelva a cargar la página y haga clic en <strong>Instalar</strong> en el cuadro de diálogo de instalación del control. Si no ve la Barra de notificaciones, intente volver a cargar la página o compruebe la configuración de seguridad (los controles ActiveX deberán estar activados).</p>",
    "chromeProgressHtml": "<p>Aurigma Upload Suite requires Java plug-in. Please, click <strong>Run this time</strong> or <strong>Always run on this site</strong> to let Java plug-in run.</p>", // TODO: translate
    "updateInstructions": "Tiene que actualizar el control del cargador de imágenes. Haga clic en el botón <strong>Instalar</strong> o <strong>Ejecutar</strong> cuando vea el cuadro de diálogo de instalación del control. Si el cuadro de diálogo de instalación no aparece, intente volver a cargar la página.",
    "macInstallJavaHtml": "<p>Use la función <a href=\"http://support.apple.com/kb/HT1338\">Actualización de software</a> (en el menú de Apple) para comprobar si dispone de la versión más reciente de Java para su Mac.</p>",
    "installJavaHtml": "<p> <a href=\"http://www.java.com/getjava/\">Descargue</a> e instale Java.</p>",
    "installFlashPlayerHtml": "<p>Tiene que instalar el reproductor Flash para ejecutar el cargador de Flash de Aurigma. Descargue la versión más reciente desde <a href='http://www.adobe.com/go/getflashplayer' title='Download Flash Player'>aquí</a>.</p>"
};
})(window);
