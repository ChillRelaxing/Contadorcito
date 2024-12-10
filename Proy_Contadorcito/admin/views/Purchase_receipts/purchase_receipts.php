<?php
session_start();
if ($_SESSION['user_role'] != 1) {
    header("Location: ../../../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Listado de Comprobantes de Compra</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />

    <!-- Fonts and icons -->
    <script src="../assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: {
                families: ["Public Sans:300,400,500,600,700"]
            },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons",
                ],
                urls: ["../assets/css/fonts.min.css"],
            },
            active: function() {
                sessionStorage.fonts = true;
            },
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/plugins.min.css" />
    <link rel="stylesheet" href="../assets/css/kaiadmin.min.css" />
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <?php include_once('../includes/sidebar.php') ?>
        <!-- End Sidebar -->

        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <!-- Logo Header -->
                    <div class="logo-header" data-background-color="dark">
                        <a href="" class="logo">
                            <img src="../assets/img/kaiadmin/logo_light.svg" alt="navbar brand" class="navbar-brand" height="20" />
                        </a>
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
                <!-- Navbar Header -->
                <?php include_once('../includes/navbar.php') ?>
                <!-- End Navbar -->
            </div>

            <div class="container">
    <div class="page-inner">
        <!-- Modales para Crear, Actualizar y Eliminar -->
        <?php
        include_once('createModal.php');
        include_once('updateModal.php');
        include_once('deleteModal.php');
        ?>

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title">Listado de Comprobantes de Compra</h4>
                        <button
                            class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                            <i class="fa fa-plus"></i> Nuevo Registro
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="receiptsTable" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tipo</th>
                                    <th>Número</th>
                                    <th>Fecha</th>
                                    <th>Total</th>
                                    <th>Proveedor</th>
                                    <th>Empresa</th>
                                    <th>PDF</th>
                                    <th>JSON</th>
                                    <th>Creado</th>
                                    <th>Actualizado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>Tipo</th>
                                    <th>Número</th>
                                    <th>Fecha</th>
                                    <th>Total</th>
                                    <th>Proveedor</th>
                                    <th>Empresa</th>
                                    <th>PDF</th>
                                    <th>JSON</th>
                                    <th>Creado</th>
                                    <th>Actualizado</th>
                                    <th>Acciones</th>
                                </tr>
                            </tfoot>
                            <tbody id="content">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!--   Core JS Files   -->
    <script src="../assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap.min.js"></script>
     <!-- jQuery Scrollbar -->
    <script src="../assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

        <!-- Datatables -->
        <script src="../assets/js/plugin/datatables/datatables.min.js"></script>

        <!-- Bootstrap Notify -->
        <script src="../assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

        <!-- Kaiadmin JS -->
        <script src="../assets/js/kaiadmin.min.js"></script>

        <script>
            $(document).ready(function() {
                getRoles();

                function getRoles() {
                    let url = '../../Controllers/userController.php'
                    let formData = new FormData();
                    formData.append('action', 'GetRoles');

                    fetch(url, {
                            method: "POST",
                            body: formData
                        })
                        .then(response => {
                            if (response.ok) {
                                return response.json()
                            } else {
                                throw new Error('Error al obtener los roles')
                            }
                        })
                        .then(data => {
                            let selects = document.querySelectorAll('#role_id');

                            selects.forEach((select) => {
                                select.innerHTML = '';
                                let option = document.createElement('option');
                                option.value = '';
                                option.textContent = 'Seleccione un rol';
                                select.appendChild(option);
                                data.forEach(rol => {
                                    let option = document.createElement('option');
                                    option.value = rol.id;
                                    option.textContent = rol.roleName;
                                    select.appendChild(option);
                                });
                            })
                        })
                        .catch(error => {
                            console.error('Error fetching roles:', error);
                        });
                }
            })
        </script>

        <script src="logic/logic.js"></script>
</body>

</html>
