/**  
 * 
 * @see single-oxy_content.php
 **/
$(document).ready(function() {
    $("#audioSwitch").click(function() {
        var classes = document.getElementById("showAudio").getAttribute("class");
        var hide = "hidden";
        if (classes.indexOf(hide) !== -1) {
            $("#showAudio").removeClass(hide);
        } else {
            $("#showAudio").addClass(hide);
        }
    });
});