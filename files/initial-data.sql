-- =============================================================================
-- VELOCITYCASH AI - INITIAL DATABASE SETUP
-- =============================================================================
-- Este archivo contiene los datos iniciales para tu sitio WordPress
-- Ejecutar después de la instalación de WordPress
-- =============================================================================

-- Insertar productos de WooCommerce

-- Producto 1: AI Cash Accelerator Elite ($997)
INSERT INTO `vc_posts` (`post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `post_name`, `post_type`) VALUES
(1, NOW(), NOW(), 
'<h2>Transforma Tu Negocio con IA en 90 Días</h2>
<p>El programa más completo de implementación de IA para empresarios que quieren resultados reales.</p>

<h3>¿Qué Incluye?</h3>
<ul>
<li>12 sesiones 1-on-1 de consultoría personalizada</li>
<li>Implementación completa de tu stack de IA</li>
<li>Acceso prioritario a toda nuestra tecnología</li>
<li>Soporte directo vía Slack 24/7</li>
<li>Revisión mensual de resultados</li>
<li>Garantía de ROI o te devolvemos tu dinero</li>
</ul>

<h3>Para Quién Es Este Programa</h3>
<p>Empresarios serios que facturan $10K+/mes y quieren escalar con automatización IA.</p>',
'AI Cash Accelerator Elite',
'Consultoría 1-on-1, implementación completa, soporte prioritario 90 días',
'publish',
'ai-cash-accelerator-elite',
'product');

-- Producto 2: AI Automation Toolkit Pro ($297)
INSERT INTO `vc_posts` (`post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `post_name`, `post_type`) VALUES
(1, NOW(), NOW(),
'<h2>15 Automatizaciones IA Listas Para Implementar Hoy</h2>
<p>El sistema completo que necesitas para automatizar tu negocio con IA.</p>

<h3>Automatizaciones Incluidas:</h3>
<ul>
<li>✅ Respuesta automática a emails con IA</li>
<li>✅ Generación de contenido para redes sociales</li>
<li>✅ Análisis de sentiment de clientes</li>
<li>✅ Chatbot de atención 24/7</li>
<li>✅ Transcripción y resumen de meetings</li>
<li>✅ Generación de reportes automatizados</li>
<li>✅ Lead scoring con machine learning</li>
<li>✅ Y 8 automatizaciones más...</li>
</ul>

<h3>Bonuses Gratis:</h3>
<ul>
<li>🎁 50 Prompts Premium ChatGPT ($297 valor)</li>
<li>🎁 Templates n8n Workflows ($497 valor)</li>
<li>🎁 Directorio 100+ Herramientas IA ($197 valor)</li>
<li>🎁 Comunidad Privada Slack ($497 valor)</li>
<li>🎁 Updates y Soporte 1 Año ($1200 valor)</li>
</ul>',
'AI Automation Toolkit Pro',
'15 automatizaciones listas, templates, workflows n8n, training videos',
'publish',
'ai-automation-toolkit-pro',
'product');

-- Producto 3: AI Fundamentals Bootcamp ($47)
INSERT INTO `vc_posts` (`post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `post_name`, `post_type`) VALUES
(1, NOW(), NOW(),
'<h2>Aprende Los Fundamentos de IA Para Negocios en 5 Días</h2>
<p>Curso intensivo diseñado para emprendedores que quieren empezar con IA desde cero.</p>

<h3>Módulos del Bootcamp:</h3>
<ul>
<li><strong>Día 1:</strong> Introducción a IA y casos de uso</li>
<li><strong>Día 2:</strong> ChatGPT masterclass</li>
<li><strong>Día 3:</strong> Automatización sin código</li>
<li><strong>Día 4:</strong> IA para marketing y ventas</li>
<li><strong>Día 5:</strong> Tu primer proyecto IA</li>
</ul>

<h3>Lo Que Recibes:</h3>
<ul>
<li>5 módulos en video (3 horas totales)</li>
<li>Workbook descargable</li>
<li>Certificado de finalización</li>
<li>Acceso a comunidad Discord</li>
<li>Actualizaciones gratuitas de por vida</li>
</ul>',
'AI Fundamentals Bootcamp',
'Curso mini de 5 módulos, certificado, comunidad Discord',
'publish',
'ai-fundamentals-bootcamp',
'product');

-- Producto 4: AI Inner Circle ($97/mes)
INSERT INTO `vc_posts` (`post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `post_name`, `post_type`) VALUES
(1, NOW(), NOW(),
'<h2>Membresía Mensual Para Mantenerte Al Día con IA</h2>
<p>Acceso continuo a training, comunidad y soporte para implementar IA en tu negocio.</p>

<h3>Cada Mes Recibes:</h3>
<ul>
<li>🎯 2 Masterminds en vivo</li>
<li>📚 1 Nueva automatización lista para usar</li>
<li>🎓 Training exclusivo con expertos</li>
<li>💬 Q&A sessions semanales</li>
<li>📊 Reportes de tendencias IA</li>
<li>🛠️ Herramientas y recursos nuevos</li>
</ul>

<h3>Beneficios Adicionales:</h3>
<ul>
<li>Descuentos en todos nuestros productos</li>
<li>Early access a nuevas herramientas</li>
<li>Networking con otros miembros</li>
<li>Cancela cuando quieras</li>
</ul>',
'AI Inner Circle',
'Masterminds mensuales, updates constantes, Q&A sessions',
'publish',
'ai-inner-circle',
'product');

-- =============================================================================
-- META DE PRODUCTOS (Precios, Stock, etc.)
-- =============================================================================

-- Meta para producto 1 ($997)
INSERT INTO `vc_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
((SELECT ID FROM vc_posts WHERE post_name = 'ai-cash-accelerator-elite' LIMIT 1), '_regular_price', '997'),
((SELECT ID FROM vc_posts WHERE post_name = 'ai-cash-accelerator-elite' LIMIT 1), '_price', '997'),
((SELECT ID FROM vc_posts WHERE post_name = 'ai-cash-accelerator-elite' LIMIT 1), '_stock_status', 'instock'),
((SELECT ID FROM vc_posts WHERE post_name = 'ai-cash-accelerator-elite' LIMIT 1), '_velocitycash_featured', 'no'),
((SELECT ID FROM vc_posts WHERE post_name = 'ai-cash-accelerator-elite' LIMIT 1), '_velocitycash_display_order', '1');

-- Meta para producto 2 ($297) - FEATURED
INSERT INTO `vc_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
((SELECT ID FROM vc_posts WHERE post_name = 'ai-automation-toolkit-pro' LIMIT 1), '_regular_price', '297'),
((SELECT ID FROM vc_posts WHERE post_name = 'ai-automation-toolkit-pro' LIMIT 1), '_price', '297'),
((SELECT ID FROM vc_posts WHERE post_name = 'ai-automation-toolkit-pro' LIMIT 1), '_stock_status', 'instock'),
((SELECT ID FROM vc_posts WHERE post_name = 'ai-automation-toolkit-pro' LIMIT 1), '_velocitycash_featured', 'yes'),
((SELECT ID FROM vc_posts WHERE post_name = 'ai-automation-toolkit-pro' LIMIT 1), '_velocitycash_display_order', '2');

-- Meta para producto 3 ($47)
INSERT INTO `vc_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
((SELECT ID FROM vc_posts WHERE post_name = 'ai-fundamentals-bootcamp' LIMIT 1), '_regular_price', '47'),
((SELECT ID FROM vc_posts WHERE post_name = 'ai-fundamentals-bootcamp' LIMIT 1), '_price', '47'),
((SELECT ID FROM vc_posts WHERE post_name = 'ai-fundamentals-bootcamp' LIMIT 1), '_stock_status', 'instock'),
((SELECT ID FROM vc_posts WHERE post_name = 'ai-fundamentals-bootcamp' LIMIT 1), '_velocitycash_featured', 'no'),
((SELECT ID FROM vc_posts WHERE post_name = 'ai-fundamentals-bootcamp' LIMIT 1), '_velocitycash_display_order', '3');

-- Meta para producto 4 ($97/mes)
INSERT INTO `vc_postmeta` (`post_id`, `meta_key`, `meta_value`) VALUES
((SELECT ID FROM vc_posts WHERE post_name = 'ai-inner-circle' LIMIT 1), '_regular_price', '97'),
((SELECT ID FROM vc_posts WHERE post_name = 'ai-inner-circle' LIMIT 1), '_price', '97'),
((SELECT ID FROM vc_posts WHERE post_name = 'ai-inner-circle' LIMIT 1), '_stock_status', 'instock'),
((SELECT ID FROM vc_posts WHERE post_name = 'ai-inner-circle' LIMIT 1), '_subscription_price', '97'),
((SELECT ID FROM vc_posts WHERE post_name = 'ai-inner-circle' LIMIT 1), '_subscription_period', 'month'),
((SELECT ID FROM vc_posts WHERE post_name = 'ai-inner-circle' LIMIT 1), '_velocitycash_featured', 'no'),
((SELECT ID FROM vc_posts WHERE post_name = 'ai-inner-circle' LIMIT 1), '_velocitycash_display_order', '4');

-- =============================================================================
-- POSTS DE BLOG DE EJEMPLO
-- =============================================================================

INSERT INTO `vc_posts` (`post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `post_name`, `post_type`) VALUES
(1, NOW(), NOW(),
'<p>La inteligencia artificial está transformando la forma en que hacemos negocios. En este artículo, exploraremos 10 formas concretas en las que puedes usar IA hoy mismo para generar más ingresos.</p>

<h2>1. Automatización de Respuestas a Clientes</h2>
<p>Usa ChatGPT integrado con tu email para responder automáticamente consultas frecuentes. Esto libera horas cada semana.</p>

<h2>2. Generación de Contenido</h2>
<p>La IA puede crear posts de blog, descripciones de productos, y copys de ventas en minutos. La clave está en los prompts correctos.</p>

<h2>3. Análisis Predictivo</h2>
<p>Herramientas de machine learning pueden predecir qué clientes tienen más probabilidad de comprar, permitiéndote enfocar tu tiempo donde más importa.</p>

<!-- Continúa con más contenido... -->',
'10 Formas de Usar IA Para Generar Más Ingresos en Tu Negocio',
'Descubre cómo la inteligencia artificial puede ayudarte a automatizar tareas y aumentar tus ingresos.',
'publish',
'10-formas-usar-ia-generar-ingresos',
'post');

-- Más posts de ejemplo...
INSERT INTO `vc_posts` (`post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `post_name`, `post_type`) VALUES
(1, NOW(), NOW(),
'<p>n8n es una herramienta poderosa de automatización que te permite conectar diferentes aplicaciones sin necesidad de programar.</p>

<h2>¿Qué es n8n?</h2>
<p>n8n es una plataforma de automatización de código abierto que te permite crear workflows complejos de forma visual.</p>

<h2>Casos de Uso Para Negocios</h2>
<ul>
<li>Sincronizar datos entre CRM y herramientas de marketing</li>
<li>Automatizar creación de facturas</li>
<li>Procesar pedidos automáticamente</li>
<li>Enviar notificaciones personalizadas</li>
</ul>

<!-- Más contenido... -->',
'Guía Completa: Automatización con n8n Para Principiantes',
'Aprende a usar n8n para automatizar tu negocio sin escribir código.',
'publish',
'guia-automatizacion-n8n',
'post');

-- =============================================================================
-- PÁGINAS ESTÁTICAS
-- =============================================================================

-- Página de inicio
INSERT INTO `vc_posts` (`post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_status`, `post_name`, `post_type`) VALUES
(1, NOW(), NOW(),
'<!-- Contenido de hero y secciones se maneja con el template -->
[pricing_table]',
'Inicio',
'publish',
'home',
'page');

-- Página "Sobre Nosotros"
INSERT INTO `vc_posts` (`post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_status`, `post_name`, `post_type`) VALUES
(1, NOW(), NOW(),
'<h2>Nuestra Misión</h2>
<p>En VelocityCash AI, creemos que la inteligencia artificial debe ser accesible para todos los empresarios, no solo para las grandes corporaciones.</p>

<h2>Nuestra Historia</h2>
<p>Comenzamos en 2023 con una simple pregunta: ¿Cómo podemos ayudar a más emprendedores a aprovechar el poder de la IA?</p>

<p>Desde entonces, hemos ayudado a más de 500 negocios a implementar automatizaciones que les han ahorrado miles de horas y generado millones en ingresos adicionales.</p>',
'Sobre Nosotros',
'publish',
'sobre-nosotros',
'page');

-- =============================================================================
-- OPCIONES DE WORDPRESS
-- =============================================================================

-- Configurar página de inicio
UPDATE `vc_options` SET `option_value` = 'page' WHERE `option_name` = 'show_on_front';
UPDATE `vc_options` SET `option_value` = (SELECT ID FROM vc_posts WHERE post_name = 'home' AND post_type = 'page' LIMIT 1) WHERE `option_name` = 'page_on_front';

-- Configurar permalinks
UPDATE `vc_options` SET `option_value` = '/%postname%/' WHERE `option_name` = 'permalink_structure';

-- Configurar sitio
UPDATE `vc_options` SET `option_value` = 'VelocityCash AI - Genera Ingresos con Inteligencia Artificial' WHERE `option_name` = 'blogname';
UPDATE `vc_options` SET `option_value` = 'Automatización y consultoría de IA para emprendedores' WHERE `option_name` = 'blogdescription';

-- Timezone
UPDATE `vc_options` SET `option_value` = 'Europe/Madrid' WHERE `option_name` = 'timezone_string';

-- =============================================================================
-- NOTA: Este archivo es solo un template. Los IDs reales serán diferentes.
-- Ajusta las queries según tu instalación real de WordPress.
-- =============================================================================
