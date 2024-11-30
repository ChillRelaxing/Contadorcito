$(document).ready(function() {
    getData();

    /* Función para cargar los datos con AJAX */
    function getData() {
        let content = document.getElementById("content");
        let url = "../../Controllers/companyController.php";

        if ($.fn.DataTable.isDataTable('#companiesTable')) {
            $('#companiesTable').DataTable().destroy();
        }

        let formData = new FormData();
        formData.append('action', 'ListCompanies');

        fetch(url, {
                method: "POST",
                body: formData,
                cache: "no-store"
            })
            .then(response => response.text())
            .then(html => {
                content.innerHTML = html;

                $('#companiesTable').DataTable({
                    pageLength: 25,
                    responsive: true,
                    columnDefs: [{
                        targets: [7], // Cambia este índice según tu tabla
                        orderable: false,
                        searchable: false
                    }]
                });
            })
            .catch(error => {
                console.error("Error fetching data:", error);
            });
    }

    // modales
    let createModal = document.getElementById('createModal');
    let updateModal = document.getElementById('updateModal');
    let deleteModal = document.getElementById('deleteModal');

    // modal agregar
    createModal.addEventListener('shown.bs.modal', event => {
        createModal.querySelector('.modal-body #company_name').focus();
    });

    createModal.addEventListener('submit', (event) => {
        event.preventDefault();

        let formData = new FormData(event.target);
        formData.append("action", 'Create');

        let url = "../../Controllers/companyController.php";

        fetch(url, {
                method: "POST",
                body: formData
            }).then(response => response.json())
            .then(data => {
                if (data.status == 'error') {
                    createModal.querySelector('.modal-body #errorMessage').innerText = data.message;
                } else if (data.status == 'success') {
                    createModal.querySelector('.modal-body #errorMessage').innerText = "";

                    var bootstrapModal = bootstrap.Modal.getInstance(createModal);
                    bootstrapModal.hide();

                    getData();
                    showAlert(data.message);
                }
            });
    });

    createModal.addEventListener('hide.bs.modal', event => {
        createModal.querySelector('.modal-body #company_name').value = "";
        createModal.querySelector('.modal-body #company_type').value = "";
        createModal.querySelector('.modal-body #address').value = "";
        createModal.querySelector('.modal-body #phone').value = "";
        createModal.querySelector('.modal-body #email').value = "";
        createModal.querySelector('.modal-body #representative').value = "";

    });

    // lógica modal actualizar
    updateModal.addEventListener('shown.bs.modal', event => {
        let button = event.relatedTarget;
        let id = button.getAttribute('data-bs-id');

        let inputId = updateModal.querySelector('.modal-body #id');
        let inputFirstName = updateModal.querySelector('.modal-body #company_name');
        let inputLastName = updateModal.querySelector('.modal-body #company_type');
        let inputAddress = updateModal.querySelector('.modal-body #address');
        let inputEmail = updateModal.querySelector('.modal-body #phone');
        let inputPhone = updateModal.querySelector('.modal-body #email');
        let inputRepresentative = updateModal.querySelector('.modal-body #representative');

        let action = "GetCompanyById";
        let url = "../../Controllers/companyController.php";

        let formData = new FormData();
        formData.append('id', id);
        formData.append('action', action);

        fetch(url, {
                method: "POST",
                body: formData
            }).then(response => {
                if (response.ok) {
                    return response.json();
                } else {
                    throw new Error('Error al obtener los datos');
                }
            })
            .then(data => {
                inputId.value = data.id || '';
                inputFirstName.value = data.firstName || '';
                inputLastName.value = data.lastName || '';
                inputAddress.value = data.address || '';
                inputEmail.value = data.email || '';
                inputPhone.value = data.phone || '';
                inputRepresentative.value = data.representative || '';

            }).catch(error => {
                console.error('Error:', error);
            });
    });

    updateModal.addEventListener('submit', event => {
        event.preventDefault();

        let url = "../../Controllers/companyController.php";
        let formData = new FormData(event.target);
        formData.append('action', 'Update');

        fetch(url, {
                method: "POST",
                body: formData
            }).then(response => {
                if (response.ok) {
                    return response.json();
                } else {
                    throw new Error('Error al obtener los datos');
                }
            })
            .then(data => {
                if (data.status == 'error') {
                    updateModal.querySelector(".modal-body #errorMessage").innerText = data.message;
                    return;
                } else if (data.status == 'success') {
                    updateModal.querySelector(".modal-body #errorMessage").innerText = '';

                    var bootstrapModal = bootstrap.Modal.getInstance(updateModal);
                    bootstrapModal.hide();

                    showAlert(data.message);
                    getData();
                }
            }).catch(error => {
                console.error('Error:', error);
            });
    });

    // lógica modal eliminar
    deleteModal.addEventListener('shown.bs.modal', event => {
        let button = event.relatedTarget;
        let id = button.getAttribute('data-bs-id');
        deleteModal.querySelector('.modal-footer #id').value = id;
    });

    deleteModal.addEventListener('submit', event => {
        event.preventDefault();

        let url = "../../Controllers/companyController.php";
        let formData = new FormData(event.target);
        formData.append('action', 'Delete');

        var bootstrapModal = bootstrap.Modal.getInstance(deleteModal);
        bootstrapModal.hide();

        fetch(url, {
            method: "POST",
            body: formData
        }).then(response => {
            if (response.ok) {
                return response.text();
            } else {
                throw new Error('Error al obtener los datos');
            }
        }).then(data => {
            getData();
            showAlert(data);
        });
    });

    // función para mostrar alertas
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