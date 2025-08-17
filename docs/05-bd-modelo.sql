-- 05. Modelo de Base de Datos (MySQL/MariaDB)
-- Crear BD (ajusta collation según necesidad)
CREATE DATABASE IF NOT EXISTS sistema_prestamos
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_general_ci;

USE sistema_prestamos;

-- Usuarios
CREATE TABLE IF NOT EXISTS usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  rol ENUM('admin','cobranzas') DEFAULT 'cobranzas',
  activo TINYINT(1) DEFAULT 1,
  creado_en DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Clientes
CREATE TABLE IF NOT EXISTS clientes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cedula VARCHAR(20) UNIQUE,
  nombre VARCHAR(150) NOT NULL,
  telefono VARCHAR(30),
  direccion VARCHAR(200),
  email VARCHAR(120),
  creado_en DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Préstamos
CREATE TABLE IF NOT EXISTS prestamos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cliente_id INT NOT NULL,
  monto DECIMAL(12,2) NOT NULL,
  tasa_mensual DECIMAL(6,4) NOT NULL, -- ej. 0.015 para 1.5% mensual
  plazo_meses INT NOT NULL,
  fecha_inicio DATE NOT NULL,
  estado ENUM('activo','cancelado','mora') DEFAULT 'activo',
  observaciones TEXT,
  creado_en DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (cliente_id) REFERENCES clientes(id)
);

-- Cuotas
CREATE TABLE IF NOT EXISTS cuotas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  prestamo_id INT NOT NULL,
  numero INT NOT NULL,
  fecha_vencimiento DATE NOT NULL,
  capital DECIMAL(12,2) NOT NULL,
  interes DECIMAL(12,2) NOT NULL,
  mora DECIMAL(12,2) DEFAULT 0,
  total DECIMAL(12,2) NOT NULL,
  pagada TINYINT(1) DEFAULT 0,
  pagada_en DATETIME NULL,
  FOREIGN KEY (prestamo_id) REFERENCES prestamos(id)
);

-- Pagos
CREATE TABLE IF NOT EXISTS pagos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  prestamo_id INT NOT NULL,
  cuota_id INT NULL,
  monto DECIMAL(12,2) NOT NULL,
  metodo VARCHAR(30) DEFAULT 'efectivo',
  referencia VARCHAR(60),
  realizado_por INT NULL, -- usuario
  creado_en DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (prestamo_id) REFERENCES prestamos(id),
  FOREIGN KEY (cuota_id) REFERENCES cuotas(id),
  FOREIGN KEY (realizado_por) REFERENCES usuarios(id)
);

-- Bitácora
CREATE TABLE IF NOT EXISTS bitacora (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  usuario_id INT NULL,
  accion VARCHAR(60) NOT NULL,  -- crear_cliente, editar_prestamo, pagar_cuota, etc.
  entidad VARCHAR(40) NOT NULL, -- clientes, prestamos, cuotas, pagos
  entidad_id BIGINT NULL,
  datos JSON NULL,
  ip VARCHAR(45),
  creado_en DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);
