document.addEventListener("DOMContentLoaded", function () {

    console.log("🔥 Data Kandang:", window.kandangChart);
    console.log("🔥 Status Kandang:", window.kandangStatus);

  /* ============================================================
 * BAR CHART — TOTAL RIWAYAT PRODUKSI ANAKAN PER KANDANG
 * ============================================================ */
const barCtx = document.getElementById("barKandangChart");

if (barCtx && window.kandangChart) {

    const barColors = [
        "#3B82F6", "#10B981", "#F59E0B", "#EF4444", "#8B5CF6",
        "#06B6D4", "#84CC16", "#D946EF", "#F97316", "#0EA5E9",
        "#14B8A6", "#A855F7"
    ];

    new Chart(barCtx.getContext("2d"), {
        type: 'bar',
        data: {
            labels: window.kandangChart.map(k => `Kandang ${k.nama}`),
            datasets: [{
                label: 'Total Riwayat Anakan',
                data: window.kandangChart.map(k => k.jumlah), // TOTAL RIWAYAT
                backgroundColor: window.kandangChart.map(
                    (_, i) => barColors[i % barColors.length]
                ),
                borderRadius: 8,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => `${ctx.raw} ekor (riwayat produksi)`
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        precision: 0,
                        callback: v => v + " ekor"
                    }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
}


  /* ============================================================
 * 2️⃣ PIE CHART — STATUS KANDANG (PREMIUM)
 * ============================================================ */
const ctxPie = document.getElementById("pieKandangChart");

if (ctxPie && window.kandangStatus) {

    const total = window.kandangStatus.kosong +
                  window.kandangStatus.bertelur +
                  window.kandangStatus.mengeram;

    new Chart(ctxPie.getContext("2d"), {
        type: "pie",
        data: {
            labels: ["Kosong", "Bertelur", "Mengeram"],
            datasets: [{
                data: [
                    window.kandangStatus.kosong,
                    window.kandangStatus.bertelur,
                    window.kandangStatus.mengeram
                ],
                backgroundColor: ["#9CA3AF", "#FBBF24", "#EF4444"],
                borderWidth: 1,
                borderColor: "#fff"
            }]
        },
        plugins: [ChartDataLabels],
        options: {
    responsive: true,
    maintainAspectRatio: false,
    layout: {
        padding: {
            top: 20,
            bottom: 20,
            left: 20,
            right: 20
        }
    },
    plugins: {
        legend: {
            position: "bottom",
            labels: {
                padding: 20
            }
        },
        datalabels: {
            color: "#fff",
            anchor: "center",
            align: "center",
            font: { weight: "bold", size: 14 },
            formatter: (value) => {
                if (value === 0) return "";
                const total =
                    window.kandangStatus.kosong +
                    window.kandangStatus.bertelur +
                    window.kandangStatus.mengeram;
                const percent = ((value / total) * 100).toFixed(1);
                return `${value} kandang`;
            }
        }
    }
}

    });
}


});
