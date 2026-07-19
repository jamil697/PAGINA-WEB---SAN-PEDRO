# Plataforma Web Iglesia San Pedro de Huánuco — Estructura Laravel 11

Este paquete contiene los ARCHIVOS QUE DEBES COPIAR dentro de un proyecto Laravel 11
ya creado con `composer create-project laravel/laravel iglesia-san-pedro`.
No es un proyecto completo (no incluye vendor/, .env, ni el core de Laravel),
sino el scaffold específico de tu servicio social, listo para pegar.

## Árbol de archivos generado

```
app/
├── Models/
│   ├── BienCultural.php
│   ├── Misa.php
│   ├── IntencionMisa.php
│   └── AvisoParroquial.php
│
└── Http/Controllers/
    ├── PublicController.php              # Home, Escaneo, vistas públicas
    ├── IntencionController.php           # Formulario público de intenciones de misa
    ├── Api/
    │   └── BienCulturalController.php    # Endpoint consumido por el JS de la cámara
    └── Admin/
        ├── BienCulturalAdminController.php
        ├── MisaController.php
        ├── AvisoParroquialController.php
        └── IntencionAdminController.php

database/migrations/
├── 2026_01_01_000001_create_misas_table.php
├── 2026_01_01_000002_create_intenciones_misa_table.php
├── 2026_01_01_000003_create_avisos_parroquiales_table.php
├── 2026_01_01_000004_create_bienes_culturales_table.php
└── 2026_01_01_000005_add_rol_to_users_table.php

routes/
├── web.php     (fragmento a fusionar con el tuyo)
└── api.php     (fragmento a fusionar con el tuyo)

resources/views/
├── layouts/
│   ├── app.blade.php        # layout público (Bootstrap 5)
│   └── admin.blade.php      # layout del dashboard
├── public/
│   ├── home.blade.php
│   ├── escaneo.blade.php    # ★ vista de reconocimiento con cámara + TF.js
│   └── intenciones.blade.php
└── admin/
    └── bienes/
        ├── index.blade.php
        └── form.blade.php   # parcial reutilizado en create/edit

public/js/
└── escaneo.js                # ★ lógica de cámara + TensorFlow.js + fetch a la API
```

## Pasos para integrar

1. Crea el proyecto base:
   ```bash
   composer create-project laravel/laravel iglesia-san-pedro
   cd iglesia-san-pedro
   composer require laravel/breeze --dev
   php artisan breeze:install blade   # para tener auth de admin ya lista
   ```
2. Copia todas las carpetas de este paquete (`app/`, `database/`, `resources/`, `public/`)
   encima de tu proyecto, respetando las rutas (sobrescribe donde ya exista `RouteServiceProvider`
   default, pero conserva los archivos generados por Breeze).
3. Fusiona el contenido de `routes/web.php` y `routes/api.php` de este paquete con los tuyos
   (agrega los bloques al final, no reemplaces el archivo completo).
4. Configura tu `.env` con la base de datos MySQL y ejecuta:
   ```bash
   php artisan migrate
   php artisan storage:link
   ```
5. Entrena tu modelo de visión artificial en **Teachable Machine**
   (https://teachablemachine.withgoogle.com/ → "Image Project") usando las fotos de tus
   santos/lienzos (la actividad "Captura Multimedia y Construcción del Dataset de IA" de tu
   cronograma). Exporta como **TensorFlow.js** y copia la carpeta resultante
   (`model.json`, `weights.bin`, `metadata.json`) a `public/modelo-ia/`.
6. Cada `className` que definas en Teachable Machine (ej. "san_pedro_lienzo_01") debe coincidir
   exactamente con el campo `label_ia` que registres en el CRUD de "Bienes Culturales" del
   panel admin. Esa es la llave que conecta la IA (frontend) con la base de datos (backend).

## Por qué esta arquitectura y no una réplica de tu proyecto Django+face_recognition

- `face_recognition` reconoce **rostros humanos**; aquí necesitas reconocer **objetos estáticos**
  (esculturas, lienzos), así que el problema es de clasificación de imágenes, no de biometría facial.
- TensorFlow.js corre la inferencia en el celular del visitante (no en tu servidor), así que
  no necesitas GPU en el hosting ni un microservicio Python aparte — ideal para un hosting
  compartido económico, que es lo que normalmente puede sostener una parroquia a largo plazo.
- Laravel se encarga solo de lo que hace bien: CRUD, autenticación, base de datos y servir
  archivos estáticos (incluido el modelo entrenado).
