const View = {
    /** Danh sách product_var.id đang có trong localStorage['cart-ekka'] (layout.js) */
    getCartVarIds() {
        try {
            const raw = localStorage.getItem("cart-ekka");
            if (!raw) return [];
            const o = JSON.parse(raw);
            if (o && o.items && Array.isArray(o.items)) {
                return o.items
                    .map((x) =>
                        x && x.productVarId ? String(x.productVarId) : "",
                    )
                    .filter((id) => id.length);
            }
        } catch (e) {}
        return [];
    },
    Description: {
        selected: {
            size_id: null,
            color_id: null,
        },
        /** Có cột size / màu trong product_var không (bỏ qua null) */
        variationMeta(product_var) {
            const vars = product_var || [];
            const hasSize = vars.some(
                (v) => v.size_id != null && v.size_id !== "",
            );
            const hasColor = vars.some(
                (v) => v.color_id != null && v.color_id !== "",
            );
            return { hasSize, hasColor, vars };
        },
        sizeMapFromVars(product_var) {
            const m = {};
            (product_var || []).forEach((v) => {
                if (v.size_id != null && v.size_id !== "")
                    m[v.size_id] = v.size_name;
            });
            return m;
        },
        /**
         * Màu hiển thị: nếu có size → chỉ màu của các dòng product_var đúng size;
         * nếu không có size → tất cả màu; nếu có size nhưng chưa chọn → rỗng (chọn size trước).
         */
        colorMapForSelection(product_var, size_id) {
            const { hasSize, hasColor, vars } =
                View.Description.variationMeta(product_var);
            const out = {};
            if (!hasColor) return out;
            if (!hasSize) {
                (vars || []).forEach((v) => {
                    if (v.color_id != null && v.color_id !== "")
                        out[v.color_id] = v.color_name;
                });
                return out;
            }
            if (size_id == null || String(size_id) === "") return out;
            (vars || []).forEach((v) => {
                if (String(v.size_id) !== String(size_id)) return;
                if (v.color_id != null && v.color_id !== "")
                    out[v.color_id] = v.color_name;
            });
            return out;
        },
        buildSizeListHTML(sizeMap) {
            return Object.entries(sizeMap)
                .map(
                    ([id, name]) =>
                        `<li size-id="${id}"><span>${name}</span></li>`,
                )
                .join("");
        },
        buildColorListHTML(colorMap) {
            return Object.entries(colorMap)
                .map(
                    ([id, name]) =>
                        `<li color-id="${id}"><span>${name}</span></li>`,
                )
                .join("");
        },
        applyVariationVisibility(hasSize, hasColor) {
            $(".ec-pro-variation-inner.ec-pro-variation-size").toggle(
                !!hasSize,
            );
            $(".ec-pro-variation-inner.ec-pro-variation-color").toggle(
                !!hasColor,
            );
        },
        /** Sau khi đổi size: vẽ lại list màu + reset màu nếu không còn hợp lệ */
        rebuildColorForSize(data) {
            const cm = View.Description.colorMapForSelection(
                data.product_var,
                View.Description.selected.size_id,
            );
            $(".product-color").html(
                View.Description.buildColorListHTML(cm),
            );
            const keys = Object.keys(cm);
            const cur = View.Description.selected.color_id;
            if (!keys.length) {
                View.Description.selected.color_id = null;
            } else if (cur == null || cm[cur] === undefined) {
                View.Description.selected.color_id = keys[0];
            }
        },
        getSelectedVariant(data) {
            const vars = data.product_var;
            if (!vars || !vars.length) return null;
            const { hasSize, hasColor } =
                View.Description.variationMeta(vars);
            const sz = View.Description.selected.size_id;
            const cl = View.Description.selected.color_id;

            let cand = vars.slice();
            if (hasSize && sz != null && String(sz) !== "")
                cand = cand.filter((v) => String(v.size_id) === String(sz));
            if (hasColor && cl != null && String(cl) !== "")
                cand = cand.filter((v) => String(v.color_id) === String(cl));
            if (cand.length) return cand[0];

            cand = vars.slice();
            if (hasSize && sz != null && String(sz) !== "")
                cand = cand.filter((v) => String(v.size_id) === String(sz));
            if (cand.length) return cand[0];

            cand = vars.slice();
            if (hasColor && cl != null && String(cl) !== "")
                cand = cand.filter((v) => String(v.color_id) === String(cl));
            if (cand.length) return cand[0];

            return vars[0];
        },
        /** Cùng class is-active với size + layout.js readVariationFromButton (.product-color .is-active) */
        refreshVariationUI() {
            $(".product-size li").removeClass("is-active");
            $(".product-color li").removeClass("is-active");
            const s = View.Description.selected.size_id;
            const c = View.Description.selected.color_id;
            if (s != null && String(s) !== "")
                $(".product-size li")
                    .filter(function () {
                        return (
                            String($(this).attr("size-id") || "") === String(s)
                        );
                    })
                    .addClass("is-active");
            if (c != null && String(c) !== "")
                $(".product-color li")
                    .filter(function () {
                        return (
                            String($(this).attr("color-id") || "") === String(c)
                        );
                    })
                    .addClass("is-active");
        },
        /** Gán data-product-var-id + text nút theo biến thể (layout cần hợp lệ) */
        syncAddToCartButton(data) {
            const varIds = View.getCartVarIds();
            const variant = View.Description.getSelectedVariant(data);
            $(".action-add-to-card").attr("data-id", data.id);
            if (variant && variant.id != null) {
                $(".action-add-to-card").attr(
                    "data-product-var-id",
                    String(variant.id),
                );
                $(".action-add-to-card").html(
                    varIds.includes(String(variant.id))
                        ? "✔ đã thêm"
                        : "+ Giỏ hàng",
                );
            } else {
                $(".action-add-to-card").removeAttr("data-product-var-id");
                $(".action-add-to-card").html("+ Giỏ hàng");
            }
        },
        render(data) {
            const varIds = View.getCartVarIds();
            const inCart = (data.product_var || []).find(
                (v) => v && v.id != null && varIds.includes(String(v.id)),
            );

            const meta = View.Description.variationMeta(data.product_var);
            const { hasSize, hasColor } = meta;
            const sizeMap = View.Description.sizeMapFromVars(
                data.product_var,
            );

            let selected_size = null;
            let selected_color = null;

            if (inCart) {
                if (inCart.size_id != null && inCart.size_id !== "")
                    selected_size = String(inCart.size_id);
                if (inCart.color_id != null && inCart.color_id !== "")
                    selected_color = String(inCart.color_id);
            }

            if (hasSize) {
                const sizeKeys = Object.keys(sizeMap);
                if (selected_size == null && sizeKeys.length)
                    selected_size = sizeKeys[0];
                else if (
                    selected_size != null &&
                    sizeMap[selected_size] === undefined
                )
                    selected_size = sizeKeys.length ? sizeKeys[0] : null;
            } else {
                selected_size = null;
            }

            const colorMap = View.Description.colorMapForSelection(
                data.product_var,
                selected_size,
            );
            if (hasColor) {
                const ckeys = Object.keys(colorMap);
                if (
                    selected_color == null ||
                    colorMap[selected_color] === undefined
                ) {
                    selected_color = ckeys.length ? ckeys[0] : null;
                }
            } else {
                selected_color = null;
            }

            View.Description.selected.size_id = hasSize ? selected_size : null;
            View.Description.selected.color_id = hasColor
                ? selected_color
                : null;

            $(".product-size").html(
                View.Description.buildSizeListHTML(sizeMap),
            );
            $(".product-color").html(
                View.Description.buildColorListHTML(colorMap),
            );
            View.Description.applyVariationVisibility(hasSize, hasColor);

            // Render giá đúng theo variant được chọn (size+color)
            const variant = View.Description.getSelectedVariant(data);

            let real_prices = 0,
                sell_prices = 0;
            let discount = data.discount || 0;
            let prices = "";

            if (variant) {
                real_prices = View.formatNumber(
                    discount == 0
                        ? variant.prices
                        : variant.prices - (variant.prices * discount) / 100,
                );
                sell_prices = View.formatNumber(
                    (variant.prices * discount) / 100,
                );
                if (discount != 0) {
                    prices += `<del> <span class="new-price">${View.formatNumber(variant.prices)}</span>  đ </del>`;
                }
                prices += `<span class="new-price">${real_prices} đ</span> `;
            } else {
                // fallback nếu không có variant
                real_prices = View.formatNumber(
                    data.discount == 0
                        ? data.prices
                        : data.prices - (data.prices * data.discount) / 100,
                );
                sell_prices = View.formatNumber(
                    (data.prices * data.discount) / 100,
                );
                if (discount != 0) {
                    prices += `<del> <span class="new-price">${View.formatNumber(data.prices)}</span>  đ </del>`;
                }
                prices += `<span class="new-price">${real_prices} đ</span> `;
            }

            $(".product-name").text(data.name);
            $(".product-description").html(data.description);
            $(".product-detail").html(data.detail);
            $(".product-prices").html(prices);
            View.Description.syncAddToCartButton(data);
            View.Description.refreshVariationUI();
        },
        // Được gọi lại để re-render giá khi lựa chọn thay đổi (size/color)
        updatePrices(data) {
            // Render giá theo variant
            const variant = View.Description.getSelectedVariant(data);
            let real_prices = 0,
                sell_prices = 0;
            let discount = data.discount || 0;
            let prices = "";
            if (variant) {
                real_prices = View.formatNumber(
                    discount == 0
                        ? variant.prices
                        : variant.prices - (variant.prices * discount) / 100,
                );
                sell_prices = View.formatNumber(
                    (variant.prices * discount) / 100,
                );
                if (discount != 0) {
                    prices += `<del> <span class="new-price">${View.formatNumber(variant.prices)}</span>  đ </del>`;
                }
                prices += `<span class="new-price">${real_prices} đ</span> `;
            } else {
                real_prices = View.formatNumber(
                    data.discount == 0
                        ? data.prices
                        : data.prices - (data.prices * data.discount) / 100,
                );
                sell_prices = View.formatNumber(
                    (data.prices * data.discount) / 100,
                );
                if (discount != 0) {
                    prices += `<del> <span class="new-price">${View.formatNumber(data.prices)}</span>  đ </del>`;
                }
                prices += `<span class="new-price">${real_prices} đ</span> `;
            }
            $(".product-prices").html(prices);
            View.Description.syncAddToCartButton(data);
            View.Description.refreshVariationUI();
        },
    },
    Images: {
        render_list(data) {
            if (!data) return;
            data.split(",").map((value, key) => {
                $(".single-product-cover").append(`
                    <div class="single-slide zoom-image-hover">
                        <img class="img-responsive" src="/${value}" alt="">
                    </div>
                `);
                $(".single-nav-thumb").append(`
                    <div class="single-slide">
                        <img class="img-responsive" src="/${value}" alt="">
                    </div>
                `);
            });
            $(".single-product-cover").slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: false,
                fade: false,
                asNavFor: ".single-nav-thumb",
            });

            $(".single-nav-thumb").slick({
                slidesToShow: 4,
                slidesToScroll: 1,
                asNavFor: ".single-product-cover",
                dots: false,
                arrows: true,
                focusOnSelect: true,
            });
        },
    },
    RelatedProduct: {
        render(data) {
            data.map((v) => {
                var image = v.images && v.images.split(",")[0];
                // Dự đoán mảng product_var truyền về ở sản phẩm liên quan
                let size = "",
                    color = "";
                if (v.product_var && v.product_var.length) {
                    // danh sách unique size
                    const sizeSet = {};
                    const colorSet = {};
                    v.product_var.forEach((pr) => {
                        sizeSet[pr.size_id] = pr.size_name;
                        colorSet[pr.color_id] = pr.color_name;
                    });
                    size = Object.values(sizeSet)
                        .map(
                            (sz) =>
                                `<li><a href="#" class="ec-opt-sz">${sz}</a></li>`,
                        )
                        .join("");
                    color = Object.values(colorSet)
                        .map(
                            (clr) =>
                                `<li><a href="#" class="ec-opt-clr-img"><span style="background-color: ${clr};"></span></a></li>`,
                        )
                        .join("");
                }
                var discount =
                    v.discount == 0
                        ? ""
                        : `<span class="percentage">${v.discount}%</span><span class="flags"> <span class="sale">Sale</span> </span>`;
                var real_prices = View.formatNumber(
                    v.discount == 0 && v.product_var && v.product_var[0]
                        ? v.product_var[0].prices
                        : v.product_var && v.product_var[0]
                          ? v.product_var[0].prices -
                            (v.product_var[0].prices * v.discount) / 100
                          : v.prices - (v.prices * v.discount) / 100,
                );
                var discount_value =
                    v.discount == 0 && v.product_var && v.product_var[0]
                        ? ""
                        : v.discount != 0 && v.product_var && v.product_var[0]
                          ? `<span class="old-price">${View.formatNumber(v.product_var[0].prices)} đ</span>`
                          : v.discount != 0
                            ? `<span class="old-price">${View.formatNumber(v.prices)} đ</span>`
                            : "";
                $(".product-related").append(`
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
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            });
        },
    },
    isNumberKey(evt) {
        var charCode = evt.which ? evt.which : event.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) return false;
        return true;
    },
    formatNumber(num) {
        if (typeof num === "undefined" || num === null) return "";
        const n = parseInt(num);
        return n.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
    },
    URL: {
        get(id) {
            var urlParam = new URLSearchParams(window.location.search);
            return urlParam.get(id);
        },
    },
    // Bổ sung phản ứng cho click size & color
    init(productData = null) {
        $(document).on("keypress", `.product-quantity`, function (event) {
            return View.isNumberKey(event);
        });

        // Lưu cache tạm thời cho dữ liệu sản phẩm đang xem
        if (!View._currentProduct) View._currentProduct = null;

        // Khi chọn size → list màu chỉ còn màu có với size đó
        $(document).on("click", `.product-size li`, function (event) {
            event.preventDefault();
            const size_id = $(this).attr("size-id");
            View.Description.selected.size_id = size_id;
            if (View._currentProduct) {
                View.Description.rebuildColorForSize(View._currentProduct);
                View.Description.updatePrices(View._currentProduct);
            }
        });

        // Khi chọn màu
        $(document).on("click", `.product-color li`, function (event) {
            event.preventDefault();
            const color_id = $(this).attr("color-id");
            View.Description.selected.color_id = color_id;
            if (View._currentProduct) {
                View.Description.updatePrices(View._currentProduct);
            }
        });
    },
};

(() => {
    // Định nghĩa biến lưu lại data sản phẩm hiện tại ở ngoài
    let _productData = null;
    View.init();

    function init() {
        getProduct();
        getRelatedProduct();
    }

    function debounce(f, timeout) {
        let isLock = false;
        let timeoutID = null;
        return function (item) {
            if (!isLock) {
                f(item);
                isLock = true;
            }
            clearTimeout(timeoutID);
            timeoutID = setTimeout(function () {
                isLock = false;
            }, timeout);
        };
    }

    function getProduct() {
        Api.Product.GetOne(View.URL.get("id"))
            .done((res) => {
                if (res && res.data) {
                    // Lưu lại dữ liệu sp hiện tại
                    _productData = res.data;
                    View._currentProduct = res.data; // lưu cho event handler dùng lại
                    View.Images.render_list(res.data.images);
                    View.Description.render(res.data);
                }
            })
            .fail((err) => {})
            .always(() => {});
    }
    function getRelatedProduct() {
        Api.Product.GetRelated(View.URL.get("id"))
            .done((res) => {
                // Danh sách vẫn là mảng
                View.RelatedProduct.render(res.data);
            })
            .fail((err) => {})
            .always(() => {});
    }
    init();
})();
