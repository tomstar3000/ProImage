(function(window, undefined) {

// Define global Aurigma.ImageUploader namespace
var AU = (window.Aurigma || (window.Aurigma = { __namespace: true })) &&
    (window.Aurigma.ImageUploader || (window.Aurigma.ImageUploader = { __namespace: true }));

AU.language || (AU.language = { __namespace: true });
AU.ip_language || (AU.ip_language = { __namespace: true });

// French
window.fr_localization = AU.language.fr = {
    "addFilesProgressDialog": {
        "cancelButtonText": "Annuler",
        "currentFileText": "Traitement du fichier : « [name] »",
        "titleText": "Ajout de fichiers à la file d’attente de chargement",
        "totalFilesText": "fichiers déjà traités : [count]",
        "waitText": "Veuillez patienter, cette opération peut prendre un certain temps..."
    },
    "authenticationDialog": {
        "cancelButtonText": "Annuler",
        "loginText": "Identifiant :",
        "okButtonText": "OK",
        "passwordText": "Mot de passe :",
        "realmText": "Royaume :",
        "text": "L’hôte [name] requiert une authentification"
    },
    "contextMenu": {
        "addFilesText": "Ajouter des fichiers...",
        "addFolderText": "Ajouter un dossier...",
        "arrangeByDimensionsText": "Dimensions",
        "arrangeByModifiedText": "Date de modification",
        "arrangeByNameText": "Nom",
        "arrangeByPathText": "Chemin d’accès",
        "arrangeBySizeText": "Taille",
        "arrangeByText": "Trier par",
        "arrangeByTypeText": "Type",
        "checkAllText": "Tout cocher",
        "checkText": "Cocher",
        "detailsViewText": "Détails",
        "editText": "Modifier l’image...",
        "editDescriptionText": "Modifier la description...",
        "listViewText": "Liste",
        "navigateToFolderText": "Accéder au dossier",
        "openText": "Ouvrir",
        "pasteText": "Coller",
        "removeAllText": "Tout supprimer",
        "removeText": "Supprimer",
        "thumbnailsViewText": "Miniatures",
        "tilesViewText": "Mosaïques",
        "uncheckAllText": "Tout décocher",
        "uncheckText": "Décocher"
    },
    "deleteFilesDialog": {
        "message": "Êtes-vous sûr de vouloir supprimer définitivement les éléments chargés ?",
        "titleText": "Supprimer le fichier"
    },
    "descriptionEditor": {
        "cancelHyperlinkText": "Annuler",
        "orEscLabelText": " (ou Esc)",
        "saveButtonText": "Enregistrer"
    },
    "detailsViewColumns": {
        "dimensionsText": "Dimensions",
        "fileNameText": "Nom",
        "fileSizeText": "Taille",
        "fileTypeText": "Type",
        "infoText": "Infos",
        "lastModifiedText": "Date de modification"
    },
    "folderPane": {
        "filterHintText": "Rechercher ",
        "headerText": "<totalLink>Total des fichiers : [totalCount]</totalLink>, <allowedLink>autorisés : [allowedCount]</allowedLink>"
    },
    "imageEditor": {
        "cancelButtonText": "Annuler",
        "cancelCropButtonText": "Annuler le rognage",
        "cropButtonText": "Rogner",
        "descriptionHintText": "Saisir la description ici...",
        "rotateButtonText": "Rotation",
        "saveButtonText": "Enregistrer et fermer"
    },
    "messages": {
        "cmykImagesNotAllowed": "Les images CMYK ne sont pas autorisées.",
        "deletingFilesError": "Impossible de supprimer le fichier « [name] »",
        "dimensionsTooLarge": "L’image « [name] » est trop grande pour pouvoir être sélectionnée.",
        "dimensionsTooSmall": "L’image « [name] » est trop petite pour pouvoir être sélectionnée.",
        "fileNameNotAllowed": "Impossible de sélectionner le fichier « [name] ». Ce fichier porte un nom qui ne peut pas être autorisé.",
        "fileSizeTooSmall": "Impossible de sélectionner le fichier « [name] ». La taille de ce fichier est inférieure à la limite.",
        "filesNotAdded": "[count] fichiers n’ont pas été ajoutés en raison des restrictions.",
        "maxFileCountExceeded": "Impossible de sélectionner le fichier « [name] ». Le nombre de fichiers dépasse la limite.",
        "maxFileSizeExceeded": "Impossible de sélectionner le fichier « [name] ». La taille du fichier dépasse la limite.",
        "maxTotalFileSizeExceeded": "Impossible de sélectionner le fichier « [name] ». La taille totale des données chargées dépasse la limite.",
        "noResponseFromServer": "Aucune réponse du serveur.",
        "serverError": "Une erreur s’est produite côté serveur. Si ce message s’affiche, contactez votre webmestre.",
        "serverNotFound": "Le serveur ou le proxy [name] est introuvable.",
        "unexpectedError": "Aurigma Upload Suite a rencontré un problème. Si ce message s’affiche, contactez le webmestre.",
        "uploadCancelled": "Le chargement est annulé.",
        "uploadCompleted": "Chargement terminé.",
        "uploadFailed": "Échec du chargement (la connexion a été interrompue)."
    },
    "paneItem": {
        "descriptionEditorIconTooltip": "Modifier la description",
        "imageCroppedIconTooltip": "L’image est rognée.",
        "imageEditorIconTooltip": "Prévisualiser/modifier l’image",
        "removalIconTooltip": "Supprimer",
        "rotationIconTooltip": "Rotation"
    },
    "statusPane": {
        "clearAllHyperlinkText": "tout effacer",
        "filesToUploadText": "Fichiers sélectionnés pour le chargement : [count]",
        "noFilesToUploadText": "Aucun fichier à charger",
        "progressBarText": "Chargement en cours ([percents] %)"
    },
    "treePane": {
        "titleText": "Dossiers",
        "unixFileSystemRootText": "Système de fichiers",
        "unixHomeDirectoryText": "Dossier de départ"
    },
    "uploadPane": {
        "dropFilesHereMacText": "Add files here...", // TODO: Translate
        "dropFilesHereText": "Déposer les fichiers ici..."
    },
    "uploadProgressDialog": {
        "cancelUploadButtonText": "Annuler",
        "estimationText": "Temps restant estimé : [time]",
        "hideButtonText": "Masquer",
        "hoursText": "heures",
        "infoText": "Fichiers chargés : [files]/[totalFiles] ([bytes] sur [totalBytes])",
        "kilobytesText": "Kb",
        "megabytesText": "Mb",
        "minutesText": "minutes",
        "preparingText": "Préparation des fichiers pour le chargement...",
        "reconnectionText": "Échec du chargement. En attente de reconnexion...",
        "secondsText": "secondes",
        "titleText": "Chargement de fichiers sur le serveur"
    },
    "cancelUploadButtonText": "Arrêter",
    "loadingFolderContentText": "Chargement du contenu...",
    "uploadButtonText": "Charger"
};
// French
AU.ip_language.fr = {
    "commonHtml": "<p>Le contrôle Aurigma Upload Suite est nécessaire pour charger rapidement et aisément vos fichiers. Grâce au bouton <strong>Parcourir</strong>, vous pourrez sélectionner plusieurs images dans une interface conviviale au lieu d’utiliser des champs de saisie peu pratiques.</p>",
    "progressHtml": "<p><img src=\"{0}\" /><br />Chargement d’Aurigma Aurigma Upload Suite...</p>",
    "beforeIE6XPSP2ProgressHtml": "<p>Pour installer Aurigma Upload Suite, veuillez attendre le chargement du contrôle et cliquer sur le bouton <strong>Oui</strong> lorsque la boîte de dialogue d’installation s’affiche.</p>",
    "beforeIE6XPSP2InstructionsHtml": "<p>Pour installer Aurigma Upload Suite, veuillez recharger la page et cliquer sur le bouton <strong>Oui</strong> lorsque la boîte de dialogue d’installation du contrôle s’affiche. Si la boîte de dialogue d’installation ne s’affiche pas, vérifiez vos paramètres de sécurité.</p>",
    "IE6XPSP2ProgressHtml": "<p>Veuillez patienter pendant le chargement du contrôle.</p>",
    "IE6XPSP2InstructionsHtml": "<p>Pour installer Aurigma Upload Suite, veuillez cliquer sur la <strong>barre d’informations</strong> et sélectionner <strong>Installer le contrôle ActiveX</strong> dans le menu déroulant. Une fois la page rechargée, cliquez sur <strong>Installer</strong> lorsque la boîte de dialogue d’installation du contrôle s’affiche. Si la barre d’informations ne s’affiche pas, rechargez la page et/ou vérifiez vos paramètres de sécurité.</p>",
    "IE7ProgressHtml": "<p>Veuillez patienter pendant le chargement du contrôle.</p>",
    "IE7InstructionsHtml": "<p>Pour installer Aurigma Upload Suite, veuillez cliquer sur la <strong>barre d’informations</strong> et sélectionner <strong>Installer le contrôle ActiveX</strong> ou <strong>Exécuter le contrôle ActiveX</strong> dans le menu déroulant.</p><p>Ensuite, cliquez sur <strong>Exécuter</strong> ou, une fois la page rechargée, cliquez sur <strong>Installer</strong> lorsque la boîte de dialogue d’installation du contrôle s’affiche. Si la barre d’informations ne s’affiche pas, rechargez la page et/ou vérifiez vos paramètres de sécurité.</p>",
    "IE8ProgressHtml": "<p>Veuillez patienter pendant le chargement du contrôle.</p>",
    "IE8InstructionsHtml": "<p>Pour installer Aurigma Upload Suite, veuillez cliquer sur la <strong>barre d’informations</strong> et sélectionner <strong>Installer ce module complémentaire</strong> ou <strong>Exécuter le module complémentaire</strong> dans le menu déroulant.</p><p>Ensuite, cliquez sur <strong>Exécuter</strong> ou, une fois la page rechargée, cliquez sur <strong>Installer</strong> lorsque la boîte de dialogue d’installation du contrôle s’affiche. Si la barre d’informations ne s’affiche pas, rechargez la page et/ou vérifiez vos paramètres de sécurité.</p>",
    "IE9ProgressHtml": "<p>Veuillez patienter pendant le chargement du contrôle.</p>",
    "IE9InstructionsHtml": "<p>Pour installer Aurigma Upload Suite, veuillez cliquer sur <strong>Autoriser</strong> ou <strong>Installer</strong> sur la <strong>barre de notification</strong> située en bas de la page.</p><p>Ensuite, une fois la page rechargée, cliquez sur <strong>Installer</strong> dans la boîte de dialogue d’installation du contrôle. Si la barre de notification ne s’affiche pas, rechargez la page et/ou vérifiez vos paramètres de sécurité (les contrôles ActiveX doivent être activés).</p>",
    "chromeProgressHtml": "<p>Aurigma Upload Suite requires Java plug-in. Please, click <strong>Run this time</strong> or <strong>Always run on this site</strong> to let Java plug-in run.</p>", // TODO: translate
    "updateInstructions": "Vous devez mettre à jour le contrôle Aurigma Upload Suite. Cliquez sur le bouton <strong>Installer</strong> ou <strong>Exécuter</strong> lorsque la boîte de dialogue d’installation du contrôle s’affiche. Si la boîte de dialogue d’installation ne s’affiche pas, rechargez la page.",
    "macInstallJavaHtml": "<p>Utilisez la fonctionnalité <a href=\"http://support.apple.com/kb/HT1338\">Mise à jour de logiciels</a> (disponible sur le menu Apple) pour vérifier que votre Mac dispose bien de la dernière version de Java.</p>",
    "installJavaHtml": "<p>Veuillez <a href=\"http://www.java.com/getjava/\">télécharger</a> et installer Java.</p>"
};
})(window);
