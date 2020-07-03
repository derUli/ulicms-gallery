// this shows a thumbnail of the selected file on text inputs with
// fm image uploader attached
function refreshFieldThumbnails() {
    $("input.fm[data-fm-type=images]").each(
            function (index, element) {
                var id = $(element).attr("name");
                if ($(element).val().length > 0) {
                    $("img#thumbnail-" + id).attr("src", $(element).val());
                    $("img#thumbnail-" + id).show();
                } else {
                    $("img#thumbnail-" + id).hide();
                }
            });
}

$(function () {
    refreshFieldThumbnails();
    $("input.fm")
            .on(
                    "click",
                    function (event) {
                        var field = $(event.target);
                        var name = $(field).data("fm-name") ? $(field)
                                .data("fm-name") : "fm_textbox";
                        var type = $(field).data("fm-type") ? $(field)
                                .data("fm-type") : "images";

                        window.KCFinder = {
                            callBack: function (url) {
                                field.val(url);
                                window.KCFinder = null;
                                refreshFieldThumbnails();
                            }
                        };
                        window
                                .open(
                                        "fm/dialog.php?fldr=images&editor=ckeditor&type=1&langCode=" +
                                        $("html").data("select2-language") + "&popup=1&field_id=field-path",
                                        name,
                                        'status=0, toolbar=0, location=0, menubar=0, directories=0, '
                                        + 'resizable=1, scrollbars=0, width=800, height=600');

                    });

    $(".clear-field").on("click", function (event) {
        event.preventDefault();
        var element = $(event.target);
        var linkFor = $(element).data("for");
        $(linkFor).val("");
        refreshFieldThumbnails();
    });
});