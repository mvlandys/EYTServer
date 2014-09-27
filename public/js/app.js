
$.ajax({ cache: false });

$(document).ready(function() {
    var route = window.location.pathname;

    $("[href='" + route + "']").parent().addClass("active");

    $("#btnLogin").on("click", login);
    $("#btnVocabFilter").on("click", filterVocab);
    $("#btnCardSortFilter").on("click", filterCardSort);
    $("#btnVocabCSV").on("click", vocabCSV);
    $("#btnCardSortCSV").on("click", cardsortCSV);
    $("#btnQuestionnaireCSV").on("click", questionnaireCSV);
    $("#btnMrAntCSV").on("click", mrAntCSV);
    $("#responseType").on("change", changeAnswerType);

    if (route.indexOf("/vocab") > -1 || route.indexOf("/cardsort") > -1 || route.indexOf("/mrant") > -1) {
        formSetup();
    }

    if (route.indexOf("/questionnaire/form") > -1) {
        qFormSetup();
        changeAnswerType()
    }
});

function qFormSetup() {
    $("[name=dob]").datepicker({
        dateFormat: "dd/mm/yy"
    });
}

function changeAnswerType() {
    var type = $("#responseType :selected").val();

    if (type == 1) {
        $("select").each(function() {

            if ($(this).attr("name") == "sex" || $(this).attr("id") == "responseType") {
                return;
            }

            $(this).html(
                "<option value='.'>Please Select</option>" +
                    "<option value='1'>Not True</option>" +
                    "<option value='3'>Somewhat True</option>" +
                    "<option value='5'>Certainly True</option>");
        });
    } else if (type == 2) {
        $("select").each(function() {

            if ($(this).attr("name") == "sex" || $(this).attr("id") == "responseType") {
                return;
            }

            $(this).html(
                "<option value='.'>Please Select</option>" +
                    "<option value='1'>Not True</option>" +
                    "<option value='2'>A Little True</option>" +
                    "<option value='3'>Moderately True</option>" +
                    "<option value='4'>Mostly True</option>" +
                    "<option value='5'>Completely True</option>");
        });
    }
}

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

function questionnaireCSV() {
    $.colorbox({
        href: "/questionnaire/csv"
    });
}

function mrAntCSV() {
    $.colorbox({
        href: "/mrant/csv"
    });
}