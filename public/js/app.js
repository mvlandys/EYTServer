
$(document).ready(function() {
    var route = window.location.pathname;

    $("[href='" + route + "']").parent().addClass("active");
});