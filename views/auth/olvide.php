<div class="contenedor olvide">
<?php include_once __DIR__ . '/../templates/nombreSitio.php'; ?>

    <div class="contenedor-sm">
<p class="descripcion-pagina">Have you forgotten your password? Fill the form</p>
<?php include_once __DIR__ . '/../templates/alertas.php';?>
<form  method="POST" class="formulario" action="/olvide" novalidate>

<div class="campo">
    <label for="email">Email</label>
    <input type="email" name="email" id="email" placeholder="Your Email">
    </div>


<input type="submit" class="boton" value="Send E-mail">
</form>
<div class="acciones">
<a href="/">Do you have an account? Login</a>
<a href="/olvide">Did you forget your password?</a>
</div>
    </div><!-- contenedor sm -->
</div>