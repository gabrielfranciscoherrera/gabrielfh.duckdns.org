<?php
// private/Controllers/AuthController.php
namespace Controllers;

class AuthController {
    public function loginForm(): void {
        echo "<div class='container my-4'><h1>Login</h1><p>Formulario de acceso (pendiente).</p></div>";
    }
    public function login(): void {
        // Validar credenciales y setear sesión (pendiente)
        header('Location: /');
    }
    public function logout(): void {
        session_destroy();
        header('Location: /login');
    }
}
