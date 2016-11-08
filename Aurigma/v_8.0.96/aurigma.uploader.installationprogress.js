(function(window, undefined) {

// Define global Aurigma.ImageUploader namespace
var AU = (window.Aurigma || (window.Aurigma = { __namespace: true })) &&
    (window.Aurigma.ImageUploader || (window.Aurigma.ImageUploader = { __namespace: true }));

AU.installationProgress = function (uploader) {
    if (!(this instanceof AU.installationProgress)) {
        return new AU.installationProgress(uploader);
    }

    this._uploader = uploader;

    //Show Loading Screen and Installation Screen by default.
    this._showDownloadingScreen = this._showInstallationScreen = true;    
    
    // Register ActiveX render callbacks.
    uploader.registerCallback('activeXBeforeOpenTagRender', this.onActiveXBeforeOpenTagRender, this);
    uploader.registerCallback('activeXBeforeCloseTagRender', this.onActiveXBeforeCloseTagRender, this);
    uploader.registerCallback('activeXAfterCloseTagRender', this.onActiveXAfterCloseTagRender, this);

    this._splashscreentimeout = 120000; // hide splash screen after 2 minutes
    var id = uploader.id();
    var type = uploader.type();
    var f = function () {
        var splash_screen = document.getElementById(id + "-progress");
        var uploader = document.getElementById(id);
        if (splash_screen)
            splash_screen.style.display = "none";
        if (uploader) {
            uploader.style.position = "";
            uploader.style.left = "0";
            uploader.style.visibility = "";
        }
    };

    uploader.events().initComplete().add(f);

    //show control after 2 min timeout
    // even if it is not loaded
    setTimeout(f, this._splashscreentimeout);

    //CSS classes
    this._progressCssClass = "au-ip-progress";
    this._instructionsCssClass = "au-ip-instructions";

    //Common description
    this._commonHtml = "<p>Aurigma Upload Suite control is necessary to upload "
		+ "your files quickly and easily. You will be able to select multiple images "
		+ "in user-friendly interface instead of clumsy input fields with <strong>Browse</strong> button.</p>";

    // Installation progress messages
    this._progressHtml = "<p><img src=\"{0}\" />"
		    + "<br />"
		    + "Loading Aurigma Upload Suite...</p>";

    this._progressImageUrl = "Scripts/InstallationProgress.gif";

    //Before IE 6 Windows XP SP2
    this._beforeIE6XPSP2ProgressHtml = "<p>To install Aurigma Upload Suite, please wait until the control is loaded and click "
		+ "the <strong>Yes</strong> button when you see the installation dialog.</p>";
    this._beforeIE6XPSP2InstructionsHtml = "<p>To install Aurigma Upload Suite, please reload the page and click "
		+ "the <strong>Yes</strong> button when you see the control installation dialog. "
		+ "If you don't see installation dialog, please check your security settings.</p>";

    //IE 6 Windows XP SP2
    this._IE6XPSP2ProgressHtml = "<p>Please wait until the control is loaded.</p>";
    this._IE6XPSP2InstructionsHtml = "<p>To install Aurigma Upload Suite, please click on the <strong>Information Bar</strong> and select "
		+ "<strong>Install ActiveX Control</strong> from the dropdown menu. After the page is reloaded click <strong>Install</strong> when "
		+ "you see the control installation dialog. If you don't see Information Bar, please try to reload the page and/or check your security settings.</p>";

    //IE 7
    this._IE7ProgressHtml = this._IE6XPSP2ProgressHtml;
    this._IE7InstructionsHtml = "<p>To install Aurigma Upload Suite, please click on the <strong>Information Bar</strong> "
		+ "and select <strong>Install ActiveX Control</strong> or <strong>Run ActiveX Control</strong> from the dropdown menu.</p>"
		+ "<p>Then either click <strong>Run</strong> or after the page is reloaded click <strong>Install</strong> "
		+ "when you see the control installation dialog. If you don't see Information Bar, please try to reload the page and/or check your security settings.</p>";

    //IE 8
    this._IE8ProgressHtml = this._IE6XPSP2ProgressHtml;
    this._IE8InstructionsHtml = "<p>To install Aurigma Upload Suite, please click on the <strong>Information Bar</strong> "
		+ "and select <strong>Install This Add-on</strong> or <strong>Run Add-on</strong> from the dropdown menu.</p>"
		+ "<p>Then either click <strong>Run</strong> or after the page is reloaded click <strong>Install</strong> "
		+ "when you see the control installation dialog. If you don't see Information Bar, please try to reload the page and/or check your security settings.</p>";

    //IE 9
    this._IE9ProgressHtml = this._IE6XPSP2ProgressHtml;
    this._IE9InstructionsHtml = "<p>To install Aurigma Upload Suite, please click <strong>Allow</strong> or "
        + "<strong>Install</strong> on the <strong>Notification Bar</strong> at the bottom of the page.</p>"
        + "<p>Then after the page is reloaded click <strong>Install</strong> on the control installation dialog. "
        + "If you don't see Notification Bar, please try to reload the page and/or check your security settings (ActiveX controls should be enabled).</p>";

    //Alternative standalone installer
    this._altInstallerHtml = "";

    /**************************************
    * Update ActiveX control instructions *
    ***************************************/
    this._updateInstructions = "You need to update Aurigma Upload Suite control. Click <strong>Install</strong> or <strong>Run</strong> button when you see the control installation dialog."
        + " If you don't see installation dialog, please try to reload the page.";
}

AU.installationProgress.prototype.__class = true;

/*******************************************************
* Render progress and installation screens for ActiveX *
********************************************************/

AU.installationProgress.prototype.onActiveXBeforeOpenTagRender = function (uploader, args) {
    // Create Loading Screen html for ActiveX control if Loading Screen enabled.
    if (this.showDownloadingScreen()) {
        args.resultHtml = this._createProgressScreen(uploader, 'ax');
    }
};

AU.installationProgress.prototype.onActiveXAfterCloseTagRender = function (uploader, args) {
    if (this.showDownloadingScreen() && uploader.activeXControl().isActiveXSupported()) {
        // If Loading Screen enabled we wrap control into div container.
        // This is the closing tag for this div.
        args.resultHtml = '</div>';
    }
};

AU.installationProgress.prototype.onActiveXBeforeCloseTagRender = function (uploader, args) {

    // Create Installation instructions html for ActiveX control if Installation Screen enabled.
    if (this.showInstallationScreen()) {

        var html = [];

        html.push('<div ');
        html.push('id="' + uploader.id() + '-install-bg"');
        html.push(' style="background-color:#fff;position:relative;z-index:1000;">');
        html.push('<div ');
        html.push('id="' + uploader.id() + '-install"');
        var w = uploader.width(), h = uploader.height();
        // if value is without units add 'px'
        w = parseInt(w) + '' == w ? w + 'px' : w;
        h = parseInt(h) + '' == h ? h + 'px' : h;

        html.push(' style="margin:0;padding:0;border:0 none;overflow: hidden;width:' + w + ';height:' + h + '"');
        if (this._instructionsCssClass) {
            html.push(' class="' + this._instructionsCssClass + '"');
        }
        html.push(">");

        html.push(this._commonHtml);

        if (uploader.activeXControl().getActiveXInstalledToUpdate()) {
            html.push(this._updateInstructions);
        } else {
            if (AU.browser.isBeforeIE6XPSP2) {
                html.push(this._beforeIE6XPSP2InstructionsHtml)
            } else if (AU.browser.isIE6XPSP2) {
                html.push(this._IE6XPSP2InstructionsHtml);
            } else if (AU.browser.isIE7) {
                html.push(this._IE7InstructionsHtml);
            } else if (AU.browser.isIE8) {
                html.push(this._IE8InstructionsHtml);
            } else if (AU.browser.isIE9) {
                html.push(this._IE9InstructionsHtml);
            } else {
                html.push(this._IE9InstructionsHtml);
            }

            if (this._altInstallerHtml) {
                html.push(this._altInstallerHtml);
            }
        }

        html.push("</div>");
        html.push("</div>");
        args.resultHtml = html.join('');
    }
};

AU.installationProgress.prototype._createProgressScreen = function (uploader, type) {
    var html = [], m;
    var w = uploader.width();
    var rg = /^(\d+)([^0-9]+)?$/;
    if (m = rg.exec(w)) {
        if (m[2] == '%') {
            uploader.width('100%');
        }
        // if value is without units add 'px'
        if (!m[2]) {
            w += 'px';
        }
    } else {
        showWarning('InstallationProgress: Can not parse uploader width: ' + w);
    }
    var h = uploader.height();
    if (m = rg.exec(h)) {
        if (m[2] == '%') {
            uploader.height('100%');
        }
        // if value is without units add 'px'
        if (!m[2]) {
            h += 'px';
        }
    } else {
        showWarning('InstallationProgress: Can not parse uploader height: ' + h);
    }

    // wrap progress screen and uloader into div
    html.push('<div ');
    html.push(' style="position: relative;width:' + w + ';height:' + h + ';"');
    html.push('>');

    // progress div
    html.push('<div');
    html.push(' id="' + uploader.id() + '-progress"');
    html.push(' style="border:0 none;margin:0;padding:0;position:absolute;top:0;left:0;overflow:hidden;width:100%;height:100%"');
    if (this._progressCssClass) {
        html.push(' class="' + this._progressCssClass + '"');
    }
    html.push('>');
    if (type === 'ax') {
        html.push((this._progressHtml + '').replace('{0}', this._progressImageUrl));
        html.push(this._commonHtml);
        if (uploader.activeXControl().getActiveXInstalledToUpdate()) {
            html.push(this._updateInstructions);
        } else {
            if (AU.browser.isBeforeIE6XPSP2) {
                html.push(this._beforeIE6XPSP2ProgressHtml);
            } else if (AU.browser.isIE6XPSP2) {
                html.push(this._IE6XPSP2ProgressHtml);
            } else if (AU.browser.isIE7) {
                html.push(this._IE7ProgressHtml);
            } else if (AU.browser.isIE8) {
                html.push(this._IE8ProgressHtml);
            } else if (AU.browser.isIE9) {
                html.push(this._IE9ProgressHtml);
            } else {
                html.push(this._IE9ProgressHtml);
            }
        }
	}

    html.push("</div>");
    return html.join("");
};

AU.installationProgress.prototype.set = function (values) {
    for (var prop in values) {
        if (values.hasOwnProperty(prop) && typeof this[prop] === 'function') {
            this[prop](values[prop]);
        }
    }
};

AU.installationProgress.prototype._prop = function (name, value, action) {
    if (action) {
        // set passed value
        this['_' + name] = value;
    } else {
        // return current value
        return this['_' + name];
    }
};

AU.installationProgress.prototype.showDownloadingScreen = function (value) {
    return this._prop('showDownloadingScreen', arguments[0], arguments.length);
};

AU.installationProgress.prototype.showInstallationScreen = function (value) {
    return this._prop('showInstallationScreen', arguments[0], arguments.length);
};

AU.installationProgress.prototype.progressCssClass = function (value) {
    return this._prop('progressCssClass', arguments[0], arguments.length);
};

AU.installationProgress.prototype.instructionsCssClass = function (value) {
    return this._prop('instructionsCssClass', arguments[0], arguments.length);
};

AU.installationProgress.prototype.commonHtml = function (value) {
    return this._prop('commonHtml', arguments[0], arguments.length);
};

AU.installationProgress.prototype.progressHtml = function (value) {
    return this._prop('progressHtml', arguments[0], arguments.length);
};

AU.installationProgress.prototype.progressImageUrl = function (value) {
    return this._prop('progressImageUrl', arguments[0], arguments.length);
};

AU.installationProgress.prototype.beforeIE6XPSP2ProgressHtml = function (value) {
    return this._prop('beforeIE6XPSP2ProgressHtml', arguments[0], arguments.length);
};

AU.installationProgress.prototype.beforeIE6XPSP2InstructionsHtml = function (value) {
    return this._prop('beforeIE6XPSP2InstructionsHtml', arguments[0], arguments.length);
};

AU.installationProgress.prototype.IE6XPSP2ProgressHtml = function (value) {
    return this._prop('IE6XPSP2ProgressHtml', arguments[0], arguments.length);
};

AU.installationProgress.prototype.IE6XPSP2InstructionsHtml = function (value) {
    return this._prop('IE6XPSP2InstructionsHtml', arguments[0], arguments.length);
};

AU.installationProgress.prototype.IE7ProgressHtml = function (value) {
    return this._prop('IE7ProgressHtml', arguments[0], arguments.length);
};

AU.installationProgress.prototype.IE7InstructionsHtml = function (value) {
    return this._prop('IE7InstructionsHtml', arguments[0], arguments.length);
};

AU.installationProgress.prototype.IE8ProgressHtml = function (value) {
    return this._prop('IE8ProgressHtml', arguments[0], arguments.length);
};

AU.installationProgress.prototype.IE8InstructionsHtml = function (value) {
    return this._prop('IE8InstructionsHtml', arguments[0], arguments.length);
};

AU.installationProgress.prototype.IE9ProgressHtml = function (value) {
    return this._prop('IE9ProgressHtml', arguments[0], arguments.length);
};

AU.installationProgress.prototype.IE9InstructionsHtml = function (value) {
    return this._prop('IE9InstructionsHtml', arguments[0], arguments.length);
};

AU.installationProgress.prototype.altInstallerHtml = function (value) {
    return this._prop('altInstallerHtml', arguments[0], arguments.length);
};

AU.installationProgress.prototype.updateInstructions = function (value) {
    return this._prop('updateInstructions', arguments[0], arguments.length);
};

var defaultStyle = '.au-ip-progress{background-color:#f5f5f5;text-align:center}\r\n' +
    '.au-ip-instructions{background-color:#f5f5f5}\r\n' +
    '.au-ip-progress p,.au-ip-instructions p{margin:10px}';

var styleId = 'au_installation_progress_style';
try {
    var stylesheet = document.getElementById(styleId);
    if (!stylesheet) {
        stylesheet = document.createElement('style');
        stylesheet.setAttribute('id', styleId);
        stylesheet.setAttribute('type', 'text/css');
        try {
            // w3c comliant
            stylesheet.appendChild(document.createTextNode(defaultStyle));
        } catch (err) {
            if (stylesheet.styleSheet) {
                // IE in quirks mode
                stylesheet.styleSheet.cssText = defaultStyle;
            }
        }
        var head = document.getElementsByTagName('head')[0];
        if (head) {
            head.insertBefore(stylesheet, head.firstChild);
        }
    }
} catch (err) {
}
})(window);
