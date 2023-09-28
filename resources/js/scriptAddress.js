const address = document.getElementById("address");
const form = document.getElementById("form-houses");
const latitude = document.getElementById("latitude");
const longitude = document.getElementById("longitude");
const inputAddress = document.getElementById("address");
const listAddress = document.getElementById("list-address");
const containerListAddress = document.getElementById("container-list-address");

// Creo una variabile d'appoggio per il timeout
let searchAddress;

// Ascolto il keyup
inputAddress.addEventListener("keyup", () => {
    // Prendo il valore dell input address
    const addressValue = address.value;
    // Controllo che non sia vuoto
    if (!addressValue) {
        while (listAddress.firstChild) {
            listAddress.removeChild(listAddress.firstChild);
        }
        return;
    }
    // Cancello il Timeout
    clearTimeout(searchAddress);
    // Creo il Timeout
    searchAddress = setTimeout(() => {
        // Faccio una chiamata per autocomplete
        axios
            .get(
                `https://api.tomtom.com/search/2/search/${addressValue}.json?limit=5&countrySet=IT%2FITA&extendedPostalCodesFor=Addr&view=Unified&key=soH7vSRFYTpCT37GOm8wEimPoDyc3GMe`
            )
            // Se mi arriva la risposta
            .then((res) => {
                // Cancello gli li
                while (listAddress.firstChild) {
                    listAddress.removeChild(listAddress.firstChild);
                }
                // creo gli li
                res.data.results.forEach((result, i) => {
                    containerListAddress.classList.remove("d-none");
                    const lists = document.createElement("li");
                    lists.classList.add("list-group-item");
                    lists.innerHTML = result.address.freeformAddress;
                    listAddress.append(lists);
                });
            })
            // Se c'è un errore
            .catch((e) => {
                // Stampo in console
                console.error(e);
            });
    }, 300);
});

// Ascolto il submit del form
form.addEventListener("submit", () => {
    // Blocco l'evento
    event.preventDefault();
    // Prendo il valore dell' input dell'address
    const addressValue = address.value;

    // Creo il config
    const config = { headers: { accept: "*/*" } };
    // Faccio una chiamata per ottenere la lat e long
    axios
        .get(
            `https://api.tomtom.com/search/2/geocode/${addressValue}.json?storeResult=false&lat=37.337&lon=-121.89&view=Unified&key=soH7vSRFYTpCT37GOm8wEimPoDyc3GMe`,
            config
        )
        .then((res) => {
            // le aggiungo nel form
            latitude.value = res.data.results[0].position.lat;
            longitude.value = res.data.results[0].position.lon;
            // Invio il form
            form.submit();
        })
        // Se c'è un errore
        .catch((e) => {
            // Stampo l'errore nella console
            console.error(e);
        });
});
