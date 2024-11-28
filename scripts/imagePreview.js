function handleImagePreview(inputElement, previewImageId) {
    const file = inputElement.files[0];
    if (file && file.type.startsWith("image/")) {
        const previewImage = document.getElementById(previewImageId);
        previewImage.src = URL.createObjectURL(file);
    }
}