(function(window, undefined) {

// Define global Aurigma.ImageUploader namespace
var AU = (window.Aurigma || (window.Aurigma = { __namespace: true })) &&
    (window.Aurigma.ImageUploader || (window.Aurigma.ImageUploader = { __namespace: true }));

AU.nirvanixExtender = function (uploader) {
    ///	<summary>
    ///		Create Nirvanix extender
    ///	</summary>
    ///	<param name="uploader" type="$au.uploader">
    ///		Uploader
    ///	</param>
    ///	<returns type="$au.nirvanixExtender" />
    if (!(this instanceof AU.nirvanixExtender)) {
        return new AU.nirvanixExtender(uploader);
    }

    if (uploader.state() == 1) {
        AU.debug().showError('NirvanixExtender should be created before uploader initialization.');
    }

    uploader.uploadSettings().actionUrl('http://localhost');
    this._uploader = uploader;
    this._serviceError = new AU.event();

    var self = this;
    uploader.events().beforeUpload().add(function () { return self._onBeforeUpload.apply(self, arguments); });
    uploader.events().beforePackageUpload().add(function () { return self._onBeforePackageUpload.apply(self, arguments); });
    uploader.events().afterPackageUpload().add(function () { return self._onAfterPackageUpload.apply(self, arguments); });

    // Nirvanix response error codes
    this._errors = { "100": "MissingRequiredParameter", "101": "InvalidParameter"
				, "160": "UnsupportedOperation", "50103": "ParameterOutOfAcceptedRange"
				, "55001": "InvalidPayment", "55100": "CreditCardChargeFailed"
				, "65001": "CreateAccountFailed", "65002": "CreateAccountContactFailed"
				, "65003": "InvalidSlaError", "65004": "InvalidAppKey"
				, "65005": "InvalidAccountType", "65006": "DuplicateUserNameUnderAppKey"
				, "65007": "DuplicateAppName", "65008": "PageSizeExceedsLimit"
				, "65009": "DeletedAccount", "65010": "InvalidStatus"
				, "65011": "InvalidSecurityResponse", "65012": "InvalidUserNameOrPassword"
				, "65016": "DuplicateMasterAccountUserName", "65101": "InvalidContactParameter"
				, "65102": "AppNameNotFound", "65104": "UserNameNotFound"
				, "70001": "FolderNotFound", "70002": "FileNotFound"
				, "70003": "PathNotFound", "70004": "AlreadyExists"
				, "70005": "InvalidPath", "70006": "DownloadPathNotFound"
				, "70007": "MetadataDoesNotExist", "70008": "FolderAlreadyExists"
				, "70009": "PathTooLong", "70010": "FileFolderNameTooLong"
				, "70101": "NullOrEmptyPath", "70102": "InvalidPathCharacter"
				, "70103": "InvalidAbsolutePath", "70104": "InvalidRelativePath"
				, "70106": "FileIntegrityError", "70107": "InvalidMetadata"
				, "70108": "RangeNotSatisfiable", "70109": "PathTooShort"
				, "70110": "FileOffline", "70111": "InvalidImageDimensions"
				, "80001": "LoginFailed", "80003": "AccessDenied"
				, "80004": "InvalidMasterAccountID", "80005": "InvalidBillableAccountID"
				, "80006": "SessionNotFound", "80007": "AccountExpired"
				, "80015": "OutOfBytes", "80016": "OutOfBytesNonOwner"
				, "80018": "InvalidIPAddress", "80019": "DownloadFileSizeLimitExceeded"
				, "80020": "UploadBandwidthLimitExceeded", "80021": "StorageLimitExceeded"
				, "80022": "LimitAlreadyExceeded", "80023": "InvalidAccessToken"
				, "80101": "InvalidSessionToken", "80102": "ExpiredAccessToken"
				, "80103": "InvalidDownloadToken", "90001": "Configuration"
				, "90002": "SSLRequired", "100001": "Database"
    };
};

AU.nirvanixExtender.prototype = {
    __class: true,
    _onBeforeUpload: function () {
        if (!this.uploadToken() || !this.uploadHost() || !this.destFolderPath()) {
            AU.debug().showError("You should specify UploadToken, UploadHost, and DestFolderPath for using NirvanixExtender");
            return false;
        }
        this._uploader.uploadSettings().actionUrl(window.location.protocol + "//" + this.uploadHost() + "/Upload.ashx");
    },
    _onBeforePackageUpload: function () {
        var u = this._uploader, m = u.metadata();
        m.addCustomField("uploadToken", this.uploadToken());
        m.addCustomField("destFolderPath", this.destFolderPath());
    },
    _onAfterPackageUpload: function (index, response) {
        var rp = response + '';
        var re = /<ResponseCode>\s*(\d+)\s*<\/ResponseCode>/ig;

        if (re.test(rp)) {
            var responseCode = parseInt(RegExp.$1, 10);

            if (responseCode != 0) {
                if (this._serviceError.count() > 0) {
                    var handlers = this._serviceError, result = true;
                    // Call event handlers
                    for (var i = 0, imax = handlers.length; i < imax; i++) {
                        var r = handlers[i]();
                        // Return false if any handler returns false
                        result &= (r == undefined || !!r);
                    }
                    return result;
                } else {
                    var d = this._errors[responseCode];
                    if (d == undefined) {
                        d = "Unknown"
                    }

                    AU.debug().showError("Error occured (#" + responseCode + ", " + d + ") during an upload to Nirvanix service.");
                    return true;
                }
            }
        }
        else {
            AU.debug().showError("Unexpected response from Nirvanix service.");
            return true;
        }

    },
    _prop: function (name, value, setter) {
        if (setter) {
            // set passed value
            this['_' + name] = value;
        } else {
            // return current value
            return this['_' + name];
        }
    },
    uploadToken: function () {
        return this._prop('uploadToken', arguments[0], arguments.length);
    },
    uploadHost: function () {
        return this._prop('uploadHost', arguments[0], arguments.length);
    },
    destFolderPath: function () {
        return this._prop('destFolderPath', arguments[0], arguments.length);
    },
    serviceError: function () {
        if (arguments.length == 0) {
            return this._serviceError;
        } else {
            this._serviceError.add(arguments[0]);
        }
    }
};
})(window);
