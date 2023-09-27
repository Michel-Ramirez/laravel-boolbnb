
const address = document.getElementById('address');
const form = document.getElementById('form-houses');
const latitude = document.getElementById('latitude');
const longitude = document.getElementById('longitude');

// address.addEventListener("keyup", () => {
//     const addressValue = address.value;
//     const newAddress = addressValue.replaceAll(" ", '%');
//     console.log(newAddress)
// })

form.addEventListener("submit", () => {
    event.preventDefault();
    const addressValue = address.value;
    // const newAddress = addressValue.replaceAll(" ", '%');
    const config={headers:{accept:"*/*"}};
    axios.get(`https://api.tomtom.com/search/2/geocode/${addressValue}.json?storeResult=false&lat=37.337&lon=-121.89&view=Unified&key=soH7vSRFYTpCT37GOm8wEimPoDyc3GMe`, config)
    .then(res => {
        latitude.value = res.data.results[0].position.lat;
        longitude.value = res.data.results[0].position.lon;
        form.submit();
    }).catch((e)=> {
        console.error(e);
        form.submit();
    })
})




