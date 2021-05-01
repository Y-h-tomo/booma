window.onload = function() {
    // モーダルによるジャンル入力
    // $(".js-modal-input").on("click", function() {
    //     var genre = [];
    //     $('[name="modal_genre"]:checked').each(function(index, element) {
    //         genre.push($(element).val());
    //     });
    //     $("#genreInput1").text(genre);
    //     $("#genreInput2").val(genre);
    //     return false;
    // });

    // フォームボタンリセット
    $(function() {
        $(".clear-button").on("click", function() {
            clearForm(this.form);
        });

        function clearForm(form) {
            $(form)
                .find("input, select, textarea")
                .not(":button, :submit, :reset, :hidden")
                .val("")
                .prop("checked", false)
                .prop("selected", false);

            $(form).find(":radio").filter("[data-default]").prop("checked", true);
        }
    });
};