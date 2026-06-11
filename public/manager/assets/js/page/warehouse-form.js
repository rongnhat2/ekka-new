(() => {
    const cfg = window.WarehouseForm;
    if (!cfg) return;

    const rowTemplate = $("#warehouse-import-row-template").html();
    const $rows = $("#warehouse-import-rows");

    const reindexRows = () => {
        $rows.find(".warehouse-import-row").each(function (i) {
            $(this)
                .find("[name]")
                .each(function () {
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
                    const label = `${v.size_name} ${v.color_name} ${v.material_name} (Tồn: ${v.stock})`;
                    $varSelect.append(`<option value="${v.id}" data-price="${v.prices}">${label}</option>`);
                });
            })
            .fail(() => alert("Không tải được biến thể sản phẩm"));
    };

    const addRow = () => {
        const index = $rows.find(".warehouse-import-row").length;
        $rows.append(rowTemplate.replace(/__INDEX__/g, index));
    };

    const resetRows = () => {
        $rows.empty();
        addRow();
    };

    $(document).on("click", '[data-action="show-create-form"][data-module="warehouse"]', function () {
        setTimeout(resetRows, 0);
    });

    $(document).on("click", '[data-action="hide-form"][data-module="warehouse"]', function () {
        $rows.empty();
    });

    $("#add-import-row").on("click", addRow);

    $(document).on("click", ".remove-import-row", function () {
        if ($rows.find(".warehouse-import-row").length <= 1) return;
        $(this).closest(".warehouse-import-row").remove();
        reindexRows();
    });

    $(document).on("change", ".warehouse-product-select", function () {
        const $row = $(this).closest(".warehouse-import-row");
        loadVariants($row, $(this).val());
    });

    $(document).on("change", ".warehouse-var-select", function () {
        const price = $(this).find(":selected").data("price");
        const $row = $(this).closest(".warehouse-import-row");
        if (price !== undefined && price !== "") {
            $row.find('[name$="[price]"]').val(price);
        }
    });
})();
