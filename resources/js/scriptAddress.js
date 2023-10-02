const address = document.getElementById("address");
const form = document.getElementById("form-houses");
const latitude = document.getElementById("latitude");
const longitude = document.getElementById("longitude");
const inputAddress = document.getElementById("address");
const listAddress = document.getElementById("list-address");
const invalidAddress = document.getElementById("invalid-address");
const containerListAddress = document.getElementById("container-list-address");
const invalidServices = document.getElementById("invalid-services");
const servicesCheckbox = document.querySelectorAll(".service-checkbox");
const dataFormType = form.dataset.type;

// Creo una variabile d'appoggio per il timeout
let searchAddress;
let addressFlag;
console.log(dataFormType);
if (dataFormType == "create") addressFlag = false;
else if (dataFormType == "edit") addressFlag = true;
// Ascolto il keyup
inputAddress.addEventListener("keyup", () => {
    addressFlag = false;
    invalidAddress.innerText = "";
    address.classList.remove("is-invalid");
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
                `https://api.tomtom.com/search/2/search/${addressValue}.json?limit=5&countrySet=IT&extendedPostalCodesFor=Addr&view=Unified&key=soH7vSRFYTpCT37GOm8wEimPoDyc3GMe`
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
                    const list = document.createElement("li");
                    list.className =
                        "list-group-item list-group-item-action cursor-pointer";
                    list.innerHTML = result.address.freeformAddress;
                    listAddress.append(list);
                    list.addEventListener("click", () => {
                        const listValue = list.innerText;
                        inputAddress.value = listValue;
                        addressFlag = true;
                        while (listAddress.firstChild) {
                            listAddress.removeChild(listAddress.firstChild);
                            containerListAddress.classList.add("d-none");
                        }
                    });
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
    // Vedo se almeno un servizio è checkato
    let serviceChecked = false;
    servicesCheckbox.forEach((serviceCheckbox) => {
        if (serviceCheckbox.checked) serviceChecked = true;
    });

    // Se nessuno è checkato mando il messaggio di errore
    if (serviceChecked == false) {
        invalidServices.classList.remove("d-none");
        invalidServices.classList.add("text-danger");
        invalidServices.innerText =
            "Almeno un servizio deve essere selezionato";
        return;
    }

    // Prendo il valore dell' input dell'address
    const addressValue = address.value;

    // Creo il config
    const config = { headers: { accept: "*/*" } };

    // Vedo se l'indirizzo iserito non e quello consigliato
    if (!addressFlag) {
        address.classList.add("is-invalid");
        invalidAddress.classList.remove("d-none");
        invalidAddress.classList.add("text-danger");
        invalidAddress.innerText =
            "L'indirizzo deve essere scelto tra quelli consigliati.";
        while (listAddress.firstChild) {
            listAddress.removeChild(listAddress.firstChild);
            containerListAddress.classList.add("d-none");
        }
        return;
    }
    // Faccio una chiamata per ottenere la lat e long
    axios
        .get(
            `https://api.tomtom.com/search/2/geocode/${addressValue}.json?storeResult=false&lat=37.337&lon=-121.89&view=Unified&key=soH7vSRFYTpCT37GOm8wEimPoDyc3GMe`,
            config
        )
        .then((res) => {
            // Le aggiungo nel form
            latitude.value = res.data.results[0].position.lat;
            longitude.value = res.data.results[0].position.lon;
            while (listAddress.firstChild) {
                listAddress.removeChild(listAddress.firstChild);
                containerListAddress.classList.add("d-none");
            }
            // Invio il form solo se l'utente clicca sull address consigliato e se almeno un servizio è stato inserito
            if (addressFlag && serviceChecked) form.submit();
        })
        // Se c'è un errore
        .catch((e) => {
            // Stampo l'errore nella console
            console.error(e);
        });
});
