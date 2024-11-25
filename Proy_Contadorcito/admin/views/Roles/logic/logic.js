$(document).ready(function() {
    getData();

    /* Función para cargar los datos con AJAX */
    function getData() {
        let content = document.getElementById("content");
        let url = "../../Controllers/roleController.php";

        if ($.fn.DataTable.isDataTable('#rolesTable')) {
            $('#rolesTable').DataTable().destroy();
        }

        let formData = new FormData();
        formData.append('action', 'ListRoles');

        fetch(url, {
                method: "POST",
                body: formData,
                cache: "no-store"
            })
            .then(response => response.text())
            .then(html => {
                content.innerHTML = html;

                $('#rolesTable').DataTable({
                    pageLength: 25,
                    responsive: true,
                    columnDefs: [{
                        targets: [3],
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
    let createModal = document.getElementById('createModal')
    let updateModal = document.getElementById('updateModal')
    let deleteModal = document.getElementById('deleteModal')

    // modal agregar
    createModal.addEventListener('shown.bs.modal', event => {
        createModal.querySelector('.modal-body #roleName').focus()
    })

    createModal.addEventListener('submit', (event) => {
        event.preventDefault();

        let formData = new FormData(event.target);
        formData.append("action", 'Create');

        let url = "../../Controllers/roleController.php";

        fetch(url, {
                method: "POST",
                body: formData
            }).then(response => response.json())
            .then(data => {
                if (data.status == 'error'){
                    createModal.querySelector('.modal-body #errorMessage').innerText = data.message;
                } else if (data.status == 'success'){
                    createModal.querySelector('.modal-body #errorMessage').innerText = "";

                    var bootstrapModal = bootstrap.Modal.getInstance(createModal);
                    bootstrapModal.hide();

                    getData()
                    showAlert(data.message);
                }
            })
    })

    createModal.addEventListener('hide.bs.modal', event => {
        createModal.querySelector('.modal-body #roleName').value = ""
        createModal.querySelector('.modal-body #description').value = ""
    })


    // logica modal actualizar
    updateModal.addEventListener('shown.bs.modal', event => {
        let button = event.relatedTarget;
        let role_id = button.getAttribute('data-bs-role_id')

        let inputId = updateModal.querySelector('.modal-body #role_id')
        let inputRoleName = updateModal.querySelector('.modal-body #roleName')
        let inputDescription = updateModal.querySelector('.modal-body #description')

        let action = "GetRoleById";
        let url = "../../Controllers/roleController.php"

        let formData = new FormData()
        formData.append('role_id', role_id)
        formData.append('action', action)

        fetch(url, {
                method: "POST",
                body: formData
            }).then(response => {
                if (response.ok) {
                    return response.json()
                } else {
                    throw new Error('Error al obtener los datos')
                }
            })
            .then(data => {
                inputId.value = data.role_id || '';
                inputRoleName.value = data.roleName || '';
                inputDescription.value = data.description || '';
            }).catch(error => {
                console.error('Error:', error);
            });
    })


    updateModal.addEventListener('submit', event => {
        event.preventDefault();

        let url = "../../Controllers/roleController.php"
        let formData = new FormData(event.target)
        formData.append('action', 'Update')

        fetch(url, {
                method: "POST",
                body: formData
            }).then(response => {
                if (response.ok) {
                    return response.json()
                } else {
                    throw new Error('Error al obtener los datos')
                }
            })
            .then(data => {
                if (data.status == 'error'){
                    updateModal.querySelector(".modal-body #errorMessage").innerText = data.message;
                    return;
                } else if (data.status == 'success') {
                    updateModal.querySelector(".modal-body #errorMessage").innerText = '';
                    
                    var bootstrapModal = bootstrap.Modal.getInstance(updateModal);
                    bootstrapModal.hide();

                    showAlert(data.message);
                    getData()
                }
            }).catch(error => {
                console.error('Error:', error);
            });
    })

    // logica modal eliminar
    deleteModal.addEventListener('shown.bs.modal', event => {
        let button = event.relatedTarget
        let role_id = button.getAttribute('data-bs-role_id')
        deleteModal.querySelector('.modal-footer #role_id').value = role_id
    })

    deleteModal.addEventListener('submit', event => {
        event.preventDefault();

        let url = "../../Controllers/roleController.php";
        let formData = new FormData(event.target)
        formData.append('action', 'Delete')

        var bootstrapModal = bootstrap.Modal.getInstance(deleteModal);
        bootstrapModal.hide();

        fetch(url, {
            method: "POST",
            body: formData
        }).then(response => {
            if (response.ok) {
                return response.text()
            } else {
                throw new Error('Error al obtener los datos')
            }
        }).then(data => {
            getData();
            showAlert(data);
        })
    })

    // funcion para mostrar alertas
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
})

