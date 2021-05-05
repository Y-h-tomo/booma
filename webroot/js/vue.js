window.onload = (function() {
    "use strict";

    /* -------------------------------- ジャンル選択ｖｍ -------------------------------- */
    var vm = new Vue({
        el: "#js-genre-modal",
        data: {
            genres: [],
        },
        methods: {
            clear: function() {
                this.genres = [];
            },
        },
        computed: {
            isDisabled: function() {
                if (this.genres.length >= 3) {
                    return true;
                }
                return false;
            },
            inputDisabled: function() {
                if (this.genres.length) {
                    return true;
                }
                return false;
            },
        },
    });

    /* ---------------------------------- 冊数ｖｍ ---------------------------------- */

    var quantity_vm = new Vue({
        el: "#js-quantity",
        data: {
            quantity: "",
            numbers: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9],
        },
        methods: {
            num: function(number) {
                this.quantity += number;
            },
            clear: function() {
                this.quantity = "";
            },
        },
        computed: {
            isDisabled: function() {
                if (this.quantity.length >= 3) {
                    return true;
                }
                return false;
            },
            inputDisabled: function() {
                if (this.quantity.length) {
                    return true;
                }
                return false;
            },
        },
    });

    /* -------------------------------- レンタル期限ｖｍ -------------------------------- */

    var deadline_vm = new Vue({
        el: "#js-deadline",
        data: {
            deadline: "",
            day: "",
        },
        methods: {
            clear: function() {
                this.deadline = "";
                this.day = "";
            },
            threeDays: function() {
                this.deadline += 24 * 3;
                this.day = 3;
            },
            oneWeek: function() {
                this.deadline += 24 * 7;
                this.day = 7;
            },
            twoWeek: function() {
                this.deadline += 24 * 14;
                this.day = 14;
            },
            threeWeek: function() {
                this.deadline += 24 * 21;
                this.day = 21;
            },
        },
        computed: {
            inputDisabled: function() {
                if (this.deadline.length) {
                    return true;
                }
                return false;
            },
        },
    });

    /* ---------------------------------- 書籍Noｖｍ ---------------------------------- */

    var bookNo_vm = new Vue({
        el: "#js-book-no",
        data: {
            book_no: "",
        },
        methods: {
            validate_no: function() {
                if (isNaN(this.book_no)) {
                    alert("書籍No入力が数値ではありません");
                    this.book_no = "";
                }
            },
        },
    });

    /* ---------------------------------- 価格ｖｍ ---------------------------------- */

    var price_vm = new Vue({
        el: "#js-price",
        data: {
            price: "",
        },
        methods: {
            validate_price: function() {
                if (isNaN(this.price)) {
                    alert("価格入力が数値ではありません");
                    this.price = "";
                }
            },
        },
        computed: {
            inputDisabled: function() {
                if (this.price.length) {
                    return true;
                }
                return false;
            },
        },
    });

    /* ------------------------------ 画像事前バリデーションｖｍ ----------------------------- */

    var image_vm = new Vue({
        el: "#js-image",
        methods: {
            validate_uploads: function(event) {
                let file = event.target.files[0],
                    name = file.name,
                    size = file.size,
                    type = file.type,
                    errors = "";

                //上限サイズは100KB
                if (size > 100000) {
                    errors += "ファイルの上限サイズ100KBを超えています\n";
                }

                //拡張子は .jpg .gif .png . pdf のみ許可
                if (
                    type != "image/jpeg" &&
                    type != "image/gif" &&
                    type != "image/png" &&
                    type != "application/pdf"
                ) {
                    errors +=
                        ".jpg、.gif、.png、.pdfのいずれかのファイルのみ許可されています\n";
                }

                if (errors) {
                    //errorsが存在する場合は内容をalert
                    alert(errors);
                    //valueを空にしてリセットする
                    event.currentTarget.value = "";
                }
            },
        },
    });

    /* -------------------------------- ユーザー検索リセット -------------------------------- */
    var user_search_vm = new Vue({
        el: "#js-search-user",
        data: {
            name: "",
            role: "",
        },
        methods: {
            reset: function() {
                this.name = "";
                this.role = "";
            },
        },
    });

    /* -------------------------------- 書籍検索リセット -------------------------------- */

    var book_search_vm = new Vue({
        el: "#js-search-book",
        data: {
            name: "",
            genres: [],
            checked: [],
        },
        methods: {
            reset: function() {
                this.name = "";
                this.genres = [];
            },
            clear: function() {
                this.genres = [];
            },
        },
        computed: {
            isDisabled: function(n) {
                if (this.genres.length > 0) {
                    return true;
                }
                return false;
            },
        },
    });
})();