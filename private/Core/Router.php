<?php
// private/Core/Router.php
namespace Core;

class Router {
    private array $routes = ['GET'=>[], 'POST'=>[], 'PUT'=>[], 'PATCH'=>[], 'DELETE'=>[]];

    public function get(string $path, $handler){ $this->routes['GET'][$this->normalize($path)] = $handler; }
    public function post(string $path, $handler){ $this->routes['POST'][$this->normalize($path)] = $handler; }
    public function put(string $path, $handler){ $this->routes['PUT'][$this->normalize($path)] = $handler; }
    public function patch(string $path, $handler){ $this->routes['PATCH'][$this->normalize($path)] = $handler; }
    public function delete(string $path, $handler){ $this->routes['DELETE'][$this->normalize($path)] = $handler; }

    private function normalize(string $path): string {
        $path = parse_url($path, PHP_URL_PATH) ?: '/';
        return rtrim($path, '/') ?: '/';
    }

    public function dispatch(string $method, string $uri): void {
        $path = $this->normalize($uri);
        $handler = $this->routes[$method][$path] ?? null;
        if (!$handler) {
            http_response_code(404);
            echo "404 Not Found";
            return;
        }
        if (is_array($handler) && count($handler) === 2) {
            [$class, $methodName] = $handler;
            $instance = new $class();
            $instance->$methodName();
            return;
        }
        if (is_callable($handler)) { $handler(); return; }
        http_response_code(500);
        echo "Handler inválido.";
    }
}
