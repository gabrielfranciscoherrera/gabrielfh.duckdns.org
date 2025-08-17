-- private/db/schema_postgres.sql – Esquema PostgreSQL
DO $$ BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'rol_usuario') THEN
        CREATE TYPE rol_usuario AS ENUM ('admin','cobranzas');
    END IF;
    IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'estado_prestamo') THEN
        CREATE TYPE estado_prestamo AS ENUM ('activo','cancelado','mora');
    END IF;
END $$;

CREATE TABLE IF NOT EXISTS usuarios (
  id BIGSERIAL PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  email VARCHAR(120) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  rol rol_usuario DEFAULT 'cobranzas',
  activo BOOLEAN DEFAULT TRUE,
  creado_en TIMESTAMPTZ DEFAULT now()
);

CREATE TABLE IF NOT EXISTS clientes (
  id BIGSERIAL PRIMARY KEY,
  cedula VARCHAR(20) UNIQUE,
  nombre VARCHAR(150) NOT NULL,
  telefono VARCHAR(30),
  direccion VARCHAR(200),
  email VARCHAR(120),
  creado_en TIMESTAMPTZ DEFAULT now()
);

CREATE TABLE IF NOT EXISTS prestamos (
  id BIGSERIAL PRIMARY KEY,
  cliente_id BIGINT NOT NULL REFERENCES clientes(id) ON DELETE RESTRICT,
  monto NUMERIC(12,2) NOT NULL,
  tasa_mensual NUMERIC(6,4) NOT NULL,
  plazo_meses INT NOT NULL,
  fecha_inicio DATE NOT NULL,
  estado estado_prestamo DEFAULT 'activo',
  observaciones TEXT,
  creado_en TIMESTAMPTZ DEFAULT now()
);

CREATE TABLE IF NOT EXISTS cuotas (
  id BIGSERIAL PRIMARY KEY,
  prestamo_id BIGINT NOT NULL REFERENCES prestamos(id) ON DELETE CASCADE,
  numero INT NOT NULL,
  fecha_vencimiento DATE NOT NULL,
  capital NUMERIC(12,2) NOT NULL,
  interes NUMERIC(12,2) NOT NULL,
  mora NUMERIC(12,2) DEFAULT 0,
  total NUMERIC(12,2) NOT NULL,
  pagada BOOLEAN DEFAULT FALSE,
  pagada_en TIMESTAMPTZ NULL,
  UNIQUE (prestamo_id, numero)
);

CREATE TABLE IF NOT EXISTS pagos (
  id BIGSERIAL PRIMARY KEY,
  prestamo_id BIGINT NOT NULL REFERENCES prestamos(id) ON DELETE CASCADE,
  cuota_id BIGINT NULL REFERENCES cuotas(id) ON DELETE SET NULL,
  monto NUMERIC(12,2) NOT NULL,
  metodo VARCHAR(30) DEFAULT 'efectivo',
  referencia VARCHAR(60),
  realizado_por BIGINT NULL REFERENCES usuarios(id) ON DELETE SET NULL,
  creado_en TIMESTAMPTZ DEFAULT now()
);

CREATE TABLE IF NOT EXISTS bitacora (
  id BIGSERIAL PRIMARY KEY,
  usuario_id BIGINT NULL REFERENCES usuarios(id) ON DELETE SET NULL,
  accion VARCHAR(60) NOT NULL,
  entidad VARCHAR(40) NOT NULL,
  entidad_id BIGINT NULL,
  datos JSONB NULL,
  ip INET,
  creado_en TIMESTAMPTZ DEFAULT now()
);

CREATE INDEX IF NOT EXISTS idx_prestamos_cliente ON prestamos(cliente_id);
CREATE INDEX IF NOT EXISTS idx_cuotas_prestamo ON cuotas(prestamo_id);
CREATE INDEX IF NOT EXISTS idx_pagos_prestamo ON pagos(prestamo_id);
CREATE INDEX IF NOT EXISTS idx_bitacora_entidad ON bitacora(entidad, entidad_id);
