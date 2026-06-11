(() => {
    const cfg = window.OrderDetail;
    if (!cfg) return;

    const $modal = $("#update-modal");
    const $wrapper = $modal.find(".fs-wrapper");
    let modalReady = false;

    const getOrderId = () => $modal.data("orderId") || null;

    const setOrderId = (id) => {
        if (id) {
            $modal.data("orderId", id);
        } else {
            $modal.removeData("orderId");
        }
    };

    const hideModal = () => {
        $modal.removeClass("show");
        $("body").removeClass("modal-fs-open");
        setOrderId(null);
    };

    const showModal = () => {
        $modal.addClass("show");
        $("body").addClass("modal-fs-open");
    };

    const initModal = () => {
        if (modalReady) return;
        $modal.find(".modal-title").html("Chi tiết đơn hàng");
        $modal.find(".fs-content-wrapper").html(Template.Order.Update());
        $modal.find(".close-modal").html("Đóng");
        $modal.find(".push-modal").html("Cập nhật");
        modalReady = true;
    };

    const setVal = (data) => {
        const order = data.data_order[0];
        $modal.find(".customer-name").html(order.username);
        $modal.find(".customer-address").html(order.address);
        $modal.find(".customer-email").html(order.email);
        $modal.find(".customer-telephone").html(order.telephone);
        $modal.find(".data-list").find("tr").remove();

        (data.data_sub || []).forEach((v) => {
            const subStatus =
                v.suborder_status == 1
                    ? '<div class="badge badge-success badge-pill">Đã hoàn thiện</div>'
                    : '<div class="badge badge-warning badge-pill">Chờ xử lí</div>';

            $modal.find(".data-list").append(`<tr>
                <td>${v.product_id}</td>
                <td>${v.name}</td>
                <td>${v.quantity}</td>
                <td>${v.size_name} ${v.color_name} ${v.material_name}</td>
                <td>${v.price}</td>
                <td>${v.discount} %</td>
                <td>${v.total_price}</td>
                <td>${v.stock ?? 0}</td>
                <td>${subStatus}</td>
            </tr>`);
        });

        $modal.find(".order-status").val(String(order.order_status));
    };

    const redirectAfterUpdate = (status) => {
        const base = cfg.indexUrl || window.location.pathname;
        const url = new URL(base, window.location.origin);
        url.searchParams.set("status", status);
        url.searchParams.set("updated", "1");
        window.location.href = url.toString();
    };

    $(document).on("click", ".modal-fs-control", function () {
        const orderId = $(this).attr("data-id");
        if (!orderId) return;

        setOrderId(orderId);
        initModal();

        $.get(`${cfg.dataUrl}/${orderId}/data`)
            .done((res) => {
                setVal(res.data);
                showModal();
            })
            .fail(() => {
                setOrderId(null);
                alert("Không tải được chi tiết đơn hàng");
            });
    });

    // Gắn trực tiếp trên modal — không dùng document delegation (tránh bị chặn bubble)
    $modal.on("click", ".modal-close, .close-modal", function (e) {
        e.preventDefault();
        hideModal();
    });

    $wrapper.on("click", function (e) {
        if (!$modal.hasClass("show")) return;
        if ($(e.target).is($wrapper)) {
            hideModal();
        }
    });

    $modal.on("click", ".push-modal", function (e) {
        e.preventDefault();

        const orderId = getOrderId();
        const status = $modal.find(".order-status").val();

        if (!orderId) {
            alert("Không xác định được mã đơn hàng. Vui lòng mở lại chi tiết đơn.");
            return;
        }
        if (status === null || status === "") {
            alert("Vui lòng chọn trạng thái đơn hàng.");
            return;
        }

        const fd = new FormData();
        fd.append("data_id", orderId);
        fd.append("data_status", status);
        fd.append("_token", cfg.csrf);

        const $btn = $(this).prop("disabled", true);

        $.ajax({
            url: cfg.updateUrl,
            method: "POST",
            data: fd,
            processData: false,
            contentType: false,
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                Accept: "application/json",
            },
        })
            .done(() => {
                hideModal();
                redirectAfterUpdate(status);
            })
            .fail((xhr) => {
                let msg = "Cập nhật thất bại";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    msg = Object.values(xhr.responseJSON.errors).flat().join("\n");
                } else if (xhr.status === 419) {
                    msg = "Phiên đăng nhập hết hạn. Vui lòng tải lại trang.";
                }
                alert(msg);
            })
            .always(() => {
                $btn.prop("disabled", false);
            });
    });
})();
