(() => {
    const $createTbody = $("#product-create-vars");
    const $editTbody = $("#product-edit-vars");
    const rowTemplate = $("#product-var-row-template").html();
    let varIndex = 0;

    const reindexRows = ($tbody) => {
        $tbody.find(".product-var-row").each(function (i) {
            $(this)
                .find("[name]")
                .each(function () {
                    this.name = this.name.replace(/variants\[\d+\]/, `variants[${i}]`);
                });
        });
        varIndex = $tbody.find(".product-var-row").length;
    };

    const addVarRow = ($tbody, html) => {
        const index = $tbody.find(".product-var-row").length;
        const row = (html || rowTemplate).replace(/__INDEX__/g, index);
        $tbody.append(row);
        varIndex = index + 1;
    };

    const resetVariants = ($tbody) => {
        $tbody.empty();
        addVarRow($tbody);
    };

    const refreshMediaFields = ($form) => {
        $form.find(".media-field").each(function () {
            $(this).trigger("media-field:refresh");
        });
    };

    $(document).on("click", '[data-action="show-create-form"][data-module="product"]', function () {
        setTimeout(() => {
            resetVariants($createTbody);
            $("#product-create-form .media-value-input").val("");
            refreshMediaFields($("#product-create-form"));
        }, 0);
    });

    $(document).on("click", '[data-action="hide-form"][data-module="product"]', function () {
        $createTbody.empty();
        $editTbody.empty();
    });

    $(document).on("click", ".add-var-row", function () {
        const target = $(this).data("target");
        addVarRow($(`#${target}`));
    });

    $(document).on("click", ".remove-var-row", function () {
        const $tbody = $(this).closest("tbody");
        if ($tbody.find(".product-var-row").length <= 1) return;
        $(this).closest("tr").remove();
        reindexRows($tbody);
    });

    $(document).on("click", '[data-action="show-product-edit"]', function (e) {
        e.preventDefault();
        const productId = $(this).data("id");
        const $form = $("#product-edit-form");
        const $list = $("#product-list");

        $.get(`/admin/product/${productId}/data`)
            .done((res) => {
                const p = res.data.product;
                const vars = res.data.variants;

                $form.find('[name="id"]').val(p.id);
                $form.find('[name="name"]').val(p.name);
                $form.find('[name="category_id"]').val(p.category_id);
                $form.find('[name="brand_id"]').val(p.brand_id);
                $form.find('[name="description"]').val(p.description || "");
                $form.find('[name="detail"]').val(p.detail || "");
                $form.find('[name="images"]').val(p.images === "[]" ? "" : p.images);
                $form.find('[name="banner"]').val(p.banner || "");
                $form.find('[name="status"]').val(p.status);
                refreshMediaFields($form);

                $editTbody.empty();
                if (vars.length) {
                    vars.forEach((v, i) => {
                        const row = rowTemplate.replace(/__INDEX__/g, i);
                        $editTbody.append(row);
                        const $row = $editTbody.find(".product-var-row").last();
                        $row.find(".var-id").val(v.id);
                        $row.find('[name$="[color_id]"]').val(v.color_id);
                        $row.find('[name$="[size_id]"]').val(v.size_id);
                        $row.find('[name$="[material_id]"]').val(v.material_id);
                        $row.find('[name$="[codeSKU]"]').val(v.codeSKU);
                        $row.find('[name$="[prices]"]').val(v.prices);
                        $row.find('[name$="[minQuantity]"]').val(v.minQuantity);
                    });
                } else {
                    addVarRow($editTbody);
                }

                $list.addClass("d-none");
                $("#product-create-form").addClass("d-none");
                $form.removeClass("d-none");
            })
            .fail(() => alert("Không tải được dữ liệu sản phẩm"));
    });
})();
