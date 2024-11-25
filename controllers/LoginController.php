<?php

namespace Controllers;
use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController{
    public static function login(Router $router){
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);

            $alertas = $auth->validarLogin();

            if(empty($alertas)){
                //verificar que el usuario exista
                $usuario = Usuario::where('email', $auth->email);

                if(!$usuario || !$usuario->confirmado){
                    Usuario::setAlerta('error', 'The user does not exists or not confirmed');
                } else{
                    //el usuario existe
                    if(password_verify($_POST['password'], $usuario->password)){
                        //iniciar sesion
                        session_start();
                        $_SESSION ['id'] = $usuario->id;
                        $_SESSION ['nombre'] = $usuario->nombre;
                        $_SESSION ['email'] = $usuario->email;
                        $_SESSION ['login'] = true;

                        header('Location: /dashboard');

                    } else {
                        Usuario::setAlerta('error', 'Wrong Password');

                    }
                }
            }
            $alertas = Usuario::getAlertas();

        }

        //render a la vista
       $router->render('auth/login', [
        'titulo'=> 'Iniciar Sesion',
        'alertas' => $alertas

       ]);
    }
    public static function logout(){

        session_start();
        $_SESSION = [];
        header('Location: /');
    }
    public static function crear(Router $router){
        $alertas = [];
        $usuario = new Usuario;
      

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();
             if(empty($alertas)){
                $existeUsuario = Usuario::where('email', $usuario->email);
                if($existeUsuario){
                    Usuario::setAlerta('error', 'The user is already registered');
                    $alertas = Usuario::getAlertas();
                 } else{
                    //hashear el password
                    $usuario->hashPassword();
                    //eliminar password2
                    unset($usuario->password2);
                    //generar token
                    $usuario->crearToken();

                    //crear nuevo usuario
                    $resultado = $usuario ->guardar();

                    //enviar email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();
                    
                    if($resultado){
                        header('Location: /mensaje');
                    }

                 }
             }
             
        }
        //render a la vista
        $router->render('auth/crear', [
            'titulo'=> 'Crea tu cuenta en UpTask',
            'usuario' => $usuario,
            'alertas' => $alertas

        ]);
    }
    public static function olvide(Router $router){
        $alertas =[];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();

            if(empty($alertas)){
                //buscar el usuario
                $usuario = Usuario::where('email', $usuario->email);

                if($usuario && $usuario->confirmado === '1'){
                    //encontro el usuario
                    //genera nuevo token
                    $usuario->crearToken();
                    unset($usuario->password2);
                    //actualizar el usuario
                    $usuario->guardar();
                    //enviar el mail
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();
                    //imprimir la alerta
                    Usuario::setAlerta('exito', 'Check your E-mail');

                } else{
                    Usuario::setAlerta('error', 'The user does not exists');
                    
                }
            }
        }
        $alertas= Usuario::getAlertas();
        $router->render('auth/olvide', [
            'titulo'=> 'Forgot Password',
            'alertas' => $alertas

        ]);
    }
    public static function restablecer(Router $router){
        $mostrar = true;
        $token = s($_GET['token']);
        if(!$token) header('Location: /');
// identificar usuario con el token
$usuario = Usuario::where('token', $token);
if(empty($usuario)){
    Usuario::setAlerta('error', 'Not valid Token');
    $mostrar = false;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($usuario) { // Validar que $usuario no sea null
        // Sincronizar con los datos del formulario
        $usuario->sincronizar($_POST);

        // Validar el nuevo password
        $alertas = $usuario->validarPassword();

        if (empty($alertas)) {
            // Hashear el nuevo password
            $usuario->hashPassword();

            // Eliminar el token
            $usuario->token = null;

            // Guardar el usuario
            $resultado = $usuario->guardar();

            if ($resultado) {
                // Redireccionar a la página principal
                header('Location: /');
                exit;
            }
        }
    } else {
        Usuario::setAlerta('error', 'User not found. Please check the token.');
    }
}
        $alertas = Usuario::getAlertas();
        $router->render('auth/restablecer', [
            'titulo'=> 'Recover password',
            'alertas' => $alertas,
            'mostrar' => $mostrar

        ]);
    }
    public static function mensaje(Router $router){
$router->render('auth/mensaje', [
    'titulo' => 'Account created succesfully'

]);
    }
    public static function confirmar(Router $router){
        $token = s($_GET ['token']);
        if(!$token) header('Location: /');
        //encontrar al usuario con el token
        $usuario = Usuario::where('token', $token);
        if(empty($usuario)){
            Usuario::setAlerta('error', 'Invalid Token');
        } else{
            //confirmar la cuenta
            $usuario->confirmado = 1;
            $usuario->token = null;
            unset($usuario->password2);
            //guardar en la BD
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Account created successfully');
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/confirmar', [
            'titulo' => 'Confirm your account in UpTask',
            'alertas' => $alertas

        ]);

    }
    
}