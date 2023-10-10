const ctx = document.getElementById("myChart");
const house = document.getElementById("houseId");
const periodInput = document.getElementById("period-statistic");

const houseId = house.dataset.houseId;
let chartInstance = null; // Variabile per memorizzare il riferimento al grafico

function fetchDataAndCreateChart() {
    axios
        .get(
            `http://127.0.0.1:8000/api/houses/views/chart?house_id=${houseId}&period_date=${periodInput.value}`
        )
        .then((res) => {
            if (chartInstance) {
                chartInstance.destroy(); // Distruzione del grafico esistente
            }

            const label = [];
            const view = [];
            const messages = {};

            res.data.views.forEach((element) => {
                label.push(element.date);
                view.push(element.views);
            });

            res.data.messages.forEach((element) => {
                const messageDate = element.date;
                const messageValue = element.message;

                // Verifica se la data del messaggio è presente nelle etichette (mesi)
                const labelIndex = label.indexOf(messageDate);

                if (labelIndex !== -1) {
                    // Se la data è presente, assegna il messaggio al mese corrispondente
                    if (!messages[messageDate]) {
                        messages[messageDate] = 0;
                    }
                    messages[messageDate] += messageValue;
                }
            });

            const messageData = label.map((month) => messages[month] || 0);

            chartInstance = new Chart(ctx, {
                type: "bar",
                data: {
                    labels: label.reverse(),
                    datasets: [
                        {
                            label: "Visualizzazioni",
                            data: view.reverse(),
                            backgroundColor: "rgba(75, 192, 192, 0.2)",
                            borderColor: "rgba(75, 192, 192, 1)",
                            borderWidth: 1,
                        },
                        {
                            label: "Messaggi",
                            data: messageData.reverse(),
                            backgroundColor: "rgba(255, 99, 132, 0.2)",
                            borderColor: "rgba(255, 99, 132, 1)",
                            borderWidth: 1,
                        },
                    ],
                },
                options: {
                    maintainAspectRatio: false,
                },
            });
        });
}
// Esegui la chiamata Axios all'avvio della pagina
fetchDataAndCreateChart();

periodInput.addEventListener("change", fetchDataAndCreateChart);
