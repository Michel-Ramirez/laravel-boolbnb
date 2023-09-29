const inputPhoto = document.getElementById("photo");
const imagePreview = document.getElementById("preview-img");
const placeholder =
    "https://saterdesign.com/cdn/shop/products/property-placeholder_a9ec7710-1f1e-4654-9893-28c34e3b6399_600x.jpg?v=1500393334";

let url = null;

inputPhoto.addEventListener("input", () => {
    if (inputPhoto.files[0]) {
        const file = inputPhoto.files[0];
        url = URL.createObjectURL(file);
        imagePreview.src = url;
    } else {
        imagePreview.src = placeholder;
    }
});
