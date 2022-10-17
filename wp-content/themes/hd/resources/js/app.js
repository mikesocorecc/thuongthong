/**jshint esversion: 6 */
import './_foundation';
import {nanoid} from 'nanoid';
import random from "lodash/random";
import isEmpty from "lodash/isEmpty";
import toString from "lodash/toString";

/** current-device*/
import device from "current-device";
const is_mobile = () => device.mobile();

/** Fancybox*/
import { Fancybox } from "@fancyapps/ui";
Fancybox.bind(".wp-block-gallery .blocks-gallery-item a, .fcy-gallery a, [id|=\"gallery\"] a", {
    groupAll: true, // Group all items
});

Fancybox.bind(".fcy-video, .fcy-popup", {});

/** Create deferred YT object */
// const YTdeferred = $.Deferred();
// window.onYouTubeIframeAPIReady = function () {
//     YTdeferred.resolve(window.YT);
// };

//require("jquery.marquee");

/* oas*/
import AOS from 'aos';
AOS.init();

/** jquery load */
$(() => {

    /** */
    let woo_image = $(".woocommerce-product-gallery__image");
    woo_image.each(function () {
        $(this).on('click', function (e) {
            e.preventDefault();
            $(".woocommerce-product-gallery__trigger").trigger('click');
        });
    });

    /** */
    $('.variations_form').each(function () {

        // when variation is found, do something
        $(this).on('found_variation', function (event, variation) {
            if (variation.price_html != '') {
                $(".single-price").html(variation.price_html);
            }
        });
    });

    /** */
    let _desc_inner = $('.product-cat-desc-inner');
    if (_desc_inner.length > 0) {
        let _height = _desc_inner.outerHeight(false);
        if (_height > 500) {
            _desc_inner.css({'max-height': '500px'});
            let _viewmore_html = '<div class="viewmore-wrapper"><a class="btn-viewmore" title="Xem thêm" data-src="#desc-inner" data-modal="true" href="javascript:;" data-glyph-after="">Xem thêm</a></div>';
            _desc_inner.append(_viewmore_html);

            //...
            Fancybox.bind("#desc-inner .btn-viewmore", {});
        }
    }

    /** Remove empty P tags created by WP inside of Accordion and Orbit */
    $('.accordion p:empty, .orbit p:empty').remove();

    /** Adds Flex Video to YouTube and Vimeo Embeds */
    $('iframe[src*="youtube.com"], iframe[src*="vimeo.com"]').each(() => {
        if ($(this).innerWidth() / $(this).innerHeight() > 1.5) {
            $(this).wrap("<div class='widescreen responsive-embed'/>");
        } else {
            $(this).wrap("<div class='responsive-embed'/>");
        }
    });

    /** tabs + cookie*/
    const _tabs_wrapper = $(".w-filter-tabs");
    _tabs_wrapper.each((index, el) => {
        const _rand = nanoid(6);
        $(el).addClass(_rand);

        //...
        const _tabs = $(el).find(".filter-tabs");
        const _tabs_content = $(el).find(".filter-tabs-content");
        _tabs_content.find('.tabs-panel').hide();
        let _hash = _tabs.find('a:first').attr("href");
        if (getCookie('lastTab_' + index) === '' || getCookie('lastTab_' + index) === 'undefined') {
            setCookie('lastTab_' + index, _hash, 100);
        }
        _tabs.find('a[href="' + getCookie('lastTab_' + index) + '"]').addClass("current");
        _tabs.find('a').on("click", function (e) {
            e.preventDefault();
            let _hash = $(this).attr("href");
            setCookie('lastTab_' + index, _hash, 100);
            _tabs.find('a.current').removeClass("current");
            _tabs_content.find('.tabs-panel:visible').hide();
            $(this.hash).fadeIn();
            $(this).addClass("current");
        }).filter(".current").trigger('click');

        //...
        let check_current = _tabs.find('a.current');
        if (check_current.length > 0) {}
        else {
            _tabs.find('a:first').addClass("current").trigger('click');
        }
    });

    /** toggle menu */
    const _toggle_menu = $(".toggle_menu");
    _toggle_menu.find("li.is-active.has-submenu-toggle").find(".submenu-toggle").trigger('click');

    /** qty */
    const _qty_controls = () => {

        /**qty*/
        $('.input-number-increment').off('click').on('click', function (e) {
            e.preventDefault();
            let $input = $(this).parents('.input-number-group').find('.qty');
            let val = parseInt($input.val(), 10);
            $input.val(val + 1);

            let update_cart = $('button[name="update_cart"]');
            if (update_cart.length > 0) {
                update_cart.prop('disabled', false)
            }
        });
        $('.input-number-decrement').off('click').on('click', function (e) {
            e.preventDefault();
            let $input = $(this).parents('.input-number-group').find('.qty');
            let val = parseInt($input.val(), 10);
            if (val > 1) {
                $input.val(val - 1);

                let update_cart = $('button[name="update_cart"]');
                if (update_cart.length > 0) {
                    update_cart.prop('disabled', false)
                }
            }
        });
    }

    // run
    _qty_controls();

    /** ajaxComplete */
    $( document ).ajaxComplete(function( event, xhr, settings ) {
        _qty_controls();
    });

    /** */
    const onload_events = () => {

        /** */
        const videos_gallery = $('section.videos-gallery');
        let gal_target_height = videos_gallery.find('.video-wrapper').find('.item-first').outerHeight(false);

        let _port_size = getViewportSize(window);
        if (device.desktop() || _port_size.w >= 782) {
            videos_gallery.find('.video-wrapper').find('.item-list>ul').css({
                'max-height': gal_target_height + 'px',
                'height': 'auto',
            });
        }

        /** */
        const woocommerce_product_gallery = $('.woocommerce-product-gallery');
        let woo_target_height = woocommerce_product_gallery.find('.flex-viewport').outerHeight(false);
        woocommerce_product_gallery.find('.flex-control-thumbs').css({
            'max-height': woo_target_height + 'px',
            'height': 'auto',
        });
    }

    $(window).on('load', () => { onload_events(); });

    /** Orientation JavaScript Callback*/
    device.onChangeOrientation(() => { onload_events(); });
});

/**
 * DOMContentLoaded
 */
document.addEventListener( 'DOMContentLoaded', () => {

    /**attribute target="_blank" is not W3C compliant*/
    const _blanks = [...document.querySelectorAll('a._blank, a.blank, a[target="_blank"]')];
    Array.prototype.forEach.call(_blanks, (el) => {
        el.removeAttribute('target');
        el.setAttribute('target', '_blank');
        if (!1 === el.hasAttribute('rel')) {
            el.setAttribute('rel', 'noopener noreferrer nofollow');
        }
    });

    /**remove style img tag*/
    const _img = [...document.querySelectorAll('img')];
    Array.prototype.forEach.call(_img, (el) => {
        el.removeAttribute('style');
    });
});

/** vars */
const getParameters = (URL) => JSON.parse('{"' + decodeURI(URL.split("?")[1]).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g, '":"') + '"}');
const touchSupported = () => { ('ontouchstart' in window || window.DocumentTouch && document instanceof window.DocumentTouch); };

/**
 * https://stackoverflow.com/questions/1248081/how-to-get-the-browser-viewport-dimensions
 *
 * @param w
 * @returns {{w: *, h: *}}
 */
function getViewportSize(w) {
    /* Use the specified window or the current window if no argument*/
    w = w || window;
    /* This works for all browsers except IE8 and before*/
    if (w.innerWidth != null) return {w: w.innerWidth, h: w.innerHeight};
    /* For IE (or any browser) in Standards mode*/
    var d = w.document;
    if ("CSS1Compat" === document.compatMode)
        return {
            w: d.documentElement.clientWidth,
            h: d.documentElement.clientHeight
        };
    /* For browsers in Quirks mode*/
    return {w: d.body.clientWidth, h: d.body.clientHeight};
}

/**
 * @param cname
 * @returns {unknown}
 */
const getCookie = (cname) => (
    document.cookie.match('(^|;)\\s*' + cname + '\\s*=\\s*([^;]+)')?.pop() || ''
)

/**
 * @param cname
 * @param cvalue
 * @param exdays
 */
function setCookie(cname, cvalue, exdays) {
    let d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    let expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

/**
 * @param url
 * @param $delay
 */
function redirect(url = null, $delay = 10) {
    setTimeout(function () {
        if (url === null || url === '' || typeof url === "undefined") {
            document.location.assign(window.location.href);
        } else {
            url = url.replace(/\s+/g, '');
            document.location.assign(url);
        }
    }, $delay);
}

/**
 * @param name
 * @returns {string}
 */
function query_string(name) {
    let _name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    let regex = new RegExp("[\\?&]" + _name + "=([^&#]*)"),
        results = regex.exec(location.search);

    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

/**
 * @param page
 * @param title
 * @param url
 */
function pushState(page, title, url) {
    if ("undefined" !== typeof history.pushState) {
        history.pushState({page: page}, title, url);
    } else {
        window.location.assign(url);
    }
}

/** */
/** import Swiper bundle with all modules installed */
import { Swiper } from 'swiper/bundle';

/** swiper container */
const _swiper_container = [...document.querySelectorAll('.w-swiper')];
_swiper_container.forEach((el, index) => {
    const _rand = nanoid(12),
        _class = 'swiper-' + _rand,
        _next_class = 'next-' + _rand,
        _prev_class = 'prev-' + _rand,
        _pagination_class = 'pagination-' + _rand,
        _scrollbar_class = 'scrollbar-' + _rand;

    el.classList.add(_class);

    /** swiper controls */
    let _controls = el.closest('.swiper-section').querySelector('.swiper-controls');
    if (_controls == null) {
        _controls = document.createElement("div");
        _controls.classList.add('swiper-controls');
        el.after(_controls);
    }

    /** swiper options */
    const el_swiper_wrapper = el.querySelector('.swiper-wrapper');
    let _obj_options = JSON.parse(el_swiper_wrapper.dataset.options);

    if (isEmpty(_obj_options)) {
        _obj_options = {
            "autoview": "true",
            "loop": "true",
            "autoplay": "true",
            "navigation": "true"
        };
    }

    /** init options*/
    let _result_options = {};

    _result_options.grabCursor = !0;
    _result_options.allowTouchMove = !0;
    _result_options.threshold = 0.5;
    _result_options.hashNavigation = !1;

    /** responsive view*/
    let _desktop_data = '1',
        _tablet_data = '1',
        _mobile_data = '1';

    if ("desktop" in _obj_options) {
        _desktop_data = _obj_options.desktop;
    }

    if ("tablet" in _obj_options) {
        _tablet_data = _obj_options.tablet;
    }

    if ("mobile" in _obj_options) {
        _mobile_data = _obj_options.mobile;
    }

    /** gap*/
    if ("gap" in _obj_options) {
        _result_options.spaceBetween = 20;
    } else if ("smallgap" in _obj_options) {
        _result_options.spaceBetween = parseInt(_obj_options.smallgap);
    }

    /** autoview */
    if ("autoview" in _obj_options) {
        _result_options.slidesPerView = 'auto';
        _result_options.loopedSlides = 12;
        if ("gap" in _obj_options) {
            _result_options.breakpoints = {
                640: { spaceBetween: 30 }
            };
        } else if ("smallgap" in _obj_options) {
            _result_options.breakpoints = {
                640: { spaceBetween: parseInt(_obj_options.smallgap) }
            };
        }

    } else {
        _result_options.slidesPerView = parseInt(_mobile_data);
        if ("gap" in _obj_options) {
            _result_options.breakpoints = {
                640: {
                    spaceBetween: 30,
                    slidesPerView: parseInt(_tablet_data)
                },
                1024: {
                    spaceBetween: 30,
                    slidesPerView: parseInt(_desktop_data)
                },
            };
        } else if ("smallgap" in _obj_options) {
            _result_options.breakpoints = {
                640: {
                    spaceBetween: parseInt(_obj_options.smallgap),
                    slidesPerView: parseInt(_tablet_data)
                },
                1024: {
                    spaceBetween: parseInt(_obj_options.smallgap),
                    slidesPerView: parseInt(_desktop_data)
                },
            };
        } else {
            _result_options.breakpoints = {
                640: { slidesPerView: parseInt(_tablet_data) },
                1024: { slidesPerView: parseInt(_desktop_data) },
            };
        }
    }
    if ("autoview" in _obj_options || _result_options.slidesPerView > 1) {
        _result_options.watchSlidesVisibility = !0;
    }

    /** centered*/
    if ("centered" in _obj_options) {
        _result_options.centeredSlides = !0;
    }

    /** speed*/
    if ("speed" in _obj_options) {
        _result_options.speed = parseInt(_obj_options.speed);
    } else {
        _result_options.speed = random(600, 1200);
    }

    /** observer*/
    if ("observer" in _obj_options) {
        _result_options.observer = !0;
        _result_options.observeParents = !0;
    }

    /** group*/
    if ("group" in _obj_options && !("autoview" in _obj_options)) {
        _result_options.slidesPerGroupSkip = !0;
        _result_options.loopFillGroupWithBlank = !0;
        _result_options.slidesPerGroup = parseInt(_obj_options.group);
    }

    /** fade*/
    if ("fade" in _obj_options) {
        _result_options.effect = 'fade';
        _result_options.fadeEffect = { crossFade: !0 };
    }

    /** autoheight*/
    if ("autoheight" in _obj_options) {
        _result_options.autoHeight = !0;
    }

    /** freemode*/
    if ("freemode" in _obj_options) {
        _result_options.freeMode = !0;
    }

    /** loop*/
    if ("loop" in _obj_options && !("row" in _obj_options)) {
        _result_options.loop = !0;
        _result_options.loopFillGroupWithBlank = !0;
    }

    /** autoplay*/
    if ("autoplay" in _obj_options) {
        if ("delay" in _obj_options) {
            _result_options.autoplay = {
                disableOnInteraction: !1,
                delay: parseInt(_obj_options.delay),
            };
        } else {
            _result_options.autoplay = {
                disableOnInteraction: !1,
                delay: random(5500, 6500),
            };
        }
        if ("reverse" in _obj_options) {
            _result_options.reverseDirection = !0;
        }
    }

    /** row*/
    if ("row" in _obj_options) {
        _result_options.direction = 'horizontal';
        _result_options.loop = !1;
        _result_options.grid = {
            rows: parseInt(_obj_options.row),
            fill: 'row',
        };
    }

    /**navigation*/
    if ("navigation" in _obj_options) {
        const _section = el.closest('.swiper-section');
        let _btn_prev = _section.querySelector('.swiper-button-prev');
        let _btn_next = _section.querySelector('.swiper-button-next');

        if (_btn_prev && _btn_next) {
            _btn_prev.classList.add(_prev_class);
            _btn_next.classList.add(_next_class);
        } else {
            _btn_prev = document.createElement("div");
            _btn_next = document.createElement("div");

            _btn_prev.classList.add('swiper-button', 'swiper-button-prev', _prev_class);
            _btn_next.classList.add('swiper-button', 'swiper-button-next', _next_class);

            _controls.appendChild(_btn_prev);
            _controls.appendChild(_btn_next);

            _btn_prev.setAttribute("data-glyph-fa", "");
            _btn_next.setAttribute("data-glyph-fa", "");
        }

        _result_options.navigation = {
            nextEl: '.' + _next_class,
            prevEl: '.' + _prev_class,
        };
    }

    /** pagination */
    if ("pagination" in _obj_options) {
        const _section = el.closest('.swiper-section');
        let _pagination = _section.querySelector('.swiper-pagination');
        if (_pagination) {
            _pagination.classList.add(_pagination_class);
        } else {
            let _pagination = document.createElement("div");

            _pagination.classList.add('swiper-pagination', _pagination_class);
            _controls.appendChild(_pagination);
        }

        if (_obj_options.pagination === 'fraction') {
            _result_options.pagination = {
                el: '.' + _pagination_class,
                type: 'fraction',
            };
        } else if (_obj_options.pagination === 'progressbar') {
            _result_options.pagination = {
                el: '.' + _pagination_class,
                type: "progressbar",
            };
        } else if (_obj_options.pagination === 'dynamic') {
            _result_options.pagination = {
                dynamicBullets: !0,
                el: '.' + _pagination_class,
            };
        } else {
            _result_options.pagination = {
                dynamicBullets: !1,
                el: '.' + _pagination_class,
            };
        }

        _result_options.pagination.clickable = !0;
    }

    /** scrollbar */
    if ("scrollbar" in _obj_options) {
        let _swiper_scrollbar = document.createElement("div");
        _swiper_scrollbar.classList.add('swiper-scrollbar', _scrollbar_class);
        _controls.appendChild(_swiper_scrollbar);
        _result_options.scrollbar = {
            hide: !0,
            el: '.' + _scrollbar_class,
        };
    }

    /** vertical*/
    if ("vertical" in _obj_options) {
        _result_options.direction = 'vertical';
    }

    /**parallax*/
    if ("parallax" in _obj_options) {
        _result_options.parallax = !0;
    }

    /**_marquee**/
    if ("marquee" in _obj_options) {
        _result_options.centeredSlides = !0;
        _result_options.autoplay = {
            delay: 1,
            disableOnInteraction: !1
        };
        _result_options.loop = !0;
        _result_options.allowTouchMove = !0;
    }

    /**progress*/
    if ("progressbar" in _obj_options) {
        let _swiper_progress = document.createElement("div");
        _swiper_progress.classList.add('swiper-progress');
        _result_options.appendChild(_swiper_progress);
    }

    /**cssMode*/
    if (!("row" in _obj_options)
        && !("marquee" in _obj_options)
        && !("centered" in _obj_options)
        && !("freemode" in _obj_options)
        && !("progressbar" in _obj_options)
        && is_mobile()
        && !el.classList.contains('sync-swiper')) {
        _result_options.cssMode = !0; /* API CSS Scroll Snap */
    }

    /** progress dom*/
    let _swiper_progress = _controls.querySelector('.swiper-progress');

    /** init*/
    _result_options.on = {
        init: function () {
            let t = this;
            if ("parallax" in _obj_options) {
                t.autoplay.stop();
                t.touchEventsData.formElements = "*";
                const parallax = el.querySelectorAll('.--bg');
                [].slice.call(parallax).map((elem) => {
                    let p = elem.dataset.swiperParallax.replace("%", "");
                    if (!p) {
                        p = 95;
                    }
                    elem.dataset.swiperParallax = toString(p / 100 * t.width);
                });
            }

            if ("progressbar" in _obj_options) {
                _swiper_progress.classList.add('progress');
            }
        },

        slideChange: function () {
            if ("progressbar" in _obj_options) {
                _swiper_progress.classList.remove('progress');
            }

            /** sync*/
            let t = this;
            if (el.classList.contains('sync-swiper')) {
                const el_closest = el.closest('section.section');
                const sync_swipers = Array.from(el_closest.querySelectorAll('.sync-swiper:not(.sync-exclude)'));
                sync_swipers.forEach((item, i) => {
                    let _local_swiper = item.swiper;
                    if ("loop" in _obj_options) {
                        _local_swiper.slideToLoop(t.activeIndex, parseInt(_obj_options.speed), true);
                    } else {
                        _local_swiper.slideTo(t.activeIndex, parseInt(_obj_options.speed), true);
                    }
                });
            }
        },

        slideChangeTransitionEnd: function () {
            if ("progressbar" in _obj_options) {
                _swiper_progress.classList.add('progress');
            }
        }
    };

    /**console.log(_obj_options);*/
    let _swiper = new Swiper('.' + _class, _result_options);
    if (!("autoplay" in _obj_options) && !("marquee" in _obj_options)) {
        _swiper.autoplay.stop();
    }

    /** now add mouseover and mouseout events to pause and resume the autoplay;*/
    el.addEventListener('mouseover', () => {
        _swiper.autoplay.stop();
    });
    el.addEventListener('mouseout', () => {
        if ("autoplay" in _obj_options) {
            _swiper.autoplay.start();
        }
    });
});