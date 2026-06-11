(() => {
    const cfg = window.StatisticCharts;
    if (!cfg || typeof Chart === "undefined") return;

    const formatMoney = (v) => v.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");

    const dayLabels = cfg.byDay.map((d) => d.label);
    const dayValues = cfg.byDay.map((d) => d.value);
    const monthLabels = cfg.byMonth.map((d) => d.label);
    const monthValues = cfg.byMonth.map((d) => d.value);

    const dayCtx = document.getElementById("revenue-day-chart");
    if (dayCtx) {
        new Chart(dayCtx.getContext("2d"), {
            type: "bar",
            data: {
                labels: dayLabels,
                datasets: [{
                    label: "Doanh thu (đ)",
                    data: dayValues,
                    backgroundColor: "#3F87F5",
                    borderColor: "#3F87F5",
                    borderWidth: 1,
                }],
            },
            options: {
                responsive: true,
                legend: { display: false },
                scales: {
                    yAxes: [{ ticks: { beginAtZero: true, callback: (v) => formatMoney(v) } }],
                },
                tooltips: {
                    callbacks: {
                        label: (item) => formatMoney(item.yLabel) + " đ",
                    },
                },
            },
        });
    }

    const monthCtx = document.getElementById("revenue-month-chart");
    if (monthCtx) {
        new Chart(monthCtx.getContext("2d"), {
            type: "line",
            data: {
                labels: monthLabels,
                datasets: [{
                    label: "Doanh thu (đ)",
                    data: monthValues,
                    backgroundColor: "rgba(0, 201, 167, 0.1)",
                    borderColor: "#00c9a7",
                    borderWidth: 2,
                    fill: true,
                    pointBackgroundColor: "#00c9a7",
                }],
            },
            options: {
                responsive: true,
                legend: { display: false },
                scales: {
                    yAxes: [{ ticks: { beginAtZero: true, callback: (v) => formatMoney(v) } }],
                },
                tooltips: {
                    callbacks: {
                        label: (item) => formatMoney(item.yLabel) + " đ",
                    },
                },
            },
        });
    }
})();
