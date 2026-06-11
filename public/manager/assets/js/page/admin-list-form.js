(() => {
    const hidePanels = (module) => {
        $(`#${module}-create-form, #${module}-edit-form`).addClass("d-none");
        $(`#${module}-create-form form, #${module}-edit-form form`).each(function () {
            this.reset?.();
        });
        $(`#${module}-list`).removeClass("d-none");
    };

    const showPanel = (module, panel) => {
        $(`#${module}-list`).addClass("d-none");
        $(`#${module}-create-form, #${module}-edit-form`).addClass("d-none");
        $(`#${module}-${panel}-form`).removeClass("d-none");
    };

    const fillEditForm = ($btn, $editForm) => {
        $.each($btn[0].attributes, function () {
            if (!this.name.startsWith("data-")) return;
            if (this.name === "data-action" || this.name === "data-module") return;
            const field = this.name.slice(5).replace(/-/g, "_");
            const $input = $editForm.find(`[name="${field}"]`);
            if ($input.length) $input.val(this.value);
        });
    };

    $(document).on("click", '[data-action="show-create-form"]', function (e) {
        e.preventDefault();
        showPanel($(this).data("module"), "create");
    });

    $(document).on("click", '[data-action="show-edit-form"]', function (e) {
        e.preventDefault();
        const module = $(this).data("module");
        fillEditForm($(this), $(`#${module}-edit-form`));
        showPanel(module, "edit");
    });

    $(document).on("click", '[data-action="hide-form"]', function (e) {
        e.preventDefault();
        hidePanels($(this).data("module"));
    });
})();
