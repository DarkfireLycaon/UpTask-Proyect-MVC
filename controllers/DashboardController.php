<?php
namespace Controllers;
use Model\Proyecto;
use MVC\Router;
use Model\Usuario;

class DashboardController{
    public static function index(Router $router){
        session_start();
        isAuth();
        $id = $_SESSION['id'];
        $proyectos = Proyecto::belongsTo('propietarioId', $id);

    $router->render('dashboard/index', [
        'titulo' => 'Proyects',
        'proyectos' => $proyectos

    ]);
}
public static function crear_proyecto(Router $router){
session_start();
isAuth();
$alertas = [];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $proyecto = new Proyecto($_POST);
    //validacion
    $alertas = $proyecto->validarProyecto();

    if(empty($alertas)){
    //generar url unica
    $hash = md5(uniqid());
    $proyecto->url = $hash;
    //almacenar el creador del proyecto
    $proyecto->propietarioId = $_SESSION['id'];
        //guarda el proyecto
        $proyecto->guardar();
        //redireccionar
        header('Location: /proyecto?id=' . $proyecto->url);
    }
}
    $router->render('dashboard/crear-proyecto', [
        'titulo' => 'Create Proyect',
        'alertas' => $alertas,

    ]);

}
public static function perfil(Router $router){
    session_start();
    $alertas = [];
    $usuario = Usuario::find($_SESSION['id']);
    isAuth();
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $usuario->sincronizar($_POST);
        $alertas = $usuario->validar_perfil();

        if(empty($alertas)) {
            $existeUsuario = Usuario::where('email', $usuario->email);
            if($existeUsuario && $existeUsuario ->id !== $usuario->id){
                //mensaje de error
                Usuario::setAlerta('error', 'the email already exists');
                $alertas = $usuario->getAlertas();

            } else{
                 //guardar usuario
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Changes saved successfully');
            $alertas = $usuario->getAlertas();
            //asigna el nombre nuevo a la barra
            $_SESSION['nombre'] = $usuario->nombre;
            }
        
        }
    }
        $router->render('dashboard/perfil', [
            'titulo' => 'Profile',
            'usuario' => $usuario,
            'alertas' => $alertas
    
        ]);
    
    }
    public static function proyecto(Router $router){
        session_start();
        isAuth();
        $token = $_GET['id'];
       


        if(!$token) header('Location: /dashboard');
        //revisar que la persona que visita el proyecto es quien lo creo
        $proyecto = Proyecto::where('url', $token);
        if($proyecto->propietarioId !== $_SESSION['id']){
            header('Location: /dashboard');
        }



        $router->render('dashboard/proyecto', [
            'titulo' => $proyecto->proyecto,

        ]);
    }
    public static function cambiar_password(Router $router){
        
        session_start();
        isAuth();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario = Usuario::find($_SESSION['id']);
            //sincronizar con los datos del usuario
            $usuario->sincronizar($_POST);
            $alertas = $usuario->nuevo_password();

            if(empty($alertas)){
                $resultado = $usuario->comprobar_password();

                if($resultado){
                    $usuario->password = $usuario->password_nuevo;
                    //eliminar propiedades no necesarias
                    unset($usuario->password_actual);
                    unset($usuario->password_nuevo);

                    //hash el nuevo password
                    $usuario->hashPassword();
                    //asignar el nuevo password
                   $resultado = $usuario->guardar();

                   if($resultado){
                    Usuario::setAlerta('exito', 'Password Changed Successfully');
                    $alertas = $usuario->getAlertas();
                   }

                } else{
                    Usuario::setAlerta('error', 'Incorrect Password');
                    $alertas = $usuario->getAlertas();
                }
            }


        }

        $router->render('dashboard/cambiar-password', [
            'titulo'=> 'Change Password',
            'alertas' => $alertas,
        ]);
    }
}