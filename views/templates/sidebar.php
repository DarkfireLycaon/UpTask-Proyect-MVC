<aside class="sidebar">
    <div class="contenedor-sidebar">
    <h2>Uptask</h2>
    <div class="cerrar-menu">
<img src="build/img/cerrar.svg" alt="cerrar menu" id="cerrar-menu">
</div>
    </div>
 
    <nav class="sidebar-nav">
        <a class="<?php echo ($titulo === 'Proyects') ? 'activo' : ''; ?>" href="/dashboard">Proyects</a>
        <a class="<?php echo ($titulo === 'Create Proyect') ? 'activo' : ''; ?>" href="/crear-proyecto">Create Proyect</a>
        <a class="<?php echo ($titulo === 'Profile') ? 'activo' : ''; ?>" href="/perfil">Profile</a>

    </nav>
    <div class="cerrar-sesion-mobile">
        <a href="/logout" class="cerrar-sesion">Logout</a>
    </div>
</aside>