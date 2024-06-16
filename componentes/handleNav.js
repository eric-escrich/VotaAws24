$(document).ready(function() {
    // Función para renderizar los elementos del menú
    function renderMenuItems() {
        let itemsHTML = '';
        if (menuItems.dashboard) itemsHTML += '<li class="navLink"><a href="dashboard.php">Dashboard</a></li>';
        if (menuItems.createPoll) itemsHTML += '<li class="navLink"><a href="create_poll.php">Crear Encuesta</a></li>';
        if (menuItems.listPolls) itemsHTML += '<li class="navLink"><a href="list_polls.php">Mis encuestas</a></li>';
        if (menuItems.myVotes) itemsHTML += '<li class="navLink"><a href="myVotes.php">Mis votos</a></li>';
        if (menuItems.logout && !menuItems.validAcount) itemsHTML += '<li class="navLink"><a href="mail_verification.php">Acaba de registrarte</a></li>';
        if (menuItems.logout) itemsHTML += '<li class="navLink"><a href="logout.php">Cerrar Sesión</a></li>';
        if (menuItems.login) itemsHTML += '<li class="navLink"><a href="login.php">Iniciar Sesión</a></li>';
        if (menuItems.register) itemsHTML += '<li class="navLink"><a href="register.php">Registrarse</a></li>';
        return itemsHTML;
    }

    // Función para ajustar la barra de navegación según el tamaño de la pantalla
    function adjustNavbar() {
        if ($(window).width() > 1125) {
            sidebarVisible = false;
            $('.hamburger-menu').remove();
            $('.sidebar').remove();

            if (!$('nav.navbar li.navLink').length) {
                let spacer = $('nav.navbar ul li.spacer');
                spacer.before(renderMenuItems());
            }

        } else {
            console.log('menor a 1000');
            sidebarVisible = false;
            $('nav.navbar li.navLink').remove();

            if (!$('.hamburger-menu').length) {
                const hamburgerMenu = $('<li>', { class: 'hamburger-menu' });
                const svgElement = $(`
                    <svg class="icono" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M4 7h16" />
                        <path d="M4 12h16" />
                        <path d="M4 17h16" />
                    </svg>
                `);
                hamburgerMenu.append(svgElement);
                $('nav.navbar ul').prepend(hamburgerMenu);
            }
        }
    }

    // Ajusta la barra de navegación cuando se redimensiona la ventana
    $(window).resize(adjustNavbar);

    let sidebarVisible = false;
    // Evento de clic para mostrar/ocultar la barra lateral
    $('nav').on('click', '.hamburger-menu',handleSidebar);
    $(document).on('click', '.close-icon', handleSidebar);
    $(document).on('click', '.overlay', handleSidebar);
    
    function handleSidebar() {
        console.log('click');
        if (!sidebarVisible) {
            const overlay = $('<div>', { class: 'overlay' });
            $('body').append(overlay);
            const sidebar = $('<div>', { class: 'sidebar' });
            const closeIcon = $('\
                <svg class="icono" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">\
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>\
                    <path d="M18 6L6 18M6 6l12 12" />\
                </svg>\
            ');
            const closeIconContainer = $('<div>', { class: 'close-icon' });
            closeIconContainer.append(closeIcon);
            sidebar.append(closeIconContainer);
            const sidebarList = $('<ul>');
            sidebarList.append(renderMenuItems());
            sidebar.append(sidebarList);

            
            $('nav.navbar').after(sidebar);
            sidebarVisible = true;
        } else {
            $('.sidebar').remove();
            $('.overlay').remove();
            sidebarVisible = false;
        }
    }
});
