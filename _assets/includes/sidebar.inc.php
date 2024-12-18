<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

<!-- Sidebar - Brand -->
<a class="sidebar-brand d-flex align-items-center justify-content-center" href="./">
    <div class="sidebar-brand-icon rotate-n-15">
        <i class="fas fa-laugh-wink"></i>
    </div>
    <div class="sidebar-brand-text mx-3">SB Admin <sup>2</sup></div>
</a>

<!-- Divider -->
<hr class="sidebar-divider my-0">

<div class="sidebar-heading">
    Movimientos
</div>

<div class="navbar bg-body-tertiary">
    <a class="nav-link" href="#"><img src="_assets/img/coin.png" width="30"> <?php print $get_user['dino_coins']; ?></a>
    </div>

<!-- Nav Item - Dashboard -->
<li class="nav-item active">
    <a class="nav-link" href="index.php">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Dashboard</span></a>
</li>
<!-- Divider -->
<hr class="sidebar-divider">

<li class="nav-item">
    <a class="nav-link" href="sales.php">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Vender</span></a>
</li>
<li class="nav-item">
    <a class="nav-link" href="money.php">
        <i class="fas fa-fw fa-chart-area"></i>
        <span>Transferir</span></a>
</li>
<!-- Divider -->
<hr class="sidebar-divider">

<!-- Heading -->
<div class="sidebar-heading">
    Productos
</div>

<li class="nav-item">
    <a class="nav-link" href="products.php">
        <i class="fas fa-fw fa-chart-area"></i>
        <span>Mis Productos</span></a>
</li>
<li class="nav-item">
    <a class="nav-link" href="products.php?action=save_product_form">
        <i class="fas fa-fw fa-chart-area"></i>
        <span>Agregar Producto</span></a>
</li>

<?php
if ($get_user['is_admin']==1)
{
?>

<div class="sidebar-heading">
    Usuarios
</div>

<li class="nav-item">
    <a class="nav-link" href="./">
        <i class="fas fa-fw fa-chart-area"></i>
        <span>Mis vendedores</span></a>
</li>

<li class="nav-item">
    <a class="nav-link" href="buyers.php">
        <i class="fas fa-fw fa-chart-area"></i>
        <span>Mis compradores</span></a>
</li>

<?php
}
?>
<!-- Nav Item - Utilities Collapse Menu 
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
        aria-expanded="true" aria-controls="collapseUtilities">
        <i class="fas fa-fw fa-wrench"></i>
        <span>Utilities</span>
    </a>
    <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
        data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Custom Utilities:</h6>
            <a class="collapse-item" href="utilities-color.html">Colors</a>
            <a class="collapse-item" href="utilities-border.html">Borders</a>
            <a class="collapse-item" href="utilities-animation.html">Animations</a>
            <a class="collapse-item" href="utilities-other.html">Other</a>
        </div>
    </div>
</li>-->

<!-- Divider -->
<hr class="sidebar-divider">


</ul>
<!-- End of Sidebar -->