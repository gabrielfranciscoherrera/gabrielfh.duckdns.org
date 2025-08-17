CREATE TABLE empresas (
    id BIGSERIAL PRIMARY KEY,
    nombre VARCHAR(255) NOT NULL,
    codigo VARCHAR(50) UNIQUE NOT NULL,
    rnc VARCHAR(20),
    direccion TEXT,
    telefono VARCHAR(20),
    email VARCHAR(255),
    logo_url VARCHAR(500),
    tema_principal VARCHAR(50) DEFAULT 'dark',
    zona_horaria VARCHAR(50) DEFAULT 'America/Santo_Domingo',
    convencion_dias VARCHAR(10) DEFAULT '30/360' CHECK (convencion_dias IN ('30/360', 'ACT/360', 'ACT/365')),
    modo_redondeo VARCHAR(10) DEFAULT 'banker' CHECK (modo_redondeo IN ('banker', 'half_up')),
    precision_tasas NUMERIC(18,6) DEFAULT 6,
    precision_dinero NUMERIC(18,2) DEFAULT 2,
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Trigger para updated_at
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ language 'plpgsql';

CREATE TRIGGER update_empresas_updated_at BEFORE UPDATE ON empresas
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- Índices
CREATE INDEX idx_empresas_activo ON empresas(activo);
CREATE INDEX idx_empresas_codigo ON empresas(codigo);

CREATE TABLE sucursales (
    id BIGSERIAL PRIMARY KEY,
    empresa_id BIGINT NOT NULL,
    nombre VARCHAR(255) NOT NULL,
    codigo VARCHAR(50) NOT NULL,
    direccion TEXT,
    telefono VARCHAR(20),
    email VARCHAR(255),
    responsable_id BIGINT,
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_sucursales_empresa FOREIGN KEY (empresa_id) REFERENCES empresas(id) ON DELETE CASCADE,
    CONSTRAINT uk_sucursales_empresa_codigo UNIQUE (empresa_id, codigo)
);

-- Trigger para updated_at
CREATE TRIGGER update_sucursales_updated_at BEFORE UPDATE ON sucursales
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- Índices
CREATE INDEX idx_sucursales_empresa ON sucursales(empresa_id);
CREATE INDEX idx_sucursales_activo ON sucursales(activo);

CREATE TYPE rol_empleado AS ENUM ('admin', 'ejecutivo_credito', 'analista', 'cobrador', 'supervisor');
CREATE TYPE estado_empleado AS ENUM ('activo', 'inactivo', 'suspendido');

CREATE TABLE empleados (
    id BIGSERIAL PRIMARY KEY,
    empresa_id BIGINT NOT NULL,
    sucursal_id BIGINT NOT NULL,
    cedula VARCHAR(20) UNIQUE NOT NULL,
    nombres VARCHAR(255) NOT NULL,
    apellidos VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    telefono VARCHAR(20),
    direccion TEXT,
    rol rol_empleado NOT NULL,
    estado estado_empleado DEFAULT 'activo',
    fecha_ingreso DATE,
    salario_base NUMERIC(18,2),
    comision_originacion NUMERIC(5,4) DEFAULT 0.0100, -- 1% por defecto
    comision_cobranza NUMERIC(5,4) DEFAULT 0.0200, -- 2% por defecto
    meta_mensual NUMERIC(18,2),
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_empleados_empresa FOREIGN KEY (empresa_id) REFERENCES empresas(id) ON DELETE CASCADE,
    CONSTRAINT fk_empleados_sucursal FOREIGN KEY (sucursal_id) REFERENCES sucursales(id) ON DELETE RESTRICT
);

-- Trigger para updated_at
CREATE TRIGGER update_empleados_updated_at BEFORE UPDATE ON empleados
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- Índices
CREATE INDEX idx_empleados_empresa ON empleados(empresa_id);
CREATE INDEX idx_empleados_sucursal ON empleados(sucursal_id);
CREATE INDEX idx_empleados_rol ON empleados(rol);
CREATE INDEX idx_empleados_estado ON empleados(estado);
CREATE INDEX idx_empleados_activo ON empleados(activo);

CREATE TYPE estado_cliente AS ENUM ('activo', 'suspendido', 'moroso', 'cancelado');

CREATE TABLE clientes (
    id BIGSERIAL PRIMARY KEY,
    empresa_id BIGINT NOT NULL,
    sucursal_id BIGINT NOT NULL,
    empleado_id BIGINT NOT NULL,
    cedula VARCHAR(20) UNIQUE NOT NULL,
    nombres VARCHAR(255) NOT NULL,
    apellidos VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    telefono VARCHAR(20),
    telefono_alternativo VARCHAR(20),
    direccion TEXT,
    fecha_nacimiento DATE,
    estado_civil VARCHAR(20) CHECK (estado_civil IN ('soltero', 'casado', 'divorciado', 'viudo')),
    ocupacion VARCHAR(255),
    ingresos_mensuales NUMERIC(18,2),
    score_crediticio INTEGER DEFAULT 0,
    estado estado_cliente DEFAULT 'activo',
    fecha_registro DATE DEFAULT CURRENT_DATE,
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_clientes_empresa FOREIGN KEY (empresa_id) REFERENCES empresas(id) ON DELETE CASCADE,
    CONSTRAINT fk_clientes_sucursal FOREIGN KEY (sucursal_id) REFERENCES sucursales(id) ON DELETE RESTRICT,
    CONSTRAINT fk_clientes_empleado FOREIGN KEY (empleado_id) REFERENCES empleados(id) ON DELETE RESTRICT
);

-- Trigger para updated_at
CREATE TRIGGER update_clientes_updated_at BEFORE UPDATE ON clientes
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- Índices
CREATE INDEX idx_clientes_empresa ON clientes(empresa_id);
CREATE INDEX idx_clientes_sucursal ON clientes(sucursal_id);
CREATE INDEX idx_clientes_empleado ON clientes(empleado_id);
CREATE INDEX idx_clientes_cedula ON clientes(cedula);
CREATE INDEX idx_clientes_estado ON clientes(estado);
CREATE INDEX idx_clientes_score ON clientes(score_crediticio);
CREATE INDEX idx_clientes_activo ON clientes(activo);

CREATE TYPE metodo_amortizacion AS ENUM ('frances', 'aleman', 'americano');

CREATE TABLE prestamos_tipos (
    id BIGSERIAL PRIMARY KEY,
    empresa_id BIGINT NOT NULL,
    nombre VARCHAR(255) NOT NULL,
    codigo VARCHAR(50) NOT NULL,
    descripcion TEXT,
    tasa_interes NUMERIC(18,6) NOT NULL,
    tasa_moratoria NUMERIC(18,6) DEFAULT 0.000000,
    plazo_minimo INTEGER NOT NULL, -- en meses
    plazo_maximo INTEGER NOT NULL, -- en meses
    monto_minimo NUMERIC(18,2) NOT NULL,
    monto_maximo NUMERIC(18,2) NOT NULL,
    comision_originacion NUMERIC(5,4) DEFAULT 0.0000,
    seguro_obligatorio BOOLEAN DEFAULT FALSE,
    gastos_legales NUMERIC(18,2) DEFAULT 0.00,
    metodo_amortizacion metodo_amortizacion DEFAULT 'frances',
    dias_gracia INTEGER DEFAULT 0,
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_prestamos_tipos_empresa FOREIGN KEY (empresa_id) REFERENCES empresas(id) ON DELETE CASCADE,
    CONSTRAINT uk_prestamos_tipos_empresa_codigo UNIQUE (empresa_id, codigo)
);

-- Trigger para updated_at
CREATE TRIGGER update_prestamos_tipos_updated_at BEFORE UPDATE ON prestamos_tipos
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- Índices
CREATE INDEX idx_prestamos_tipos_empresa ON prestamos_tipos(empresa_id);
CREATE INDEX idx_prestamos_tipos_activo ON prestamos_tipos(activo);

CREATE TYPE estado_prestamo AS ENUM ('borrador', 'evaluado', 'aprobado', 'desembolsado', 'en_curso', 'en_mora', 'reestructurado', 'castigado', 'cancelado');
CREATE TYPE politica_abono AS ENUM ('reduce_plazo', 'reduce_cuota');

CREATE TABLE prestamos (
    id BIGSERIAL PRIMARY KEY,
    empresa_id BIGINT NOT NULL,
    sucursal_id BIGINT NOT NULL,
    cliente_id BIGINT NOT NULL,
    empleado_id BIGINT NOT NULL,
    tipo_prestamo_id BIGINT NOT NULL,
    numero_prestamo VARCHAR(50) UNIQUE NOT NULL,
    monto_solicitado NUMERIC(18,2) NOT NULL,
    monto_aprobado NUMERIC(18,2) NOT NULL,
    tasa_interes NUMERIC(18,6) NOT NULL,
    plazo_meses INTEGER NOT NULL,
    cuota_mensual NUMERIC(18,2) NOT NULL,
    fecha_solicitud DATE NOT NULL,
    fecha_aprobacion DATE,
    fecha_desembolso DATE,
    fecha_primer_vencimiento DATE,
    fecha_ultimo_vencimiento DATE,
    estado estado_prestamo DEFAULT 'borrador',
    saldo_capital NUMERIC(18,2) NOT NULL,
    saldo_intereses NUMERIC(18,2) DEFAULT 0.00,
    saldo_moratorios NUMERIC(18,2) DEFAULT 0.00,
    saldo_comisiones NUMERIC(18,2) DEFAULT 0.00,
    politica_abono politica_abono NOT NULL,
    motivo_cancelacion TEXT,
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_prestamos_empresa FOREIGN KEY (empresa_id) REFERENCES empresas(id) ON DELETE CASCADE,
    CONSTRAINT fk_prestamos_sucursal FOREIGN KEY (sucursal_id) REFERENCES sucursales(id) ON DELETE RESTRICT,
    CONSTRAINT fk_prestamos_cliente FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE RESTRICT,
    CONSTRAINT fk_prestamos_empleado FOREIGN KEY (empleado_id) REFERENCES empleados(id) ON DELETE RESTRICT,
    CONSTRAINT fk_prestamos_tipo FOREIGN KEY (tipo_prestamo_id) REFERENCES prestamos_tipos(id) ON DELETE RESTRICT,
    CONSTRAINT ck_prestamos_monto CHECK (monto_aprobado > 0),
    CONSTRAINT ck_prestamos_plazo CHECK (plazo_meses > 0),
    CONSTRAINT ck_prestamos_cuota CHECK (cuota_mensual > 0)
);

-- Trigger para updated_at
CREATE TRIGGER update_prestamos_updated_at BEFORE UPDATE ON prestamos
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- Índices
CREATE INDEX idx_prestamos_empresa ON prestamos(empresa_id);
CREATE INDEX idx_prestamos_sucursal ON prestamos(sucursal_id);
CREATE INDEX idx_prestamos_cliente ON prestamos(cliente_id);
CREATE INDEX idx_prestamos_empleado ON prestamos(empleado_id);
CREATE INDEX idx_prestamos_estado ON prestamos(estado);
CREATE INDEX idx_prestamos_fecha_desembolso ON prestamos(fecha_desembolso);
CREATE INDEX idx_prestamos_fecha_primer_vencimiento ON prestamos(fecha_primer_vencimiento);
CREATE INDEX idx_prestamos_activo ON prestamos(activo);

CREATE TABLE eventos_prestamo (
    id BIGSERIAL PRIMARY KEY,
    prestamo_id BIGINT NOT NULL,
    empleado_id BIGINT NOT NULL,
    estado_anterior estado_prestamo,
    estado_nuevo estado_prestamo NOT NULL,
    motivo TEXT,
    datos_adicionales JSONB,
    ip_address INET,
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_eventos_prestamo_prestamo FOREIGN KEY (prestamo_id) REFERENCES prestamos(id) ON DELETE CASCADE,
    CONSTRAINT fk_eventos_prestamo_empleado FOREIGN KEY (empleado_id) REFERENCES empleados(id) ON DELETE RESTRICT
);

-- Índices
CREATE INDEX idx_eventos_prestamo_prestamo ON eventos_prestamo(prestamo_id);
CREATE INDEX idx_eventos_prestamo_empleado ON eventos_prestamo(empleado_id);
CREATE INDEX idx_eventos_prestamo_estado_nuevo ON eventos_prestamo(estado_nuevo);
CREATE INDEX idx_eventos_prestamo_created_at ON eventos_prestamo(created_at);
CREATE INDEX idx_eventos_prestamo_datos_adicionales ON eventos_prestamo USING GIN (datos_adicionales);

CREATE TYPE estado_cuota AS ENUM ('pendiente', 'pagada', 'vencida', 'parcial');

CREATE TABLE cuotas_amortizacion (
    id BIGSERIAL PRIMARY KEY,
    prestamo_id BIGINT NOT NULL,
    numero_cuota INTEGER NOT NULL,
    fecha_vencimiento DATE NOT NULL,
    monto_cuota NUMERIC(18,2) NOT NULL,
    capital NUMERIC(18,2) NOT NULL,
    intereses NUMERIC(18,2) NOT NULL,
    saldo_capital NUMERIC(18,2) NOT NULL,
    estado estado_cuota DEFAULT 'pendiente',
    fecha_pago DATE,
    monto_pagado NUMERIC(18,2) DEFAULT 0.00,
    intereses_moratorios NUMERIC(18,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_cuotas_prestamo FOREIGN KEY (prestamo_id) REFERENCES prestamos(id) ON DELETE CASCADE,
    CONSTRAINT uk_cuotas_prestamo_numero UNIQUE (prestamo_id, numero_cuota),
    CONSTRAINT ck_cuotas_numero CHECK (numero_cuota > 0),
    CONSTRAINT ck_cuotas_monto CHECK (monto_cuota > 0),
    CONSTRAINT ck_cuotas_capital CHECK (capital > 0),
    CONSTRAINT ck_cuotas_intereses CHECK (intereses >= 0)
);

-- Trigger para updated_at
CREATE TRIGGER update_cuotas_amortizacion_updated_at BEFORE UPDATE ON cuotas_amortizacion
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- Índices
CREATE INDEX idx_cuotas_prestamo ON cuotas_amortizacion(prestamo_id);
CREATE INDEX idx_cuotas_numero ON cuotas_amortizacion(prestamo_id, numero_cuota);
CREATE INDEX idx_cuotas_vencimiento ON cuotas_amortizacion(fecha_vencimiento);
CREATE INDEX idx_cuotas_estado ON cuotas_amortizacion(estado);

CREATE TYPE metodo_pago AS ENUM ('efectivo', 'transferencia', 'tarjeta', 'cheque');

CREATE TABLE pagos (
    id BIGSERIAL PRIMARY KEY,
    prestamo_id BIGINT NOT NULL,
    cuota_id BIGINT,
    empleado_id BIGINT NOT NULL,
    numero_recibo VARCHAR(50) UNIQUE NOT NULL,
    fecha_pago DATE NOT NULL,
    monto_total NUMERIC(18,2) NOT NULL,
    metodo_pago metodo_pago NOT NULL,
    referencia_bancaria VARCHAR(255),
    observaciones TEXT,
    idempotency_key VARCHAR(255) UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_pagos_prestamo FOREIGN KEY (prestamo_id) REFERENCES prestamos(id) ON DELETE CASCADE,
    CONSTRAINT fk_pagos_cuota FOREIGN KEY (cuota_id) REFERENCES cuotas_amortizacion(id) ON DELETE SET NULL,
    CONSTRAINT fk_pagos_empleado FOREIGN KEY (empleado_id) REFERENCES empleados(id) ON DELETE RESTRICT,
    CONSTRAINT ck_pagos_monto CHECK (monto_total > 0)
);

-- Trigger para updated_at
CREATE TRIGGER update_pagos_updated_at BEFORE UPDATE ON pagos
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- Índices
CREATE INDEX idx_pagos_prestamo ON pagos(prestamo_id);
CREATE INDEX idx_pagos_cuota ON pagos(cuota_id);
CREATE INDEX idx_pagos_empleado ON pagos(empleado_id);
CREATE INDEX idx_pagos_fecha ON pagos(fecha_pago);
CREATE INDEX idx_pagos_metodo ON pagos(metodo_pago);

CREATE TYPE tipo_concepto AS ENUM ('mora_penalidades', 'intereses_corrientes', 'intereses_atraso', 'capital', 'comisiones', 'gastos');

CREATE TABLE pagos_detalle (
    id BIGSERIAL PRIMARY KEY,
    pago_id BIGINT NOT NULL,
    tipo_concepto tipo_concepto NOT NULL,
    monto NUMERIC(18,2) NOT NULL,
    cuota_id BIGINT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_pagos_detalle_pago FOREIGN KEY (pago_id) REFERENCES pagos(id) ON DELETE CASCADE,
    CONSTRAINT fk_pagos_detalle_cuota FOREIGN KEY (cuota_id) REFERENCES cuotas_amortizacion(id) ON DELETE SET NULL,
    CONSTRAINT ck_pagos_detalle_monto CHECK (monto > 0)
);

-- Índices
CREATE INDEX idx_pagos_detalle_pago ON pagos_detalle(pago_id);
CREATE INDEX idx_pagos_detalle_tipo ON pagos_detalle(tipo_concepto);
CREATE INDEX idx_pagos_detalle_cuota ON pagos_detalle(cuota_id);

CREATE TYPE metodo_desembolso AS ENUM ('efectivo', 'transferencia', 'cheque');

CREATE TABLE desembolsos (
    id BIGSERIAL PRIMARY KEY,
    prestamo_id BIGINT NOT NULL,
    empleado_id BIGINT NOT NULL,
    monto NUMERIC(18,2) NOT NULL,
    metodo metodo_desembolso NOT NULL,
    referencia_bancaria VARCHAR(255),
    fecha_desembolso DATE NOT NULL,
    observaciones TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_desembolsos_prestamo FOREIGN KEY (prestamo_id) REFERENCES prestamos(id) ON DELETE CASCADE,
    CONSTRAINT fk_desembolsos_empleado FOREIGN KEY (empleado_id) REFERENCES empleados(id) ON DELETE RESTRICT,
    CONSTRAINT ck_desembolsos_monto CHECK (monto > 0)
);

-- Índices
CREATE INDEX idx_desembolsos_prestamo ON desembolsos(prestamo_id);
CREATE INDEX idx_desembolsos_empleado ON desembolsos(empleado_id);
CREATE INDEX idx_desembolsos_fecha ON desembolsos(fecha_desembolso);

CREATE TYPE tipo_contacto AS ENUM ('llamada', 'visita', 'email', 'sms', 'whatsapp');
CREATE TYPE resultado_cobro AS ENUM ('contactado', 'no_contesta', 'promesa_pago', 'rechaza', 'acuerdo');

CREATE TABLE cobros (
    id BIGSERIAL PRIMARY KEY,
    prestamo_id BIGINT NOT NULL,
    empleado_id BIGINT NOT NULL,
    tipo_contacto tipo_contacto NOT NULL,
    resultado resultado_cobro NOT NULL,
    fecha_contacto DATE NOT NULL,
    proxima_accion DATE,
    monto_prometido NUMERIC(18,2),
    observaciones TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_cobros_prestamo FOREIGN KEY (prestamo_id) REFERENCES prestamos(id) ON DELETE CASCADE,
    CONSTRAINT fk_cobros_empleado FOREIGN KEY (empleado_id) REFERENCES empleados(id) ON DELETE RESTRICT
);

-- Índices
CREATE INDEX idx_cobros_prestamo ON cobros(prestamo_id);
CREATE INDEX idx_cobros_empleado ON cobros(empleado_id);
CREATE INDEX idx_cobros_fecha ON cobros(fecha_contacto);
CREATE INDEX idx_cobros_resultado ON cobros(resultado);

CREATE TYPE tipo_comision AS ENUM ('originacion', 'cobranza');
CREATE TYPE estado_comision AS ENUM ('pendiente', 'pagada', 'cancelada');

CREATE TABLE comisiones (
    id BIGSERIAL PRIMARY KEY,
    empleado_id BIGINT NOT NULL,
    prestamo_id BIGINT,
    tipo tipo_comision NOT NULL,
    monto_base NUMERIC(18,2) NOT NULL,
    porcentaje NUMERIC(5,4) NOT NULL,
    monto_comision NUMERIC(18,2) NOT NULL,
    fecha_calculo DATE NOT NULL,
    fecha_pago DATE,
    estado estado_comision DEFAULT 'pendiente',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_comisiones_empleado FOREIGN KEY (empleado_id) REFERENCES empleados(id) ON DELETE CASCADE,
    CONSTRAINT fk_comisiones_prestamo FOREIGN KEY (prestamo_id) REFERENCES prestamos(id) ON DELETE SET NULL,
    CONSTRAINT ck_comisiones_monto_base CHECK (monto_base > 0),
    CONSTRAINT ck_comisiones_porcentaje CHECK (porcentaje >= 0),
    CONSTRAINT ck_comisiones_monto CHECK (monto_comision > 0)
);

-- Trigger para updated_at
CREATE TRIGGER update_comisiones_updated_at BEFORE UPDATE ON comisiones
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- Índices
CREATE INDEX idx_comisiones_empleado ON comisiones(empleado_id);
CREATE INDEX idx_comisiones_prestamo ON comisiones(prestamo_id);
CREATE INDEX idx_comisiones_tipo ON comisiones(tipo);
CREATE INDEX idx_comisiones_estado ON comisiones(estado);
CREATE INDEX idx_comisiones_fecha_calculo ON comisiones(fecha_calculo);

CREATE TYPE tipo_feriado AS ENUM ('nacional', 'regional', 'institucional');

CREATE TABLE feriados (
    id BIGSERIAL PRIMARY KEY,
    empresa_id BIGINT NOT NULL,
    fecha DATE NOT NULL,
    descripcion VARCHAR(255) NOT NULL,
    tipo tipo_feriado DEFAULT 'nacional',
    activo BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_feriados_empresa FOREIGN KEY (empresa_id) REFERENCES empresas(id) ON DELETE CASCADE,
    CONSTRAINT uk_feriados_empresa_fecha UNIQUE (empresa_id, fecha)
);

-- Índices
CREATE INDEX idx_feriados_empresa ON feriados(empresa_id);
CREATE INDEX idx_feriados_fecha ON feriados(fecha);
CREATE INDEX idx_feriados_activo ON feriados(activo);

CREATE TYPE accion_auditoria AS ENUM ('INSERT', 'UPDATE', 'DELETE');

CREATE TABLE auditoria (
    id BIGSERIAL PRIMARY KEY,
    empresa_id BIGINT NOT NULL,
    tabla VARCHAR(100) NOT NULL,
    registro_id BIGINT NOT NULL,
    accion accion_auditoria NOT NULL,
    empleado_id BIGINT,
    datos_anteriores JSONB,
    datos_nuevos JSONB,
    ip_address INET,
    user_agent TEXT,
    hash_anterior VARCHAR(64),
    hash_actual VARCHAR(64),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    CONSTRAINT fk_auditoria_empresa FOREIGN KEY (empresa_id) REFERENCES empresas(id) ON DELETE CASCADE,
    CONSTRAINT fk_auditoria_empleado FOREIGN KEY (empleado_id) REFERENCES empleados(id) ON DELETE SET NULL
);

-- Índices
CREATE INDEX idx_auditoria_empresa ON auditoria(empresa_id);
CREATE INDEX idx_auditoria_tabla ON auditoria(tabla);
CREATE INDEX idx_auditoria_registro ON auditoria(tabla, registro_id);
CREATE INDEX idx_auditoria_empleado ON auditoria(empleado_id);
CREATE INDEX idx_auditoria_accion ON auditoria(accion);
CREATE INDEX idx_auditoria_created_at ON auditoria(created_at);
CREATE INDEX idx_auditoria_datos_anteriores ON auditoria USING GIN (datos_anteriores);
CREATE INDEX idx_auditoria_datos_nuevos ON auditoria USING GIN (datos_nuevos);

-- Para consultas de cartera por sucursal y estado
CREATE INDEX idx_prestamos_sucursal_estado ON prestamos(sucursal_id, estado);

-- Para consultas de vencimientos
CREATE INDEX idx_cuotas_vencimiento_estado ON cuotas_amortizacion(fecha_vencimiento, estado);

-- Para consultas de pagos por fecha y método
CREATE INDEX idx_pagos_fecha_metodo ON pagos(fecha_pago, metodo_pago);

-- Para consultas de clientes por empleado y estado
CREATE INDEX idx_clientes_empleado_estado ON clientes(empleado_id, estado);

-- Para búsquedas en clientes
CREATE INDEX idx_clientes_busqueda ON clientes USING GIN (to_tsvector('spanish', nombres || ' ' || apellidos || ' ' || cedula || ' ' || COALESCE(email, '')));

-- Para búsquedas en préstamos
CREATE INDEX idx_prestamos_busqueda ON prestamos USING GIN (to_tsvector('spanish', numero_prestamo));

CREATE VIEW v_cartera_activa AS
SELECT 
    p.id,
    p.numero_prestamo,
    c.cedula,
    CONCAT(c.nombres, ' ', c.apellidos) AS cliente,
    p.monto_aprobado,
    p.saldo_capital,
    p.estado,
    p.fecha_primer_vencimiento,
    p.fecha_ultimo_vencimiento,
    e.nombres AS ejecutivo
FROM prestamos p
JOIN clientes c ON p.cliente_id = c.id
JOIN empleados e ON p.empleado_id = e.id
WHERE p.estado IN ('en_curso', 'en_mora')
AND p.activo = TRUE;

CREATE VIEW v_cuotas_vencidas AS
SELECT 
    ca.id,
    ca.prestamo_id,
    p.numero_prestamo,
    c.cedula,
    CONCAT(c.nombres, ' ', c.apellidos) AS cliente,
    ca.numero_cuota,
    ca.fecha_vencimiento,
    ca.monto_cuota,
    ca.saldo_capital,
    CURRENT_DATE - ca.fecha_vencimiento AS dias_vencida
FROM cuotas_amortizacion ca
JOIN prestamos p ON ca.prestamo_id = p.id
JOIN clientes c ON p.cliente_id = c.id
WHERE ca.estado = 'vencida'
AND ca.fecha_vencimiento < CURRENT_DATE;

-- Insertar empresa por defecto
INSERT INTO empresas (nombre, codigo, rnc, direccion, telefono, email) 
VALUES ('Empresa Demo', 'DEMO', '123456789', 'Santo Domingo, RD', '809-555-0000', 'admin@demo.com');

-- Insertar sucursal principal
INSERT INTO sucursales (empresa_id, nombre, codigo, direccion, telefono, email)
SELECT e.id, 'Sucursal Principal', 'PRINCIPAL', 'Santo Domingo, RD', '809-555-0001', 'sucursal@demo.com'
FROM empresas e WHERE e.codigo = 'DEMO';

-- Insertar empleado administrador
INSERT INTO empleados (empresa_id, sucursal_id, cedula, nombres, apellidos, email, rol, estado)
SELECT e.id, s.id, '00000000000', 'Administrador', 'Sistema', 'admin@demo.com', 'admin', 'activo'
FROM empresas e, sucursales s 
WHERE e.codigo = 'DEMO' AND s.codigo = 'PRINCIPAL';

-- Insertar tipo de préstamo básico
INSERT INTO prestamos_tipos (empresa_id, nombre, codigo, descripcion, tasa_interes, plazo_minimo, plazo_maximo, monto_minimo, monto_maximo)
SELECT e.id, 'Préstamo Personal', 'PERSONAL', 'Préstamo personal básico', 0.020000, 12, 60, 10000.00, 500000.00
FROM empresas e WHERE e.codigo = 'DEMO';

-- Ejemplo de particionamiento para la tabla pagos
CREATE TABLE pagos (
    -- ... columnas ...
) PARTITION BY RANGE (fecha_pago);

-- Crear particiones por año
CREATE TABLE pagos_2024 PARTITION OF pagos
    FOR VALUES FROM ('2024-01-01') TO ('2025-01-01');

CREATE TABLE pagos_2025 PARTITION OF pagos
    FOR VALUES FROM ('2025-01-01') TO ('2026-01-01');

-- En el servidor maestro (postgresql.conf)
-- wal_level = replica
-- max_wal_senders = 3
-- max_replication_slots = 3

-- En el servidor esclavo (recovery.conf)
-- primary_conninfo = 'host=master_host port=5432 user=repl password=password'

-- Los índices GIN ya están creados en la tabla auditoria
-- para los campos datos_anteriores y datos_nuevos

