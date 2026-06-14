# SERPOST Envío Fácil

Aplicación web **mobile-first** para la **pre-admisión digital de envíos postales internacionales**. Permite que el cliente registre su envío desde el celular mediante un asistente guiado, verifique si su contenido se puede enviar, genere los formularios aduaneros oficiales (CN22 / CN23 EMS / CP72) y el rótulo de la encomienda, y obtenga un código QR para agilizar la atención en ventanilla. El personal de ventanilla cuenta con un panel para revisar, editar y dar seguimiento a cada solicitud.

El objetivo es reducir el tiempo de atención en ventanilla evitando la re-digitación de datos y los errores en los formularios.

## Características

### Cliente
- **Asistente de envío paso a paso**: producto/contenido, destino, remitente, servicio y pago, revisión y confirmación.
- **Verificador "¿Puedo enviar esto?"**: clasifica el contenido (permitido / restringido / prohibido) y sugiere la entidad reguladora y el documento requerido (p. ej. DIGEMID, SENASA, SERFOR).
- **Formularios aduaneros en PDF**: genera CN22, CN23 (EMS) y CP72 listos para imprimir, fieles a los formatos oficiales.
- **Rótulo imprimible** de la encomienda (remitente / destinatario / código).
- **Código QR** con los datos del envío para lectura en ventanilla.
- **Seguimiento** del estado del envío y carga de documentos de soporte (certificados, licencias, facturas).
- **Historial de envíos** con búsqueda y opción de repetir un envío anterior.
- Autocompletado de datos por número de documento (consulta RENIEC simulada, lista para integración real).
- Correo de bienvenida al registrarse y notificaciones de cambio de estado.

### Ventanilla / Administración
- **Bandeja de solicitudes** con filtros por estado y búsqueda.
- **Detalle y edición** completa del envío, con reclasificación del tipo de formulario.
- **Línea de tiempo de estados** (pre-admisión → admitido → clasificación → tránsito → aduana → distribución → entregado).
- **Gestión documental** privada (subida y descarga de adjuntos).
- **Métricas de uso** anónimas (panel de analítica para administradores).

## Stack

- **Backend**: Laravel 12 (PHP 8.4)
- **Frontend**: Vue 3 + Inertia.js + Pinia
- **Estilos**: Tailwind CSS 3
- **Build**: Vite 7
- **Base de datos**: SQLite
- **PDF / QR**: dompdf, FPDI, TCPDF, qrcode.vue
- **Mapas**: Leaflet + OpenStreetMap
- **Correo**: SMTP / Resend (configurable)
- **Servidor**: Nginx + PHP-FPM (con OPcache)
- **Contenedores**: Docker + Docker Compose

## Requisitos

- [Docker](https://www.docker.com/) y Docker Compose.

> No se necesita PHP ni Node instalados en el host: todo corre en contenedores.

## Puesta en marcha (Docker)

```bash
# 1. Variables de entorno
cp .env.example .env

# 2. Dependencias PHP (vendor/)
docker compose run --rm --no-deps app composer install

# 3. Dependencias y compilación de assets (node_modules/ + public/build/)
docker compose run --rm vite npm install --legacy-peer-deps
docker compose run --rm vite npm run build

# 4. Levantar la aplicación (Nginx + PHP-FPM)
docker compose up -d app nginx

# 5. Clave de aplicación
docker compose exec app php artisan key:generate

# 6. Base de datos + datos de demostración
docker compose exec app sh -c "touch database/database.sqlite"
docker compose exec app php artisan migrate --seed

# 7. Cachear configuración/rutas/vistas
docker compose exec app php artisan optimize
```

La aplicación queda disponible en **http://localhost:8000**.

> Para desarrollo con recarga en caliente (HMR), levanta también el servicio de Vite:
> `docker compose up -d vite` (sirve en el puerto `5173`). Si editas código PHP,
> reinicia el contenedor de la app: `docker compose restart app`.

### Usuarios de demostración

Tras ejecutar el seeder, puedes ingresar con (contraseña: `demo1234`):

| Rol         | Correo                |
|-------------|-----------------------|
| Administrador | `admin@demo.pe`     |
| Ventanilla    | `ventanilla@demo.pe`|
| Cliente       | `cliente@demo.pe`   |

## Estructura del proyecto

```
app/            Lógica de la aplicación (controladores, modelos, servicios, enums, mailables)
config/         Configuración de Laravel
database/       Migraciones, seeders y factories
resources/js/   Frontend Vue 3 (páginas, componentes, stores, composables)
resources/views/Plantillas Blade (formularios PDF, rótulo, correos)
routes/         Definición de rutas (web y api)
public/         Punto de entrada y assets compilados
docker/         Configuración de PHP-FPM y Nginx
tests/          Pruebas
```

## Notas

- La consulta de identidad (RENIEC) está **simulada** de forma determinística; el código está preparado para conectar el servicio real.
- El catálogo de productos restringidos/prohibidos es **local y curado**; no consume una API externa.
- El correo usa por defecto el driver `log` (se escribe en `storage/logs/laravel.log`). Para envío real, configura SMTP o Resend en `.env`.

## Licencia

Distribuido bajo licencia [MIT](LICENSE).
