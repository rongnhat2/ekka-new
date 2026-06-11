(() => {
    const cfg = window.OrderDetail;
    if (!cfg) return;

    const $modal = $("#update-modal");
    let currentOrderId = null;
    let modalReady = false;

    const hideModal = () => {
        $modal.removeClass("show");
        $("body").removeClass("modal-fs-open");
        currentOrderId = null;
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
        $(".customer-name").html(order.username);
        $(".customer-address").html(order.address);
        $(".customer-email").html(order.email);
        $(".customer-telephone").html(order.telephone);
        $(".data-list").find("tr").remove();

        (data.data_sub || []).forEach((v) => {
            const subStatus = v.suborder_status == 1
                ? '<div class="badge badge-success badge-pill">Đã hoàn thiện</div>'
                : '<div class="badge badge-warning badge-pill">Chờ xử lí</div>';

            $(".data-list").append(`<tr>
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

        $(".order-status").val(order.order_status);
    };

    $(document).on("click", ".modal-fs-control", function () {
        currentOrderId = $(this).data("id");
        if (!currentOrderId) return;

        initModal();

        $.get(`${cfg.dataUrl}/${currentOrderId}/data`)
            .done((res) => {
                setVal(res.data);
                showModal();
            })
            .fail(() => alert("Không tải được chi tiết đơn hàng"));
    });

    $(document).on("click", ".modal-close, .close-modal", hideModal);

    $(document).mouseup(function (e) {
        const container = $(".fs-body");
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            hideModal();
        }
    });

    $(document).on("click", `${$modal.selector} .push-modal`, function () {
        const atr = ($(this).attr("atr") || "").trim();
        if (!currentOrderId || atr !== "Push") return;

        const fd = new FormData();
        fd.append("data_id", currentOrderId);
        fd.append("data_status", $(".order-status").val());
        fd.append("_token", cfg.csrf);

        $.ajax({
            url: cfg.updateUrl,
            method: "POST",
            data: fd,
            processData: false,
            contentType: false,
        })
            .done(() => {
                hideModal();
                window.location.reload();
            })
            .fail(() => alert("Cập nhật thất bại"));
    });
})();
