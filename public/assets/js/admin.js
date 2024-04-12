document.addEventListener('DOMContentLoaded', function() {
    let formAdmin = document.getElementById('form-admin');
    let classes = ["packages", "category", "users"];

    if (formAdmin) {
        formAdmin.addEventListener('change', function(event) {
            event.preventDefault();

            let allCheckboxes = formAdmin.querySelectorAll('input[type=checkbox]');
            
            allCheckboxes.forEach(checkbox => {
                let classElement = '.container-' + checkbox.value;
                let titleElement = '.title-' + checkbox.value;
                let container = document.querySelector(classElement);
                let title = document.querySelector(titleElement);

                if (checkbox.checked) {
                    container.style.display = 'flex';
                    title.style.display = 'block';
                } else {
                    container.style.display = 'none';
                    title.style.display = 'none';
                }
            });
        });
    }
});