<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <a href="/tutorial/phpProject2/admin/index.php" class="navbar-brand">Admin tiendita</a>
    <ul class="nav navbar-nav">
      <!--Menu items-->
      <li><a href="index.php">Dashboard</a></li>
      <li><a href="brands.php">Marcas</a></li>
      <li><a href="categories.php">Categorías</a></li>
      <li><a href="products.php">Productos</a></li>
      <li><a href="archived.php">Productos archivados</a></li>
      <?php if(has_permission('admin')): ?>
        <li><a href="users.php">Usuarios</a></li>
      <?php endif; ?>
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" >Hola <?=$user_data['first'];?>!
          <span class="caret"></span>
        </a>
        <ul class="dropdown-menu" role="menu">
          <li><a href="change_password.php">Cambiar contraseña</a></li>
          <li><a href="logout.php">Cerrar sesión</a></li>
        </ul>
      </li>
      <!-- <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $parent['category']; ?> <span class="caret"></span></a>
        <ul class="dropdown-menu" role="menu">
            <li><a href="#"></a></li>
        </ul>
      </li> -->
    </ul>
  </div>
</nav>
