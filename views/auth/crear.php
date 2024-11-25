<div class="contenedor crear">
<?php include_once __DIR__ . '/../templates/nombreSitio.php'; ?>

    <div class="contenedor-sm">
<p class="descripcion-pagina">Create your account in UpTask</p>
<?php include_once __DIR__ . '/../templates/alertas.php'; ?>
<form action="/crear" method="POST" class="formulario">
<div class="campo">
    <label for="nombre">Name</label>
    <input type="text" name="nombre" id="nombre" value="<?php echo $usuario->nombre?>" placeholder="Your Name">
    </div>
<div class="campo">
    <label for="email">Email</label>
    <input type="email" name="email" id="email" placeholder="Your Email"value="<?php echo $usuario->email?>" >
    </div>

    <div class="campo">
    <label for="password2">Password</label>
    <input type="password" name="password" id="password2" placeholder="Your Password">
    </div>
    <div class="campo">

<label for="password">Repeat Password</label>
<input type="password" name="password2" id="password" placeholder="Repeat your Password">
</div>

<input type="submit" class="boton" value="Login">
</form>
<div class="acciones">
<a href="/">Do you have an account? Login</a>
<a href="/olvide">Did you forget your password?</a>
</div>
    </div><!-- contenedor sm -->
</div>