# VelocityCash AI - WordPress Site Documentation

## 📋 Tabla de Contenidos
1. [Resumen del Proyecto](#resumen)
2. [Requisitos Previos](#requisitos)
3. [Instalación Paso a Paso](#instalación)
4. [Configuración Post-Instalación](#configuración)
5. [Integración n8n](#n8n)
6. [Configuración de Productos](#productos)
7. [SEO y Analytics](#seo)
8. [Mantenimiento](#mantenimiento)
9. [Troubleshooting](#troubleshooting)

---

## 🎯 Resumen del Proyecto {#resumen}

**VelocityCash AI** es un sitio WordPress completo optimizado para conversiones usando el framework de Alex Hormozi. Incluye:

- ✅ Blog optimizado para SEO
- ✅ E-commerce con WooCommerce
- ✅ 4 productos configurados (Elite $997, Pro $297, Bootcamp $47, Membership $97/mes)
- ✅ Integración con n8n para automatización
- ✅ Sistema de analytics y conversion tracking
- ✅ Templates de ventas con value stacking
- ✅ Lead magnets y email capture
- ✅ Scarcity timers y urgency triggers

---

## ⚙️ Requisitos Previos {#requisitos}

### Hosting
- ✅ SiteGround Plan GrowBig (ya contratado)
- ✅ Dominio: velocitycashai.com (ya registrado)
- ✅ SSL Certificate (activar en SiteGround)

### Cuentas Necesarias
- [ ] Stripe Account (para pagos)
- [ ] PayPal Business Account
- [ ] n8n Cloud: jcm156.app.n8n.cloud (ya tienes)
- [ ] Mailchimp Account (para email marketing)
- [ ] Google Analytics 4 (opcional)
- [ ] Facebook Pixel (opcional)

---

## 🚀 Instalación Paso a Paso {#instalación}

### PASO 1: Preparar SiteGround

1. **Accede a Site Tools en SiteGround**
   - Ve a https://my.siteground.com
   - Selecciona velocitycashai.com
   - Click en "Site Tools"

2. **Activar SSL**
   - Ve a Security > SSL Manager
   - Click en "Install" para el certificado Let's Encrypt
   - Espera 5-10 minutos a que se active

3. **Crear Base de Datos**
   - Ve a MySQL > Databases
   - Click en "Create Database"
   - Nombre: `velocitycashai_db`
   - Usuario: `velocitycashai_user`
   - Genera contraseña segura (GUARDAR ESTA CONTRASEÑA)

### PASO 2: Instalar WordPress

1. **WordPress Toolkit**
   - Ve a WordPress > Install & Manage
   - Click en "Install WordPress"
   - Configuración:
     - Protocol: https://
     - Domain: velocitycashai.com
     - Directory: (dejar vacío - raíz)
     - Database Name: velocitycashai_db
     - Admin Username: (tu usuario admin)
     - Admin Password: (contraseña segura)
     - Admin Email: tu@email.com
   - Click "Install"

2. **Esperar Instalación**
   - Toma 2-3 minutos
   - Recibirás email de confirmación

### PASO 3: Subir Archivos del Proyecto

1. **Acceder a File Manager**
   - En Site Tools > File Manager
   - Navega a `/public_html/`

2. **Subir Child Theme**
   - Ve a `wp-content/themes/`
   - Crea carpeta `velocitycash-child`
   - Sube todos los archivos de `/wp-content/themes/velocitycash-child/`

3. **Subir Plugin Personalizado**
   - Ve a `wp-content/plugins/`
   - Crea carpeta `velocitycash-custom`
   - Sube todos los archivos de `/wp-content/plugins/velocitycash-custom/`

4. **Reemplazar wp-config.php**
   - PRIMERO: Haz backup del wp-config.php actual
   - Abre el nuevo wp-config.php
   - Actualiza las siguientes líneas con tus datos:
     ```php
     define( 'DB_NAME', 'velocitycashai_db' );
     define( 'DB_USER', 'velocitycashai_user' );
     define( 'DB_PASSWORD', 'TU_PASSWORD_DE_MYSQL' );
     ```
   - Genera nuevas SALT keys en: https://api.wordpress.org/secret-key/1.1/salt/
   - Copia y pega las nuevas keys en el wp-config.php
   - Sube el archivo (reemplazar el existente)

5. **Reemplazar .htaccess**
   - Sube el archivo .htaccess a `/public_html/`
   - Reemplaza el existente

### PASO 4: Instalar Tema Base y Plugins

1. **Accede al Admin de WordPress**
   - Ve a https://velocitycashai.com/wp-admin
   - Login con tus credenciales

2. **Instalar Astra Theme**
   - Appearance > Themes > Add New
   - Busca "Astra"
   - Instala y activa

3. **Activar Child Theme**
   - Appearance > Themes
   - Activa "VelocityCash AI Child"

4. **Instalar Plugins Esenciales**
   Ir a Plugins > Add New e instalar:
   
   **OBLIGATORIOS:**
   - WooCommerce (e-commerce)
   - WooCommerce Stripe Gateway (pagos)
   - WooCommerce PayPal Payments (pagos)
   
   **RECOMENDADOS:**
   - Rank Math SEO (SEO optimization)
   - WP Rocket (caching - si tienes presupuesto)
   - Wordfence Security (seguridad)
   - UpdraftPlus (backups)
   - Contact Form 7 (formularios)

5. **Activar Plugin Personalizado**
   - Plugins > Installed Plugins
   - Activa "VelocityCash Custom Functionality"

### PASO 5: Importar Datos Iniciales

1. **Importar SQL**
   - Ve a Site Tools > MySQL > phpMyAdmin
   - Selecciona base de datos `velocitycashai_db`
   - Click en "Import"
   - Sube el archivo `initial-data.sql`
   - Click "Go"

2. **Verificar Importación**
   - Ve a tu sitio WordPress
   - Deberías ver los 4 productos creados
   - Ve a Posts - deberías ver los posts de ejemplo

---

## ⚙️ Configuración Post-Instalación {#configuración}

### 1. Configurar WooCommerce

```bash
WooCommerce > Settings
```

**General:**
- Store Address: Tu dirección
- Selling Location: Vender a todos los países
- Currency: USD ($) o EUR (€) según prefieras

**Products:**
- Shop Page: Selecciona o crea página "Tienda"
- Add to cart behaviour: 
  - ✅ Redirect to cart page after successful addition
  - ✅ Enable AJAX add to cart buttons on archives

**Tax:**
- Enable taxes: Yes (si es necesario)
- Prices entered with tax: Excluding tax
- Calculate tax based on: Customer shipping address

**Shipping:**
- Configurar zonas de envío si vendes productos físicos
- Para productos digitales, deshabilitar shipping

**Payments:**
1. **Stripe:**
   - Enable Stripe
   - Get your keys from https://dashboard.stripe.com/apikeys
   - Test mode primero, luego live mode
   
2. **PayPal:**
   - Enable PayPal
   - Connect your PayPal Business account

### 2. Configurar VelocityCash Settings

```bash
Settings > VelocityCash (o VelocityCash > Configuración en el menú)
```

**n8n Webhook Base URL:**
```
https://jcm156.app.n8n.cloud/webhook/
```

**Mailchimp API Key:**
- Consigue tu API key en Mailchimp
- Mailchimp > Account > Extras > API keys
- Copia y pega aquí

**Facebook Pixel ID:**
- Ve a Facebook Business Manager
- Pixels > Tu pixel > Pixel ID
- Copia el número

**Google Analytics 4 ID:**
- Ve a Google Analytics
- Admin > Data Streams > Tu stream
- Measurement ID (formato: G-XXXXXXXXXX)

### 3. Configurar Permalinks

```bash
Settings > Permalinks
```
- Selecciona: "Post name"
- Click "Save Changes"

### 4. Configurar Reading Settings

```bash
Settings > Reading
```
- Your homepage displays: A static page
- Homepage: Selecciona "Inicio"
- Posts page: Selecciona o crea página "Blog"

---

## 🔗 Integración n8n {#n8n}

### Crear Webhooks en n8n

Necesitas crear los siguientes workflows en n8n:

1. **New Order Workflow**
   - Webhook URL: `https://jcm156.app.n8n.cloud/webhook/new-order`
   - Trigger: Webhook
   - Actions:
     - Send email to customer
     - Add to Google Sheets
     - Notify Slack channel
     - Add to CRM

2. **User Registration Workflow**
   - Webhook URL: `https://jcm156.app.n8n.cloud/webhook/user-registration`
   - Actions:
     - Send welcome email
     - Add to Mailchimp
     - Create user record in database

3. **Lead Magnet Workflow**
   - Webhook URL: `https://jcm156.app.n8n.cloud/webhook/lead-magnet`
   - Actions:
     - Send email with download link
     - Add to Mailchimp "Leads" list
     - Tag based on magnet type

4. **Cart Abandonment Workflow**
   - Webhook URL: `https://jcm156.app.n8n.cloud/webhook/cart-abandonment`
   - Actions:
     - Wait 1 hour
     - Send recovery email
     - Wait 24 hours
     - Send second recovery email

### Ejemplo de Workflow n8n (JSON)

```json
{
  "nodes": [
    {
      "parameters": {
        "httpMethod": "POST",
        "path": "new-order"
      },
      "name": "Webhook",
      "type": "n8n-nodes-base.webhook",
      "position": [250, 300]
    },
    {
      "parameters": {
        "operation": "append",
        "sheetId": "YOUR_SHEET_ID",
        "range": "Orders!A:E"
      },
      "name": "Google Sheets",
      "type": "n8n-nodes-base.googleSheets",
      "position": [450, 300]
    }
  ]
}
```

---

## 🛍️ Configuración de Productos {#productos}

### Editar Productos

Los 4 productos ya están importados, pero necesitas:

1. **Añadir Imágenes**
   - Ve a Products > All Products
   - Edita cada producto
   - Set product image (imagen principal)
   - Product gallery images (galería)

2. **Configurar Download Files** (para productos digitales)
   - En cada producto, ve a "Product data" > "General"
   - Check "Virtual" y "Downloadable"
   - Add files que el cliente recibirá

3. **Configurar Subscriptions** (para AI Inner Circle)
   - Instala "WooCommerce Subscriptions" plugin
   - En el producto AI Inner Circle:
     - Product data > Subscription
     - Subscription price: $97 / month
     - Sign-up fee: $0
     - Free trial: 7 days (opcional)

### Añadir Bonuses a Productos

Para mostrar el value stack:

```bash
Products > Edit Product > Scroll to Custom Fields
```

Añade bonuses así:
1. Custom Field: `_velocitycash_bonuses`
2. Value (formato JSON):
```json
[
  {"name": "50 Prompts Premium ChatGPT", "value": 297},
  {"name": "Templates n8n Workflows", "value": 497},
  {"name": "Directorio 100+ Herramientas IA", "value": 197},
  {"name": "Comunidad Privada Slack", "value": 497},
  {"name": "Updates y Soporte 1 Año", "value": 1200}
]
```

---

## 📈 SEO y Analytics {#seo}

### Configurar Rank Math SEO

1. **Setup Wizard**
   - Plugins > Rank Math > Setup Wizard
   - Conecta tu cuenta Google
   - Configura Search Console

2. **General Settings**
   - Titles & Meta > Homepage
   - Title: "VelocityCash AI - Genera Ingresos con Inteligencia Artificial"
   - Description: "Automatización y consultoría de IA para emprendedores. Aprende a usar ChatGPT, n8n y herramientas de IA para generar $10K+/mes"

3. **Focus Keywords**
   - Para cada post/página, añade focus keywords
   - Ejemplos: "automatización IA", "generar ingresos con IA", "ChatGPT para negocios"

### Configurar Google Analytics 4

1. **Añadir Tracking Code**
   - El código ya está en functions.php
   - Solo necesitas añadir tu GA4 ID en Settings > VelocityCash

2. **Configurar Goals**
   - En GA4, crea los siguientes eventos como conversiones:
     - purchase (compra)
     - lead_magnet (descarga)
     - button_click (clicks en CTAs)

---

## 🔧 Mantenimiento {#mantenimiento}

### Backups Automáticos

**UpdraftPlus:**
1. Settings > UpdraftPlus Backups
2. Settings tab:
   - Schedule: Daily
   - Retain: 30 days
   - Remote Storage: Google Drive (recomendado)
3. Save Changes

### Actualizaciones

**Frecuencia recomendada:**
- WordPress Core: Actualizar inmediatamente
- Plugins: Revisar semanalmente
- Theme: Revisar semanalmente

**Antes de actualizar:**
1. Hacer backup completo
2. Probar en staging si es posible
3. Actualizar uno a la vez

### Monitoreo

**Qué revisar semanalmente:**
- [ ] Uptime del sitio
- [ ] Velocidad de carga (< 2 segundos)
- [ ] Errores en logs
- [ ] Intentos de login fallidos (seguridad)
- [ ] Tasa de conversión de productos

**Herramientas:**
- GTmetrix: https://gtmetrix.com
- Google PageSpeed Insights
- Wordfence > Scan

---

## 🆘 Troubleshooting {#troubleshooting}

### Problema: Sitio muestra error 500

**Solución:**
1. Revisa error logs en SiteGround > Site Tools > Statistics > Error Log
2. Probablemente es un problema con wp-config.php o .htaccess
3. Reemplaza .htaccess con el default de WordPress
4. Verifica permisos de archivos (644 para archivos, 755 para carpetas)

### Problema: Productos no se muestran

**Solución:**
1. Ve a Products > All Products
2. Verifica que estén publicados ("Published")
3. Ve a WooCommerce > Settings > Products > Display
4. Asegúrate de que "Shop page" está seleccionada

### Problema: Pagos no funcionan

**Solución Stripe:**
1. Verifica que usas las keys correctas (test vs live)
2. Webhooks configurados en Stripe dashboard
3. SSL debe estar activo

**Solución PayPal:**
1. Verifica que PayPal account está en modo Business
2. Email de PayPal coincide con configuración

### Problema: Emails no llegan

**Solución:**
1. Instala plugin "WP Mail SMTP"
2. Configura con SendGrid o Mailgun (gratis hasta cierto límite)
3. Verifica que dominio tiene registros SPF y DKIM

### Problema: Sitio muy lento

**Solución:**
1. Instala WP Rocket (caching)
2. Optimiza imágenes (plugin Smush)
3. Activa CDN en SiteGround
4. Minimiza plugins innecesarios

---

## 📞 Soporte

### Recursos

- **Documentación WordPress:** https://wordpress.org/support/
- **WooCommerce Docs:** https://woocommerce.com/documentation/
- **n8n Docs:** https://docs.n8n.io/
- **Astra Theme:** https://wpastra.com/docs/

### Contacto Técnico

Si necesitas ayuda adicional:
- SiteGround Support: https://my.siteground.com/support/tickets
- WooCommerce Support: https://woocommerce.com/my-account/create-a-ticket/

---

## ✅ Checklist Final de Lanzamiento

Antes de hacer el sitio público:

- [ ] SSL certificado activo y forzado (https://)
- [ ] Todos los productos configurados con precios correctos
- [ ] Stripe y PayPal en modo LIVE (no test)
- [ ] Webhooks de n8n funcionando (hacer test)
- [ ] Google Analytics tracking code funcionando
- [ ] Facebook Pixel funcionando
- [ ] Emails de confirmación de pedido se envían correctamente
- [ ] Lead magnets funcionan y se entregan
- [ ] Formularios de contacto funcionan
- [ ] Sitio carga en < 2 segundos
- [ ] Responsive - se ve bien en móvil
- [ ] SEO básico configurado (titles, descriptions)
- [ ] Backup automático configurado
- [ ] Security plugins activos (Wordfence)
- [ ] Privacy Policy página creada
- [ ] Terms & Conditions página creada
- [ ] Cookie notice configurado (GDPR compliance)

---

## 🎉 ¡Listo para Lanzar!

Una vez completados todos los pasos, tu sitio estará:
- ✅ Optimizado para conversiones (framework Hormozi)
- ✅ Seguro y rápido
- ✅ Integrado con n8n para automatización
- ✅ Listo para procesar pagos
- ✅ Capturando leads automáticamente
- ✅ Tracking analytics

**Próximos pasos:**
1. Drive traffic (ads, SEO, social media)
2. Monitor analytics y conversiones
3. Iterar y optimizar basándose en datos
4. Escalar con más automatizaciones

---

**Versión:** 1.0.0  
**Última actualización:** 2025  
**Autor:** VelocityCash Team
