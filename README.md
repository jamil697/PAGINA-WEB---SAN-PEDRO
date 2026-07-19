# Plataforma Web Institucional — Iglesia San Pedro de Huánuco

Proyecto de **Servicio Social Universitario** — Universidad de Huánuco (UDH)
Facultad de Ingeniería · Programa Académico de Ingeniería de Sistemas e Informática

Plataforma web institucional con un **módulo interactivo de visión artificial** que
permite a los visitantes escanear con la cámara de su celular los bienes culturales
del templo (santos, lienzos, reliquias) y recibir al instante su información
histórica e iconográfica.

---

## Integrantes

| Nombre | Código |
|---|---|
| Adamaris Valentina Espinoza Castañeda | 2022110493 |
| Juan Jose Fernandez Ramos | — |
| Jamil Estrada Rivera | — |

**Institución:** Iglesia San Pedro de Huánuco
**Responsable institucional:** Sergio Agustoni
**Docente supervisor:** Ethel Jhovani Manzano Lozano
**Línea de acción:** Innovación y Tecnología
**Periodo:** 4 meses (mayo – octubre 2026)

---

## ¿De qué trata el proyecto?

El sistema tiene dos partes:

1. **Portal informativo** (historia, misión, visión, horarios de misa, avisos
   parroquiales, formulario de intenciones de misa).
2. **Módulo de escaneo con IA**: el usuario activa la cámara trasera de su celular
   desde el navegador, un modelo de visión artificial reconoce el bien cultural
   enfocado y la plataforma muestra su ficha histórica sin recargar la página.

La parte de "IA" **no es reconocimiento facial** (no usamos `face_recognition`/dlib).
Es **clasificación de imágenes** de objetos estáticos, entrenada con Teachable
Machine y ejecutada en el navegador con TensorFlow.js. Así no necesitamos servidor
con GPU ni instalar Python — todo corre del lado del cliente.

---

## Stack tecnológico

| Capa | Tecnología |
|---|---|
| Backend | Laravel 11 (PHP 8.2+) |
| Base de datos | MySQL |
| Frontend | Blade + Bootstrap 5 + JavaScript Vanilla |
| Autenticación admin | Laravel Breeze |
| Visión artificial | TensorFlow.js + Teachable Machine (Image Project) |
| Control de versiones | Git + GitHub |

---

## Requisitos previos (instalar antes de clonar)

Cada integrante debe tener instalado en su laptop:

- **PHP 8.2 o superior** → verificar con `php -v`
- **Composer** (gestor de dependencias de PHP) → https://getcomposer.org
- **Node.js 18+ y npm** → verificar con `node -v` y `npm -v`
- **MySQL** (o MariaDB) — se recomienda instalar **Laragon** (Windows) o
  **XAMPP/Herd** (Windows/Mac) para tener PHP + MySQL + Composer listos de una vez
- **Git** → https://git-scm.com
- Editor recomendado: **VS Code** con las extensiones "PHP Intelephense" y
  "Laravel Blade Snippets"

> 💡 Si usan Laragon, ya viene con PHP, Composer y MySQL preconfigurados, es la
> forma más rápida de empezar en Windows.

---

## Instalación (primera vez)

```bash
# 1. Clonar el repositorio
git clone <URL_DEL_REPOSITORIO>
cd iglesia-san-pedro

# 2. Instalar dependencias de PHP
composer install

# 3. Instalar dependencias de JS (Bootstrap, Breeze assets, etc.)
npm install

# 4. Crear el archivo de entorno y la key de la app
cp .env.example .env
php artisan key:generate

# 5. Configurar la base de datos en .env
#    DB_DATABASE=iglesia_san_pedro
#    DB_USERNAME=root
#    DB_PASSWORD=

# 6. Crear la base de datos "iglesia_san_pedro" en MySQL (Workbench, phpMyAdmin, o consola)

# 7. Ejecutar migraciones
php artisan migrate

# 8. Enlazar el storage público (para las imágenes subidas desde el admin)
php artisan storage:link

# 9. Compilar los assets del frontend
npm run dev

# 10. Levantar el servidor local
php artisan serve
```

La aplicación quedará disponible en `http://127.0.0.1:8000`.

---

## Estructura general del proyecto

```
app/
├── Models/                     # BienCultural, Misa, IntencionMisa, AvisoParroquial
└── Http/Controllers/
    ├── PublicController.php    # Home, vista de escaneo
    ├── IntencionController.php # Formulario público de intenciones
    ├── Api/                    # Endpoint consumido por el JS de la cámara
    └── Admin/                  # CRUD del panel administrativo

database/migrations/            # Definición de tablas (misas, bienes_culturales, etc.)

resources/views/
├── layouts/                    # Layout público y layout admin
├── public/                     # Vistas del portal (home, escaneo, intenciones)
└── admin/                      # Vistas del panel administrativo

public/js/escaneo.js            # Cámara + TensorFlow.js + fetch a la API
public/modelo-ia/               # Aquí va el modelo exportado de Teachable Machine
                                 # (model.json, weights.bin, metadata.json)
```

---

## Flujo de trabajo en Git

Para evitar pisarnos el código entre los tres, seguimos este flujo:

1. **Nunca trabajar directo en `main`.**
2. Antes de empezar algo nuevo:
   ```bash
   git checkout main
   git pull origin main
   git checkout -b nombre-de-la-rama
   ```
   Ejemplo de nombres de rama: `feature/crud-bienes-culturales`, `feature/escaneo-camara`, `fix/formulario-intenciones`.
3. Al terminar:
   ```bash
   git add .
   git commit -m "feat: descripción corta de lo que hiciste"
   git push origin nombre-de-la-rama
   ```
4. Abrir un **Pull Request** en GitHub hacia `main` para que el resto revise antes de fusionar.
5. Después de que se fusione, actualizar la rama local:
   ```bash
   git checkout main
   git pull origin main
   ```

**No subir al repositorio:** `.env`, `/vendor`, `/node_modules`, `/storage/framework`
(ya deben estar en `.gitignore` por defecto de Laravel).

---

## Módulo de IA — nota importante para quien trabaje en el escaneo

- El modelo se entrena en https://teachablemachine.withgoogle.com (proyecto de tipo
  "Image Project"), subiendo fotos de cada santo/lienzo desde varios ángulos y con
  distinta iluminación.
- El nombre de clase que le pongan en Teachable Machine (ej. `san_pedro_lienzo_01`)
  debe registrarse **exactamente igual** en el campo `label_ia` al crear el bien
  cultural desde el panel admin. Esa es la llave que conecta la cámara con la
  base de datos.
- El modelo exportado (`model.json`, `weights.bin`, `metadata.json`) se coloca en
  `public/modelo-ia/`, no se sube pesado al repo si es muy grande — mejor coordinar
  cómo lo compartimos (Drive, o Git LFS si el equipo lo prefiere).

---

## Contacto / dudas

Coordinar avances y dudas técnicas por el grupo del equipo. Cualquier cambio grande
de estructura (nuevas tablas, nuevas rutas) avisar antes en el chat para no duplicar
trabajo.
