<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <li class="nav-item active">
                    <a
                        href="../../index.php"
                        aria-expanded="false">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Components</h4>
                </li>

                <?php if ($_SESSION['user_role'] == 1) { ?>
                    <li class="nav-item">
                        <a href="../Users/users.php">
                            <i class="fas fa-users"></i>
                            <p>Usuarios</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../Roles/roles.php">
                            <i class="fas fa-briefcase"></i>
                            <p>Roles</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../Clients/clients.php">
                            <i class="fas fa-users"></i>
                            <p>Clientes</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../Suppliers/suppliers.php">
                            <i class="fas fa-truck"></i>
                            <p>Proveedores</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../Companies/companies.php">
                            <i class="fas fa-warehouse"></i>
                            <p>empresa</p>
                        </a>
                    </li>
                    
                <?php } ?>
                <li class="nav-item">
                    <a href="../purchase_receipts/purchase_receipts.php">
                        <i class="fas fa-warehouse"></i>
                        <p>Comprobantes de compras</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../Inventory/inventoryIndex.php">
                        <i class="fas fa-warehouse"></i>
                        <p>Comprobantes de ventas</p>
                    </a>
                    </li>
                <li class="nav-item">
                    <a href="../Sales/sales.php">
                        <i class="fas fa-box-open"></i>
                        <p>Reportes</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>