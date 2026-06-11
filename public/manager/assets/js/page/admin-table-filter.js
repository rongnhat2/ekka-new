(() => {
    const $form = $("#admin-table-filter");
    if (!$form.length) return;

    let searchTimer;

    $form.on("change", '[name="per_page"]', function () {
        $form.find('[name="page"]').val(1);
        $form.submit();
    });

    $form.on("input", '[name="search"]', function () {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            $form.find('[name="page"]').val(1);
            $form.submit();
        }, 400);
    });
})();
