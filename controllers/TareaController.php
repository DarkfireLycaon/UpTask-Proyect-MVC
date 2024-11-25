<?php

namespace Controllers;

use Model\Proyecto;
use Model\Tarea;

class TareaController{
    public static function index (){
        session_start();
        $proyectoId = $_GET['id'];

        if(!$proyectoId)header('Location: /dashboard');

        $proyecto = Proyecto::where('url', $proyectoId);
        if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) header ('Location: /404');

        $tareas = Tarea::belongsTo('proyectoId', $proyecto->id);
        echo json_encode(['tareas' => $tareas]);
    }
    public static function crear(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    
            session_start();
    
            // Get project ID from the POST data
            $proyectoId = $_POST['proyectoId'];
    
            // Find the project by its URL or unique identifier
            $proyecto = Proyecto::where('url', $proyectoId);
    
            // Check if project exists and if the current user is the owner
            if (!$proyecto || $proyecto->propietarioId != $_SESSION['id']) {
                // Return an error response if project not found or user doesn't own the project
                $respuesta = [
                    'tipo' => 'error', 
                    'mensaje' => 'There was an error on creating the task'
                ];
                echo json_encode($respuesta);
                return;
            }
    
            // Create a new task based on the POST data
            $tarea = new Tarea($_POST);
            $tarea->proyectoId = $proyecto->id;
            
            // Save the task and check if it was successful
            $resultado = $tarea->guardar();
            
            if ($resultado) {
                // Respond with success and task details
                $respuesta = [
                    'tipo' => 'exito',
                    'id' => $tarea->id,
                    'proyectoId' => $proyecto->id,
                    'mensaje' => 'Task created successfully', // Update message to reflect task creation
                     'proyectoId' => $proyecto->id
                ];
                echo json_encode($respuesta);
            } else {
                // In case saving the task fails, send an error response
                $respuesta = [
                    'tipo' => 'error', 
                    'mensaje' => 'Failed to create the task'
                ];
                echo json_encode($respuesta);
            }
        }
    }
    public static function actualizar(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
              //validar que el proyecto exista
              $proyecto = Proyecto::where('url', $_POST['proyectoId']);
              session_start();
  
              if (!$proyecto || $proyecto->propietarioId != $_SESSION['id']) {
                  // Return an error response if project not found or user doesn't own the project
                  $respuesta = [
                      'tipo' => 'error', 
                      'mensaje' => 'There was an error on uploading the task'
                  ];
                  echo json_encode($respuesta);
                  return;
              }
              $tarea = new Tarea($_POST);
              $tarea->proyectoId = $proyecto->id;
  
              $resultado = $tarea->guardar();
              if($resultado ){
                  $respuesta = [
                      'tipo' => 'exito',
                      'id' => $tarea->id,
                      'proyectoId' => $proyecto->id,
                      'mensaje' => 'Updates successfully'
                      
                  ];
                  echo json_encode(['respuesta' => $respuesta]);
                }
        }

    }
    public static function eliminar(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){

            $proyecto = Proyecto::where('url', $_POST['proyectoId']);
            session_start();

            if (!$proyecto || $proyecto->propietarioId != $_SESSION['id']) {
                // Return an error response if project not found or user doesn't own the project
                $respuesta = [
                    'tipo' => 'error', 
                    'mensaje' => 'There was an error on uploading the task'
                ];
                echo json_encode($respuesta);
                return;
            }
            $tarea = new Tarea($_POST);
            $resultado = $tarea->eliminar();

            $resultado = [
                'resultado' => $resultado,
                'mensaje' => 'Deleted Successfully',
                'tipo' => 'exito'
            ];
             echo json_encode($resultado);
          
            }

        }

    
}