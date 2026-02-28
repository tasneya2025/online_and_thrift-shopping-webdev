const uploadBtn = document.getElementById('uploadBtn');
const fileInput = document.getElementById('file-input');
const fileText = document.getElementById('file-text');

if (uploadBtn && fileInput) {
    uploadBtn.addEventListener('click', () => {
        fileInput.click();
    });
}

if (fileInput) {
    fileInput.addEventListener('change', function() {
        if (this.files && this.files.length > 0) {

            fileText.textContent = "Selected: " + this.files[0].name;
            fileText.style.color = "#3b71fe"; 
            fileText.style.fontWeight = "bold";
        } else {
            fileText.textContent = "Click to upload image";
        }
    });
}