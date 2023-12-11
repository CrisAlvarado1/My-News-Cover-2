document.addEventListener('DOMContentLoaded', function () {
    // Agrega un evento de cambio a todos los checkboxes
    document.querySelectorAll('input[type="checkbox"]').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            // Envía automáticamente el formulario cuando cambia un checkbox
            document.getElementById('filterForm').submit();
        });
    });
});