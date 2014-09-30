$.ajax({ cache: false });

$(document).ready(function () {
    var route = window.location.pathname;

    $("[href='" + route + "']").parent().addClass("active");

    $("#btnLogin").on("click", login);
    $("#btnVocabFilter").on("click", filterVocab);
    $("#btnCardSortFilter").on("click", filterCardSort);
    $("#btnVocabCSV").on("click", vocabCSV);
    $("#btnCardSortCSV").on("click", cardsortCSV);
    $("#btnQuestionnaireCSV").on("click", questionnaireCSV);
    $("#btnMrAntCSV").on("click", mrAntCSV);
    $("#btnFishSharkCSV").on("click", fishSharkCSV);
    $("#responseType").on("change", changeAnswerType);
    $("#btnNewUser").on("click", createNewUser);
    $("#btnUpdateUser").on("click", updateUser);
    $(document).delegate(".btnDeleteGame", "click", deleteGame);

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
        $("select").each(function () {

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
        $("select").each(function () {

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
    var test = $("#test_name").val();
    var start = $("#date_start");
    var end = $("#date_end");

    if (test == "" || start.val() == "" || end.val() == "") {
        alert("Please fill in all fields");
        return false;
    }

    $.colorbox({
        onOpen: function () {
            window.location.pathname = "/vocab/" + test + "/" + getDate(start) + "/" + getDate(end) + "/"
        }
    });
}

function filterCardSort() {
    var test = $("#test_name").val();
    var start = $("#date_start");
    var end = $("#date_end");

    if (test == "" || start.val() == "" || end.val() == "") {
        alert("Please fill in all fields");
        return false;
    }

    $.colorbox({
        onOpen: function () {
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
        url: "/login/submit",
        type: "POST",
        data: form.serialize(),
        beforeSend: function () {
            var returnVal = true;

            $("input").each(function () {
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
        complete: function (data) {
            try {
                var json = $.parseJSON(data.responseText);
            } catch (e) {
                alert("JSON Error");
                return false;
            }

            console.log(json);

            if (json.error) {
                alert(json.error);
                $.colorbox.close();
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

function fishSharkCSV() {
    $.colorbox({
        href: "/fishshark/csv"
    });
}

function createNewUser() {
    $.ajax({
        url: "/admin/newuser/submit",
        type: "POST",
        data: $("#frmNewUser").serialize(),
        beforeSend: function () {
            var username = $("[name=username]").val();
            var password = $("[name=password]").val();

            if (username == "") {
                alert("Please enter a valid username");
                return false;
            }

            if (password == "") {
                alert("Please enter a valid password");
                return false;
            }

            cBoxLoading();

            return true;
        },
        complete: function (data) {
            try {
                var json = $.parseJSON(data.responseText);
            } catch (e) {
                alert("JSON Error");
                console.log(data.responseText);
            }

            if (json.errorMsg) {
                alert(json.errorMsg);
            } else if (json.success == 1) {
                renderAlert("success", "Successfully Created New User", "/admin/users");
            } else if (json.redirect) {
                renderAlert("success", "Successfully Created New User", "/logout");
            }
        }
    })
}

function updateUser() {
    var user_id = $(this).data("user_id");

    $.ajax({
        url: "/admin/user/" + user_id + "/update",
        type: "POST",
        data: $("#frmUpdateUser").serialize(),
        complete: function (data) {
            try {
                var json = $.parseJSON(data.responseText);
            } catch (e) {
                alert("JSON Error");
                console.log(data.responseText);
            }

            if (json.errorMsg) {
                alert(json.errorMsg);
            } else if (json.success == 1) {
                renderAlert("success", "Successfully Updated User", "/admin/users");
            }
        }
    })
}

function renderAlert(type, msg, redirect) {
    var onclick = "";
    var href = "";

    if (redirect == false) {
        onclick = 'onclick="$.colorbox.close()"';
        href = "#";
    } else {
        href = redirect;
    }

    var view = '<div class="alert alert-' + type + '">' + msg + '<br/><br/><div class="text-center">';
    view += '<a class="btn btn-default" ' + onclick + ' href="' + href + '">OK</a></div>';

    $.colorbox({
        html: view
    });
}

function cBoxLoading() {
    $.colorbox({
        html: '<div class="well text-center"><img src="/vendor/colorbox/images/loading.gif" /></div>',
        onComplete: function () {
            $.colorbox.resize();
        }
    });
}

function deleteGame() {
    var game = $(this).data("game_type");
    var id = $(this).data("game_id");
    var confirm = $(this).data("confirm");

    if (confirm == 0) {
        $.colorbox({
            html: '<div class="well"><h3>Are you sure you want to delete this game?</h3><br/>' +
                '<a class="btn btn-danger pull-right btnDeleteGame" data-game_type="'+game+'" data-game_id="'+id+'" data-confirm="1"><i class="glyphicon glyphicon-trash"></i> YES: DELETE</a>' +
                '<a class="btn btn-default" onclick="$.colorbox.close();">NO: Cancel</a> </div>'
        })
    }

    if (confirm == 1) {
        $.ajax({
            url:    "/" + game + "/game/" + id + "/delete",
            type:   "GET",
            beforeSend: function() {
                cBoxLoading();
            },
            complete: function(data) {
                try {
                    var json = $.parseJSON(data.responseText);
                } catch (e) {
                    alert("JSON Error");
                    console.log(data.responseText);
                }

                if (json.success) {
                    renderAlert("success", "You have successfully deleted this game", "/" + game);
                }
            }
        });
    }

}