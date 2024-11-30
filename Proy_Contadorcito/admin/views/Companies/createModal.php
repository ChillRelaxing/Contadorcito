<!-- modal agregar -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="createModalLabel">Agregar Cliente</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="company_name" class="form-label">Nombre: </label>
                        <input type="text" name="company_name" id="company_name" class="form-control" required>
                    </div>

                    <label for="company_type" class="form-label">Tipo de Compañía:</label>
                    <select id="company_type" name="company_type" class="form-select" required>
                        <option value="Natural">Natural</option>
                        <option value="Juridica">Jurídica</option>
                    </select>

                    <div class="mb-3">
                        <label for="address" class="form-label">Dirección:</label>
                        <input type="text" name="address" id="address" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Telefono:</label>
                        <input type="text" name="phone" id="phone" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="representative" class="form-label">Representante:</label>
                        <input type="text" name="representative" id="representative" class="form-control" required>
                    </div>

                    <span class="text-danger" id="errorMessage"></span>

                    <div class="">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>

                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>