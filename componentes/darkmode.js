$(document).ready(function() {
    console.log('Dark mode script loaded');
    // Check and apply saved theme or detect system preference
    if (localStorage.getItem('theme')) {
        if (localStorage.getItem('theme') === 'dark') {
            $('body').addClass('dark-mode');
            $('#darkModeSwitch').prop('checked', true);
        } else {
            $('body').addClass('light-mode');
            $('#darkModeSwitch').prop('checked', false);
        }
    } else {
        // Detect system preference
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            $('body').addClass('dark-mode');
            $('#darkModeSwitch').prop('checked', true);
        } else {
            $('body').addClass('light-mode');
            $('#darkModeSwitch').prop('checked', false);
        }
    }

    // Toggle dark mode on switch change
    $('#darkModeSwitch').change(function() {
        if ($(this).is(':checked')) {
            $('body').removeClass('light-mode').addClass('dark-mode');
            localStorage.setItem('theme', 'dark');
        } else {
            $('body').removeClass('dark-mode').addClass('light-mode');
            localStorage.setItem('theme', 'light');
        }
    });
});
