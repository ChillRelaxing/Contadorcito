$(document).ready(function() {
    getData();

    /* Función para cargar los datos de la tabla con AJAX */
    function getData() {
        let content = document.getElementById("content");
        let url = "../../Controllers/sales_receiptsController.php";

        if ($.fn.DataTable.isDataTable('#salesReceiptsTable')) {
            $('#salesReceiptsTable').DataTable().destroy();
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

                $('#salesReceiptsTable').DataTable({
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

    // Lógica de los modales
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

        let url = "../../Controllers/sales_receiptsController.php";
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
                    throw new Error("Error al obtener los datos");
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

        let url = "../../Controllers/sales_receiptsController.php";

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
        createModal.querySelector(".modal-body #sale_date").value = "";
        createModal.querySelector(".modal-body #total").value = "";
        createModal.querySelector(".modal-body #pdf_path").value = "";
        createModal.querySelector(".modal-body #json_path").value = "";
        createModal.querySelector(".modal-body #client_id").value = "";
        createModal.querySelector(".modal-body #user_id").value = "";
        createModal.querySelector(".modal-body #company_id").value = "";
        createModal.querySelector(".modal-body #errorMessage").innerText = "";
    });

    updateModal.addEventListener("hide.bs.modal", (event) => {
        updateModal.querySelector(".modal-body #errorMessage").innerText = "";
    })

    updateModal.addEventListener("shown.bs.modal", (event) => {
        let button = event.relatedTarget;
        let id = button.getAttribute("data-bs-id");

        let inputId = updateModal.querySelector(".modal-body #id");
        let inputReceiptType = updateModal.querySelector(".modal-body #receiptType");
        let inputSaleDate = updateModal.querySelector(".modal-body #sale_date");
        let inputTotal = updateModal.querySelector(".modal-body #total");
        let inputPdfPath = updateModal.querySelector(".modal-body #pdf_path");
        let inputJsonPath = updateModal.querySelector(".modal-body #json_path");
        let inputClientId = updateModal.querySelector(".modal-body #client_id");
        let inputUserId = updateModal.querySelector(".modal-body #user_id");
        let inputCompanyId = updateModal.querySelector(".modal-body #company_id");

        let action = "GetReceiptById";
        let url = "../../Controllers/sales_receiptsController.php";
        let formData = new FormData();
        formData.append("id", id);
        formData.append("action", action);

        fetch(url, {
                method: "POST",
                body: formData,
            })
            .then((response) => {
                if (response.ok) {
                    return response.json();
                } else {
                    throw new Error("Error al obtener los datos");
                }
            })
            .then((data) => {
                inputId.value = data.id || "";
                inputReceiptType.value = data.receiptType || "";
                inputSaleDate.value = data.sale_date || "";
                inputTotal.value = data.total || "";
                inputPdfPath.value = data.pdf_path || "";
                inputJsonPath.value = data.json_path || "";
                inputClientId.value = data.client_id || "";
                inputUserId.value = data.user_id || "";
                inputCompanyId.value = data.company_id || "";
            })
            .catch((error) => {
                console.error("Error:", error);
            });
    });

    updateModal.addEventListener("submit", (event) => {
        event.preventDefault();
        let action = "Update"

        let url = "../../Controllers/sales_receiptsController.php";
        let formData = new FormData(event.target);
        formData.append("action", action);

        fetch(url, {
                method: "POST",
                body: formData,
            })
            .then((response) => {
                if (response.ok) {
                    return response.json();
                } else {
                    throw new Error("Error al obtener los datos");
                }
            })
            .then((data) => {
                if (data.status == 'error'){
                    updateModal.querySelector(".modal-body #errorMessage").innerText = data.message;
                } else if (data.status == 'success'){
                    var bootstrapModal = bootstrap.Modal.getInstance(updateModal);
                    bootstrapModal.hide();

                    getData();
                    showAlert(data.message);
                }
            })
            .catch((error) => {
                console.error("Error:", error);
            });
    });

    function showAlert(message) {
        $.notify({
            icon: 'icon-bell',
            title: 'Kaiadmin',
            message: `${message}`,
        }, {
            type: 'success',
            allow_dismiss: true,
            placement: {
                from: "bottom",
                align: "right"
            },
            delay: 4000,
            timer: 1000,
            animate: {
                enter: 'animated fadeInDown',
                exit: 'animated fadeOutUp'
            },
            z_index: 1031
        });
    }
});