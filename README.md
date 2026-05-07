# CRM Comercial Ps-pool — Laravel

Migración del módulo comercial PHP a **Laravel 10** con diseño totalmente responsive usando **Tailwind CSS**.

---

## Requisitos

| Software      | Versión mínima |
|---------------|----------------|
| PHP           | 8.1+           |
| Composer      | 2.x            |
| Extensiones PHP | `curl`, `mbstring`, `openssl`, `pdo`, `tokenizer`, `xml`, `json` |

> **Sin base de datos propia** — toda la lógica de datos se delega a la API GesPlane existente (`RUTA_API`).

---

## Instalación rápida

```bash
# 1. Instalar dependencias
composer install

# 2. Copiar configuración
cp .env.example .env

# 3. Generar clave de aplicación
php artisan key:generate

# 4. Editar .env — configurar la URL de la API
nano .env
# → CRM_API_URL=http://pspool.dyndns.org:10445/ApiGesplanet_TC/public/api/

# 5. Crear enlace simbólico de storage (si se usan imágenes)
php artisan storage:link

# 6. Limpiar caché (en producción)
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Arrancar servidor de desarrollo
php artisan serve
# → http://localhost:8000
```

---

## Estructura del proyecto

```
crm-pspool-laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/          ← Un controller por sección del CRM
│   │   │   ├── AuthController.php
│   │   │   ├── AgendaController.php
│   │   │   ├── ClientesController.php
│   │   │   ├── ArticulosController.php
│   │   │   ├── PresupuestosController.php
│   │   │   ├── ExpedientesController.php
│   │   │   ├── GastosController.php
│   │   │   ├── PedidosController.php
│   │   │   ├── ListadosController.php
│   │   │   ├── ObjetivosController.php
│   │   │   ├── IncidenciasController.php
│   │   │   └── RrhhController.php
│   │   └── Middleware/
│   │       └── CheckComercialAuth.php   ← Protege rutas con sesión
│   └── Services/
│       └── ApiService.php              ← TODA la comunicación con la API
├── config/
│   └── crm.php                         ← Config específica del CRM
├── public/
│   ├── css/app.css                     ← Estilos custom (complementa Tailwind)
│   ├── js/jquery.min.js
│   └── jpg/                            ← Logos e imágenes
├── resources/views/
│   ├── layouts/app.blade.php           ← Layout principal (sidebar + topbar)
│   ├── home.blade.php                  ← Login page
│   ├── partials/
│   │   └── icon.blade.php              ← SVG icons inline
│   ├── agenda/
│   ├── clientes/
│   ├── articulos/
│   ├── presupuestos/
│   ├── expedientes/
│   ├── gastos/
│   ├── pedidos/
│   ├── listados/
│   ├── objetivos/
│   ├── incidencias/
│   └── rrhh/
└── routes/web.php                      ← Todas las rutas declaradas
```

---

## Arquitectura

### Patrón AJAX (heredado y mejorado)
El CRM carga sub-vistas mediante peticiones AJAX (`$.get()`) para evitar recargas de página completa, igual que el sistema original. La diferencia es que ahora:

- Los **controladores Laravel** reciben los filtros, llaman a la API y devuelven vistas Blade parciales
- Las **rutas `/*/list`** devuelven solo el fragmento HTML con la tabla de resultados
- Las **rutas `/*/index`** devuelven la página completa con el layout

### Autenticación
- **Comercial**: `POST /auth/login` → guarda `comercial_id`, `comercial_nombre` en sesión
- **RRHH**: `POST /auth/login-rrhh` → guarda `rrhh_id`, `rrhh_nombre` en sesión
- Middleware `CheckComercialAuth` protege todas las rutas bajo el grupo `auth.comercial`

### ApiService
Todos los métodos de `peticionApi()`, `peticionApiPost()` y `peticionApiDelete()` originales están encapsulados en `App\Services\ApiService` usando `Illuminate\Support\Facades\Http` (Guzzle).

---

## Diseño responsive

| Breakpoint | Comportamiento |
|------------|----------------|
| `< 768px`  | Sidebar oculto, hamburger button visible, tablas con scroll horizontal |
| `768–1023px` | Sidebar drawer deslizante (toggle hamburger) |
| `≥ 1024px` | Sidebar fija, contenido ocupa el espacio restante |

**Tecnologías de UI:**
- [Tailwind CSS](https://tailwindcss.com/) via CDN (sin build step)
- `public/css/app.css` — estilos custom para tablas, botones, badges, spinner
- Google Fonts: `Outfit` (headings) + `DM Sans` (body)
- jQuery 1.12 (heredado, para compatibilidad con plugins existentes)

---

## Despliegue en producción

```bash
# Apache — añadir en httpd.conf o .htaccess
DocumentRoot /var/www/crm-pspool/public

# Nginx — config básica
server {
    listen 80;
    root /var/www/crm-pspool/public;
    index index.php;
    location / { try_files $uri $uri/ /index.php?$query_string; }
    location ~ \.php$ { fastcgi_pass unix:/var/run/php/php8.1-fpm.sock; include fastcgi_params; }
}

# Permisos
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## Migrar las páginas de `mantenimientos/`

Las páginas de la carpeta `mantenimientos/` (M_agenda, M_contactos, M_gastos, etc.) **no están incluidas** en esta primera migración. Para migrarlas:

1. Crear un controller en `app/Http/Controllers/Mantenimientos/`
2. Añadir las rutas en `routes/web.php` bajo un prefijo `/admin`
3. Crear las vistas Blade en `resources/views/mantenimientos/`
4. Reemplazar las llamadas directas a la BD Firebird por llamadas a `ApiService`

---

## Créditos

By [info-ges.com](https://www.info-ges.com)
