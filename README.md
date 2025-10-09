# 🧠 Tareas API — Backend con Laravel 12 + Sanctum

API REST construida con **Laravel 12**, **MySQL/MariaDB** y autenticación mediante **Laravel Sanctum**.
Permite a cada usuario gestionar sus propias tareas con etiquetas (tags), aplicar filtros, paginación, orden y control de acceso seguro.

---

## ⚙️ Tecnologías principales

| Componente                                      | Versión recomendada    |
| ----------------------------------------------- | ---------------------- |
| PHP                                             | ≥ 8.2                  |
| Laravel                                         | 12.x                   |
| MySQL / MariaDB                                 | 8.x / 10.4+            |
| Composer                                        | ≥ 2.x                  |
| Node.js *(opcional, solo si usas Vue frontend)* | ≥ 18                   |
| Postman / cURL                                  | Para pruebas de la API |

---

## 🚀 Instalación local

### 1️⃣ Clonar el repositorio

```bash
git clone https://github.com/tu-usuario/tareas-api.git
cd tareas-api
```

### 2️⃣ Instalar dependencias PHP

```bash
composer install
```

### 3️⃣ Crear archivo de entorno

```bash
cp .env.example .env
```

Luego edita el archivo `.env` y configura tu base de datos:

```env
APP_NAME="Tareas API"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tareas_api
DB_USERNAME=root
DB_PASSWORD=

SANCTUM_STATEFUL_DOMAINS=localhost:5173
SESSION_DOMAIN=localhost
```

---

### 4️⃣ Generar clave de aplicación

```bash
php artisan key:generate
```

---

### 5️⃣ Ejecutar migraciones y seeders

Este paso creará todas las tablas necesarias y cargará datos de ejemplo (usuario demo, tareas y tags):

```bash
php artisan migrate --seed
```

Usuario de prueba:

```
Email: demo@example.com
Password: password
```

---

### 6️⃣ Iniciar el servidor de desarrollo

```bash
php artisan serve
```

📍 El servidor quedará disponible en:
**[http://127.0.0.1:8000](http://127.0.0.1:8000)**

---

## 🔐 Autenticación

La API usa **Laravel Sanctum** con **tokens personales** (tipo `Bearer <token>`).
Para usar los endpoints protegidos, primero debes autenticarte y obtener un token.

---

## 📡 Endpoints principales

### 🩺 Health Check

```
GET /api/health
```

Verifica que el servidor esté operativo y respondiendo correctamente.

---

### 🔑 Autenticación

| Método | Ruta                 | Descripción                                 |
| ------ | -------------------- | ------------------------------------------- |
| `POST` | `/api/auth/register` | Registro de nuevo usuario                   |
| `POST` | `/api/auth/login`    | Inicio de sesión y obtención de token       |
| `GET`  | `/api/auth/me`       | Obtener información del usuario autenticado |
| `POST` | `/api/auth/logout`   | Cerrar sesión (revocar token)               |

---

### ✅ Tareas

| Método          | Ruta                     | Descripción                                        |
| --------------- | ------------------------ | -------------------------------------------------- |
| `GET`           | `/api/tasks`             | Listar tareas del usuario con filtros y paginación |
| `POST`          | `/api/tasks`             | Crear nueva tarea con etiquetas                    |
| `GET`           | `/api/tasks/{id}`        | Ver detalle de una tarea específica                |
| `PUT` / `PATCH` | `/api/tasks/{id}`        | Actualizar tarea (total o parcial)                 |
| `DELETE`        | `/api/tasks/{id}`        | Eliminar tarea                                     |
| `POST`          | `/api/tasks/{id}/toggle` | Cambiar estado completada ⇄ pendiente              |

---

### 🏷️ Tags

| Método | Ruta        | Descripción                 |
| ------ | ----------- | --------------------------- |
| `GET`  | `/api/tags` | Listar etiquetas existentes |
| `POST` | `/api/tags` | Crear una nueva etiqueta    |

---

## 🤪 Pruebas con Postman

1. Importa la colección incluida: **`Tareas_API.postman_collection.json`**
2. Actualiza la variable `{{baseUrl}}` con tu URL local:

   ```
   http://127.0.0.1:8000/api
   ```
3. Regístrate o inicia sesión para obtener tu token.
4. Añade el token en los headers de tus peticiones:

   ```
   Authorization: Bearer <tu_token>
   ```
5. Prueba los endpoints de **tareas** y **etiquetas**.
   Todos los resultados se devolverán en formato JSON.

---

## 💡 Consejos

* Si ves un error `CORS`, revisa `config/cors.php` y ejecuta:

  ```bash
  php artisan optimize:clear
  ```
* Puedes ver los datos en **phpMyAdmin**, **DBeaver** o el cliente MySQL de tu preferencia.
* La estructura del proyecto sigue el estándar de Laravel (controladores, modelos, resources y form requests).

---



