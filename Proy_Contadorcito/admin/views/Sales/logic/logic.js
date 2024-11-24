$(document).ready(function () {
  getData();

  /* Función para cargar los datos con AJAX */
  function getData() {
    let content = document.getElementById("content");
    let url = "../../Controllers/saleController.php";

    if ($.fn.DataTable.isDataTable("#saleTable")) {
      $("#saleTable").DataTable().destroy();
    }

    let formData = new FormData();
    formData.append("action", "ListSales");

    fetch(url, {
      method: "POST",
      body: formData,
      cache: "no-store",
    })
      .then((response) => response.text())
      .then((html) => {
        content.innerHTML = html;

        $("#saleTable").DataTable({
          pageLength: 25,
          responsive: true,
          columnDefs: [
            {
              targets: [5],
              orderable: false,
              searchable: false,
            },
          ],
        });
      })
      .catch((error) => {
        console.error("Error fetching data:", error);
      });
  }

  // modales
  let createModal = document.getElementById("createModal");
  let detailModal = document.getElementById("saleDetailsModal");
  let deleteModal = document.getElementById("deleteModal");

  createModal.addEventListener("submit", (event) => {
    event.preventDefault();

    let formData = new FormData(event.target);
    formData.append("action", "Create");

    console.log(formData);

    let url = "../../Controllers/saleController.php";

    if (!validateDuplicateProducts()) {
      createModal.querySelector(".modal-body #errorMessage").innerText =
        "Se tienen productos duplicados";
      return;
    }

    if (!hasProducts()) {
      createModal.querySelector(".modal-body #errorMessage").innerText =
        "Debe agregar productos al detalle";
      return;
    }

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
        if (data.status == "error") {
          createModal.querySelector(".modal-body #errorMessage").innerText =
            data.message;
        } else if (data.status == "success") {
          createModal.querySelector(".modal-body #errorMessage").innerText = "";
          createModal.querySelector("#details-table #table-body").innerHTML =
            "";
          createModal.querySelector("#total").innerText = "$0.00";

          var bootstrapModal = bootstrap.Modal.getInstance(createModal);
          bootstrapModal.hide();

          getData();
          showAlert(data.message);
        }
      });
  });

  createModal.addEventListener("hide.bs.modal", (event) => {
    createModal.querySelector(".modal-body #customer_id").value = "";
    createModal.querySelector(".modal-body #errorMessage").innerText = "";
  });

  // logica modal detalles
  detailModal.addEventListener('shown.bs.modal', event => {
      let button = event.relatedTarget;
      let id = button.getAttribute('data-bs-id')
      console.log(id)

      let action = "GetSaleDetails";
      let url = "../../Controllers/saleController.php"

      let formData = new FormData()
      formData.append('id', id)
      formData.append('action', action)

      fetch(url, {
              method: "POST",
              body: formData
          }).then(response => {
              if (response.ok) {
                  return response.text()
              } else {
                  throw new Error('Error al obtener los datos')
              }
          })
          .then(data => {
            document.querySelector('#saleDetailsTable').innerHTML = data
          }).catch(error => {
              console.error('Error:', error);
          });
  })

  // logica modal eliminar
  deleteModal.addEventListener("shown.bs.modal", (event) => {
    let button = event.relatedTarget;
    let id = button.getAttribute("data-bs-id");
    deleteModal.querySelector(".modal-footer #id").value = id;
  });

  deleteModal.addEventListener("submit", (event) => {
    event.preventDefault();

    let url = "../../Controllers/saleController.php";
    let formData = new FormData(event.target);
    formData.append("action", "Delete");

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
        showAlert(data, "danger");
      });
  });

  // valida que no hay productos repetidos
  function validateDuplicateProducts() {
    var products = []; // aquí guardamos los id de los productos
    var hasDuplicates = false;

    if ($("#details-table tbody tr").length === 0) {
      // si no existen filas no hay duplicados
      return true;
    }

    $("#details-table tbody tr").each(function () {
      // iteramos cada fila de la tabla
      var $row = $(this);
      var productID = $row.find(".product-select").val(); // recuperamos el id del producto (selectList de productos)

      if (products.includes(productID)) {
        // si la lista ya incluye este id significa que el producto está repetido
        $row.addClass("bg-danger"); // agrega una clase a la fila que la pinta en rojo
        hasDuplicates = true; // cambiamos la variable a true
      } else {
        $row.removeClass("bg-danger"); // remueve la clase del fondo rojo de la fila
        products.push(productID); // agrega el id a la lista
      }
    });

    // devolvemos true si no hay duplicados y false si los hay
    return !hasDuplicates;
  }

  // valida que se agrege al menos 1 producto
  function hasProducts() {
    return $("#details-table tbody tr").length > 0; // si la longitud es mayor a cero retorna true (hay al menos un producto) si no false (no hay productos)
  }

  // funcion para mostrar alertas
  function showAlert(message, type = "success") {
    $.notify(
      {
        icon: "icon-bell",
        title: "Kaiadmin",
        message: `${message}`,
      },
      {
        type: type,
        allow_dismiss: true,
        placement: {
          from: "bottom",
          align: "right",
        },
        delay: 4000,
        timer: 1000,
        animate: {
          enter: "animated fadeInDown",
          exit: "animated fadeOutUp",
        },
        z_index: 1031,
      }
    );
  }
});
