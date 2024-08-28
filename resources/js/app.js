import './bootstrap';
import './custom';
import $ from 'jquery'; // Import jQuery

window.$ = window.jQuery = $; // Make jQuery available globally

// Example usage
$(document).ready(function() {
    console.log('jQuery is successfully loaded with Vite!!');
});