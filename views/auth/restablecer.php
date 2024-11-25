<div class="contenedor restablecer">
   <?php include_once __DIR__ . '/../templates/nombreSitio.php'; ?>
    <div class="contenedor-sm">
<p class="descripcion-pagina">Write your new Password</p>
<?php include_once __DIR__ . '/../templates/alertas.php'; ?>
<?php if($mostrar){ ?>

<form  method="POST" class="formulario">

    <div class="campo">
    <label for="email">Your New Password</label>
    <input type="password" name="password" id="password" placeholder="Your Password">
    </div>

<input type="submit" class="boton" value="Save Password">
</form>
<?php } ?>
<div class="acciones">
<a href="/crear">Still don't have an account? Create one</a>
<a href="/olvide">Did you forget your password?</a>
</div>
    </div><!-- contenedor sm -->
</div>