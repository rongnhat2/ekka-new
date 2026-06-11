const View = {
    Product: {
        parseMetadata(raw) {
            if (raw == null || raw === "") {
                return { size: [], color: [] };
            }
            if (
                typeof raw === "object" &&
                raw !== null &&
                !Array.isArray(raw)
            ) {
                return {
                    size: Array.isArray(raw.size) ? raw.size : [],
                    color: Array.isArray(raw.color) ? raw.color : [],
                };
            }
            var s = String(raw).trim();
            if (!s) return { size: [], color: [] };
            try {
                var o = JSON.parse(s);
                if (typeof o !== "object" || o == null) {
                    return { size: [], color: [] };
                }
                return {
                    size: Array.isArray(o.size) ? o.size : [],
                    color: Array.isArray(o.color) ? o.color : [],
                };
            } catch (e) {
                return { size: [], color: [] };
            }
        },
        renderNew(data) {
            if (!data || !Array.isArray(data)) return;
            data.forEach((v) => {
                var image = (v.images || "").split(",")[0] || "";
                var metadata = View.Product.parseMetadata(v.metadata);
                var size = (metadata.size || [])
                    .map(
                        (sz) =>
                            `<li><a href="#" class="ec-opt-sz">${sz}</a></li>`,
                    )
                    .join("");
                var color = (metadata.color || [])
                    .map(
                        (cl) =>
                            `<li><a href="#" class="ec-opt-clr-img" ><span style="background-color: ${cl};"></span></a></li>`,
                    )
                    .join("");
                var discount =
                    v.discount == 0
                        ? ""
                        : `<span class="percentage">${v.discount}%</span><span class="flags"> <span class="sale">Sale</span> </span>`;
                var real_prices = View.formatNumber(
                    v.discount == 0
                        ? v.prices
                        : v.prices - (v.prices * v.discount) / 100,
                );
                var discount_value =
                    v.discount == 0
                        ? ""
                        : `<span class="old-price">${View.formatNumber(v.prices)} đ</span>`;
                $(".new-product").append(`
                    <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6 mb-6  ec-product-content" data-animation="fadeIn">
                        <div class="ec-product-inner">
                            <div class="ec-pro-image-outer">
                                <div class="ec-pro-image">
                                    <a href="/product?id=${v.id}" class="image">
                                        <img class="main-image" src="${image}" alt="Product" />
                                    </a>
                                    ${discount}
                                </div>
                            </div>
                            <div class="ec-pro-content">
                                <h5 class="ec-pro-title"><a href="/product?id=${v.id}">${v.name}</a></h5>
                                <span class="ec-price">
                                    ${discount_value}
                                    <span class="new-price">${real_prices} đ</span>
                                </span>
                                <div class="ec-pro-option">
                                    <div class="ec-pro-color">
                                        <span class="ec-pro-opt-label">Color</span>
                                        <ul class="ec-opt-swatch ec-change-img">
                                            ${color}
                                        </ul>
                                    </div>
                                    <div class="ec-pro-size">
                                        <span class="ec-pro-opt-label">Size</span>
                                        <ul class="ec-opt-size">
                                            ${size}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`);
            });
            $(".new-product").append(
                `<div class="col-sm-12 shop-all-btn"><a href="/category?sort=1">Xem thêm</a></div>`,
            );
        },
    },
    Cart: {
        unitLinePrice(data) {
            if (!data) return 0;
            const v = data.var_prices;
            if (v != null && v !== "" && !isNaN(parseFloat(v))) {
                return parseFloat(v);
            }
            return parseFloat(data.prices) || 0;
        },
        variantLabelHtml(data) {
            if (!data) return "";
            const parts = [];
            if (data.size_name) parts.push("Size: " + data.size_name);
            if (data.color_name) parts.push("Màu: " + data.color_name);
            return parts.length
                ? `<div class="ec-cart-opts text-muted" style="font-size:0.9em;">${parts.join(" · ")}</div>`
                : "";
        },
        render(data, qty, productVarId) {
            if (!data) return;
            const vid = String(
                productVarId != null ? productVarId : data.product_var_id || "",
            );
            const image = (data.images || "").split(",")[0] || "";
            const unit = View.Cart.unitLinePrice(data);
            const discVal = data.discount || 0;
            const discount = (unit * discVal) / 100;
            const realUnit = discVal == 0 ? unit : unit - discount;
            const q = Math.max(1, parseInt(qty, 10) || 1);
            const lineTotal = realUnit * q;
            const label = View.Cart.variantLabelHtml(data);
            const priceText = View.formatNumber(realUnit);
            const lineText = View.formatNumber(lineTotal);

            $(".ec-checkout-pro.cart-list").append(`
                <div class="col-sm-12 mb-3 ec-checkout-line"
                    data-product-var-id="${vid}"
                    data-line-total="${lineTotal}">
                    <div class="ec-product-inner d-flex">
                        <div class="ec-pro-image-outer" style="max-width:80px;flex:0 0 80px;">
                            <a href="/product?id=${data.id}" class="image">
                                <img class="img-fluid" src="/${image}" alt="" />
                            </a>
                        </div>
                        <div class="ec-pro-content pl-3 flex-grow-1">
                            <h6 class="ec-pro-title mb-1"><a href="/product?id=${data.id}">${data.name}</a></h6>
                            ${label}
                            <p class="mb-0 text-muted" style="font-size:0.9em;">${q} × ${priceText} đ = <strong>${lineText} đ</strong></p>
                        </div>
                    </div>
                </div>
            `);
            View.Cart.setTotal();
        },
        setTotal() {
            var total = 0;
            $(".ec-checkout-line").each(function () {
                total += +($(this).attr("data-line-total") || 0);
            });
            $(".real-total").html(View.formatNumber(total) + " đ");
            $(".real-total").attr("data-total", total);
        },
    },
    Order: {
        getVal() {
            var resource = "#checkout-form";
            var fd = new FormData();
            var required_data = [];
            var onPushData = true;

            if (typeof LayoutView === "undefined" || !LayoutView.CartLocal) {
                required_data.push("Thiếu bộ lưu giỏ hàng (layout).");
                onPushData = false;
            }
            var o =
                onPushData && LayoutView.CartLocal
                    ? LayoutView.CartLocal.load()
                    : { items: [] };
            if (!o.items || !o.items.length) {
                required_data.push("Giỏ hàng trống.");
                onPushData = false;
            }

            var lines = [];
            if (o.items && o.items.length) {
                o.items.forEach(function (it) {
                    var id = String(it.productVarId || "").trim();
                    if (!id) return;
                    lines.push({
                        id: id,
                        qty: Math.max(1, parseInt(it.qty, 10) || 1),
                    });
                });
            }
            var data_item = lines.map(function (l) {
                return l.id;
            }).join("-");
            var data_quantity = lines
                .map(function (l) {
                    return String(l.qty);
                })
                .join("-");
            var data_size = lines
                .map(function () {
                    return "-";
                })
                .join(",");

            var data_prices = $(".real-total").attr("data-total") || "0";

            var data_username = $("#username").val();
            var data_address = $("#address").val();
            var data_telephone = $("#telephone").val();
            var data_payment = $("input[name=payment_method]:checked").val();
            var data_email = $("#email").val();

            if (data_item === "") {
                required_data.push("Hãy chọn sản phẩm.");
                onPushData = false;
            }
            if (data_username == "") {
                required_data.push("Nhập tên.");
                onPushData = false;
            }
            if (data_address == "") {
                required_data.push("Nhập địa chỉ.");
                onPushData = false;
            }
            if (data_telephone == "") {
                required_data.push("Nhập số điện thoại.");
                onPushData = false;
            }

            if (View.validateEmail(data_email) == null) {
                if (data_email == "") {
                    required_data.push("Hãy nhập email.");
                    onPushData = false;
                } else {
                    required_data.push("Email không hợp lệ.");
                    onPushData = false;
                }
            }
            if (onPushData) {
                var tSync = $(".real-total").attr("data-total");
                if (
                    o.items &&
                    o.items.length &&
                    tSync != null &&
                    String(tSync) !== "" &&
                    LayoutView.CartLocal.save
                ) {
                    LayoutView.CartLocal.save({
                        items: o.items,
                        lastTotal: String(tSync),
                    });
                }
                fd.append("data_login", 1);
                fd.append("data_item", data_item);
                fd.append("data_size", data_size);
                fd.append("data_quantity", data_quantity);
                fd.append("data_prices", data_prices);
                fd.append("data_username", data_username);
                fd.append("data_address", data_address);
                fd.append("data_telephone", data_telephone);
                fd.append("data_email", data_email);
                fd.append("data_payment", data_payment);
                return fd;
            } else {
                $(resource).find(".error-log .js-errors").remove();
                var required_noti = "";
                for (var i = 0; i < required_data.length; i++) {
                    required_noti += `<li class="error">${required_data[i]}</li>`;
                }
                $(resource)
                    .find(".error-log")
                    .prepend(` <ul class="js-errors">${required_noti}</ul> `);
                return false;
            }
        },
        CreateOrder(callback) {
            var fire = function (e) {
                e.preventDefault();
                e.stopPropagation();
                var payload = View.Order.getVal();
                if (payload) {
                    callback(payload);
                }
            };
            $(document)
                .off("click.checkout", ".order-action")
                .on("click.checkout", ".order-action", fire);
        },
    },
    validateEmail(email) {
        return email.match(
            /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
        );
    },
    isNumberKey(evt) {
        var charCode = evt.which ? evt.which : event.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) return false;
        return true;
    },
    formatNumber(num) {
        if (num === null || num === undefined || isNaN(num)) return "0";
        return Number(num)
            .toString()
            .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
    },
    init() {},
};

(() => {
    View.init();
    function getCart() {
        $(".ec-checkout-pro.cart-list").empty();
        if (typeof LayoutView === "undefined" || !LayoutView.CartLocal) {
            View.Cart.setTotal();
            return;
        }
        var o = LayoutView.CartLocal.load();
        var items = o.items || [];
        if (!items.length) {
            View.Cart.setTotal();
            return;
        }
        var pending = items.length;
        for (var i = 0; i < items.length; i++) {
            (function (idx) {
                var it = items[idx];
                var vid = String((it && it.productVarId) || "").trim();
                if (!vid) {
                    if (--pending === 0) {
                        View.Cart.setTotal();
                    }
                    return;
                }
                var qty = Math.max(1, parseInt(it.qty, 10) || 1);
                Api.Product.GetOneItem(vid)
                    .done((res) => {
                        if (res && res.data) {
                            View.Cart.render(res.data, qty, vid);
                        }
                    })
                    .fail(() => {
                        if (
                            LayoutView.CartLocal &&
                            LayoutView.CartLocal.removeItemByVarId
                        ) {
                            LayoutView.CartLocal.removeItemByVarId(vid);
                        }
                    })
                    .always(() => {
                        if (--pending === 0) {
                            View.Cart.setTotal();
                        }
                    });
            })(i);
        }
    }
    function getNewArrivals() {
        Api.Product.NewArrivals()
            .done((res) => {
                View.Product.renderNew(res.data);
            })
            .fail((err) => {})
            .always(() => {});
    }
    if (window.jQuery) {
        jQuery(function () {
            View.Order.CreateOrder((fd) => {
                Api.Order.Create(fd)
                    .done((res) => {
                        if (LayoutView && LayoutView.CartLocal) {
                            LayoutView.CartLocal.save(null);
                        }
                        try {
                            LayoutView.Cart.update();
                        } catch (e) {}
                        // save(null) đã xóa cart-ekka + key cũ (clearOldKeys trong layout)
                        localStorage.removeItem("card");
                        localStorage.removeItem("quantity");
                        localStorage.removeItem("total_prices");
                        localStorage.removeItem("size");
                        localStorage.removeItem("color");
                        if (res.payment == 1) {
                            window.location.replace("/profile");
                        } else {
                            var formVNpay = new FormData();
                            formVNpay.append("data_id", res.id);
                            formVNpay.append("data_prices", res.total);
                            Api.VNpay.Create(formVNpay)
                                .done((r) => {
                                    window.location.replace(r);
                                })
                                .fail((err) => {
                                    if (LayoutView && LayoutView.helper) {
                                        LayoutView.helper.showToastError(
                                            "Error",
                                            "Có lỗi sảy ra",
                                        );
                                    }
                                });
                        }
                    })
                    .fail((err) => {
                        if (LayoutView && LayoutView.helper) {
                            LayoutView.helper.showToastError(
                                "Error",
                                "Có lỗi sảy ra",
                            );
                        }
                    });
            });
            getNewArrivals();
            getCart();
        });
    } else {
        getNewArrivals();
        getCart();
    }
})();
