-- Script de verificación de la base de datos
-- Verifica que todas las estructuras y datos estén correctos

-- ============================
-- VERIFICACIÓN DE ESTRUCTURA
-- ============================

-- Verificar tablas principales
SELECT 'TABLAS' as tipo, count(*) as cantidad FROM information_schema.tables 
WHERE table_schema = 'public' AND table_type = 'BASE TABLE';

-- Verificar vistas
SELECT 'VISTAS' as tipo, count(*) as cantidad FROM information_schema.views 
WHERE table_schema = 'public';

-- Verificar funciones
SELECT 'FUNCIONES' as tipo, count(*) as cantidad FROM information_schema.routines 
WHERE routine_schema = 'public';

-- Verificar tipos ENUM
SELECT 'TIPOS ENUM' as tipo, count(*) as cantidad FROM pg_type 
WHERE typnamespace = (SELECT oid FROM pg_namespace WHERE nspname = 'public') 
AND typtype = 'e';

-- ============================
-- VERIFICACIÓN DE DATOS
-- ============================

-- Verificar datos de ejemplo
SELECT 'EMPRESAS' as tabla, count(*) as registros FROM empresas;
SELECT 'USUARIOS' as tabla, count(*) as registros FROM usuarios;
SELECT 'CLIENTES' as tabla, count(*) as registros FROM clientes;

-- ============================
-- VERIFICACIÓN DE FUNCIONALIDAD
-- ============================

-- Probar función round_banker
SELECT 'round_banker(123.456, 2)' as prueba, round_banker(123.456, 2) as resultado;

-- Verificar trigger updated_at
SELECT 'TRIGGERS' as tipo, count(*) as cantidad FROM information_schema.triggers 
WHERE trigger_schema = 'public';

-- ============================
-- VERIFICACIÓN DE PERMISOS
-- ============================

-- Verificar que el usuario puede acceder a las tablas
SELECT 'PERMISOS' as tipo, 'OK' as estado WHERE EXISTS (
    SELECT 1 FROM information_schema.table_privileges 
    WHERE grantee = 'gabriel_fh_prestamos' 
    AND table_schema = 'public' 
    LIMIT 1
);

-- ============================
-- RESUMEN FINAL
-- ============================
SELECT 'VERIFICACIÓN COMPLETADA' as estado, 
       NOW() as fecha_verificacion;

