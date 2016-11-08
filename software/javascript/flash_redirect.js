// Flash Detection / Redirect  v1.1.1
// documentation: http://www.dithered.com/javascript/flash_redirect/index.html
// license: http://creativecommons.org/licenses/by/1.0/
// code by Chris Nott (chris[at]dithered[dot]com)
// requires: flash_detect.js (http://www.dithered.com/javascript/flash_detect/index.html)


// use flash_detect.js to return the Flash version
var flashVersion = getFlashVersion();

// Redirect to appropriate page
if (flashVersion >= requiredFlashVersion) location.replace(hasFlashURL);
else if (flashVersion > 0) location.replace(upgradeFlashURL);
else if (flashVersion == 0) location.replace(noFlashURL);
else if (flashVersion == flashVersion_DONTKNOW || flashVersion == null) location.replace(dontKnowFlashVersionURL);
