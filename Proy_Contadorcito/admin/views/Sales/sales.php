<?php
session_start();
if ($_SESSION['user_role'] != 1 && $_SESSION['user_role'] != 2) {
    header("Location: ../../../index.php");
    exit();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Contadorcito</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <meta
        content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
        name="viewport" />

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
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5/8/Zj/0uT/ncIYB2F6r8x7f5Rj6H5ztQxyN9s5b" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> -->

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
                            <img
                                src="../assets/img/kaiadmin/logo_light.svg"
                                alt="navbar brand"
                                class="navbar-brand"
                                height="20" />
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

                    <!-- FORMULARIOS MODALES -->
                    <?php
                    include_once('createModal.php');
                    include_once('saleDetailsModal.php');
                    include_once('deleteModal.php');
                    include_once('addClientModal.php');
                    ?>

                    <div>
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <h4 class="card-title">Listado de Ventas</h4>
                                        <button
                                            class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
                                            <i class="fa fa-plus"></i>
                                            Registrar Venta
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">

                                    <div class="table-responsive">
                                        <table
                                            id="saleTable"
                                            class="display table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Empleado</th>
                                                    <th>Cliente</th>
                                                    <th>Total</th>
                                                    <th>Fecha</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Empleado</th>
                                                    <th>Cliente</th>
                                                    <th>Total</th>
                                                    <th>Fecha</th>
                                                    <th>Action</th>
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

                <!-- FOOTER -->
                <?php include_once('../includes/footer.php') ?>
                <!-- End Footer -->
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

        <script src="logic/logic.js">

        </script>

        <script>
            $(document).ready(function() {
                getClients();

                function getClients() {
                    let url = '../../Controllers/saleController.php'
                    let formData = new FormData();
                    formData.append('action', 'GetClients');

                    fetch(url, {
                            method: "POST",
                            body: formData
                        })
                        .then(response => {
                            if (response.ok) {
                                return response.json()
                            } else {
                                throw new Error('Error al obtener los clientes')
                            }
                        })
                        .then(data => {
                            let selects = document.querySelectorAll('#customer_id');

                            selects.forEach((select) => {
                                select.innerHTML = '';
                                let option = document.createElement('option');
                                option.value = '';
                                option.textContent = 'Seleccione un cliente';
                                select.appendChild(option);
                                data.forEach(client => {
                                    let option = document.createElement('option');
                                    option.value = client.id;
                                    option.textContent = client.fullName;
                                    select.appendChild(option);
                                });
                            })
                        })
                        .catch(error => {
                            console.error('Error fetching roles:', error);
                        });
                }

                let addClientModal = document.getElementById("addClientModal");

                addClientModal.addEventListener('submit', (event) => {
                    event.preventDefault();

                    let formData = new FormData(event.target);
                    formData.append("action", 'Create');

                    let url = "../../Controllers/clientController.php";

                    fetch(url, {
                            method: "POST",
                            body: formData
                        }).then(response => response.json())
                        .then(data => {
                            if (data.status == 'error') {
                                addClientModal.querySelector('.modal-body #errorMessage').innerText = data.message;
                            } else if (data.status == 'success') {
                                addClientModal.querySelector('.modal-body #errorMessage').innerText = "";

                                var bootstrapModal = bootstrap.Modal.getInstance(addClientModal);
                                bootstrapModal.hide();

                                getClients();
                                showAlert(data.message);
                            }
                        });
                });
            })
        </script>

        <script>
            $(document).ready(function() {
                function getProducts(select) {
                    let url = '../../Controllers/saleController.php'
                    let formData = new FormData();
                    formData.append('action', 'GetProducts');

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
                            if (select) {
                                fillSelect(select, data);
                            } else {
                                // Si no se pasa un select, llena todos los selects
                                let selects = document.querySelectorAll('.product-select');
                                selects.forEach(select => fillSelect(select, data));
                            }

                        })
                        .catch(error => {
                            console.error('Error fetching roles:', error);
                        });
                }

                // Función para llenar un select específico
                function fillSelect(select, data) {
                    select.innerHTML = '';
                    let option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'Seleccione un Producto';
                    select.appendChild(option);

                    data.forEach(product => {
                        let option = document.createElement('option');
                        option.value = product.id;
                        option.textContent = product.bookName;
                        select.appendChild(option);
                    });
                }

                // Agrega filas en la tabla de productos
                $('#add-row').click(function() {
                    var index = $('#details-table tbody tr').length; // Obtiene la longitud de las filas
                    var fila = `<tr>
                        <td style="width:30%;">
                            <select name="SaleDetails[${index}][ProductID]" class="form-control product-select" required>
                                <option value="">Seleccione un Producto</option>
                            </select>
                        </td>
                        <td style="width:15%;"><input type="number" name="SaleDetails[${index}][Quantity]" class="form-control text-center" value="1" required /></td>
                        <td class="text-center">
                            <span class="productStock">0</span>
                        </td>
                        <td class="text-center">$ 
                            <span class="productPrice">0</span>
                            <input type="hidden" name="SaleDetails[${index}][UnitPrice]" class="form-control text-center" />
                        </td>
                        <td class="text-center">$ <span class="totalDetail">0</span></td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-success"><i class="fa fa-solid fa-check"></i></button>
                            <button type="button" class="btn btn-sm btn-danger remove-row"><i class="fa fa-times"></i></button>
                        </td>
                    </tr>`;
                    $('#details-table tbody').append(fila);

                    const newSelect = $('#details-table tbody tr:last-child .product-select')[0];
                    getProducts(newSelect);
                });

                // Eliminar fila de la tabla
                $('#details-table').on('click', '.remove-row', function() {
                    $(this).closest('tr').remove();
                    calculateTotalSale()
                });





                // searchProductInfo junto con la fila
                $('#details-table').on('change', '.product-select', function() {
                    var selectedProduct = $(this).val();
                    searchProductInfo(selectedProduct, $(this).closest('tr'));
                });

                // trae el precio y el stock del producto y actualiza la fila
                function searchProductInfo(productID, $row) {
                    if (productID) {
                        $.ajax({
                            url: '../../Controllers/saleController.php', // hace la peticion al metodo SearchProductById del controlador sale
                            method: 'POST',
                            data: {
                                product_id: productID,
                                action: "GetProductById"

                            }, // aqui envia el id del producto al metodo 
                            success: function(data) { // data es la informacion del producto que recibimos del metodo en el controlador de sale
                                data = JSON.parse(data)
                                if (data) {
                                    // se actualizan los datos de la fila con los datos que vienen de la (data)
                                    $row.find('.productPrice').text(data.sale_price);
                                    $row.find('input[name$="[ProductName]"]').val(data.bookName); // input oculo guarda el nombre del producto para el envio del email
                                    $row.find('.productStock').text(data.stock);
                                    $row.find('input[name$="[UnitPrice]"]').val(data.sale_price); // input oculto en la vista se usa para el email
                                    var cantidad = parseFloat($row.find('input[name$="[Quantity]"]').val()) || 0;
                                    var total = data.sale_price * cantidad
                                    $row.find('.totalDetail').text(total.toFixed(2));
                                    calculateTotalSale();
                                } else {
                                    console.error('Producto no encontrado');
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('Error al obtener la información del producto:', error);
                            }
                        });
                    }
                }



                // VALIDACIONES 

                // valida que la cantidad solicitada no supere el Stock
                function checkQuantity($row, cantidad, stock) {
                    if (cantidad > stock || cantidad <= 0) { // si la cantidad es mayor al stock o la cantidad es menos o igual 0
                        $row.find('input[name$="[Quantity]"]').addClass('is-invalid'); // agrega una clase al input que lo marca en rojo y retornamos false
                        return false;
                    } else {
                        $row.find('input[name$="[Quantity]"]').removeClass('is-invalid');
                        return true;
                    }
                }
                // llena la fila cuando se selecciona un producto
                $('#details-table').on('change', 'input[name$="[Quantity]"]', function() {
                    var $row = $(this).closest('tr');
                    var cantidad = parseFloat($row.find('input[name$="[Quantity]"]').val()) || 0;
                    console.log(cantidad);
                    var total = parseFloat($row.find('.productPrice').text() * cantidad)
                    var stock = parseFloat($row.find('.productStock').text()) || 0;
                    $row.find('.totalDetail').text(total.toFixed(2));
                    checkQuantity($row, cantidad, stock);
                    calculateTotalSale();
                });

                // suma los totales de los detalles para mostrar el total de la venta
                function calculateTotalSale() {
                    var totalVenta = 0;
                    $('#details-table tbody tr').each(function() {
                        var total = parseFloat($(this).find('.totalDetail').text()) || 0;
                        totalVenta += total;
                    });
                    $('#total').text(totalVenta.toFixed(2));
                }
                // remueve la clase cuando se cambia el producto repetido
                $('#details-table').on('change', '.product-select', function() {
                    var $row = $(this).closest('tr');
                    $row.removeClass('bg-danger');
                });
            });
        </script>
</body>

</html>