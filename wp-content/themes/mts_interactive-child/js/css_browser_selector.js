// Opera 8.0+ (UA detection to detect Blink/v8-powered Opera)
isOpera = !!window.opera || navigator.userAgent.indexOf(' OPR/') >= 0;
// Firefox 1.0+
isFirefox = typeof InstallTrigger !== 'undefined';
// At least Safari 3+: "[object HTMLElementConstructor]"
isSafari = Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0;
// At least IE6
isIE = /*@cc_on!@*/!!document.documentMode;
// Edge 20+
isEdge = !isIE && !!window.StyleMedia;
// Chrome 1+
isChrome = !!window.chrome && !!window.chrome.webstore;
// Blink engine detection
isBlink = (isChrome || isOpera) && !!window.CSS;

if (isChrome) {
  jQuery("html").addClass("chrome");
} else if (isFirefox) {
  jQuery("html").addClass("firefox");
}else if (isOpera) {
  jQuery("html").addClass("opera");
}else if (isSafari) {
  jQuery("html").addClass("safari");
}else if (isIE) {
  jQuery("html").addClass("ie");
}else if (isEdge) {
  jQuery("html").addClass("edge");
}else if (isBlink) {
  jQuery("html").addClass("blink");
}else {
  jQuery("html").addClass("other");
}