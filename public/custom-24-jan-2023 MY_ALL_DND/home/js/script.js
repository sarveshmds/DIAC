var message = "function disabled";

function rtclickcheck(keyp) {
    if (navigator.appName == "Netscape" && keyp.which == 3) { alert(message); return false; }

    if (navigator.appVersion.indexOf("MSIE") != -1 && event.button == 2) { alert(message); return false; }
}

document.onmousedown = rtclickcheck;



//////////F12 disable code////////////////////////
document.onkeypress = function (event) {
    event = (event || window.event);
    if (event.keyCode == 123) {
        //alert('No F-12');
        return false;
    }
}
document.onmousedown = function (event) {
    event = (event || window.event);
    if (event.keyCode == 123) {
        //alert('No F-keys');
        return false;
    }
}
document.onkeydown = function (event) {
    event = (event || window.event);
    if (event.keyCode == 123) {
        //alert('No F-keys');
        return false;
    }
}
/////////////////////end///////////////////////

//Disable right click script 
//visit http://www.rainbow.arch.scriptmania.com/scripts/ 
var message = "Sorry, right-click has been disabled";
/////////////////////////////////// 
function clickIE() { if (document.all) { (message); return false; } }
function clickNS(e) {
    if
        (document.layers || (document.getElementById && !document.all)) {
        if (e.which == 2 || e.which == 3) { (message); return false; }
    }
}
if (document.layers) { document.captureEvents(Event.MOUSEDOWN); document.onmousedown = clickNS; }
else { document.onmouseup = clickNS; document.oncontextmenu = clickIE; }
document.oncontextmenu = new Function("return false")
// 
function disableCtrlKeyCombination(e) {
    //list all CTRL + key combinations you want to disable
    var forbiddenKeys = new Array('a', 'n', 'c', 'x', 'v', 'j', 'w');
    var key;
    var isCtrl;
    if (window.event) {
        key = window.event.keyCode; //IE
        if (window.event.ctrlKey)
            isCtrl = true;
        else
            isCtrl = false;
    }
    else {
        key = e.which; //firefox
        if (e.ctrlKey)
            isCtrl = true;
        else
            isCtrl = false;
    }
    //if ctrl is pressed check if other key is in forbidenKeys array
    if (isCtrl) {
        for (i = 0; i < forbiddenKeys.length; i++) {
            //case-insensitive comparation
            if (forbiddenKeys[i].toLowerCase() == String.fromCharCode(key).toLowerCase()) {
                alert('Operation not permitted');
                return false;
            }
        }
    }
    return true;
}




$(function () {
    //----- OPEN
    $('[pd-popup-open]').on('click', function (e) {
        var targeted_popup_class = jQuery(this).attr('pd-popup-open');
        $('[pd-popup="' + targeted_popup_class + '"]').fadeIn(100);

        e.preventDefault();
    });

    //----- CLOSE
    $('[pd-popup-close]').on('click', function (e) {
        var targeted_popup_class = jQuery(this).attr('pd-popup-close');
        $('[pd-popup="' + targeted_popup_class + '"]').fadeOut(200);

        e.preventDefault();
    });
});

$(document).ready(function () {
    $("#testimonial-slider").owlCarousel({
        items: 2,
        itemsDesktop: [1000, 2],
        itemsDesktopSmall: [990, 1],
        itemsTablet: [768, 1],
        pagination: true,
        navigation: false,
        navigationText: ["", ""],
        slideSpeed: 1000,
        autoPlay: true
    });
});

function countChar(val) {
    var len = val.value.length;
    if (len >= 90) {
        val.value = val.value.substring(0, 90);
    } else {
        $("#charNum").text(90 - len);
    }
}

$("textarea").change(function () {
    if ($("textarea").val() !== 0) {
        $(this).css("opacity", "0.8");
    }
});