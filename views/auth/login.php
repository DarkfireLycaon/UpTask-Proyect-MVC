<div class="contenedor login">
   <?php include_once __DIR__ . '/../templates/nombreSitio.php'; ?>
    <div class="contenedor-sm">
<p class="descripcion-pagina">Login</p>
   <?php include_once __DIR__ . '/../templates/alertas.php'; ?>

<form action="/" method="POST" class="formulario" novalidate>
<div class="campo">
    <label for="email">Email</label>
    <input type="email" name="email" id="email" placeholder="Your Email">
    </div>
    <div class="campo">

    <label for="email">Password</label>
    <input type="password" name="password" id="password" placeholder="Your Password">
    </div>
<input type="submit" class="boton" value="Login">
</form>
<div class="acciones">
<a href="/crear">Still don't have an account? Create one</a>
<a href="/olvide">Did you forget your password?</a>
</div>
    </div><!-- contenedor sm -->
</div>