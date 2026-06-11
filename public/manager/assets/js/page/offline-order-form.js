(() => {
    const cfg = window.OfflineOrderForm;
    if (!cfg) return;

    const rowTemplate = $("#offline-order-row-template").html();
    const $rows = $("#offline-order-rows");

    const reindexRows = () => {
        $rows.find(".warehouse-import-row").each(function (i) {
            $(this).find("[name]").each(function () {
                this.name = this.name.replace(/items\[\d+\]/, `items[${i}]`);
            });
        });
    };

    const loadVariants = ($row, productId) => {
        const $varSelect = $row.find(".warehouse-var-select");
        $varSelect.html('<option value="">-- Chọn biến thể --</option>');
        if (!productId) return;

        $.get(`${cfg.variantsUrl}/${productId}/variants`)
            .done((res) => {
                (res.data || []).forEach((v) => {
                    const label = `${v.size_name} ${v.color_name} ${v.material_name} — ${Number(v.prices).toLocaleString()}đ (Tồn: ${v.stock})`;
                    $varSelect.append(`<option value="${v.id}" data-price="${v.prices}" data-stock="${v.stock}">${label}</option>`);
                });
            })
            .fail(() => alert("Không tải được biến thể"));
    };

    const addRow = () => {
        const index = $rows.find(".warehouse-import-row").length;
        $rows.append(rowTemplate.replace(/__INDEX__/g, index));
    };

    $("#add-offline-row").on("click", addRow);

    $(document).on("click", ".remove-offline-row", function () {
        if ($rows.find(".warehouse-import-row").length <= 1) return;
        $(this).closest(".warehouse-import-row").remove();
        reindexRows();
    });

    $(document).on("change", ".warehouse-product-select", function () {
        loadVariants($(this).closest(".warehouse-import-row"), $(this).val());
    });
})();
