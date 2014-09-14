
$.ajax({ cache: false });

$(document).ready(function() {
    var route = window.location.pathname;

    $("[href='" + route + "']").parent().addClass("active");

    $("#btnLogin").on("click", login);
    $("#btnVocabFilter").on("click", filterVocab);
    $("#btnCardSortFilter").on("click", filterCardSort);
    $("#btnVocabCSV").on("click", vocabCSV);
    $("#btnCardSortCSV").on("click", cardsortCSV);

    if (route.indexOf("/vocab") > -1 || route.indexOf("/cardsort") > -1) {
        formSetup();
    }
});

function formSetup() {
    $("#date_start").datepicker({
        dateFormat: "dd/mm/yy"
    });
    $("#date_end").datepicker({
        dateFormat: "dd/mm/yy"
    });
}

function filterVocab() {
    var test  = $("#test_name").val();
    var start = $("#date_start");
    var end   = $("#date_end");

    if (test == "" || start.val()== "" || end.val() == "") {
        alert("Please fill in all fields");
        return false;
    }

    $.colorbox({
        onOpen:function() {
            window.location.pathname = "/vocab/" + test + "/" + getDate(start) + "/" + getDate(end) + "/"
        }
    });
}

function filterCardSort() {
    var test  = $("#test_name").val();
    var start = $("#date_start");
    var end   = $("#date_end");

    if (test == "" || start.val()== "" || end.val() == "") {
        alert("Please fill in all fields");
        return false;
    }

    $.colorbox({
        onOpen:function() {
            window.location.pathname = "/cardsort/" + test + "/" + getDate(start) + "/" + getDate(end) + "/"
        }
    });
}

function getDate(input) {
    var dateObj = input.datepicker("getDate");
    var month = dateObj.getMonth() + 1;
    month = (month < 10) ? "0" + month : month;

    var day = dateObj.getDate();
    day = (day < 10) ? "0" + day : day;

    return dateObj.getFullYear() + "-" + month + "-" + day;
}

function login() {
    var form = $("#frmLogin");

    $.ajax({
        url:  "/login/submit",
        type: "POST",
        data: form.serialize(),
        beforeSend: function() {
            var returnVal = true;

            $("input").each(function() {
                if ($(this).val() == "") {
                    returnVal = false;
                }
            });

            if (returnVal == true) {
                $.colorbox({
                    escKey: false,
                    overlayClose: false,
                    closeButton: false
                });
            }

            return returnVal;
        },
        complete: function(data) {
            try {
                var json = $.parseJSON(data.responseText);
            } catch (e) {
                alert("JSON Error");
                return false;
            }

            console.log(json);

            if (json.error) {
                alert(json.error);
            } else {
                window.location.pathname = "/";
            }

            return true;
        }
    });
}

function vocabCSV() {
    $.colorbox({
        href: "/vocab/csv"
    });
}

function cardsortCSV() {
    $.colorbox({
        href: "/cardsort/csv"
    });
}