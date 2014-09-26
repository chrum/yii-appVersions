/**
 * Created by chrystian on 9/16/14.
 */
var appVersion = function() {
    var currentLang = "dk";
    var previousMessage = "";
    var defaultMessage = "" +
        "<div class='header-text'>" +
            "<h4>New version</h4>" +
        "</div>"+
        "<div class='text'>" +
            "New version of the app is available, please install it." +
        "</div>";

    var updatePreview = function(newMessage) {
        //var html = $.parseHTML(message);
        $("#maintenanceView").html(newMessage);
    };

    var showDefault = function() {
        $("#showDefault").hide();
        $("#showPrevious").show();
        previousMessage = $("#message-"+currentLang).val();
        $("#message-"+currentLang).val(defaultMessage);
        updatePreview(defaultMessage);
    };

    var showPrevious = function() {
        $("#message-"+currentLang).val(previousMessage);
        updatePreview(previousMessage);
        previousMessage = "";
        $("#showPrevious").hide();
        $("#showDefault").show();
    };

    var langChanged = function(newLang) {
        currentLang = newLang;
        $("#updatePreview").click();
        $("#showPrevious").hide();
        $("#showDefault").show();
    };

    var changeVersion = function(newVersion) {
        var address = document.location.href.split("?")[0];
        document.location.href = address + "?currentVersion="+newVersion;
    };

    var save = function() {
        var data = $('#messagesForm').serialize();
        $.post(siteUrl + "/appversions/manage/save", data,
            function(result) {
                if (typeof(result.error) != 'undefined') {
                    alert("Error!");
                } else {
                    alert("Saved");
                }
            }
        )
    };

    var bindEvents = function() {
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var lang = $(e.target).data("lang");
            langChanged(lang);
        });
        $("#version").change(function() {
            changeVersion($("#version").val());
        });
        $("#save").click(function(event) {
            event.preventDefault();
            save();
        });
        $("#message").keyup(function() {
            previousMessage = "";
            $("#showPrevious").hide();
            $("#showDefault").show();
        });
        $("#updatePreview").click(function() {
            var message = $("#message-"+currentLang).val();
            updatePreview(message);
        });
        $("#showDefault").click(function() {
            showDefault();
        });
        $("#showPrevious").click(function() {
            showPrevious();
        })

    };

    var init = function() {
        $('#langTabs a:first').tab('show');
        bindEvents();
    };

    init();
};


$(document).ready(function() {
    new appVersion;

});