import './bootstrap'
import SimpleBar from 'simplebar'
import '/resources/js/coreui/config.js'
import '/resources/js/coreui/coreui.bundle.js'
import '/resources/js/coreui/color-modes.js'
// import '/resources/js/coreui/main.js'

Array.prototype.forEach.call(
    document.querySelectorAll('[data-simplebar]'),
    (el) => new SimpleBar(el)
);
