

CREATE DATABASE IF NOT EXISTS hotel_aurora CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE hotel_aurora;

-- Tabla de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('cliente', 'admin') DEFAULT 'cliente',
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabla de habitaciones
CREATE TABLE IF NOT EXISTS habitaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(10) NOT NULL UNIQUE,
    tipo ENUM('Estandar', 'Deluxe', 'Suite') NOT NULL,
    capacidad INT NOT NULL,
    precio_noche DECIMAL(10,2) NOT NULL,
    descripcion TEXT,
    servicios TEXT,
    imagen VARCHAR(100),
    estado ENUM('disponible', 'ocupada', 'mantenimiento') DEFAULT 'disponible'
);

-- Tabla de reservas
CREATE TABLE IF NOT EXISTS reservas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    habitacion_id INT NOT NULL,
    fecha_entrada DATE NOT NULL,
    fecha_salida DATE NOT NULL,
    cantidad_personas INT NOT NULL,
    precio_total DECIMAL(10,2) NOT NULL,
    estado ENUM('pendiente', 'confirmada', 'cancelada') DEFAULT 'pendiente',
    fecha_reserva DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (habitacion_id) REFERENCES habitaciones(id) ON DELETE CASCADE
);

-- Datos iniciales

-- Admin por defecto (password: admin123)
INSERT INTO usuarios (nombre, email, password, rol) VALUES
('Administrador', 'admin@hotelaurora.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Habitaciones
INSERT INTO habitaciones (codigo, tipo, capacidad, precio_noche, descripcion, servicios, imagen, estado) VALUES
('H-01', 'Estándar', 2, 45000.00, 'Habitación cómoda con vista, ideal para viaje en pareja o trabajo.', 'WiFi,Aire acondicionado,TV', 'Estandar.jpg', 'disponible'),
('H-02', 'Estándar', 2, 45000.00, 'Habitación cómoda con vista, ideal para viaje en pareja o trabajo.', 'WiFi,Aire acondicionado,TV', 'Estandar.jpg', 'disponible'),
('H-03', 'Deluxe', 3, 65000.00, 'Habitación cómoda con vista al jardín, ideal para viajes en familia o trabajo.', 'WiFi,Desayuno,Aire acondicionado,TV', 'Deluxe.jpg', 'disponible'),
('H-04', 'Deluxe', 3, 65000.00, 'Habitación cómoda con vista al jardín, ideal para viajes en familia o trabajo.', 'WiFi,Desayuno,Aire acondicionado,TV', 'Deluxe.jpg', 'disponible'),
('H-05', 'Suite', 4, 90000.00, 'Habitación de lujo con vista panorámica, gran espacio ideal para viajes en familia.', 'WiFi,Desayuno,Aire acondicionado,TV,Jacuzzi,Minibar', 'Suite.jpg', 'disponible'),
('H-06', 'Suite', 4, 90000.00, 'Habitación de lujo con vista panorámica, gran espacio ideal para viajes en familia.', 'WiFi,Desayuno,Aire acondicionado,TV,Jacuzzi,Minibar', 'Suite.jpg', 'disponible');
