-- PostgreSQL schema para Sistema de Préstamos multiempresa
-- Incluye: ENUMs, función utilitaria, tablas, triggers, vistas y datos de ejemplo

-- ============================
-- ENUMS
-- ============================
DO $$ BEGIN
    CREATE TYPE rol_usuario AS ENUM ('admin','oficial','cajero');
EXCEPTION WHEN duplicate_object THEN null; END $$;

DO $$ BEGIN
    CREATE TYPE estado_prestamo AS ENUM ('pendiente','activo','atrasado','pagado','cancelado');
EXCEPTION WHEN duplicate_object THEN null; END $$;

-- ============================
-- FUNCIONES
-- ============================
-- Redondeo tipo banco
CREATE OR REPLACE FUNCTION round_banker(val numeric, decs int) RETURNS numeric AS $$
BEGIN
  RETURN ROUND(val::numeric, decs);
END; $$ LANGUAGE plpgsql;

-- Trigger updated_at
CREATE OR REPLACE FUNCTION set_updated_at() RETURNS TRIGGER AS $$
BEGIN
  NEW.updated_at = NOW();
  RETURN NEW;
END; $$ LANGUAGE plpgsql;

-- ============================
-- TABLAS PRINCIPALES
-- ============================
CREATE TABLE IF NOT EXISTS empresas (
    id BIGSERIAL PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    rnc VARCHAR(20) UNIQUE,
    direccion TEXT,
    telefono VARCHAR(20),
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS usuarios (
    id BIGSERIAL PRIMARY KEY,
    empresa_id BIGINT REFERENCES empresas(id) ON DELETE CASCADE,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(150) UNIQUE NOT NULL,
    clave_hash TEXT NOT NULL,
    rol rol_usuario DEFAULT 'oficial',
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

CREATE TRIGGER trg_usuarios_updated
BEFORE UPDATE ON usuarios
FOR EACH ROW EXECUTE FUNCTION set_updated_at();

CREATE TABLE IF NOT EXISTS clientes (
    id BIGSERIAL PRIMARY KEY,
    empresa_id BIGINT REFERENCES empresas(id) ON DELETE CASCADE,
    nombre VARCHAR(150) NOT NULL,
    cedula VARCHAR(20) UNIQUE NOT NULL,
    telefono VARCHAR(20),
    direccion TEXT,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

CREATE TRIGGER trg_clientes_updated
BEFORE UPDATE ON clientes
FOR EACH ROW EXECUTE FUNCTION set_updated_at();

CREATE TABLE IF NOT EXISTS prestamos (
    id BIGSERIAL PRIMARY KEY,
    empresa_id BIGINT REFERENCES empresas(id) ON DELETE CASCADE,
    cliente_id BIGINT REFERENCES clientes(id) ON DELETE CASCADE,
    monto NUMERIC(12,2) NOT NULL,
    interes NUMERIC(5,2) NOT NULL,
    plazo_meses INT NOT NULL,
    estado estado_prestamo DEFAULT 'pendiente',
    fecha_inicio DATE DEFAULT CURRENT_DATE,
    created_at TIMESTAMP DEFAULT NOW(),
    updated_at TIMESTAMP DEFAULT NOW()
);

CREATE TRIGGER trg_prestamos_updated
BEFORE UPDATE ON prestamos
FOR EACH ROW EXECUTE FUNCTION set_updated_at();

CREATE TABLE IF NOT EXISTS amortizaciones (
    id BIGSERIAL PRIMARY KEY,
    prestamo_id BIGINT REFERENCES prestamos(id) ON DELETE CASCADE,
    numero_cuota INT NOT NULL,
    fecha_pago DATE NOT NULL,
    capital NUMERIC(12,2) NOT NULL,
    interes NUMERIC(12,2) NOT NULL,
    cuota NUMERIC(12,2) NOT NULL,
    saldo NUMERIC(12,2) NOT NULL,
    pagada BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT NOW()
);

-- Particionamiento de pagos por año
CREATE TABLE IF NOT EXISTS pagos (
    id BIGSERIAL NOT NULL,
    prestamo_id BIGINT NOT NULL REFERENCES prestamos(id) ON DELETE CASCADE,
    amortizacion_id BIGINT REFERENCES amortizaciones(id) ON DELETE CASCADE,
    fecha DATE NOT NULL DEFAULT CURRENT_DATE,
    monto NUMERIC(12,2) NOT NULL,
    metodo VARCHAR(50),
    created_at TIMESTAMP DEFAULT NOW(),
    PRIMARY KEY (id, fecha)
) PARTITION BY RANGE (fecha);

CREATE TABLE IF NOT EXISTS pagos_2025 PARTITION OF pagos
    FOR VALUES FROM ('2025-01-01') TO ('2026-01-01');

CREATE TABLE IF NOT EXISTS pagos_2026 PARTITION OF pagos
    FOR VALUES FROM ('2026-01-01') TO ('2027-01-01');

CREATE TABLE IF NOT EXISTS bitacora (
    id BIGSERIAL PRIMARY KEY,
    usuario_id BIGINT REFERENCES usuarios(id) ON DELETE SET NULL,
    accion TEXT NOT NULL,
    datos JSONB,
    ip INET,
    created_at TIMESTAMP DEFAULT NOW()
);

-- ============================
-- ÍNDICES
-- ============================
CREATE INDEX IF NOT EXISTS idx_prestamos_estado ON prestamos(estado);
CREATE INDEX IF NOT EXISTS idx_amortizaciones_prestamo ON amortizaciones(prestamo_id);
CREATE INDEX IF NOT EXISTS idx_pagos_prestamo ON pagos(prestamo_id);

-- ============================
-- VISTAS
-- ============================
CREATE OR REPLACE VIEW v_cartera_activa AS
SELECT e.id AS empresa_id, e.nombre AS empresa, SUM(p.monto) AS total_colocado,
       COUNT(p.id) AS prestamos_activos
FROM prestamos p
JOIN empresas e ON e.id = p.empresa_id
WHERE p.estado = 'activo'
GROUP BY e.id, e.nombre;

CREATE OR REPLACE VIEW v_cuotas_vencidas AS
SELECT a.*, c.nombre AS cliente, p.empresa_id
FROM amortizaciones a
JOIN prestamos p ON p.id = a.prestamo_id
JOIN clientes c ON c.id = p.cliente_id
WHERE a.pagada = FALSE AND a.fecha_pago < CURRENT_DATE;

-- ============================
-- VISTAS DE COMPATIBILIDAD LEGACY
-- ============================
CREATE OR REPLACE VIEW prestamos_legacy AS
SELECT p.id, p.monto, p.interes, p.plazo_meses, p.estado, p.fecha_inicio,
       c.nombre AS cliente_nombre, c.cedula AS cliente_cedula
FROM prestamos p
JOIN clientes c ON c.id = p.cliente_id;

CREATE OR REPLACE VIEW cuotas AS
SELECT a.id, a.prestamo_id, a.numero_cuota, a.fecha_pago, a.capital, a.interes, a.cuota, a.saldo, a.pagada
FROM amortizaciones a;

-- ============================
-- DATOS DE EJEMPLO
-- ============================
INSERT INTO empresas (nombre, rnc, direccion, telefono)
VALUES ('Financiera Demo', '123456789', 'Av. Principal #123', '809-555-1234')
ON CONFLICT DO NOTHING;

INSERT INTO usuarios (empresa_id, nombre, correo, clave_hash, rol)
VALUES (1, 'Admin Demo', 'admin@demo.com', 'hash', 'admin')
ON CONFLICT DO NOTHING;

INSERT INTO clientes (empresa_id, nombre, cedula, telefono, direccion, email)
VALUES (1, 'Juan Pérez', '001-1234567-8', '809-555-0001', 'C/ Ejemplo #45', 'juan@example.com')
ON CONFLICT DO NOTHING;
