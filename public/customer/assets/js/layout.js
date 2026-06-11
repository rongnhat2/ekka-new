const LayoutView = {
    Category: {
        render_top(data) {
            data.map((v) => {
                $(".category-nav-list").append(
                    `<li><a href="/category?tag=${v.id}">${v.name}</a></li>`,
                );
            });
        },
    },
    Cart: {
        add_to_card(name, callback) {
            $(document).on("click", `.action-add-to-card`, function () {
                var atr = ($(this).attr("atr") || "").trim();
                if (atr === name) {
                    callback($(this));
                }
            });
        },
        update() {
            var n = 0;
            try {
                if (LayoutView.CartLocal && LayoutView.CartLocal.load) {
                    n = LayoutView.CartLocal.load().items.length;
                }
            } catch (e) {
                n = 0;
            }
            $(".cart-count").html(n);
        },
    },
    /**
     * Một key duy nhất: localStorage["cart-ekka"] = JSON
     * { items: [ { productVarId, qty, size?, color? } ], lastTotal: string | null }
     * Migrate từ card / quantity / size / color / total_prices (lần đầu), rồi xóa key cũ.
     * Nút cần data-product-var-id = product_var.id
     */
    CartLocal: {
        STORAGE_KEY: "cart-ekka",
        clearOldKeys: function () {
            localStorage.removeItem("card");
            localStorage.removeItem("quantity");
            localStorage.removeItem("size");
            localStorage.removeItem("color");
            localStorage.removeItem("total_prices");
        },
        load: function () {
            var key = LayoutView.CartLocal.STORAGE_KEY;
            var raw = localStorage.getItem(key);
            if (raw) {
                try {
                    var o = JSON.parse(raw);
                    if (o && o.items && Array.isArray(o.items)) {
                        o.items = o.items.filter(function (it) {
                            return it && String(it.productVarId || "").trim();
                        });
                        LayoutView.CartLocal.clearOldKeys();
                        if (o.lastTotal === undefined) {
                            o.lastTotal = null;
                        }
                        return o;
                    }
                } catch (e) {}
            }
            var card = localStorage.getItem("card");
            var qu = localStorage.getItem("quantity");
            var sz = localStorage.getItem("size");
            var cl = localStorage.getItem("color");
            var tot = localStorage.getItem("total_prices");
            var items = [];
            if (card) {
                var ids = String(card)
                    .split("-")
                    .map(function (s) {
                        return String(s).trim();
                    })
                    .filter(function (s) {
                        return s.length;
                    });
                var qs = qu
                    ? String(qu)
                          .split("-")
                          .map(function (s) {
                              return s.trim();
                          })
                    : [];
                var szs = sz
                    ? String(sz)
                          .split(",")
                          .map(function (s) {
                              return s.trim();
                          })
                    : [];
                var cls = cl
                    ? String(cl)
                          .split(",")
                          .map(function (s) {
                              return s.trim();
                          })
                    : [];
                for (var i = 0; i < ids.length; i++) {
                    var it = {
                        productVarId: ids[i],
                        qty: Math.max(1, parseInt(qs[i], 10) || 1),
                    };
                    if (szs[i]) {
                        it.size = szs[i];
                    }
                    if (cls[i]) {
                        it.color = cls[i];
                    }
                    items.push(it);
                }
            }
            var out = {
                items: items,
                lastTotal:
                    tot != null && String(tot) !== "" ? String(tot) : null,
            };
            if (items.length || out.lastTotal) {
                localStorage.setItem(key, JSON.stringify(out));
            }
            LayoutView.CartLocal.clearOldKeys();
            return out;
        },
        save: function (o) {
            var key = LayoutView.CartLocal.STORAGE_KEY;
            if (!o) {
                localStorage.removeItem(key);
                LayoutView.CartLocal.clearOldKeys();
                return;
            }
            var hasItems = o.items && o.items.length > 0;
            var hasTotal =
                o.lastTotal != null && String(o.lastTotal).length > 0;
            if (!hasItems && !hasTotal) {
                localStorage.removeItem(key);
            } else {
                localStorage.setItem(
                    key,
                    JSON.stringify({
                        items: hasItems ? o.items : [],
                        lastTotal: hasTotal ? String(o.lastTotal) : null,
                    }),
                );
            }
            LayoutView.CartLocal.clearOldKeys();
        },
        read: function () {
            var o = LayoutView.CartLocal.load();
            var cards = o.items.map(function (it) {
                return String(it.productVarId);
            });
            var qtys = o.items.map(function (it) {
                return String(Math.max(1, parseInt(it.qty, 10) || 1));
            });
            return { cards: cards, qtys: qtys };
        },
        write: function (cards, qtys) {
            var o = LayoutView.CartLocal.load();
            var items = [];
            for (var i = 0; i < cards.length; i++) {
                var id = String(cards[i]).trim();
                var it = {
                    productVarId: id,
                    qty: Math.max(1, parseInt(qtys[i], 10) || 1),
                };
                for (var j = 0; j < o.items.length; j++) {
                    if (String(o.items[j].productVarId) === id) {
                        if (o.items[j].size) {
                            it.size = o.items[j].size;
                        }
                        if (o.items[j].color) {
                            it.color = o.items[j].color;
                        }
                        break;
                    }
                }
                items.push(it);
            }
            LayoutView.CartLocal.save({ items: items, lastTotal: o.lastTotal });
        },
        /** Gỡ 1 dòng theo product_var (API 404 / dữ liệu hỏng) */
        removeItemByVarId: function (varId) {
            if (!varId) return;
            var o = LayoutView.CartLocal.load();
            o.items = o.items.filter(function (it) {
                return String(it.productVarId) !== String(varId);
            });
            var hasItems = o.items.length > 0;
            var hasTotal =
                o.lastTotal != null && String(o.lastTotal).length > 0;
            if (!hasItems && !hasTotal) {
                LayoutView.CartLocal.save(null);
            } else {
                LayoutView.CartLocal.save({
                    items: o.items,
                    lastTotal: o.lastTotal,
                });
            }
            try {
                LayoutView.Cart.update();
            } catch (e) {}
        },
        /**
         * Lấy size/color đang chọn từ trang chi tiết; bắt buộc nếu có tùy chọn tương ứng.
         * $btn: nút .action-add-to-card
         */
        readVariationFromButton($btn) {
            var $root = $btn.closest(".single-pro-content");
            if (!$root.length) {
                $root = $(".single-pro-content").first();
            }
            var needSize = $root.find(".product-size li").length > 0;
            var needColor = $root.find(".product-color li").length > 0;
            var sizeId = $root.find(".product-size .is-active").attr("size-id");
            var colorId = $root
                .find(".product-color .is-active")
                .attr("color-id");
            return {
                needSize: needSize,
                needColor: needColor,
                sizeId: sizeId && String(sizeId) ? String(sizeId) : "",
                colorId: colorId && String(colorId) ? String(colorId) : "",
            };
        },
        showVariantAlert(msg) {
            var $row = $(".ec-pro-variation").first();
            if (!$row.length) {
                $row = $(".ec-pro-variation-size");
            }
            if (!$row.length) {
                $row = $(".ec-pro-variation-color");
            }
            $row.find(".alert").remove();
            if (msg) {
                $row.append(
                    '<div class="alert alert-danger" role="alert">' +
                        msg +
                        "</div>",
                );
            }
        },
        addFromButton($btn) {
            var v = LayoutView.CartLocal.readVariationFromButton($btn);
            if (v.needSize && !v.sizeId) {
                LayoutView.CartLocal.showVariantAlert("Hãy chọn size");
                return false;
            }
            if (v.needColor && !v.colorId) {
                LayoutView.CartLocal.showVariantAlert("Hãy chọn màu");
                return false;
            }
            var varId = String($btn.attr("data-product-var-id") || "").trim();
            if (!varId) {
                LayoutView.CartLocal.showVariantAlert(
                    "Thiếu product_var: gán data-product-var-id lên nút theo biến thể.",
                );
                return false;
            }
            LayoutView.CartLocal.showVariantAlert("");

            var st = LayoutView.CartLocal.read();
            var idx = st.cards.indexOf(varId);
            if (idx > -1) {
                var prev = parseInt(st.qtys[idx], 10) || 1;
                st.qtys[idx] = String(prev + 1);
            } else {
                st.cards.push(varId);
                st.qtys.push("1");
            }
            LayoutView.CartLocal.write(st.cards, st.qtys);
            $btn.text("✔ đã thêm");
            return true;
        },
    },
    onPushSearch() {
        var search_data = LayoutView.toSlug($(".product-search-field").val());
        return `keyword=${search_data}`;
    },
    toSlug(title) {
        slug = title.toLowerCase();
        //Đổi ký tự có dấu thành không dấu
        slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, "a");
        slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, "e");
        slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, "i");
        slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, "o");
        slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, "u");
        slug = slug.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, "y");
        slug = slug.replace(/đ/gi, "d");
        //Xóa các ký tự đặt biệt
        slug = slug.replace(
            /\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|_/gi,
            "",
        );
        //Đổi khoảng trắng thành ký tự gạch ngang
        slug = slug.replace(/ /gi, " - ");
        //Đổi nhiều ký tự gạch ngang liên tiếp thành 1 ký tự gạch ngang
        //Phòng trường hợp người nhập vào quá nhiều ký tự trắng
        slug = slug.replace(/\-\-\-\-\-/gi, "-");
        slug = slug.replace(/\-\-\-\-/gi, "-");
        slug = slug.replace(/\-\-\-/gi, "-");
        slug = slug.replace(/\-\-/gi, "-");
        //Xóa các ký tự gạch ngang ở đầu và cuối
        slug = "@" + slug + "@";
        slug = slug.replace(/\@\-|\-\@|\@/gi, "");
        return slug;
    },
};
(() => {
    $(document).on("click", "[modal-control]", function () {
        var name = $(this).attr("modal-control");
        $(`.I-modal[modal-block=${name}]`).addClass("active");
        $("html").addClass("o-hidden");
    });
    $(document).mouseup(function (e) {
        var container = $(".modal-dialog");
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            $(".I-modal").removeClass("active");
            $("html").removeClass("o-hidden");
        }
    });
    function init() {
        getCategory();
        LayoutView.Cart.update();
    }
    function getCategory() {
        Api.Category.GetAll()
            .done((res) => {
                LayoutView.Category.render_top(res.data);
            })
            .fail((err) => {})
            .always(() => {});
    }
    LayoutView.Cart.add_to_card("Add to card", function (item) {
        if (LayoutView.CartLocal.addFromButton(item)) {
            LayoutView.Cart.update();
        }
    });
    $(document).on("click", ".header-search-button", function () {
        window.location.href = `/category?${LayoutView.onPushSearch()}`;
    });
    init();
})();
