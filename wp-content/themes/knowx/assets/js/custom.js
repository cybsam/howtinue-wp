/**
 * File custom.js.
 *
 * Theme Custom enhancements for a better user experience.
 *
 */
// the semi-colon before the function invocation is a safety
// net against concatenated scripts and/or other plugins
// that are not closed properly.
;
(function($, window, document, undefined) {
    'use strict';

    var KNOWX = window.KNOWX || {};


    // Site Loader
    KNOWX.siteLoader = function() {
        $('.site-loader').addClass('loaded');
    };

    // Header Class
    KNOWX.headerClass = function() {
        var $document = $(document),
            $elementHeader = $('body.sticky-header, .sticky-header .site-header-wrapper'),
            className = 'has-sticky-header';

        $document.scroll(function() {
            $elementHeader.toggleClass(className, $document.scrollTop() >= 1);
        });
    };

    // Header Scroll
    KNOWX.headerScroll = function() {
        var header_height = $('.site-header-wrapper').height();

        if ($('body.sticky-header').hasClass('has-sticky-header')) {
            $('.site').css("paddingTop", header_height + 10 + "px");
        } else {
            $('.site').css("paddingTop", 0 + "px");
        }
    };

    // Header Search
    KNOWX.headerSearch = function() {

        $('.search-icon').on('click', function(e) {
            e.preventDefault();
            $('.site-header .top-menu-search-container').toggle();
        });

        $(document).mouseup(function(e) {
            var container = $(".top-menu-search-container");
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                container.fadeOut();
            }
        });

        $("#primary-menu a, .cart a.menu-icons-wrapper, .bp-icon-wrap, a.user-link, .site-sub-header a, .site-wrapper a").focusin(function() {
            $('.site-header .top-menu-search-container').hide();
        });

    };

    // Desktop Menu Toggle
    KNOWX.desktopMenuToggle = function() {
        if ($(window).width() > 768) {
            $('.primary-menu-container #primary-menu').superfish({
                delay: 600,
                animation: {
                    opacity: 'show'
                },
                animationOut: {
                    opacity: 'hide'
                },
                speed: 'fast',
                speedOut: 'fast',
                cssArrows: false,
                disableHI: false,
            });
        }

        $(".knowx-mobile-menu").focusout(function() {
            $('.mobile-menu-heading .close-menu').focusin();
        });

    };

    // Mobile Menu Toggle
    KNOWX.mobileNav = function() {
        var widget = $('.menu-toggle'),

            body = $('body');
        widget.on('click', function(e) {
            e.preventDefault();
            if (isOpened()) {
                closeWidget();
            } else {
                setTimeout(function() {
                    openWidget();
                }, 10);
            }
        });

        widget.on('click', function(e) {
            e.preventDefault();
            if (isOpened()) {
                closeWidget();

            } else {
                setTimeout(function() {
                    openWidget();
                }, 10);
            }
        });

        body.on("click touchstart", ".mobile-menu-close", function() {
            if (isOpened()) {
                closeWidget();
            }
        });

        body.on("click", ".menu-close", function(e) {
            e.preventDefault();
            if (isOpened()) {
                closeWidget();
            }
        });

        $(document).keyup(function(e) {
            if (e.keyCode === 27 && isOpened())
                closeWidget();
        });

        var closeWidget = function() {
            $('body').removeClass('mobile-menu-opened');
            $(widget).removeClass('menu-toggle-open');
        };

        var openWidget = function() {
            $('body').addClass('mobile-menu-opened');
            $(widget).addClass('menu-toggle-open');
        };

        var isOpened = function() {
            return $('body').hasClass('mobile-menu-opened');
        };

    };

    // Blog Layout
    KNOWX.blogLayout = function() {

        $('.grid-layout').isotope({
            // options
            itemSelector: '.entry-layout',
            layoutMode: 'fitRows'
        });

        $('.masonry-layout').isotope({
            itemSelector: '.entry-layout',
            percentPosition: true,
            masonry: {
                // use outer width of grid-sizer for columnWidth
                columnWidth: '.grid-sizer',
            }
        })
    };

    // fitVids
    KNOWX.fitVids = function() {

        var doFitVids = function() {
            setTimeout(function() {
                $('iframe[src*="youtube"], iframe[src*="vimeo"]').parent().fitVids();
            }, 300);
        };
        doFitVids();
        $(document).ajaxComplete(doFitVids);

        var doFitVidsOnLazyLoad = function(event, data) {
            if (typeof data !== 'undefined' && typeof data.element !== 'undefined') {
                //load iframe in correct dimension
                if (data.element.getAttribute('data-lazy-type') == 'iframe') {
                    doFitVids();
                }
            }
        };
        $(document).on('knowx_lazy_load', doFitVidsOnLazyLoad);

    };

    // stickySidebar
    KNOWX.stickySidebar = function() {

        var headerHeight = $('.site-header-wrapper').height();
        if ($('body').hasClass('sticky-header')) {
            var headerHeightExt = headerHeight + 54;
        } else {
            var headerHeightExt = headerHeight;
        }
        $('.sticky-sidebar-enable .sticky-sidebar').stick_in_parent({
            offset_top: headerHeightExt,
            recalc_every: 1,
        });

    };

    // tableDataAtt
    KNOWX.tableDataAtt = function() {

        if ($('table').length) {
            var $th = $("thead th");
            $('tbody tr td').attr('data-attr', function() {
                return $th.eq($(this).index()).text();
            });
        }
    };

    $(document).ready(function() {

        KNOWX.headerClass();
        KNOWX.headerSearch();
        KNOWX.desktopMenuToggle();
        KNOWX.mobileNav();
        KNOWX.fitVids();
        KNOWX.tableDataAtt();

    });

    $(window).resize(function() {
        // do stuff
        KNOWX.headerClass();
    });

    $(window).scroll(function() {
        // do stuff
        KNOWX.headerScroll();
    });

    $(window).load(function() {
        KNOWX.headerClass();
        KNOWX.siteLoader();
        KNOWX.stickySidebar();
        KNOWX.blogLayout();
    });

})(jQuery, window, document);