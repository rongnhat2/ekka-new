const View = {
    Cart: {
        /** Đơn giá 1 sản phẩm: ưu tiên giá biến thể (var_prices) */
        unitLinePrice(data) {
            if (!data) return 0;
            const v = data.var_prices;
            if (v != null && v !== "" && !isNaN(parseFloat(v))) {
                return parseFloat(v);
            }
            return parseFloat(data.prices) || 0;
        },
        /** Ghi chú size/màu dưới tên sản phẩm */
        variantLabelHtml(data) {
            if (!data) return "";
            const parts = [];
            if (data.size_name) parts.push("Size: " + data.size_name);
            if (data.color_name) parts.push("Màu: " + data.color_name);
            return parts.length
                ? `<div class="ec-cart-opts text-muted" style="font-size:0.9em;">${parts.join(" · ")}</div>`
                : "";
        },
        /**
         * data: 1 dòng từ API (product + product_var_id, var_prices, size_name, color_name)
         * qty: số lượng từ cart-ekka
         * productVarId: để gắn data-product-var-id (khi cần trùng id)
         */
        render(data, qty, productVarId) {
            if (!data) return;
            const vid = String(
                productVarId != null ? productVarId : data.product_var_id || "",
            );
            const image = (data.images || "").split(",")[0];
            const unit = View.Cart.unitLinePrice(data);
            const discVal = data.discount || 0;
            const discount = (unit * discVal) / 100;
            const realUnit = discVal == 0 ? unit : unit - discount;
            const q = Math.max(1, parseInt(qty, 10) || 1);
            const totalReal = realUnit * q;
            const totalOrig = unit * q;
            const totalDisc = discount * q;

            let prices = "";
            if (discVal != 0) {
                prices += `<del class="m-r-10">${View.formatNumber(unit)} đ</del>`;
            }
            prices += `<span class="amount"> ${View.formatNumber(realUnit)} đ </span>`;

            $(".cart-list").append(`
                <tr
                    data-product-id="${data.id}"
                    data-product-var-id="${vid}"
                    data-prices="${unit}"
                    data-real-prices="${realUnit}"
                    data-discount="${discount}"
                    data-discount-value="${discVal}"
                    data-total-prices="${totalOrig}"
                    data-total-discount="${totalDisc}"
                    data-total-real-prices="${totalReal}"
                    data-quatity="${q}">
                    <td data-label="Product" class="ec-cart-pro-name">
                        <a href="/product?id=${data.id}">
                            <img class="ec-cart-pro-img mr-4" src="/${image}" alt="" />${data.name}</a>
                        ${View.Cart.variantLabelHtml(data)}
                    </td>
                    <td data-label="Price" class="ec-cart-pro-price">
                        ${prices}
                    </td>
                    <td data-label="Quantity" class="ec-cart-pro-qty" style="text-align: center;">
                        <div class="cart-qty-plus-minus">
                            <input class="cart-plus-minus input-qty" type="text" name="cartqtybutton" value="${q}" />
                        </div>
                    </td>
                    <td data-label="Total" class="ec-cart-pro-subtotal data-total-prices">${View.formatNumber(totalReal)} đ</td>
                    <td data-label="Remove" class="ec-cart-pro-remove">
                        <a href="#" class="remove-item"><i class="ecicon eci-trash-o"></i></a>
                    </td>
                </tr> 
            `);
            View.Cart.setTotal();
        },
        setTotal() {
            var subtotal = 0;
            var discount = 0;
            var total = 0;
            $(".cart-list tr").each(function (index, el) {
                subtotal += +$(this).attr("data-total-prices");
                discount += +$(this).attr("data-total-discount");
                total += +$(this).attr("data-total-real-prices");
            });
            $(".sub-total").html(View.formatNumber(subtotal) + " đ");
            $(".discount-total").html(
                "- " + View.formatNumber(discount) + " đ",
            );
            $(".real-total").html(View.formatNumber(total) + " đ");
            $(".real-total").attr("data-total", total);
        },
    },
    Product: {
        /** metadata DB có thể null, "" hoặc không phải JSON */
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
    formatNumber(num) {
        if (num === null || num === undefined || isNaN(num)) return "0";
        return Number(num)
            .toString()
            .replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
    },
    isNumberKey(evt) {
        var charCode = evt.which ? evt.which : event.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) return false;
        return true;
    },
    onRemove(callback) {
        $(document).on("click", `.remove-item`, function (event) {
            event.preventDefault();
            var father = $(this).closest("tr");
            var varId = father.attr("data-product-var-id");
            father.remove();
            callback(varId);
        });
    },
    onCheckout(callback) {
        $(document).on("click", `.action-checkout`, function (event) {
            callback($(".real-total").attr("data-total"));
        });
    },
    /** Cập nhật qty trong cart-ekka theo 1 dòng */
    _updateEkkaQtyForVarId(productVarId, quantity) {
        var o = LayoutView.CartLocal.load();
        o.items = o.items.map(function (it) {
            if (String(it.productVarId) === String(productVarId)) {
                return {
                    productVarId: it.productVarId,
                    qty: Math.max(1, parseInt(quantity, 10) || 1),
                    size: it.size,
                    color: it.color,
                };
            }
            return it;
        });
        LayoutView.CartLocal.save({
            items: o.items,
            lastTotal: o.lastTotal,
        });
        try {
            LayoutView.Cart.update();
        } catch (e) {}
    },
    /** Xóa 1 dòng theo product_var_id (logic lưu ở layout.js) */
    _removeEkkaLine(productVarId) {
        if (productVarId && LayoutView.CartLocal.removeItemByVarId) {
            LayoutView.CartLocal.removeItemByVarId(productVarId);
        }
    },
    init() {
        $(document).on("keypress", `.input-qty`, function (event) {
            return View.isNumberKey(event);
        });
        $(document).on("keyup", `.input-qty`, function (event) {
            var father = $(this).closest("tr");
            var unit = parseFloat(father.attr("data-prices")) || 0;
            var discVal = parseFloat(father.attr("data-discount-value")) || 0;
            var discountPer = (unit * discVal) / 100;
            var realPer = discVal == 0 ? unit : unit - discountPer;
            var quantity = parseInt(father.find(".input-qty").val(), 10) || 1;

            var totalReal = realPer * quantity;
            var totalOrig = unit * quantity;
            var totalDisc = discountPer * quantity;

            father
                .find(".data-total-prices")
                .html(View.formatNumber(totalReal) + " đ");
            father.attr("data-quatity", quantity);
            father.attr("data-total-prices", totalOrig);
            father.attr("data-total-discount", totalDisc);
            father.attr("data-total-real-prices", totalReal);
            father.attr("data-real-prices", realPer);

            var vid = father.attr("data-product-var-id");
            if (vid) {
                View._updateEkkaQtyForVarId(vid, quantity);
            }
            View.Cart.setTotal();
        });
    },
};
(() => {
    View.init();
    function init() {
        getCart();
        getNewArrivals();
    }
    View.onRemove((productVarId) => {
        if (productVarId) {
            View._removeEkkaLine(productVarId);
        }
        View.Cart.setTotal();
    });
    View.onCheckout((total_prices) => {
        var o = LayoutView.CartLocal.load();
        $(".cart-list tr").each(function () {
            var vid = $(this).attr("data-product-var-id");
            var q = parseInt($(this).find(".input-qty").val(), 10) || 1;
            if (!vid) return;
            o.items = o.items.map(function (it) {
                if (String(it.productVarId) === String(vid)) {
                    return {
                        productVarId: it.productVarId,
                        qty: Math.max(1, q),
                        size: it.size,
                        color: it.color,
                    };
                }
                return it;
            });
        });
        o.lastTotal = total_prices != null ? String(total_prices) : null;
        LayoutView.CartLocal.save({
            items: o.items,
            lastTotal: o.lastTotal,
        });
    });
    function getCart() {
        $(".cart-list").empty();
        if (typeof LayoutView === "undefined" || !LayoutView.CartLocal) {
            return;
        }
        var o = LayoutView.CartLocal.load();
        var items = o.items || [];
        if (!items.length) {
            View.Cart.setTotal();
            return;
        }
        var pending = items.length;
        var i;
        for (i = 0; i < items.length; i++) {
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
                        if (LayoutView.CartLocal.removeItemByVarId) {
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
    init();
})();
