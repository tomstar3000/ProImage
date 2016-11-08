(function (window, undefined) {
    var 
    // local variable for global namespace
    AU = window.Aurigma ? (window.Aurigma.ImageUploader || {}) : {};

    function prop(name, value, setter) {
        if (setter) {
            // set passed value
            this['_' + name] = value;
        } else {
            // return current value
            return this['_' + name];
        }
    }

    AU.amazonS3Extender = function (uploader) {
        ///	<summary>
        ///		Create Amazon S3 extenser
        ///	</summary>
        ///	<param name="uploader" type="$au.uploader">
        ///		Uploader
        ///	</param>
        ///	<returns type="$au.amazonS3Extender" />
        if (!(this instanceof AU.amazonS3Extender)) {
            return new AU.amazonS3Extender(uploader);
        }

        if (uploader.state() == 1) {
            $au.debug().showError('AmazonS3Extender should be created before uploader initialization.');
        }

        this._uploader = uploader;
        this._converters = new AU.amazonS3Extender.converters();
        this._converterIndex = -1;
        var self = this;

        uploader.events().beforeSendRequest().add(function () { return self._onBeforeSendRequest.apply(self, arguments); });
        uploader.events().initComplete().add(function () { return self._onInitComplete.apply(self, arguments); });
        uploader.events().beforeUpload().add(function () { return self._onBeforeUpload.apply(self, arguments); });
    };

    AU.amazonS3Extender.prototype = {
        __class: true,

        // TODO: Make sure if all field names are presented here and the names are corrected.
        predefinedFields: {
            description: "Description_[itemIndex]",
            width: "Width_[itemIndex]",
            height: "Height_[itemIndex]",
            angle: "Angle_[itemIndex]",
            sourceName: "SourceName_[itemIndex]",
            horizontalResolution: "HorizontalResolution_[itemIndex]",
            verticalResolution: "VerticalResolution_[itemIndex]",
            sourceFileSize: "SourceSize_[itemIndex]",
            sourceCreatedDateTime: "SourceCreatedDateTime_[itemIndex]",
            sourceLastModifiedDateTime: "SourceLastModifiedDateTime_[itemIndex]",
            sourceCreatedDateTimeLocal: "SourceCreatedDateTimeLocal_[itemIndex]",
            sourceLastModifiedDateTimeLocal: "SourceLastModifiedDateTimeLocal_[itemIndex]",
            thumbnailSucceeded: "Thumbnail[converterIndex]Succeeded_[itemIndex]",
            fileMode: "File[converterIndex]Mode_[itemIndex]",
            fileSize: "File[converterIndex]Size_[itemIndex]",
            fileWidth: "File[converterIndex]Width_[itemIndex]",
            fileHeight: "File[converterIndex]Height_[itemIndex]",
            fileName: "File[converterIndex]Name_[itemIndex]",
            packageFileCount: "PackageFileCount",
            packageIndex: "PackageIndex",
            packageCount: "PackageCount",
            packageGuid: "PackageGuid",
            cropBounds: "CropBounds_[itemIndex]"
        },

        _prop: prop,

        bucket: function (value) {
            ///	<summary>
            ///		Get or set bucket
            ///	</summary>
            ///	<param name="value" type="String" />
            ///	<returns type="String" />
            return this._prop('bucket', value, arguments.length);
        },
        accessKeyId: function (value) {
            ///	<summary>
            ///		Get or set AWS access key
            ///	</summary>
            ///	<param name="value" type="String" />
            ///	<returns type="String" />
            return this._prop('accessKeyId', value, arguments.length);
        },
        bucketHostName: function (value) {
            ///	<summary>
            ///		Get or set bucket host name. Bucket host name. "s3.amazonaws.com" will be used if not specified.
            ///	</summary>
            ///	<param name="value" type="String" />
            ///	<returns type="String" />
            return this._prop('bucketHostName', value, arguments.length);
        },
        converters: function (value) {
            ///	<summary>
            ///		Get or set settings for file for every converter
            ///	</summary>
            ///	<param name="value" type="Array">
            ///		Array of settings for converters files
            ///	</param>
            ///	<returns type="$au.amazonS3Extender.converters" />
            if (arguments.length == 0) {
                return this._converters;
            } else {
                this._converters.set(value);
            }
        },
        _onBeforeSendRequest: function () {
            var u = this._uploader, converterIndex = (++this._converterIndex % this._converterCount), m = u.metadata(),
                amzMetaPrefix = 'x-amz-meta-';

            // disable standard fiels
            m.enableAllStandardFields(false);

            // rename and enable file field
            m.enableStandardField("File[converterIndex]_[itemIndex]", true);
            m.renameStandardField("File[converterIndex]_[itemIndex]", "file");

            // clear custom fields from previous file
            var prevMeta = this.converters().get((converterIndex + this._converterCount - 1) % this._converterCount).meta();
            if (prevMeta && prevMeta.length > 0) {
                for (var i = 0, imax = prevMeta.length; i < imax; i++) {
                    var mm = prevMeta[i];
                    if (mm.name && mm.value != null) {
                        m.removeCustomField(amzMetaPrefix + mm.name);
                    }
                }
            }

            // add files fields
            var f = this.converters().get(converterIndex);
            if (f) {
                m.addCustomField('acl', f.acl());
                m.addCustomField('key', f.key());
                m.addCustomField('policy', f.policy());
                m.addCustomField('signature', f.signature());

                // Add custom fields
                var meta = f.meta();
                if (meta && meta.length > 0) {
                    for (var i = 0, imax = meta.length; i < imax; i++) {
                        var mm = meta[i], name = mm.name;
                        if (name) {
                            var value = meta[i].value, field = meta[i].field;
                            if (field != null) {
                                // Add standard field
                                m.renameStandardField(field, amzMetaPrefix + name);
                                m.enableStandardField(field, true);
                            } else if (value != null) {
                                // Add custom field
                                m.addCustomField(amzMetaPrefix + name, value);
                            }
                        }
                    }
                }
            }

            this._converterIndex = converterIndex;

        },
        _onInitComplete: function () {
            var u = this._uploader;

            // Set action to Amazon S3
            var hostName = this.bucketHostName() ? this.bucketHostName() : this.bucket() + ".s3.amazonaws.com";
            var us = u.uploadSettings();
            us.actionUrl(window.location.protocol + "//" + hostName);

            // Upload one file per request.
            us.uploadConverterOutputSeparately(true);

            // To prepare and upload files simultaneously.
            us.filesPerPackage(1);

            // Chunk upload on Amazon S3 is unsupported.
            us.chunkSize(0);

            // Add common Amazon S3 fields.
            var m = u.metadata();
            m.addCustomField("success_action_status", "200");
            m.addCustomField("AWSAccessKeyId", this.accessKeyId());
        },
        _onBeforeUpload: function () {
            this._converterCount = this._uploader.converters().count();
            this._converterIndex = -1;
        }
    };

    AU.amazonS3Extender.prototype.constructor = AU.amazonS3Extender;

    AU.amazonS3Extender.converters = function () {
        ///	<summary>
        ///		Get or set settings for file for every converter
        ///	</summary>
        ///	<param name="value" type="Array">
        ///		Array of settings for files
        ///	</param>
        ///	<returns type="$au.amazonS3Extender.converters" />
        if (!(this instanceof AU.amazonS3Extender.converters)) {
            return new AU.amazonS3Extender.converters();
        }

        this._list = {};
    };

    AU.amazonS3Extender.converters.prototype = {
        __class: true,

        clear: function () {
            ///	<summary>
            ///		Clear settings for all converters
            ///	</summary>
            this._list = {};
        },
        get: function (index) {
            ///	<summary>
            ///		Get settings for particular converter
            ///	</summary>
            ///	<param name="index" type="Number">
            ///		Index of converter settings
            ///	</param>
            ///	<returns type="$au.amazonS3Extender.fileSettings" />
            if (!this._list[index]) {
                this._list[index] = new AU.amazonS3Extender.fileSettings();
            }
            return this._list[index];
        },
        remove: function (index) {
            ///	<summary>
            ///		Remove settings for particular converter
            ///	</summary>
            ///	<param name="index" type="Number">
            ///		Index of converter
            ///	</param>
            this._list.splice(index, 1);
        },
        set: function (obj) {
            for (var i = 0, imax = obj.length; i < imax; i++) {
                var fs = new AU.amazonS3Extender.fileSettings(), opt = obj[i];
                this._list[i] = fs;
                for (var name in opt) {
                    if (!opt.hasOwnProperty || opt.hasOwnProperty(name)) {
                        if (typeof fs[name] === 'function') {
                            fs[name](opt[name]);
                        } else {
                            showWarning('$au.amazonS3Extender.fileSettings has not "' + name + '" property.');
                        }
                    }
                }
            }
        }
    };

    AU.amazonS3Extender.converters.prototype.constructor = AU.amazonS3Extender.converters;

    AU.amazonS3Extender.fileSettings = function () {
        if (!(this instanceof AU.amazonS3Extender.fileSettings)) {
            return new AU.amazonS3Extender.fileSettings();
        }
        this._meta = [];
    };

    AU.amazonS3Extender.fileSettings.prototype = {
        __class: true,
        _prop: prop,

        acl: function (value) {
            return this._prop('acl', value, arguments.length);
        },
        key: function (value) {
            return this._prop('key', value, arguments.length);
        },
        policy: function (value) {
            return this._prop('policy', value, arguments.length);
        },
        signature: function (value) {
            return this._prop('signature', value, arguments.length);
        },
        meta: function (value) {
            ///	<summary>
            ///		Get or set array of additional fields to send along with file.
            ///     Note, that this fields should be added to the policy.
            ///	</summary>
            ///	<param name="value" type="Array">
            ///		Array of additional fields. 
            ///     Additional field can be custom field, like { name: 'author', value: 'John Doe' },
            ///     or it can be predefined field, like { name: 'width', field: 'Width_[itemIndex]'}.
            ///	</param>
            ///	<returns type="Array" />
            return this._prop('meta', value, arguments.length);
        }
    };

    AU.amazonS3Extender.fileSettings.prototype.constructor = AU.amazonS3Extender.fileSettings;

    //expose to global
    window.Aurigma = window.Aurigma || { __namespace: true };
    window.Aurigma.ImageUploader = AU;

})(window);