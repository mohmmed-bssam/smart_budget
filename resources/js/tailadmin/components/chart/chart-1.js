export const initChartOne = () => {
    const chartElement = document.querySelector('#chartOne');
    if (!chartElement) return;

    const income = JSON.parse(chartElement.dataset.income || '[]');
    const expenses = JSON.parse(chartElement.dataset.expenses || '[]');
    const incomeLabel = chartElement.dataset.incomeLabel || 'Income';
    const expensesLabel = chartElement.dataset.expensesLabel || 'Expenses';
    const chartOneOptions = {
        series: [
            {
                name: incomeLabel,
                data: income,
            },
            {
                name: expensesLabel,
                data: expenses,
            },
        ],

        colors: ["#12B76A", "#F04438"],

        chart: {
            fontFamily: "Outfit, sans-serif",
            type: "bar",
            height: 260,
            toolbar: {
                show: false,
            },
        },

        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: "45%",
                borderRadius: 5,
                borderRadiusApplication: "end",
            },
        },

        dataLabels: {
            enabled: false,
        },

        stroke: {
            show: true,
            width: 4,
            colors: ["transparent"],
        },

        xaxis: {
            categories: [
                "Jan",
                "Feb",
                "Mar",
                "Apr",
                "May",
                "Jun",
                "Jul",
                "Aug",
                "Sep",
                "Oct",
                "Nov",
                "Dec",
            ],
            axisBorder: {
                show: false,
            },
            axisTicks: {
                show: false,
            },
        },

        legend: {
            show: true,
            position: "top",
            horizontalAlign: "left",
            fontFamily: "Outfit",
            markers: {
                radius: 99,
            },
        },

        yaxis: {
            labels: {
                formatter: function (val) {
                    return Number(val).toLocaleString();
                },
            },
        },

        grid: {
            yaxis: {
                lines: {
                    show: true,
                },
            },
        },

        fill: {
            opacity: 1,
        },

        tooltip: {
            x: {
                show: true,
            },
            y: {
                formatter: function (val) {
                    return Number(val).toLocaleString();
                },
            },
        },
    };

    const chart = new ApexCharts(chartElement, chartOneOptions);
    chart.render();

    return chart;
};

export default initChartOne;
