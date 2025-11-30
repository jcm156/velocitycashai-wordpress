/**
 * VelocityCash AI - Custom JavaScript
 * Hormozi-style conversion optimization
 */

(function($) {
    'use strict';

    /**
     * =========================================================================
     * COUNTDOWN TIMER - SCARCITY & URGENCY
     * =========================================================================
     */
    function initCountdownTimers() {
        $('.countdown-timer').each(function() {
            const $timer = $(this);
            const endDate = new Date($timer.data('end')).getTime();
            
            const updateTimer = function() {
                const now = new Date().getTime();
                const distance = endDate - now;
                
                if (distance < 0) {
                    $timer.html('<span class="expired">¡Oferta Expirada!</span>');
                    return;
                }
                
                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                $timer.find('.days').text(String(days).padStart(2, '0'));
                $timer.find('.hours').text(String(hours).padStart(2, '0'));
                $timer.find('.minutes').text(String(minutes).padStart(2, '0'));
                $timer.find('.seconds').text(String(seconds).padStart(2, '0'));
            };
            
            updateTimer();
            setInterval(updateTimer, 1000);
        });
    }

    /**
     * =========================================================================
     * LEAD MAGNET FORMS
     * =========================================================================
     */
    function initLeadMagnetForms() {
        $('.lead-magnet-form').on('submit', function(e) {
            e.preventDefault();
            
            const $form = $(this);
            const $button = $form.find('button[type="submit"]');
            const buttonText = $button.text();
            const email = $form.find('input[name="email"]').val();
            const magnetId = $form.data('magnet');
            
            // Validar email
            if (!isValidEmail(email)) {
                showNotification('Por favor, introduce un email válido', 'error');
                return;
            }
            
            // Deshabilitar botón
            $button.prop('disabled', true).text('Procesando...');
            
            // Enviar a WordPress y n8n
            $.ajax({
                url: velocitycashAjax.ajaxurl,
                type: 'POST',
                data: {
                    action: 'velocitycash_lead_magnet',
                    nonce: velocitycashAjax.nonce,
                    email: email,
                    magnet_id: magnetId,
                    url: window.location.href
                },
                success: function(response) {
                    if (response.success) {
                        showNotification(response.data.message, 'success');
                        $form.find('input[name="email"]').val('');
                        
                        // Redirigir a página de gracias después de 2 segundos
                        setTimeout(function() {
                            window.location.href = '/gracias-lead-magnet/?magnet=' + magnetId;
                        }, 2000);
                    } else {
                        showNotification(response.data.message, 'error');
                    }
                },
                error: function() {
                    showNotification('Error al procesar. Por favor, intenta de nuevo.', 'error');
                },
                complete: function() {
                    $button.prop('disabled', false).text(buttonText);
                }
            });
        });
    }

    /**
     * =========================================================================
     * EMAIL VALIDATION
     * =========================================================================
     */
    function isValidEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }

    /**
     * =========================================================================
     * NOTIFICATION SYSTEM
     * =========================================================================
     */
    function showNotification(message, type = 'info') {
        // Eliminar notificaciones previas
        $('.vc-notification').remove();
        
        const $notification = $('<div>', {
            class: 'vc-notification vc-notification-' + type,
            html: message
        });
        
        $('body').append($notification);
        
        setTimeout(function() {
            $notification.addClass('show');
        }, 100);
        
        setTimeout(function() {
            $notification.removeClass('show');
            setTimeout(function() {
                $notification.remove();
            }, 300);
        }, 5000);
    }

    /**
     * =========================================================================
     * SCROLL TO ADD TO CART
     * =========================================================================
     */
    function initScrollToCart() {
        $('.scroll-to-cart').on('click', function(e) {
            e.preventDefault();
            
            $('html, body').animate({
                scrollTop: $('.single_add_to_cart_button').offset().top - 100
            }, 800);
        });
    }

    /**
     * =========================================================================
     * STICKY ADD TO CART BAR
     * =========================================================================
     */
    function initStickyCart() {
        if (!$('.single-product').length) return;
        
        const $productSummary = $('.product .summary');
        const $stickyBar = $('<div>', {
            class: 'sticky-cart-bar',
            html: `
                <div class="sticky-cart-content">
                    <div class="sticky-product-info">
                        <span class="sticky-product-name">${$('.product_title').text()}</span>
                        <span class="sticky-product-price">${$('.price').first().html()}</span>
                    </div>
                    <button class="btn btn-primary sticky-add-to-cart">
                        Añadir al Carrito
                    </button>
                </div>
            `
        });
        
        $('body').append($stickyBar);
        
        $(window).on('scroll', function() {
            const summaryBottom = $productSummary.offset().top + $productSummary.outerHeight();
            const scrollTop = $(window).scrollTop();
            
            if (scrollTop > summaryBottom) {
                $stickyBar.addClass('visible');
            } else {
                $stickyBar.removeClass('visible');
            }
        });
        
        $stickyBar.find('.sticky-add-to-cart').on('click', function() {
            $('.single_add_to_cart_button').click();
        });
    }

    /**
     * =========================================================================
     * EXIT INTENT POPUP
     * =========================================================================
     */
    function initExitIntent() {
        let exitIntentShown = false;
        
        $(document).on('mouseleave', function(e) {
            if (e.clientY < 10 && !exitIntentShown && !sessionStorage.getItem('exitIntentShown')) {
                showExitIntentPopup();
                exitIntentShown = true;
                sessionStorage.setItem('exitIntentShown', 'true');
            }
        });
    }

    function showExitIntentPopup() {
        const $popup = $(`
            <div class="exit-intent-overlay">
                <div class="exit-intent-popup">
                    <button class="exit-intent-close">&times;</button>
                    <h2>¡Espera! No Te Vayas Sin Esto...</h2>
                    <p>Consigue acceso GRATIS a nuestros 50 mejores prompts de ChatGPT</p>
                    <form class="exit-intent-form">
                        <input type="email" name="email" placeholder="Tu mejor email" required />
                        <button type="submit" class="btn btn-secondary">Enviar Prompts GRATIS</button>
                    </form>
                    <p class="exit-intent-disclaimer">Sin spam. Solo contenido de valor.</p>
                </div>
            </div>
        `);
        
        $('body').append($popup);
        
        setTimeout(function() {
            $popup.addClass('show');
        }, 50);
        
        $popup.find('.exit-intent-close, .exit-intent-overlay').on('click', function(e) {
            if (e.target === this) {
                closeExitIntent($popup);
            }
        });
        
        $popup.find('.exit-intent-form').on('submit', function(e) {
            e.preventDefault();
            const email = $(this).find('input[name="email"]').val();
            
            $.ajax({
                url: velocitycashAjax.ajaxurl,
                type: 'POST',
                data: {
                    action: 'velocitycash_lead_magnet',
                    nonce: velocitycashAjax.nonce,
                    email: email,
                    magnet_id: 'exit-intent-prompts',
                    url: window.location.href
                },
                success: function(response) {
                    if (response.success) {
                        $popup.find('.exit-intent-popup').html(`
                            <h2>¡Perfecto!</h2>
                            <p>Revisa tu email - te hemos enviado los prompts.</p>
                            <button class="btn btn-primary" onclick="location.reload()">Continuar</button>
                        `);
                        
                        setTimeout(function() {
                            closeExitIntent($popup);
                        }, 3000);
                    }
                }
            });
        });
    }

    function closeExitIntent($popup) {
        $popup.removeClass('show');
        setTimeout(function() {
            $popup.remove();
        }, 300);
    }

    /**
     * =========================================================================
     * CART ABANDONMENT TRACKING
     * =========================================================================
     */
    function trackCartActivity() {
        if (typeof wc_add_to_cart_params === 'undefined') return;
        
        $(document.body).on('added_to_cart', function(event, fragments, cart_hash, $button) {
            // Enviar evento a n8n
            $.post(velocitycashAjax.n8nWebhook + 'cart-add', {
                event: 'cart_add',
                product_id: $button.data('product_id'),
                timestamp: Date.now()
            });
        });
        
        // Track cuando el usuario está en el checkout
        if ($('body').hasClass('woocommerce-checkout')) {
            // Enviar evento cada 30 segundos mientras está en checkout
            const checkoutInterval = setInterval(function() {
                $.post(velocitycashAjax.n8nWebhook + 'checkout-active', {
                    event: 'checkout_active',
                    timestamp: Date.now()
                });
            }, 30000);
            
            // Limpiar intervalo cuando sale de la página
            $(window).on('beforeunload', function() {
                clearInterval(checkoutInterval);
            });
        }
    }

    /**
     * =========================================================================
     * SMOOTH SCROLL
     * =========================================================================
     */
    function initSmoothScroll() {
        $('a[href*="#"]:not([href="#"])').on('click', function(e) {
            if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && 
                location.hostname === this.hostname) {
                
                let target = $(this.hash);
                target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                
                if (target.length) {
                    e.preventDefault();
                    $('html, body').animate({
                        scrollTop: target.offset().top - 80
                    }, 800);
                }
            }
        });
    }

    /**
     * =========================================================================
     * LAZY LOAD IMAGES
     * =========================================================================
     */
    function initLazyLoad() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });
            
            document.querySelectorAll('img.lazy').forEach(img => {
                imageObserver.observe(img);
            });
        } else {
            // Fallback para navegadores antiguos
            $('img.lazy').each(function() {
                $(this).attr('src', $(this).data('src')).removeClass('lazy');
            });
        }
    }

    /**
     * =========================================================================
     * TESTIMONIAL SLIDER
     * =========================================================================
     */
    function initTestimonialSlider() {
        const $slider = $('.testimonials-slider');
        if (!$slider.length) return;
        
        let currentSlide = 0;
        const $slides = $slider.find('.testimonial-card');
        const totalSlides = $slides.length;
        
        function showSlide(index) {
            $slides.removeClass('active');
            $slides.eq(index).addClass('active');
        }
        
        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            showSlide(currentSlide);
        }
        
        function prevSlide() {
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            showSlide(currentSlide);
        }
        
        showSlide(currentSlide);
        
        // Auto-rotate cada 5 segundos
        setInterval(nextSlide, 5000);
        
        // Botones de navegación
        $slider.find('.slider-next').on('click', nextSlide);
        $slider.find('.slider-prev').on('click', prevSlide);
    }

    /**
     * =========================================================================
     * PRICE ANIMATION
     * =========================================================================
     */
    function animatePriceReveal() {
        const $prices = $('.animate-price');
        
        const priceObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    $(entry.target).addClass('revealed');
                    priceObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        
        $prices.each(function() {
            priceObserver.observe(this);
        });
    }

    /**
     * =========================================================================
     * COPY TO CLIPBOARD
     * =========================================================================
     */
    function initCopyButtons() {
        $('.copy-button').on('click', function() {
            const $button = $(this);
            const textToCopy = $button.data('copy');
            
            navigator.clipboard.writeText(textToCopy).then(function() {
                const originalText = $button.text();
                $button.text('¡Copiado!');
                
                setTimeout(function() {
                    $button.text(originalText);
                }, 2000);
            });
        });
    }

    /**
     * =========================================================================
     * ANALYTICS TRACKING
     * =========================================================================
     */
    function trackCustomEvents() {
        // Track button clicks
        $('.btn-primary, .btn-secondary').on('click', function() {
            const buttonText = $(this).text();
            const buttonLocation = $(this).closest('section').attr('class') || 'unknown';
            
            if (typeof gtag !== 'undefined') {
                gtag('event', 'button_click', {
                    'button_text': buttonText,
                    'button_location': buttonLocation
                });
            }
        });
        
        // Track scroll depth
        let scrollDepths = [25, 50, 75, 100];
        let trackedDepths = [];
        
        $(window).on('scroll', function() {
            const scrollPercent = Math.round(($(window).scrollTop() / ($(document).height() - $(window).height())) * 100);
            
            scrollDepths.forEach(depth => {
                if (scrollPercent >= depth && !trackedDepths.includes(depth)) {
                    trackedDepths.push(depth);
                    
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'scroll_depth', {
                            'depth_percentage': depth
                        });
                    }
                }
            });
        });
    }

    /**
     * =========================================================================
     * INITIALIZE ALL
     * =========================================================================
     */
    $(document).ready(function() {
        initCountdownTimers();
        initLeadMagnetForms();
        initScrollToCart();
        initStickyCart();
        initExitIntent();
        trackCartActivity();
        initSmoothScroll();
        initLazyLoad();
        initTestimonialSlider();
        animatePriceReveal();
        initCopyButtons();
        trackCustomEvents();
        
        console.log('VelocityCash AI - Sistema cargado ✓');
    });

})(jQuery);
