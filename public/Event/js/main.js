(function ($) {
    "use strict";
    
    // Dropdown on mouse hover
    $(document).ready(function () {
        function toggleNavbarMethod() {
            if ($(window).width() > 992) {
                $('.navbar .dropdown').on('mouseover', function () {
                    $('.dropdown-toggle', this).trigger('click');
                }).on('mouseout', function () {
                    $('.dropdown-toggle', this).trigger('click').blur();
                });
            } else {
                $('.navbar .dropdown').off('mouseover').off('mouseout');
            }
        }
        toggleNavbarMethod();
        $(window).resize(toggleNavbarMethod);
    });
    
    
    // Back to top button
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $('.back-to-top').fadeIn('slow');
        } else {
            $('.back-to-top').fadeOut('slow');
        }
    });
    $('.back-to-top').click(function () {
        $('html, body').animate({scrollTop: 0}, 1500, 'easeInOutExpo');
        return false;
    });


    // Date and time picker
    $('.date').datetimepicker({
        format: 'L'
    });
    $('.time').datetimepicker({
        format: 'LT'
    });


    // Testimonials carousel
    $(".testimonial-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 1500,
        margin: 30,
        dots: true,
        loop: true,
        center: true,
        responsive: {
            0:{
                items:1
            },
            576:{
                items:1
            },
            768:{
                items:2
            },
            992:{
                items:3
            }
        }
    });
    
})(jQuery);
// Récupérer la référence de la fenêtre modale et du bouton pour ouvrir la fenêtre modale
var modal = document.getElementById("myModal");
var btn = document.getElementById("openModalBtn");

// Récupérer la référence de l'élément de fermeture de la fenêtre modale
var span = document.getElementsByClassName("close")[0];

// Quand l'utilisateur clique sur le bouton, ouvrir la fenêtre modale
btn.onclick = function() {
  modal.style.display = "block";
}

// Quand l'utilisateur clique sur l'élément de fermeture, fermer la fenêtre modale
span.onclick = function() {
  modal.style.display = "none";
}

// Quand l'utilisateur clique en dehors de la fenêtre modale, fermer la fenêtre modale
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
