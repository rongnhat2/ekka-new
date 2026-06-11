(() => {
    const listUrl = "/admin/media/list";
    let $targetInput = null;
    let mode = "multiple";
    let selected = [];

    const renderPickerGrid = (items) => {
        const $grid = $("#media-picker-grid");
        const $empty = $("#media-picker-empty");
        $grid.empty();

        if (!items.length) {
            $empty.removeClass("d-none");
            return;
        }
        $empty.addClass("d-none");

        items.forEach((item) => {
            const isSelected = selected.includes(item.path);
            $grid.append(`
                <div class="media-grid-item media-picker-item ${isSelected ? "is-selected" : ""}"
                     data-path="${item.path}" title="${item.name}">
                    <div class="media-grid-thumb" style="background-image:url('/${item.path}')"></div>
                </div>
            `);
        });
    };

    const loadMedia = () => {
        $.get(listUrl).done((res) => renderPickerGrid(res.data || []));
    };

    const renderPreview = ($wrap) => {
        const $input = $wrap.find(".media-value-input");
        const $preview = $wrap.find(".media-preview-list");
        const paths = ($input.val() || "").split(",").filter(Boolean);
        $preview.empty();
        paths.forEach((path) => {
            $preview.append(`
                <div class="media-preview-item" data-path="${path}">
                    <div class="media-preview-thumb" style="background-image:url('/${path}')"></div>
                    <button type="button" class="media-preview-remove" title="Bỏ chọn">&times;</button>
                </div>
            `);
        });
    };

    window.MediaPicker = {
        renderPreview,
        syncInputFromPreview($wrap) {
            const paths = [];
            $wrap.find(".media-preview-item").each(function () {
                paths.push($(this).data("path"));
            });
            $wrap.find(".media-value-input").val(paths.join(","));
        },
    };

    $(document).on("click", ".open-media-picker", function (e) {
        e.preventDefault();
        const $wrap = $(this).closest(".media-field");
        $targetInput = $wrap.find(".media-value-input");
        mode = $(this).data("mode") || "multiple";
        selected = ($targetInput.val() || "").split(",").filter(Boolean);

        $(".media-picker-hint").text(
            mode === "single" ? "Chọn 1 ảnh" : "Chọn nhiều ảnh (bấm để bật/tắt)"
        );

        loadMedia();
        $("#media-picker-modal").modal("show");
    });

    $(document).on("click", ".media-picker-item", function () {
        const path = $(this).data("path");
        if (mode === "single") {
            selected = [path];
            $(".media-picker-item").removeClass("is-selected");
            $(this).addClass("is-selected");
            return;
        }
        if (selected.includes(path)) {
            selected = selected.filter((p) => p !== path);
            $(this).removeClass("is-selected");
        } else {
            selected.push(path);
            $(this).addClass("is-selected");
        }
    });

    $("#media-picker-confirm").on("click", function () {
        if (!$targetInput) return;
        const $wrap = $targetInput.closest(".media-field");
        $targetInput.val(selected.join(","));
        renderPreview($wrap);
        $("#media-picker-modal").modal("hide");
    });

    $(document).on("click", ".media-preview-remove", function () {
        const $wrap = $(this).closest(".media-field");
        $(this).closest(".media-preview-item").remove();
        MediaPicker.syncInputFromPreview($wrap);
    });

    $(document).on("media-field:refresh", ".media-field", function () {
        renderPreview($(this));
    });
})();
