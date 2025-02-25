import './bootstrap'
import SimpleBar from 'simplebar'
import '/resources/js/coreui/config.js'
import '/resources/js/coreui/color-modes.js'
// import '/resources/js/coreui/main.js'

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.tom-select').forEach(function (select) {
        new TomSelect(select, {
            plugins: ['clear_button'],
            persist: false,
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            }
        });
    });
});
