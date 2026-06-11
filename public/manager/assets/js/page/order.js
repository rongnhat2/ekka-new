const View = {
    table: {
        __generateDTRow(data) {
            var order_status = [
                "badge-warning badge-pill",
                "badge-secondary badge-pill",
                "badge-info badge-pill",
                "badge-success badge-pill",
                "badge-danger badge-pill",
            ];
            var order_status_title = [
                "Chờ xử lí",
                "Chưa hoàn thiện",
                "Đã hoàn thiện",
                "Đã giao hàng",
                "Hoàn trả",
            ];
            var order_payment = [
                " ",
                "badge-pill badge-gold",
                "badge-pill badge-green",
            ];
            var order_payment_title = [" ", "Chưa thanh toán", "Đã thanh toán"];
            return [
                `<div class="id-order">${data.id}</div>`,
                `<p><i class="far fa-user m-r-10"></i>${data.username}</p>
                <p><i class="far fa-envelope m-r-10"></i>${data.email}</p>
                <p><i class="fas fa-phone-alt m-r-10"></i>${data.telephone}</p>`,
                `<div class="d-flex align-items-center">
                    <div class="badge badge-primary badge-dot m-r-10"></div>
                    <div>Tạm tính: ${ViewIndex.table.formatNumber(data.subtotal)} đ</div>
                </div>
                <div class="d-flex align-items-center">
                    <div class="badge badge-secondary badge-dot m-r-10"></div>
                    <div>Giảm giá: ${data.discount} %</div>
                </div>
                <div class="d-flex align-items-center">
                    <div class="badge badge-success badge-dot m-r-10"></div>
                    <div>Thực tính: ${ViewIndex.table.formatNumber(data.total)} đ</div>
                </div>`,
                data.created_at,
                `<div class="badge ${order_status[data.order_status]}">${order_status_title[data.order_status]}</div>
                <div class="badge ${order_payment[data.status]}">${order_payment_title[data.status]}</div>`,
                `<div class="view-data modal-fs-control" style="cursor: pointer" atr="View" data-id="${data.id}"><i class=" feather-eye "></div>`,
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
                    title: "Thông tin",
                    name: "name",
                    orderable: true,
                },
                {
                    title: "Đơn hàng",
                    name: "name",
                    orderable: true,
                },
                {
                    title: "Ngày đặt",
                    name: "icon",
                    orderable: true,
                },
                {
                    title: "Trạng thái",
                    name: "icon",
                    orderable: true,
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
    TabData: {
        onChange(name, callback) {
            $(document).on("click", `.status-event`, function () {
                $(".status-event").removeClass("is-select");
                $(this).addClass("is-select");
                if ($(this).attr("atr").trim() == name) {
                    callback();
                }
            });
        },
    },
    modals: {
        onControl(name, callback) {
            $(document).on("click", ".modal-fs-control", function () {
                var id = $(this).attr("data-id");
                if ($(this).attr("atr").trim() == name) {
                    callback(id);
                }
            });
        },
        onClose() {
            $(document).on("click", ".modal-close", function () {
                $(".modal-fullscreen").removeClass("show");
                $("body").removeClass("modal-fs-open");
            });
            $(document).on("click", ".close-modal", function () {
                $(".modal-fullscreen").removeClass("show");
                $("body").removeClass("modal-fs-open");
            });
            $(document).mouseup(function (e) {
                var container = $(".fs-body");
                if (
                    !container.is(e.target) &&
                    container.has(e.target).length === 0
                ) {
                    $(".modal-fullscreen").removeClass("show");
                    $("body").removeClass("modal-fs-open");
                }
            });
        },
        launch(resource, modalTitleHTML, modalBodyHTML, modalFooterHTML) {
            $(`${resource} .fs-title .modal-title`).html(modalTitleHTML);
            $(`${resource} .fs-content .fs-content-wrapper`).html(
                modalBodyHTML,
            );
            $(`${resource} .fs-footer .close-modal`).html(modalFooterHTML[0]);
            $(`${resource} .fs-footer .push-modal`).html(modalFooterHTML[1]);
        },
        onShow(resource) {
            $(resource).addClass("show");
            $("body").addClass("modal-fs-open");
            $(document).off("click", `${resource} .push-modal`);
        },
        onHide(resource) {
            $(resource).removeClass("show");
            $("body").removeClass("modal-fs-open");
        },
        Update: {
            resource: "#update-modal",
            setDefaul() {
                this.init();
            },
            textDefaul() {
                ViewIndex.textCount.defaul(
                    this.resource + " .data-name",
                    this.resource + " .data-name-return",
                    254,
                );
            },
            createCategory(data) {
                var resource = this.resource;
                $(`${this.resource} .category-list`).find("option").remove();
                $(`${this.resource} .category-list`).append(
                    `<option value="0">----------</option>`,
                );
                data.map((v) => {
                    $(`${this.resource} .category-list`).append(
                        `<option value="${v.id}">${v.name}</option>`,
                    );
                });
            },
            setVal(data) {
                $(".customer-name").html(data.data_order[0].username);
                $(".customer-address").html(data.data_order[0].address);
                $(".customer-email").html(data.data_order[0].email);
                $(".customer-telephone").html(data.data_order[0].telephone);
                var order_status = [
                    "badge-warning badge-pill",
                    "badge-success badge-pill",
                ];
                var order_status_title = ["Chờ xử lí", "Đã hoàn thiện"];
                $(".data-list").find("tr").remove();
                data.data_sub.map((v) => {
                    $(".data-list").append(`<tr>
                                    <td>${v.product_id}</td>
                                    <td>${v.name}</td>
                                    <td>${v.quantity}</td>
                                    <td>${v.size_name} ${v.color_name} ${v.material_name}</td>
                                    <td>${v.price}</td>
                                    <td>${v.discount} %</td>
                                    <td>${v.total_price}</td>
                                    <td>${v.stock ?? 0}</td>
                                    <td><div class="badge ${order_status[v.suborder_status]}">${order_status_title[v.suborder_status]}</div></td>
                                </tr>`);
                });
                $(".order-status").val(data.data_order[0].order_status);
            },
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
                var modalTitleHTML = `Cập nhật`;
                var modalBodyHTML = Template.Order.Update();
                var modalFooterHTML = ["Đóng", "Cập nhật"];
                View.modals.launch(
                    this.resource,
                    modalTitleHTML,
                    modalBodyHTML,
                    modalFooterHTML,
                );
            },
        },
        init() {
            this.onClose();

            this.Update.init();
        },
    },
    init() {
        View.table.init();
        View.modals.init();
    },
};
(() => {
    View.init();

    View.TabData.onChange("Pending", () => {
        getData(0);
        localStorage.setItem("item_tab", 0);
    });
    View.TabData.onChange("Unfulfilled", () => {
        getData(1);
        localStorage.setItem("item_tab", 1);
    });
    View.TabData.onChange("Fulfilled", () => {
        getData(2);
        localStorage.setItem("item_tab", 2);
    });
    View.TabData.onChange("Shipped", () => {
        getData(3);
        localStorage.setItem("item_tab", 3);
    });
    View.TabData.onChange("Refund", () => {
        getData(4);
        localStorage.setItem("item_tab", 4);
    });

    function init() {
        getData(0);
        localStorage.setItem("item_tab", 0);
    }

    View.modals.onControl("View", (id) => {
        var resource = View.modals.Update.resource;
        Api.Order.GetOne(id)
            .done((res) => {
                View.modals.onShow(resource);
                View.modals.Update.setVal(res.data);
                View.modals.Update.onPush("Push", () => {
                    var fd = new FormData();
                    var data_id = id;
                    var data_status = $(".order-status").val();
                    fd.append("data_id", data_id);
                    fd.append("data_status", data_status);
                    Api.Order.Update(fd)
                        .done((res) => {
                            if (res.message == 500) {
                                ViewIndex.helper.showToastError(
                                    "Success",
                                    "Cập nhật thất bại !",
                                );
                            } else {
                                ViewIndex.helper.showToastSuccess(
                                    "Success",
                                    "Cập nhật thành công !",
                                );
                            }
                            getData(localStorage.getItem("item_tab"));
                        })
                        .fail((err) => {
                            ViewIndex.helper.showToastError(
                                "Error",
                                "Có lỗi sảy ra",
                            );
                        })
                        .always(() => {});
                    View.modals.onHide(resource);
                    View.modals.Update.setDefaul();
                });
            })
            .fail((err) => {
                ViewIndex.helper.showToastError("Error", "Có lỗi sảy ra");
            })
            .always(() => {});
    });

    function getData(id) {
        Api.Order.GetAll(id)
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

    init();
})();
