(() => {
    const cfg = window.MediaLibrary;
    if (!cfg) return;

    const $grid = $("#media-grid");
    const $input = $("#media-upload-input");
    const $zone = $("#media-upload-zone");

    const escapeHtml = (str) =>
        String(str)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;");

    const appendItem = (item) => {
        if (!item || !item.path) return false;
        $grid.find(".media-grid-empty").remove();
        const cacheBust = Date.now();
        $grid.prepend(`
            <div class="media-grid-item" data-id="${item.id}" data-path="${item.path}">
                <div class="media-grid-thumb" style="background-image:url('/${item.path}?t=${cacheBust}')"></div>
                <div class="media-grid-meta">
                    <span class="media-grid-name" title="${escapeHtml(item.name)}">${escapeHtml(item.name)}</span>
                    <button type="button" class="btn btn-default btn-sm media-delete-btn" data-id="${item.id}" title="Xóa">
                        <i class="feather-trash"></i>
                    </button>
                </div>
            </div>
        `);
        return true;
    };

    const uploadFile = (file) => {
        if (file.size > 5242880) {
            alert("File quá lớn, tối đa 5MB");
            return;
        }
        const fd = new FormData();
        fd.append("file", file);
        fd.append("_token", cfg.csrf);

        $.ajax({
            url: cfg.uploadUrl,
            method: "POST",
            data: fd,
            processData: false,
            contentType: false,
            dataType: "json",
            headers: {
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
        })
            .done((res) => {
                if (!appendItem(res.data)) {
                    window.location.reload();
                }
            })
            .fail(() => alert("Upload thất bại"));
    };

    $input.on("change", function () {
        Array.from(this.files).forEach(uploadFile);
        this.value = "";
    });

    $zone.on("dragover", (e) => {
        e.preventDefault();
        $zone.addClass("is-dragover");
    });
    $zone.on("dragleave drop", (e) => {
        e.preventDefault();
        $zone.removeClass("is-dragover");
    });
    $zone.on("drop", (e) => {
        const files = e.originalEvent.dataTransfer.files;
        Array.from(files).forEach(uploadFile);
    });

    $(document).on("click", ".media-delete-btn", function () {
        if (!confirm("Xóa ảnh này khỏi thư viện?")) return;
        const id = $(this).data("id");
        const $item = $(this).closest(".media-grid-item");
        $.ajax({
            url: `${cfg.deleteUrl}/${id}`,
            method: "POST",
            data: { _token: cfg.csrf },
            dataType: "json",
            headers: {
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
        })
            .done(() => {
                $item.remove();
                if (!$grid.find(".media-grid-item").length) {
                    $grid.html('<p class="text-muted media-grid-empty">Chưa có ảnh nào trong thư viện</p>');
                }
            })
            .fail(() => alert("Xóa thất bại"));
    });
})();
