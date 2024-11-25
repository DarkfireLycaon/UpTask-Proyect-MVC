<?php include_once __DIR__ . '/header.php' ?>
<div class="contenedor-sm">
<?php include_once __DIR__ . '/../templates/alertas.php'; ?>
<a href="/perfil" class="enlace">Return to the profile</a>
<form action="" class="formulario" method="POST" action="/cambiar-password">
<div class="campo">
<label for="Nombre">Actual Password</label>
<input type="password"
value= ""
placeholder="Your Actual Password"
name="password_actual">
</div>
<div class="campo">
<label for="Nombre">New Password</label>
<input type="password"
value= ""
placeholder="Your New Password"
name="password_nuevo">
</div>
<input type="submit" value="Save Changes">
</form>
</div>
<?php include_once __DIR__ . '/footer.php' ?>