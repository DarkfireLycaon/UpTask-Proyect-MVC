<?php include_once __DIR__ . '/header.php' ?>
<div class="contenedor-sm">
<?php include_once __DIR__ . '/../templates/alertas.php'; ?>
<a href="/cambiar-password" class="enlace"> Change Password</a>
<form action="" class="formulario" method="POST" action="/perfil">
<div class="campo">
<label for="Nombre">Name</label>
<input type="text"
value= "<?php echo $usuario->nombre; ?>"
placeholder="Your Name"
name="nombre">
</div>
<div class="campo">
<label for="Nombre">Email</label>
<input type="email"
value= "<?php echo $usuario->email; ?>"
placeholder="Your Email"
name="email">
</div>
<input type="submit" value="Save Changes">
</form>
</div>
<?php include_once __DIR__ . '/footer.php' ?>