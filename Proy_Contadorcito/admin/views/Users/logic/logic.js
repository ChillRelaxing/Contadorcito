$(document).ready(function() {
    getData();

    /* Función para cargar los datos de la tabla con AJAX */
    function getData() {
        let content = document.getElementById("content");
        let url = "../../Controllers/userController.php";

        if ($.fn.DataTable.isDataTable('#usersTable')) {
            $('#usersTable').DataTable().destroy();
        }

        let formData = new FormData();
        formData.append('action', 'ListUsers');

        fetch(url, {
                method: "POST",
                body: formData,
                cache: "no-store"
            })
            .then(response => response.text())
            .then(html => {
                content.innerHTML = html;

                $('#usersTable').DataTable({
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


    // logica de los modales
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

        let url = "../../Controllers/userController.php";
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
        createModal.querySelector(".modal-body #firstName").focus();
    });

    createModal.addEventListener("submit", (event) => {
        event.preventDefault();

        let action = "Create";

        let formData = new FormData(event.target);
        formData.append("action", action);

        let url = "../../Controllers/userController.php";

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
        createModal.querySelector(".modal-body #firstName").value = "";
        createModal.querySelector(".modal-body #lastName").value = "";
        createModal.querySelector(
            ".modal-body #userName"
        ).value = "";
        createModal.querySelector(".modal-body #password").value = "";
        createModal.querySelector(".modal-body #email").value = "";
        createModal.querySelector(".modal-body #phone").value = "";
        createModal.querySelector(".modal-body #role_id").value = "";
        createModal.querySelector(".modal-body #errorMessage").innerText = "";
    });


    updateModal.addEventListener("hide.bs.modal", (event) => {
        updateModal.querySelector(".modal-body #errorMessage").innerText = "";
    })


    updateModal.addEventListener("shown.bs.modal", (event) => {
        let button = event.relatedTarget;
        let id = button.getAttribute("data-bs-id");

        let inputId = updateModal.querySelector(".modal-body #id");
        let inputFirstName = updateModal.querySelector(".modal-body #firstName");
        let inputLastName = updateModal.querySelector(".modal-body #lastName");
        let inputUserName = updateModal.querySelector(".modal-body #userName");
        let inputEmail = updateModal.querySelector(".modal-body #email");
        let inputPhone = updateModal.querySelector(".modal-body #phone");
        let inputRole = updateModal.querySelector(".modal-body #role_id");

        let action = "GetUserById";
        let url = "../../Controllers/userController.php";
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
                inputFirstName.value = data.firstName || "";
                inputLastName.value = data.lastName || "";
                inputUserName.value = data.userName || "";
                inputEmail.value = data.email || "";
                inputPhone.value = data.phone || "";
                inputRole.value = data.role_id || "";
            })
            .catch((error) => {
                console.error("Error:", error);
            });
    });

    updateModal.addEventListener("submit", (event) => {
        event.preventDefault();
        let action = "Update"

        let url = "../../Controllers/userController.php";
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
})