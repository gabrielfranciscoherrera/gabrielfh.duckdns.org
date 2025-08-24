<?php
// private/Core/Response.php
namespace Core;

class Response {
    public static function json($data, int $code = 200): void {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}


/**
 * La clase Response actúa como intermediario para enviar respuestas en formato JSON.
 *
 * Proceso:
 * 1. Recibe los datos que se desean enviar.
 * 2. Convierte esos datos al formato estándar JSON, comprensible para aplicaciones.
 * 3. Agrega la cabecera HTTP indicando: "Content-Type: application/json; charset=utf-8".
 * 4. Envía la respuesta al cliente que realizó la petición.
 *
 * De esta manera, cuando una página web o aplicación solicita información al servidor,
 * la clase Response asegura que la respuesta esté organizada, correctamente etiquetada
 * y lista para ser interpretada por el cliente.
 */
