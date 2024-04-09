document.addEventListener('DOMContentLoaded', function() {
    let selectCategory = document.getElementById('category');
    let formCategory = document.getElementById('form-category');

    if (selectCategory) {
        selectCategory.addEventListener('change', function() {
            formCategory.submit();    
        });
    }
});