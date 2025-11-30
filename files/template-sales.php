<?php
/**
 * Template Name: Página de Ventas Hormozi
 * 
 * @package VelocityCash_AI
 */

get_header(); ?>

<div class="sales-page-wrapper">
    
    <!-- Scarcity Bar -->
    <div class="scarcity-bar">
        <span>⚡ OFERTA LIMITADA - SOLO QUEDAN 7 CUPOS ESTE MES</span>
        <div class="countdown-timer" data-end="2025-12-31T23:59:59">
            <div class="countdown-segment">
                <span class="days">00</span>
                <span class="countdown-label">Días</span>
            </div>
            <div class="countdown-segment">
                <span class="hours">00</span>
                <span class="countdown-label">Horas</span>
            </div>
            <div class="countdown-segment">
                <span class="minutes">00</span>
                <span class="countdown-label">Min</span>
            </div>
            <div class="countdown-segment">
                <span class="seconds">00</span>
                <span class="countdown-label">Seg</span>
            </div>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content container-narrow">
            <h1 class="hero-headline">
                Descubre Cómo Generar $10,000+ al Mes con IA Sin Experiencia Técnica
            </h1>
            <p class="hero-subheadline">
                Sistema probado paso a paso que ya está generando ingresos pasivos para más de 500 emprendedores
            </p>
            <div class="hero-cta">
                <a href="#pricing" class="btn btn-primary btn-large">
                    Conseguir Acceso Ahora
                </a>
                <a href="#how-it-works" class="btn btn-secondary btn-large">
                    Ver Cómo Funciona
                </a>
            </div>
            <div class="hero-trust-badges mt-md">
                <span>✓ Garantía 90 Días</span>
                <span>✓ Soporte Prioritario</span>
                <span>✓ Acceso Inmediato</span>
            </div>
        </div>
    </section>

    <!-- Social Proof -->
    <section class="social-proof-section">
        <div class="container">
            <h2 class="text-center mb-lg">Lo Que Dicen Nuestros Clientes</h2>
            <div class="testimonials-grid">
                <?php
                $testimonials_args = array(
                    'post_type' => 'testimonial',
                    'posts_per_page' => 3,
                    'orderby' => 'rand'
                );
                
                $testimonials = new WP_Query($testimonials_args);
                
                if ($testimonials->have_posts()) :
                    while ($testimonials->have_posts()) : $testimonials->the_post();
                ?>
                    <div class="testimonial-card">
                        <div class="testimonial-text">
                            <?php the_content(); ?>
                        </div>
                        <div class="testimonial-author">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="testimonial-avatar">
                                    <?php the_post_thumbnail('thumbnail'); ?>
                                </div>
                            <?php endif; ?>
                            <div class="testimonial-author-info">
                                <div class="testimonial-name"><?php the_title(); ?></div>
                                <div class="testimonial-title">
                                    <?php echo get_post_meta(get_the_ID(), 'testimonial_title', true); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </div>
        </div>
    </section>

    <!-- Problema - Agitar el dolor -->
    <section class="problem-section">
        <div class="container-narrow">
            <h2 class="text-center mb-md">Si Estás Luchando Por...</h2>
            <div class="problem-list">
                <div class="problem-item">
                    <span class="problem-icon">❌</span>
                    <div class="problem-content">
                        <h3>Generar Ingresos Consistentes Online</h3>
                        <p>Has probado múltiples métodos pero ninguno te da resultados predecibles</p>
                    </div>
                </div>
                <div class="problem-item">
                    <span class="problem-icon">❌</span>
                    <div class="problem-content">
                        <h3>Aprovechar la IA Sin Saber Programar</h3>
                        <p>Ves que otros están ganando con IA pero tú no sabes por dónde empezar</p>
                    </div>
                </div>
                <div class="problem-item">
                    <span class="problem-icon">❌</span>
                    <div class="problem-content">
                        <h3>Automatizar Tu Negocio</h3>
                        <p>Pierdes horas en tareas repetitivas que podrían estar automatizadas</p>
                    </div>
                </div>
            </div>
            <div class="text-center mt-lg">
                <p class="problem-conclusion">
                    <strong>Entonces este sistema es exactamente lo que necesitas...</strong>
                </p>
            </div>
        </div>
    </section>

    <!-- Solución - El "Cómo" -->
    <section class="solution-section" id="how-it-works">
        <div class="container">
            <h2 class="text-center mb-lg">El Sistema de 3 Pasos Que Cambiará Tu Negocio</h2>
            <div class="solution-steps">
                <div class="solution-step">
                    <div class="step-number">1</div>
                    <h3>Configura Tu Stack de IA</h3>
                    <p>Te damos las herramientas exactas y configuraciones que funcionan. Copia y pega nuestros templates probados.</p>
                </div>
                <div class="solution-step">
                    <div class="step-number">2</div>
                    <h3>Implementa las Automatizaciones</h3>
                    <p>Usa nuestros flujos de trabajo pre-construidos en n8n. No necesitas saber programar.</p>
                </div>
                <div class="solution-step">
                    <div class="step-number">3</div>
                    <h3>Escala y Multiplica</h3>
                    <p>Una vez que funciona, duplicas el sistema. Cada nueva automatización = más ingresos.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Value Stack - HORMOZI FRAMEWORK -->
    <section class="value-stack" id="pricing">
        <div class="value-stack-container">
            <h2 class="text-center mb-lg">Todo Lo Que Obtienes Cuando Te Unes Hoy</h2>
            
            <div class="value-item">
                <span class="value-item-name">🤖 15 Automatizaciones IA Listas Para Usar</span>
                <span class="value-item-price">$1,497</span>
            </div>
            
            <div class="value-item">
                <span class="value-item-name">📚 Curso Completo de Implementación (12 módulos)</span>
                <span class="value-item-price">$997</span>
            </div>
            
            <div class="value-item">
                <span class="value-item-name">🎯 Templates de n8n Workflows</span>
                <span class="value-item-price">$497</span>
            </div>
            
            <div class="value-item">
                <span class="value-item-name">💬 Comunidad Privada Slack</span>
                <span class="value-item-price">$497</span>
            </div>
            
            <div class="value-item">
                <span class="value-item-name">📖 Directorio de 100+ Herramientas IA</span>
                <span class="value-item-price">$197</span>
            </div>
            
            <div class="value-item">
                <span class="value-item-name">🎁 BONUS: 50 Prompts Premium ChatGPT</span>
                <span class="value-item-price">$297</span>
            </div>
            
            <div class="value-item">
                <span class="value-item-name">🎁 BONUS: Webinars Mensuales con Expertos</span>
                <span class="value-item-price">$997</span>
            </div>
            
            <div class="value-item">
                <span class="value-item-name">🎁 BONUS: Actualizaciones y Soporte 1 Año</span>
                <span class="value-item-price">$1,200</span>
            </div>
            
            <div class="value-total">
                <div class="value-total-label">Valor Total:</div>
                <div class="value-total-regular">$6,179</div>
                <div class="value-total-label">Precio Hoy:</div>
                <div class="value-total-amount">$297</div>
                <p class="mt-sm" style="opacity: 0.9; font-size: 1.125rem;">
                    Ahorras más del 95% cuando actúas ahora
                </p>
            </div>
            
            <div class="text-center mt-lg">
                <a href="#order-form" class="btn btn-secondary btn-large btn-full btn-urgent">
                    🚀 SÍ, QUIERO ACCESO AHORA POR SOLO $297
                </a>
                <p class="mt-sm" style="opacity: 0.7;">
                    Procesamiento seguro • Garantía 90 días • Acceso inmediato
                </p>
            </div>
        </div>
    </section>

    <!-- Garantía - Risk Reversal -->
    <section class="guarantee-section">
        <div class="container-narrow">
            <div class="guarantee-badge">
                90 DÍAS
            </div>
            <h2 class="mb-md">Garantía "Todo el Riesgo Es Nuestro"</h2>
            <div class="guarantee-text">
                <p>
                    Usa el sistema completo durante 90 días. Implementa las automatizaciones. 
                    Aplica las estrategias. Si no ves resultados o simplemente decides que no es para ti, 
                    te devolvemos el 100% de tu dinero. Sin preguntas. Sin problemas.
                </p>
                <p>
                    <strong>¿Por qué ofrecemos esto?</strong> Porque sabemos que funciona. 
                    Más del 87% de nuestros estudiantes implementan al menos 3 automatizaciones 
                    en su primer mes y ven resultados tangibles.
                </p>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section class="faq-section">
        <div class="container-narrow">
            <h2 class="text-center mb-lg">Preguntas Frecuentes</h2>
            <div class="faq-list">
                <div class="faq-item">
                    <h3 class="faq-question">¿Necesito saber programar?</h3>
                    <div class="faq-answer">
                        <p>No. Todo está diseñado para copiar y pegar. Te damos los templates exactos que necesitas.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <h3 class="faq-question">¿Cuánto tiempo toma ver resultados?</h3>
                    <div class="faq-answer">
                        <p>La mayoría de estudiantes implementan su primera automatización en 48 horas. Los resultados financieros varían, pero típicamente ves ROI en 30-60 días.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <h3 class="faq-question">¿Funciona esto en [mi industria]?</h3>
                    <div class="faq-answer">
                        <p>Sí. Tenemos estudiantes en más de 40 industrias diferentes. La IA es universal - funciona para servicios, productos físicos, info-productos, consultorías, etc.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <h3 class="faq-question">¿Hay costos adicionales?</h3>
                    <div class="faq-answer">
                        <p>Necesitarás suscripciones a algunas herramientas de IA (muchas tienen versiones gratuitas). Estimamos $20-50/mes dependiendo de tu escala.</p>
                    </div>
                </div>
                
                <div class="faq-item">
                    <h3 class="faq-question">¿Qué pasa si no tengo tiempo?</h3>
                    <div class="faq-answer">
                        <p>El sistema está diseñado para personas ocupadas. Puedes implementar una automatización en 2-3 horas. Haz una por semana y en un mes tendrás un stack completo funcionando.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Final -->
    <section class="final-cta-section">
        <div class="container-narrow">
            <h2 class="text-center mb-md">Tienes Dos Opciones Ahora Mismo...</h2>
            <div class="choice-comparison">
                <div class="choice-box choice-bad">
                    <h3>❌ Opción A</h3>
                    <p>Seguir como hasta ahora. Perder tiempo en tareas manuales. Ver cómo otros aprovechan la IA mientras tú te quedas atrás. Seguir luchando por ingresos consistentes.</p>
                </div>
                <div class="choice-box choice-good">
                    <h3>✅ Opción B</h3>
                    <p>Tomar acción hoy. Implementar un sistema probado. Automatizar tu negocio. Generar más ingresos trabajando menos horas. Unirte a los que ya están ganando con IA.</p>
                </div>
            </div>
            <div class="text-center mt-lg">
                <a href="#order-form" class="btn btn-primary btn-large">
                    Elegir Opción B - Conseguir Acceso Ahora
                </a>
                <p class="mt-md" style="opacity: 0.8;">
                    ⏰ Esta oferta expira en <strong><span id="final-countdown">24 horas</span></strong>
                </p>
            </div>
        </div>
    </section>

    <!-- Order Form Section -->
    <section class="order-form-section" id="order-form">
        <div class="container-narrow">
            <h2 class="text-center mb-lg">Completa Tu Pedido</h2>
            <?php echo do_shortcode('[woocommerce_checkout]'); ?>
        </div>
    </section>

</div>

<style>
/* Estilos específicos para sales page */
.sales-page-wrapper { overflow-x: hidden; }

.problem-section {
    background: var(--vc-light);
    padding: var(--spacing-xl) var(--spacing-md);
}

.problem-list {
    display: flex;
    flex-direction: column;
    gap: var(--spacing-md);
    margin-top: var(--spacing-lg);
}

.problem-item {
    display: flex;
    gap: var(--spacing-md);
    background: var(--vc-white);
    padding: var(--spacing-md);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
}

.problem-icon {
    font-size: 2rem;
    flex-shrink: 0;
}

.problem-conclusion {
    font-size: 1.5rem;
    color: var(--vc-primary);
}

.solution-section {
    background: var(--vc-dark);
    color: var(--vc-white);
    padding: var(--spacing-xl) var(--spacing-md);
}

.solution-section h2,
.solution-section h3 {
    color: var(--vc-white);
}

.solution-steps {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: var(--spacing-md);
    margin-top: var(--spacing-lg);
}

.solution-step {
    background: var(--vc-dark-light);
    padding: var(--spacing-md);
    border-radius: var(--radius-lg);
    text-align: center;
}

.step-number {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: var(--vc-secondary);
    color: var(--vc-dark);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: 800;
    margin: 0 auto var(--spacing-md);
}

.faq-section {
    padding: var(--spacing-xl) var(--spacing-md);
}

.faq-item {
    background: var(--vc-white);
    border: 2px solid var(--vc-light);
    border-radius: var(--radius-md);
    padding: var(--spacing-md);
    margin-bottom: var(--spacing-sm);
    cursor: pointer;
    transition: all var(--transition-base);
}

.faq-item:hover {
    border-color: var(--vc-primary);
}

.faq-question {
    margin: 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.faq-question::after {
    content: '+';
    font-size: 1.5rem;
}

.faq-item.active .faq-question::after {
    content: '−';
}

.faq-answer {
    display: none;
    margin-top: var(--spacing-sm);
    padding-top: var(--spacing-sm);
    border-top: 1px solid var(--vc-light);
}

.faq-item.active .faq-answer {
    display: block;
}

.final-cta-section {
    background: var(--vc-light);
    padding: var(--spacing-xl) var(--spacing-md);
}

.choice-comparison {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: var(--spacing-md);
    margin-top: var(--spacing-lg);
}

.choice-box {
    padding: var(--spacing-md);
    border-radius: var(--radius-lg);
    text-align: center;
}

.choice-bad {
    background: #FFF5F5;
    border: 2px solid var(--vc-danger);
}

.choice-good {
    background: #F0FFF4;
    border: 2px solid var(--vc-success);
}

.order-form-section {
    padding: var(--spacing-xl) var(--spacing-md);
    background: var(--vc-white);
}

.testimonials-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: var(--spacing-md);
}

@media (max-width: 768px) {
    .choice-comparison {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // FAQ Toggle
    $('.faq-item').on('click', function() {
        $(this).toggleClass('active');
    });
});
</script>

<?php get_footer(); ?>
