<h2>Usuario</h2>

<form method="POST" action="<?= BASE_URL ?>usuario/acceder">
    <input type="text" name="usuario" placeholder="Usuario" required>
    <input type="password" name="clave" placeholder="Contraseña" required>
    <button type="submit">Entrar</button>
</form>
