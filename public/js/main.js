(function ($) {
    "use strict";

    // Global Premium Spinner Control
    var spinner = function () {
        var $fill = $('#spinner-progress-fill');
        
        // Growth simulation (Initial 35% is already set in CSS for instant feel)
        setTimeout(() => $fill.css('width', '65%'), 500);
        setTimeout(() => $fill.css('width', '85%'), 1200);

        // Wait for full page load
        $(window).on('load', function() {
            // Complete progress
            $fill.css('width', '100%');

            setTimeout(function () {
                if ($('#spinner').length > 0) {
                    $('#spinner').addClass('hide');
                    
                    setTimeout(function() {
                        $('#spinner').removeClass('show');
                        $('body').addClass('page-ready');
                    }, 850); 
                }
            }, 2000); 
        });
    };
    spinner();


    // Initiate the wowjs
    new WOW().init();



    // Dropdown behavior: Handled by Bootstrap 5 natively (Click to open)
    // Custom hover logic removed as per user request.


    // Back to top button
    $('.back-to-top').hide(); // Explicit double-check on load
    $(window).scroll(function () {
        if ($(this).scrollTop() > 400) {
            $('.back-to-top').fadeIn('slow');
        } else {
            $('.back-to-top').fadeOut('slow');
        }
    });
    $('.back-to-top').click(function () {
        $('html, body').animate({scrollTop: 0}, 600, 'swing');
        return false;
    });


    // Facts counter
    $('[data-toggle="counter-up"]').counterUp({
        delay: 10,
        time: 2000
    });


    // Modal Video
    $(document).ready(function () {
        var $videoSrc;
        $('.btn-play').click(function () {
            $videoSrc = $(this).data("src");
        });
        console.log($videoSrc);

        $('#videoModal').on('shown.bs.modal', function (e) {
            $("#video").attr('src', $videoSrc + "?autoplay=1&amp;modestbranding=1&amp;showinfo=0");
        })

        $('#videoModal').on('hide.bs.modal', function (e) {
            $("#video").attr('src', $videoSrc);
        })
    });


    // Testimonials carousel (only if plugin is loaded)
    if (typeof $.fn.owlCarousel === 'function') {
        $(".testimonial-carousel").owlCarousel({
            autoplay: true,
            smartSpeed: 1000,
            margin: 25,
            dots: false,
            loop: true,
            nav : true,
            navText : [
                '<i class="bi bi-arrow-left"></i>',
                '<i class="bi bi-arrow-right"></i>'
            ],
            responsive: {
                0:{
                    items:1
                },
                768:{
                    items:2
                }
            }
        });
    }

    if (typeof $.fn.datetimepicker === 'function') {
        $('#date1').datetimepicker({
            format: 'YYYY-MM-DD'
        });
        $('#date2').datetimepicker({
            format: 'YYYY-MM-DD'
        });
    }
    // ════════════════════════════════════════════════
    // AUTOMATED TABLE RESPONSIVENESS (DATA-LABELS)
    // ════════════════════════════════════════════════
    $(document).ready(function() {
        $('table').each(function() {
            var $table = $(this);
            var $headers = $table.find('thead th');
            
            if ($headers.length > 0) {
                $table.find('tbody tr').each(function() {
                    $(this).children('td, th').each(function(index) {
                        // 1. Set dynamic labels for mobile cards
                        var label = $headers.eq(index).text().trim();
                        if (label && label !== '#' && label !== 'Action' && label !== 'Actions') {
                            $(this).attr('data-label', label);
                        }

                        // 2. UNIVERSAL FIX: Wrap naked text nodes in <span> for Safari Flexbox stability
                        // This ensures that text content isn't discarded when using display:flex on the cell
                        $(this).contents().filter(function() {
                            return this.nodeType === 3 && $.trim(this.nodeValue).length > 0;
                        }).wrap('<span></span>');
                    });
                });
            }
        });
    });

    // ════════════════════════════════════════════════
    // MOBILE SIDEBAR TOGGLE SYSTEM (v2)
    // ════════════════════════════════════════════════
    $(document).ready(function() {
        if ($('.admin-sidebar-col').length > 0) {
            
            // 1. Setup Elements
            // Elements are now rendered via blade directly
            // No need to inject them.

            // UI is hardcoded in Blade.


            // Use event delegation for reliability on mobile
            $(document).on('click', '.mobile-toggle-btn', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const $icon = $(this).find('i');
                const isCurrentlyActive = $('.admin-sidebar-col').hasClass('active');
                
                if (!isCurrentlyActive) {
                    $('.admin-sidebar-col').addClass('active');
                    $('.sidebar-overlay').addClass('active');
                    $icon.removeClass('fa-bars').addClass('fa-times');
                    $('body').css('overflow', 'hidden'); // Prevent background scroll
                } else {
                    $('.admin-sidebar-col').removeClass('active');
                    $('.sidebar-overlay').removeClass('active');
                    $icon.removeClass('fa-times').addClass('fa-bars');
                    $('body').css('overflow', '');
                }
            });

            $(document).on('click', '.sidebar-overlay', function() {
                $('.admin-sidebar-col').removeClass('active');
                $('.sidebar-overlay').removeClass('active');
                $('.mobile-toggle-btn i').removeClass('fa-times').addClass('fa-bars');
                $('body').css('overflow', '');
            });

            // Close on nav link click (mobile) - Exclude dropdown toggles
            $(document).on('click', '.admin-sidebar-col a:not(.dropdown-toggle)', function() {
                if ($(window).width() <= 768) {
                    $('.admin-sidebar-col').removeClass('active');
                    $('.sidebar-overlay').removeClass('active');
                    $('.mobile-toggle-btn i').removeClass('fa-times').addClass('fa-bars');
                    $('body').css('overflow', '');
                }
            });

            // Prevent scroll on sidebar if it's too long
            $('#adminSidebar').on('touchmove', function(e) {
                e.stopPropagation();
            });
        }
    });

})(jQuery);



