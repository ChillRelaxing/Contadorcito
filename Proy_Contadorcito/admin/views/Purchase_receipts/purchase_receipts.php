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
    <title>Recepciones de Compras</title>
    <link rel="stylesheet" href="../assets/css/style.css">
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
                    <div class="logo-header" data-background-color="dark">
                        <a href="" class="logo">
                            <img src="assets/img/kaiadmin/logo_light.svg" alt="navbar brand" class="navbar-brand" height="20" />
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
                </div>
                <?php include_once('../includes/navbar.php') ?>
            </div>

            <div class="container">
                <div class="page-inner">
                    <!-- FORMULARIOS MODALES -->
                    <?php
                    include_once('createModal.php');
                    include_once('updateModal.php');
                    include_once('deleteModal.php');
                    ?>

                    <div>
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h4 class="card-title">Listado de Recibos de Compra</h4>
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                                            <i class="fa fa-plus"></i> Nuevo Registro
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="receiptsTable" class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Tipo de recibo</th>
                                                    <th>Fecha de compra</th>
                                                    <th>Total</th>
                                                    <th>PDF</th>
                                                    <th>JSON</th>
                                                    <th>Proveedor</th>
                                                    <th>Usuario</th>
                                                    <th>Empresa</th>
                                                    <th>Creado</th>
                                                    <th>Actualizado</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Tipo de recibo</th>
                                                    <th>Fecha de compra</th>
                                                    <th>Total</th>
                                                    <th>PDF</th>
                                                    <th>JSON</th>
                                                    <th>Proveedor</th>
                                                    <th>Usuario</th>
                                                    <th>Empresa</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </tfoot>
                                            <tbody id="content"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FOOTER -->
                <?php include_once('../includes/footer.php') ?>
            </div>
        </div>

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
                getData();

                function getData() {
                    let content = document.getElementById("content");
                    let url = "../../Controllers/purchase_receiptsController.php";

                    if ($.fn.DataTable.isDataTable('#receiptsTable')) {
                        $('#receiptsTable').DataTable().destroy();
                    }

                    let formData = new FormData();
                    formData.append('action', 'ListReceipts');

                    fetch(url, {
                        method: "POST",
                        body: formData,
                        cache: "no-store"
                    })
                    .then(response => response.text())
                    .then(html => {
                        content.innerHTML = html;
                        $('#receiptsTable').DataTable({
                            pageLength: 25,
                            responsive: true,
                            columnDefs: [{
                                targets: [9],
                                orderable: false,
                                searchable: false
                            }]
                        });
                    })
                    .catch(error => {
                        console.error("Error fetching data:", error);
                    });
                }

                // Modal logic for create, update, delete
                let createModal = document.getElementById("createModal");
                let updateModal = document.getElementById("updateModal");
                let deleteModal = document.getElementById("deleteModal");

                deleteModal.addEventListener("shown.bs.modal", (event) => {
                    let button = event.relatedTarget;
                    let id = button.getAttribute("data-bs-id");
                    deleteModal.querySelector(".modal-footer #id").value = id;
                });

                deleteModal.addEventListener("submit", (event) => {
                    event.preventDefault();
                    let action = "Delete";
                    let formData = new FormData(event.target);
                    formData.append("action", action);

                    var bootstrapModal = bootstrap.Modal.getInstance(deleteModal);
                    bootstrapModal.hide();

                    fetch(url, {
                        method: "POST",
                        body: formData,
                    })
                    .then((response) => {
                        if (response.ok) {
                            return response.text();
                        } else {
                            throw new Error("Error al eliminar el recibo");
                        }
                    })
                    .then((data) => {
                        getData();
                        showAlert(data);
                    });
                });

                createModal.addEventListener("shown.bs.modal", (event) => {
                    createModal.querySelector(".modal-body #receiptType").focus();
                });

                createModal.addEventListener("submit", (event) => {
                    event.preventDefault();
                    let action = "Create";
                    let formData = new FormData(event.target);
                    formData.append("action", action);

                    let url = "../../Controllers/purchase_receiptsController.php";

                    fetch(url, {
                        method: "POST",
                        body: formData,
                    })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data.status == 'error') {
                            createModal.querySelector(".modal-body #errorMessage").innerText = data.message;
                        } else if (data.status == 'success') {
                            var bootstrapModal = bootstrap.Modal.getInstance(createModal);
                            bootstrapModal.hide();
                            getData();
                            showAlert(data.message);
                        }
                    });
                });

                createModal.addEventListener("hide.bs.modal", (event) => {
                    createModal.querySelector(".modal-body #receiptType").value = "";
                    createModal.querySelector(".modal-body #purchase_date").value = "";
                    createModal.querySelector(".modal-body #total").value = "";
                    createModal.querySelector(".modal-body #pdf_path").value = "";
                    createModal.querySelector(".modal-body #json_path").value = "";
                    createModal.querySelector(".modal-body #supplier_id").value = "";
                    createModal.querySelector(".modal-body #user_id").value = "";
                    createModal.querySelector(".modal-body #company_id").value = "";
                    createModal.querySelector(".modal-body #errorMessage").innerText = "";
                });

                updateModal.addEventListener("hide.bs.modal", (event) => {
                    updateModal.querySelector(".modal-body #errorMessage").innerText = "";
                });

                updateModal.addEventListener("shown.bs.modal", (event) => {
                    let button = event.relatedTarget;
                    let id = button.getAttribute("data-bs-id");
                    // Fill form for update
                });
            });
        </script>

        <script src="logic/logic.js"></script>
</body>

</html>