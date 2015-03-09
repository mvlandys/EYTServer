$.ajax({ cache: false });

$(document).ready(function () {
    var route = window.location.pathname;

    //$("[href='" + route + "']").parent().addClass("active");

    $("#btnLogin").on("click", login);
    $("#btnVocabFilter").on("click", filterVocab);
    $("#btnCardSortFilter").on("click", filterCardSort);
    $("#btnMrAntFilter").on("click", filterMrAnt);
    $("#btnFishSharkFilter").on("click", filterFishShark);
    $("#btnNotThisFilter").on("click", filterNotThis);
    $("#btnVocabCSV").on("click", vocabCSV);
    $("#btnCardSortCSV").on("click", cardsortCSV);
    $("#btnQuestionnaireCSV").on("click", questionnaireCSV);
    $("#btnMrAntCSV").on("click", mrAntCSV);
    $("#btnFishSharkCSV").on("click", fishSharkCSV);
    $("#btnNotThisCSV").on("click", notThisCSV);
    $("#responseType").on("change", changeAnswerType);
    $("#btnNewUser").on("click", createNewUser);
    $("#btnUpdateUser").on("click", updateUser);
    $("#btnSubmitPasswordResetRequest").on("click", requestPasswordReset);
    $("#btnSubmitPasswordReset").on("click", submitPasswordReset);
    $("#btnAllGameData").on("click", allGameDate);
    $("#btnDeleteUser").on("click", deleteUser);
    $(document).delegate(".btnDeleteGame", "click", deleteGame);

    if (route.indexOf("/vocab") > -1 || route.indexOf("/cardsort") > -1 || route.indexOf("/mrant") > -1 || route.indexOf("/fishshark") > -1 || route.indexOf("/notthis") > -1) {
        formSetup();
    }

    if (route.indexOf("/questionnaire/form") > -1) {
        qFormSetup();
        changeAnswerType()
    }

    if (route.indexOf("/admin/user/") > -1) {
        $("[name='perms[]']").chosen();
    }

    if (route == "/" && $("[name=test_name]").length) {
        $("[name=start]").datepicker({
            dateFormat: "dd/mm/yy"
        });
        $("[name=end]").datepicker({
            dateFormat: "dd/mm/yy"
        });
    }
});

function deleteUser() {
    $.colorbox();
}

function allGameDate() {
    $.colorbox();

    var test  = $("[name=test_name]").val();
    var start = $("[name=start]");
    var end   = $("[name=end]");
    var url   = "";

    if (start.val() != "" && end.val() != "") {
        url = "/csv/" + test + "/" + getDate(start) + "/" + getDate(end);
    } else {
        url = (test == "all") ? "/csv" : "/csv/" + test;
    }

    $.ajax({
        url:    url,
        type:   "GET",
        complete: function(data) {
            $.colorbox({
                html: data.responseText
            });
        }
    });
}

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
    $.colorbox({
        onOpen: function () {
            window.location.pathname = filterURL("vocab");
        }
    });
}

function filterCardSort() {
    $.colorbox({
        onOpen: function () {
            window.location.pathname = filterURL("cardsort");
        }
    });
}

function filterMrAnt() {
    $.colorbox({
        onOpen: function () {
            window.location.pathname = filterURL("mrant");
        }
    });
}

function filterFishShark() {
    $.colorbox({
        onOpen: function () {
            window.location.pathname = filterURL("fishshark");
        }
    });
}

function filterNotThis() {
    $.colorbox({
        onOpen: function () {
            window.location.pathname = filterURL("notthis");
        }
    });
}

function filterURL(game) {
    var test  = $("#test_name").val();
    var start = $("#date_start");
    var end   = $("#date_end");
    var url   = "";

    if (start.val() != "" && end.val() != "") {
        url = "/" + game +"/" + test + "/" + getDate(start) + "/" + getDate(end);
    } else if (test != "") {
        url = "/" + game +"/" + test;
    } else {
        url = "/" + game;
    }

    return url;
}

function getDate(input) {
    var dateObj = input.datepicker("getDate");
    var month = dateObj.getMonth() + 1;
    month = (month < 10) ? "0" + month : month;

    var day = dateObj.getDate();
    day = (day < 10) ? "0" + day : day;

    return dateObj.getFullYear() + "-" + month + "-" + day;
}

function login() { console.log("a");
    var form = $("#frmLogin");

    $.ajax({
        url: "/login/submit",
        type: "POST",
        data: form.serialize(),
        beforeSend: function () {
            var returnVal = true;

            var username = $("[name=username]").val();
            var password = $("[name=password]").val();

            if (username == "" || password == "") {
                returnVal = false;
                alert("Please enter a valid username and password");
            }

            if (returnVal == true) {
                $.colorbox({
                    escKey: false,
                    overlayClose: false,
                    closeButton: false
                });
            }

            console.log("b");

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
    var test    = $("#test_name").val();
    var start   = $("#date_start");
    var end     = $("#date_end");
    var url     = (start.val() == "" && end.val() == "") ? "/vocab/csv/" + test : "/vocab/csv/"  + test + "/" + getDate(start) + "/" + getDate(end) + "/";

    $.colorbox({
        href: url
    });
}

function cardsortCSV() {
    var test    = $("#test_name").val();
    var start   = $("#date_start");
    var end     = $("#date_end");
    var url     = (start.val() == "" && end.val() == "") ? "/cardsort/csv/" + test : "/cardsort/csv/"  + test + "/" + getDate(start) + "/" + getDate(end) + "/";

    $.colorbox({
        href: url
    });
}

function questionnaireCSV() {
    var test    = $("#test_name").val();
    var start   = $("#date_start");
    var end     = $("#date_end");
    var url     = (test == "" || start.val() == "" || end.val() == "") ? "/questionnaire/csv" : "/questionnaire/csv/"  + test + "/" + getDate(start) + "/" + getDate(end) + "/";

    $.colorbox({
        href: url
    });
}

function mrAntCSV() {
    var test    = $("#test_name").val();
    var start   = $("#date_start");
    var end     = $("#date_end");
    var url     = (start.val() == "" && end.val() == "") ? "/mrant/csv/" + test : "/mrant/csv/"  + test + "/" + getDate(start) + "/" + getDate(end) + "/";

    $.colorbox({
        href: url
    });
}

function fishSharkCSV() {
    var test    = $("#test_name").val();
    var start   = $("#date_start");
    var end     = $("#date_end");
    var url     = (start.val() == "" && end.val() == "") ? "/fishshark/csv/" + test : "/fishshark/csv/"  + test + "/" + getDate(start) + "/" + getDate(end) + "/";

    $.colorbox({
        href: url
    });
}

function notThisCSV() {
    var test    = $("#test_name").val();
    var start   = $("#date_start");
    var end     = $("#date_end");
    var url     = (start.val() == "" && end.val() == "") ? "/notthis/csv/" + test : "/notthis/csv/"  + test + "/" + getDate(start) + "/" + getDate(end) + "/";

    $.colorbox({
        href: url
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
            var password2 = $("[name=password2]").val();

            if (username == "") {
                alert("Please enter a valid username");
                return false;
            }

            if (password == "") {
                alert("Please enter a valid password");
                return false;
            }

            if (password != password2) {
                alert("Passwords do not match!");
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
    var perms = [];
    var form = $("#frmUpdateUser").serialize();

    /*
    $("#perms :selected").each(function() {
        perms.push($(this).val());
    });

    form.perms = perms;*/

    console.log(form);

    $.ajax({
        url: "/admin/user/" + user_id + "/update",
        type: "POST",
        data: form,
        beforeSend: function() {
            var password1 = $("[name=password]").val();
            var password2 = $("[name=password2]").val();

            if (password1 != password2) {
                alert("Passwords do not match!");
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
                renderAlert("success", "Successfully Updated User", "/admin/users");
            }
        }
    })
}

function renderAlert(type, msg, redirect) {
    var view = '<div class="alert alert-' + type + '">' + msg + '<br/><br/><div class="text-center">';
    view += '<a class="btn btn-default" onclick="$.colorbox.close()">OK</a></div>';

    $.colorbox({
        html: view,
        onCleanup: function() {
            if (redirect != false) {
                window.location.href = redirect;
            }
        }
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
    var last_id = $(this).data("last_id");

    if (confirm == 0) {
        $.colorbox({
            html: '<div class="well"><h3>Are you sure you want to delete this game?</h3><br/>' +
                '<a class="btn btn-danger pull-right btnDeleteGame" data-last_id="' + last_id + '" data-game_type="'+game+'" data-game_id="'+id+'" data-confirm="1"><i class="glyphicon glyphicon-trash"></i> YES: DELETE</a>' +
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
                    renderAlert("success", "You have successfully deleted this game", filterURL(game) + "#row" + last_id);
                }
            }
        });
    }
}

function requestPasswordReset() {
    $.ajax({
        url: "/passwordreset/request/submit",
        type: "POST",
        data: $("#frmPasswordResetRequest").serialize(),
        beforeSend: function() {
            var email = $("[name=email]").val();

            if (email == "") {
                alert("Please enter an email address");
                return false;
            }

            cBoxLoading();

            return true;
        },
        complete: function(data) {
            try {
                var json = $.parseJSON(data.responseText);
            } catch (e) {
                alert("Json Error");
                console.log(data.responseText);
                return;
            }

            if (json.error) {
                alert(json.error);
                console.log(json.error);
            } else if (json.success) {
                renderAlert("success", "Successfully requested a password reset. Please check your email", "/");
            }
        }
    });
}

function submitPasswordReset() {
    $.ajax({
        url: "/passwordreset/submit",
        type: "POST",
        data: $("#frmPasswordReset").serialize(),
        beforeSend: function() {
            var password = $("[name=password]").val();
            var password2 = $("[name=password2]").val();

            if (password != password2) {
                alert("Passwords do not match!");
                return false;
            }

            cBoxLoading();

            return true;
        },
        complete: function(data) {
            try {
                var json = $.parseJSON(data.responseText);
            } catch (e) {
                alert("Json Error");
                console.log(data.responseText);
                return;
            }

            if (json.error) {
                alert(json.error);
                console.log(json.error);
            } else if (json.success) {
                renderAlert("success", "Successfully reset password", "/");
            }
        }
    });
}