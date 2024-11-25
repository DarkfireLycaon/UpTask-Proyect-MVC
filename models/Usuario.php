<?php
namespace Model;

class Usuario extends ActiveRecord{
protected static $tabla = 'usuarios';
protected static $columnasDB = ['id', 'nombre', 'email', 'password', 'token', 'confirmado'];

public $id;
public $nombre;
public $email;
public $password;
public $password2;
public $password_actual;
public $password_nuevo;
public $token;
public $confirmado;

 public function __construct($args = [])
 {
    $this->id = $args['id'] ?? null;
    $this->nombre = $args['nombre'] ?? '';
    $this->email = $args['email'] ?? '';
    $this->password = $args['password'] ?? '';
    $this->password2 = $args['password2'] ?? null;
    $this->password_actual = $args['password_actual'] ?? '';
    $this->password_nuevo = $args['password_nuevo'] ?? '';
    $this->token = $args['token'] ?? '';
    $this->confirmado = $args['confirmado'] ?? 0;


 }
 //validar login
 public function validarLogin(){
   if(!$this->email){
      self::$alertas['error'][]='The email of the user is a must';
   }
   if(!$this->password){
      self::$alertas['error'][]='The password of the user is a must';
   }
   if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
      self::$alertas['error'][] = 'Invalid E-mail';

   }
return self::$alertas;
 }
 //validacion para nuevas cuentas
 public function validarNuevaCuenta(){
   if(!$this->nombre){
      self::$alertas['error'][]='The name of the user is a must';
   }
   if(!$this->email){
      self::$alertas['error'][]='The email of the user is a must';
   }
   if(!$this->password){
      self::$alertas['error'][]='The password of the user is a must';
   }
   if(strlen($this->password)<6){
      self::$alertas['error'][]='The  password should contain at least 6 characters';
   }
   if($this->password !== $this->password2){
      self::$alertas['error'][]='The password are differents';
   }
   return self::$alertas;
 }
 public function comprobar_password() : bool {
   return password_verify($this->password_actual, $this->password);
 }
 public function hashPassword () : void {
   $this->password = password_hash($this->password, PASSWORD_BCRYPT);
 }
 //generar token
 public function crearToken() : void {
   $this->token = uniqid();
 }
 public function validarEmail(){
   if(!$this->email){
      self::$alertas['error'][] = 'You must write a valid E-mail';
   }
   if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
      self::$alertas['error'][] = 'Invalid E-mail';

   }
   return self::$alertas;
 }
 public function validarPassword(){
   if(strlen($this->password)<6){
      self::$alertas['error'][]='The  password should contain at least 6 characters';
   }
   if(!$this->password){
      self::$alertas['error'][]='The password of the user is a must';
   }
   return self::$alertas;
 }
 public function validar_perfil(){
   if(!$this->nombre){
      self::$alertas['error'][] = 'You must write a name';
   }
   if(!$this->email){
      self::$alertas['error'][] = 'You must write an email';
   }
   return self::$alertas;
 }
 public function nuevo_password() : array {
   if(!$this->password_actual){
      self::$alertas['error'][] = 'The current password cannot be empty';
   }
   if(!$this->password_nuevo){
      self::$alertas['error'][] = 'The new password cannot be empty';
   }
   if(strlen($this->password_nuevo) <6 ){
      self::$alertas['error'][] = 'The new password must contain at least six characters';
      
   }
   return self::$alertas;
 }
}


