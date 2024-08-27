//image eventListener
document.getElementById('image').addEventListener('change', function(event) {
    const imagePreview = document.getElementById('imageDisplay');
    const file = event.target.files[0];

    if (file) {
        const reader = new FileReader();

        reader.onload = function(e) {
            imagePreview.src = e.target.result;
            imagePreview.style.display = 'block';

            console.log("CHANGED IMAGE");
        };

        reader.readAsDataURL(file);
    }
});
