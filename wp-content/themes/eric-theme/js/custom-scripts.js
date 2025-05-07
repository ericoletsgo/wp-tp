(function($) {
    $(document).ready(function() {
        $('body').on('click', '#page-nav a, .next-section-button', function(e) {
            e.preventDefault();
            const targetId = $(this).is('a') ? $(this).attr('href') : $(this).data('target');
            const targetElement = $(targetId);

            if (targetElement.length) {
                $('html, body').animate({
                    scrollTop: targetElement.offset().top
                }, 800);
            }
        });

        const sections = $('.full-page-section');
        const navLinks = $('#page-nav ul li a');
        const scrollOffset = $(window).height() * 0.4;

        $(window).on('scroll', function() {
            const currentScroll = $(this).scrollTop();

            sections.each(function() {
                const sectionTop = $(this).offset().top - scrollOffset;
                const sectionBottom = sectionTop + $(this).outerHeight();
                const sectionId = $(this).attr('id');

                if (currentScroll >= sectionTop && currentScroll < sectionBottom) {
                    navLinks.removeClass('active-section');
                    $('#page-nav a[href="#' + sectionId + '"]').addClass('active-section');
                }
            });

            if (currentScroll < $(sections[0]).offset().top - scrollOffset + 100) {
                navLinks.removeClass('active-section');
                $('#page-nav a[href="#home-section"]').addClass('active-section');
            }

            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 50 && $('#contact-section').is(':visible')) {
                navLinks.removeClass('active-section');
                $('#page-nav a[href="#contact-section"]').addClass('active-section');
            }
        });

        if ($('.project-carousel').length) {
            const projectSwiper = new Swiper('.project-carousel', {
                slidesPerView: 1,
                spaceBetween: 30,
                loop: true,
                centeredSlides: true,
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    640: {
                        slidesPerView: 1,
                        spaceBetween: 20
                    },
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 30
                    },
                    1024: {
                        slidesPerView: 3,
                        spaceBetween: 40
                    }
                }
            });
        }
    });
})(jQuery);
