const View = {
    data: {
        category: [],
        brand: [],
        size: [],
        material: [],
        color: [],
    },
    table: {
        __generateDTRow(data) {
            let metadata = `
                <table class="meta-variant-table">
                    <thead>
                        <tr>
                            <th>Kích cỡ</th>
                            <th>Màu sắc</th>
                            <th>Chất liệu</th>
                            <th>Số lượng</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.product_var
                            .map((v) => {
                                return `
                                    <tr>
                                        <td><span class="meta-item-table">${v.size_name}</span></td>
                                        <td><span class="meta-item-table">${v.color_name}</span></td>
                                        <td><span class="meta-item-table">${v.material_name}</span></td>
                                        <td>
                                            <span class="meta-item-table" style="${parseInt(v.minQuantity) >= parseInt(v.stock) ? "border: 1px solid red;" : ""}">
                                                ${v.stock}
                                            </span>
                                        </td>
                                   
                                    </tr>
                                `;
                            })
                            .join("")}
                    </tbody>
                </table>
            `;

            return [
                `<div class="id-order">${data.id}</div>`,
                `<h5>${data.name}</h5>
                ${
                    data.images == ""
                        ? null
                        : data.images
                              .split(",")
                              .map((v) => {
                                  return `<div class="image-table-preview" style="background-image: url('/${v}')"></div>`;
                              })
                              .join("")
                }
                `,
                `<p>Danh mục: <span class="meta-item-table">${data.category_name ?? "Chưa có"}</span></p>
                <p>Thương hiệu: <span class="meta-item-table">${data.brand_name ?? "Chưa có"}</span></p>
                <p>Mô tả ngắn:  ${data.description ?? "Chưa có"} </p>`,
                metadata,
                `<label class="switch" data-id="${data.id}" data-status="${data.status == "1" ? "0" : "1"}" atr="Status"> <span class="slider round ${data.trending == "1" ? "active" : ""}"></span> </label>`,
                `<div class="view-data modal-control main-tab-control" style="cursor: pointer" atr="View" data-id="${data.id}"><i class="feather-eye"></i></div>
                <div class="view-data modal-control" style="cursor: pointer" atr="Delete" data-id="${data.id}"><i class="feather-trash"></i></div>`,
            ];
        },
        init() {
            var row_table = [
                {
                    title: "ID",
                    name: "id",
                    orderable: true,
                    width: "5%",
                },
                {
                    title: "Sản phẩm",
                    name: "name",
                    orderable: true,
                    width: "10%",
                },
                {
                    title: "Thông tin",
                    name: "name",
                    orderable: true,
                    width: "15%",
                },
                {
                    title: "Biến thể",
                    name: "icon",
                    orderable: true,
                    width: "30%",
                },
                {
                    title: "Trending",
                    name: "icon",
                    orderable: true,
                    width: "8%",
                },
                {
                    title: "Hành động",
                    name: "Action",
                    orderable: true,
                    width: "10%",
                },
            ];
            ViewIndex.table.init("#data-table", row_table);
        },
    },
    product: {
        setBrand() {
            View.data.category.map((v) => {
                $(".product-category").append(
                    `<option value="${v.id}">${v.name}</option>`,
                );
            });
            View.data.brand.map((v) => {
                $(".product-brand").append(
                    `<option value="${v.id}">${v.name}</option>`,
                );
            });
        },
        renderVarOptions(sel, data, key = "name", value = "id") {
            sel.innerHTML = `<option value="">Chọn</option>`;
            data.forEach(function (item) {
                sel.innerHTML += `<option value="${item[value] ?? item}">${item[key] ?? item}</option>`;
            });
        },
        addProductVarRow(selectedVal = {}) {
            const tmpl = document.getElementById("product-var-row-template");
            const node = tmpl.content.cloneNode(true);
            // Fill select option
            const row = node.querySelector(".product-var-row");
            // Size
            const sizeSel = row.querySelector(".var-size");
            View.product.renderVarOptions(
                sizeSel,
                View.data.size,
                typeof View.data.size[0] === "object" ? "name" : undefined,
                typeof View.data.size[0] === "object" ? "id" : undefined,
            );
            sizeSel.value = selectedVal.size ?? "";

            // Color
            const colorSel = row.querySelector(".var-color");
            View.product.renderVarOptions(
                colorSel,
                View.data.color,
                typeof View.data.color[0] === "object" ? "name" : undefined,
                typeof View.data.color[0] === "object" ? "id" : undefined,
            );
            colorSel.value = selectedVal.color ?? "";

            // Material
            const materialSel = row.querySelector(".var-material");
            View.product.renderVarOptions(
                materialSel,
                View.data.material,
                typeof View.data.material[0] === "object" ? "name" : undefined,
                typeof View.data.material[0] === "object" ? "id" : undefined,
            );
            materialSel.value = selectedVal.material ?? "";

            // SKU & Price
            row.querySelector(".var-sku").value = selectedVal.sku ?? "";
            row.querySelector(".var-price").value = selectedVal.price ?? "";
            row.querySelector(".var-minQuantity").value =
                selectedVal.minQuantity ?? "";

            // Remove handler
            row.querySelector(".btn-remove-var").onclick = function () {
                row.remove();
            };

            document.getElementById("product-var-list").appendChild(node);
        },
        getVal() {
            // Lấy dữ liệu các dòng biến thể sản phẩm (product variant rows)
            const varRows = document.querySelectorAll(
                "#product-var-list .product-var-row",
            );
            const variants = [];
            varRows.forEach(function (row) {
                const size = row.querySelector(".var-size")?.value ?? "";
                const color = row.querySelector(".var-color")?.value ?? "";
                const material =
                    row.querySelector(".var-material")?.value ?? "";
                const sku = row.querySelector(".var-sku")?.value ?? "";
                const price = row.querySelector(".var-price")?.value ?? "";
                const minQuantity =
                    row.querySelector(".var-minQuantity")?.value ?? "";
                variants.push({
                    size,
                    color,
                    material,
                    sku,
                    price,
                    minQuantity,
                });
            });
            return variants;
        },
        init() {
            document.getElementById("add-product-var").onclick = function () {
                View.product.addProductVarRow();
            };
        },
    },
    mainTab: {
        onValid() {
            $(document).on("keyup", `input`, function (event) {
                var father = $(this);
                var data = $(this).val();
                var valid_data = $(this).attr("valid-data");
                if (valid_data == "valid-number") {
                    var valid = /^-?[\d.]+(?:e-?\d+)?$/.test(data);
                    $(this).removeClass("is-invalid");
                    $(this).removeClass("is-valid");
                    if (data == "") {
                        $(this).addClass(
                            data != "" ? "is-valid" : "is-invalid",
                        );
                        father.parent().find(".valid-message").remove();
                        father
                            .parent()
                            .append(
                                `<div class="valid-message invalid-feedback"> Trường bắt buộc </div>`,
                            );
                    } else {
                        $(this).addClass(valid ? "is-valid" : "is-invalid");
                        father.parent().find(".valid-message").remove();
                        father
                            .parent()
                            .append(
                                `<div class="valid-message invalid-feedback"> Định dạng không hợp lệ </div>`,
                            );
                    }
                } else if (valid_data == "valid-text") {
                    $(this).removeClass("is-invalid");
                    $(this).removeClass("is-valid");

                    $(this).addClass(data != "" ? "is-valid" : "is-invalid");
                    father.parent().find(".valid-message").remove();
                    father
                        .parent()
                        .append(
                            `<div class="valid-message invalid-feedback"> Trường bắt buộc </div>`,
                        );
                }
            });
        },
        onChangeText(resource) {
            $(resource).on("keyup", function (event) {
                var value = $(this).val();
                var valid_append = $(this).attr("valid-append");
                $(`[valid-appended=${valid_append}]`).html(value);
            });
        },
        onControl(name, callback) {
            $(document).on("click", ".main-tab-control", function () {
                var id = $(this).attr("data-id");
                if ($(this).attr("atr").trim() == name) {
                    callback(id);
                }
            });
        },
        onClose() {
            $(document).on("click", ".main-tab-close", function () {
                $(".main-tab").removeClass("on-show");
                $(".main-tab[tab-name=Main]").addClass("on-show");
            });
        },
        onShow(resource) {
            $(".main-tab").removeClass("on-show");
            $(`.main-tab[tab-name=${resource}]`).addClass("on-show");
        },
        setDefaul() {
            $(document).off("click", `.push-data`);
            $(".product-name").val("");
            $(".product-prices").val("");
            $(".product-images").val("");
            $(".metadata-render").find(".metadata-item").remove();
            $(".product-var-list").find(".product-var-row").remove();
            $(".product-description").val("");
            $(".multi-upload .prev-upload").remove();
            $(".multi-upload .image-loader").remove();
            $(".error-log li").remove();
            $("[valid-appended=name-append]").text("");
            ViewIndex.summerNote.update(".product-detail", "");
        },
        setVal(data) {
            $(".product-id").val(data.id);
            $(".product-name").val(data.name);
            $(".data-banner").css({
                "background-image": `url(/'${data.banner}')`,
            });
            $(".product-prices").val(data.prices);
            $(".product-category").val(data.category_id);
            $(".product-trademark").val(data.trademark_id);
            $(".product-description").val(data.description);
            ViewIndex.summerNote.update(`.product-detail`, data.detail);
            data.images == "" ? null : ViewIndex.multiImage.setVal(data.images);

            var metadata = JSON.parse(data.metadata);
            for (const [key, value] of Object.entries(metadata)) {
                if (key == "size") {
                    $(`.metadata-render[data-name=${key}]`).append(
                        value
                            .map(
                                (v) =>
                                    `<div class="metadata-item data-size" data-value="${v}"><div class="remove-item"><i class="far fa-times-circle"></i></div>${v}</div>`,
                            )
                            .join(""),
                    );
                } else if (key == "color") {
                    $(`.metadata-render[data-name=${key}]`).append(
                        value
                            .map(
                                (v) =>
                                    `<div class="metadata-item data-color" data-value="${v}" style="background-color: ${v}"><div class="remove-item"><i class="far fa-times-circle"></i></div></div>`,
                            )
                            .join(""),
                    );
                }
            }
        },
        getVal() {
            var fd = new FormData();
            var required_data = [];
            var onPushData = true;

            var data_id = $(".product-id").val();
            var data_name = $(".product-name").val();
            var data_prices = $(".product-prices").val();
            var data_category = $(".product-category").val();
            var data_brand = $(".product-brand").val();
            var data_description = $(".product-description").val();
            var data_detail = $(".product-detail").val();
            var data_images = $(".product-images")[0].files;
            var data_banner = $(".product-banner")[0].files;
            var data_product_var = View.product.getVal();

            var data_images_preview = [];
            $(`.main-body`)
                .find(".image-preview-item.image-load-data")
                .each(function (index, el) {
                    data_images_preview.push($(this).attr("data-url"));
                });

            // --Required Value
            if (data_images.length <= 0 && data_images_preview.length == 0) {
                required_data.push("Hãy chọn ảnh.");
                onPushData = false;
            }
            if (data_name == "") {
                required_data.push("Nhập tên sản phẩm.");
                onPushData = false;
            }
            if (data_prices == "") {
                required_data.push("Nhập giá sản phẩm.");
                onPushData = false;
            }

            if (onPushData) {
                fd.append("data_id", data_id);
                fd.append("data_name", data_name);
                fd.append("data_prices", data_prices);
                fd.append("data_category", data_category);
                fd.append("data_brand", data_brand);
                fd.append("data_description", data_description);
                fd.append("data_detail", data_detail);
                fd.append("data_banner", data_banner[0]);
                fd.append("data_product_var", JSON.stringify(data_product_var));
                fd.append(
                    "data_images_preview",
                    data_images_preview.toString(),
                );

                fd.append("image_list_length", data_images.length);
                for (var i = 0; i < data_images.length; i++) {
                    fd.append("image_list_item_" + i, data_images[i]);
                }

                return fd;
            } else {
                $(`#tab-create`).find(".error-log .js-errors").remove();
                var required_noti = ``;
                for (var i = 0; i < required_data.length; i++) {
                    required_noti += `<li class="error">${required_data[i]}</li>`;
                }
                $(`#tab-create`)
                    .find(".error-log")
                    .prepend(` <ul class="js-errors">${required_noti}</ul> `);
                return false;
            }
        },
        onPush(name, callback) {
            $(document).on("click", ".push-data", function () {
                if ($(this).attr("atr").trim() == name) {
                    var data = View.mainTab.getVal();
                    if (data) callback(data);
                }
            });
        },
        init() {
            View.mainTab.onClose();
            View.mainTab.onValid();
            $(document).on("keypress", `.product-prices`, function (event) {
                return View.isNumberKey(event);
            });
            this.setDefaul();
            this.onChangeText(".name-append");
            ViewIndex.summerNote.init(".product-detail", "Mô tả đầy đủ", 400);
        },
    },

    modals: {
        onControl(name, callback) {
            $(document).on("click", ".modal-control", function () {
                var id = $(this).attr("data-id");
                if ($(this).attr("atr").trim() == name) {
                    callback(id);
                }
            });
        },
        launch(resource, modalTitleHTML, modalBodyHTML, modalFooterHTML) {
            $(`${resource} .modal-title`).html(modalTitleHTML);
            $(`${resource} .modal-body`).html(modalBodyHTML);
            $(`${resource} .modal-footer .close-modal`).html(
                modalFooterHTML[0],
            );
            $(`${resource} .modal-footer .push-modal`).html(modalFooterHTML[1]);
        },
        onShow(resource) {
            $(resource).modal("show");
            $(document).off("click", `${resource} .push-modal`);
        },
        onHide(resource) {
            $(resource).modal("hide");
        },
        onClose(resource) {
            $(document).on("click", ".close-modal", function () {
                $(resource).modal("hide");
            });
        },
        Delete: {
            resource: "#modal-delete",
            setDefaul() {
                this.init();
            },
            textDefaul() {},
            setVal(data) {},
            getVal() {},
            onPush(name, callback) {
                var resource = this.resource;
                $(document).on(
                    "click",
                    `${this.resource} .push-modal`,
                    function () {
                        if ($(this).attr("atr").trim() == name) {
                            callback();
                        }
                    },
                );
            },
            init() {
                var modalTitleHTML = `Xóa`;
                var modalBodyHTML = Template.Category.Delete();
                var modalFooterHTML = ["Đóng", "Xóa"];
                View.modals.onClose("#modal-delete");
                View.modals.launch(
                    this.resource,
                    modalTitleHTML,
                    modalBodyHTML,
                    modalFooterHTML,
                );
            },
        },
        init() {
            this.Delete.init();
        },
    },
    isNumberKey(evt) {
        var charCode = evt.which ? evt.which : event.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) return false;
        return true;
    },
    init() {
        View.table.init();
        View.mainTab.init();
        View.modals.init();
        View.product.init();
    },
};
(() => {
    View.init();

    View.mainTab.onControl("Create", () => {
        View.mainTab.setDefaul();
        View.mainTab.onShow("Create");
        View.product.setBrand();
        View.mainTab.onPush("Create", (fd) => {
            Api.Product.Store(fd)
                .done((res) => {
                    ViewIndex.helper.showToastSuccess(
                        "Success",
                        "Tạo thành công !",
                    );
                    getData();
                    View.mainTab.onShow("Main");
                })
                .fail((err) => {
                    ViewIndex.helper.showToastError("Error", "Có lỗi sảy ra");
                })
                .always(() => {});
        });
    });

    View.mainTab.onControl("View", (id) => {
        View.mainTab.setDefaul();
        Api.Category.GetAll()
            .done((res) => {
                View.mainTab.render.category(res.data);
            })
            .fail((err) => {
                ViewIndex.helper.showToastError("Error", "Có lỗi sảy ra");
            })
            .always(() => {});
        Api.Trademark.GetAll()
            .done((res) => {
                View.mainTab.render.trademark(res.data);
            })
            .fail((err) => {
                ViewIndex.helper.showToastError("Error", "Có lỗi sảy ra");
            })
            .always(() => {});
        Api.Product.getOne(id)
            .done((res) => {
                View.mainTab.onShow("Create");
                setTimeout(View.mainTab.setVal(res.data[0]), 1000);
                View.mainTab.onPush("Create", (fd) => {
                    Api.Product.Update(fd)
                        .done((res) => {
                            ViewIndex.helper.showToastSuccess(
                                "Success",
                                "Tạo thành công !",
                            );
                            getData();
                            View.mainTab.onShow("Main");
                        })
                        .fail((err) => {
                            ViewIndex.helper.showToastError(
                                "Error",
                                "Có lỗi sảy ra",
                            );
                        })
                        .always(() => {});
                });
            })
            .fail((err) => {
                ViewIndex.helper.showToastError("Error", "Có lỗi sảy ra");
            })
            .always(() => {});
    });

    View.modals.onControl("Delete", (id) => {
        var resource = View.modals.Delete.resource;
        View.modals.onShow(resource);
        View.modals.Delete.onPush("Push", () => {
            ViewIndex.helper.showToastProcessing("Processing", "Đang xóa!");
            Api.Product.Delete(id)
                .done((res) => {
                    ViewIndex.helper.showToastSuccess(
                        "Success",
                        "Xóa thành công !",
                    );
                    getData();
                })
                .fail((err) => {
                    ViewIndex.helper.showToastError("Error", "Có lỗi sảy ra");
                })
                .always(() => {});
            View.modals.onHide(resource);
            View.modals.Delete.setDefaul();
        });
    });
    ViewIndex.table.onSwitch(
        debounce((item) => {
            Api.Product.Trending(item.attr("data-id"))
                .done((res) => {
                    // getData()
                    item.find(".slider").toggleClass("active");
                })
                .fail((err) => {
                    console.log(err);
                })
                .always(() => {});
        }, 500),
    );

    function init() {
        getData();
        getCategory();
        getBrand();
        getSize();
        getMaterial();
        getColor();
    }

    function getCategory() {
        Api.Category.GetAll()
            .done((res) => {
                View.data.category = res.data;
            })
            .fail((err) => {
                ViewIndex.helper.showToastError("Error", "Có lỗi sảy ra");
            })
            .always(() => {});
    }

    function getBrand() {
        Api.Brand.GetAll()
            .done((res) => {
                View.data.brand = res.data;
            })
            .fail((err) => {
                ViewIndex.helper.showToastError("Error", "Có lỗi sảy ra");
            })
            .always(() => {});
    }

    function getSize() {
        Api.Size.GetAll()
            .done((res) => {
                View.data.size = res.data;
            })
            .fail((err) => {
                ViewIndex.helper.showToastError("Error", "Có lỗi sảy ra");
            })
            .always(() => {});
    }

    function getMaterial() {
        Api.Material.GetAll()
            .done((res) => {
                View.data.material = res.data;
            })
            .fail((err) => {
                ViewIndex.helper.showToastError("Error", "Có lỗi sảy ra");
            })
            .always(() => {});
    }

    function getColor() {
        Api.Color.GetAll()
            .done((res) => {
                View.data.color = res.data;
            })
            .fail((err) => {
                ViewIndex.helper.showToastError("Error", "Có lỗi sảy ra");
            })
            .always(() => {});
    }

    function getData() {
        Api.Product.GetAll()
            .done((res) => {
                ViewIndex.table.clearRows();
                Object.values(res.data).map((v) => {
                    ViewIndex.table.insertRow(View.table.__generateDTRow(v));
                    ViewIndex.table.render();
                });
                ViewIndex.table.render();
            })
            .fail((err) => {
                ViewIndex.helper.showToastError("Error", "Có lỗi sảy ra");
            })
            .always(() => {});
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
    init();
})();
