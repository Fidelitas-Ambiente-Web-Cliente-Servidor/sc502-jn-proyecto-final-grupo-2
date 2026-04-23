# 🏨 Hotel Resort Aurora
**SC-502 Ambiente Web Cliente Servidor — Grupo 2**

Sistema web de reservaciones para el Hotel Resort Aurora.

---

## 🚀 Cómo correr el proyecto

### Requisitos
- Docker Desktop instalado y corriendo

### Pasos

```bash
# 1. Clonar o descomprimir el proyecto
cd hotel-aurora

# 2. Levantar todos los servicios
docker-compose up --build

# 3. Abrir el navegador
# Sitio web:   http://localhost:8080
# phpMyAdmin:  http://localhost:8081
```

### Detener el proyecto
```bash
docker-compose down
```

### Reiniciar con BD limpia
```bash
docker-compose down -v   # borra el volumen de la BD
docker-compose up --build
```

---

## 👥 Credenciales por defecto

| Rol | Correo | Contraseña |
|-----|--------|------------|
| Admin | admin@hotelaurora.com | admin123 |

*(Los clientes se registran desde el sitio)*

---

## 📁 Estructura del proyecto

```
hotel-aurora/
├── index.php              # Página principal
├── habitaciones.php       # Catálogo de habitaciones
├── reservacion.php        # Formulario de reserva
├── mis_reservas.php       # Reservas del cliente
├── login.php              # Inicio de sesión
├── registro.php           # Registro de usuarios
├── admin/
│   ├── dashboard.php      # Panel admin
│   ├── reservas.php       # Gestión de reservas
│   ├── habitaciones.php   # CRUD de habitaciones
│   └── confirmar_reserva.php
├── php/
│   ├── conexion.php       # Conexión PDO a MySQL
│   ├── sesion.php         # Helpers de sesión
│   ├── login_handler.php
│   ├── registro_handler.php
│   ├── reserva_handler.php
│   ├── cancelar_reserva.php
│   └── logout.php
├── css/
│   └── estilo.css         # Estilos globales
├── js/
│   └── reservacion.js     # Cálculo dinámico de precio
├── img/                   # Imágenes del hotel
├── database.sql           # Esquema + datos iniciales
├── Dockerfile
├── docker-compose.yml
└── .htaccess
```

---

## 🗄️ Diagrama relacional

```
usuarios (id, nombre, email, password, rol, fecha_registro)
    │
    └──< reservas (id, usuario_id, habitacion_id, fecha_entrada,
                   fecha_salida, cantidad_personas, precio_total,
                   estado, fecha_reserva)
                        │
habitaciones (id, codigo, tipo, capacidad, precio_noche,
              descripcion, servicios, imagen, estado) >──┘
```

---

## ⚙️ Tecnologías usadas

- **Frontend:** HTML5, CSS3, JavaScript (ES6)
- **Backend:** PHP 8.2
- **Base de datos:** MySQL 8.0
- **Servidor:** Apache 2 (via Docker)
- **Contenedores:** Docker + Docker Compose
