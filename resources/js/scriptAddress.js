const address = document.getElementById("address");
const form = document.getElementById("form-houses");
const latitude = document.getElementById("latitude");
const longitude = document.getElementById("longitude");
const inputAddress = document.getElementById("address");
const listAddress = document.getElementById("list-address");
const containerListAddress = document.getElementById("container-list-address");

// address.addEventListener("keyup", () => {
//     const addressValue = address.value;
//     const newAddress = addressValue.replaceAll(" ", '%');
//     console.log(newAddress)
// })

let searchAddress;

inputAddress.addEventListener("keyup", () => {
    const addressValue = address.value;
    if (!addressValue) {
        while (listAddress.firstChild) {
            listAddress.removeChild(listAddress.firstChild);
        }
        return;
    }
    clearTimeout(searchAddress);
    searchAddress = setTimeout(() => {
        axios
            .get(
                `https://api.tomtom.com/search/2/search/${addressValue}.json?limit=5&countrySet=IT%2FITA&extendedPostalCodesFor=Addr&view=Unified&key=soH7vSRFYTpCT37GOm8wEimPoDyc3GMe`
            )
            .then((res) => {
                while (listAddress.firstChild) {
                    listAddress.removeChild(listAddress.firstChild);
                }
                res.data.results.forEach((result, i) => {
                    containerListAddress.classList.remove("d-none");
                    const lists = document.createElement("li");
                    lists.classList.add("list-group-item");
                    lists.innerHTML = result.address.freeformAddress;
                    listAddress.append(lists);
                });
            })
            .catch((e) => {
                console.error(e);
            });
    }, 300);
});

form.addEventListener("submit", () => {
    event.preventDefault();
    const addressValue = address.value;
    // const newAddress = addressValue.replaceAll(" ", '%');
    const config = { headers: { accept: "*/*" } };
    axios
        .get(
            `https://api.tomtom.com/search/2/geocode/${addressValue}.json?storeResult=false&lat=37.337&lon=-121.89&view=Unified&key=soH7vSRFYTpCT37GOm8wEimPoDyc3GMe`,
            config
        )
        .then((res) => {
            latitude.value = res.data.results[0].position.lat;
            longitude.value = res.data.results[0].position.lon;
            form.submit();
        })
        .catch((e) => {
            console.error(e);
            form.submit();
        });
});
