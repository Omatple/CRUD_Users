function handlerImagePreview(input, idPreviewImage) {
    let file = input.files[0];
    if (file && file.type.startsWith("image/")) {
        previewImg = document.getElementById(idPreviewImage);
        previewImg.src = URL.createObjectURL(file);
    }
}