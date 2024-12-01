<!-- modal agregar -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="createModalLabel">Agregar reporte</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="post" enctype="multipart/form-data">

                    <label for="report_type" class="form-label">Tipo de Compañía:</label>
                    <select id="report_type" name="report_type" class="form-select" required>
                        <option value="Ventas">Ventas</option>
                        <option value="Compras">Compras</option>
                    </select>

                    <div class="mb-3">
                        <label for="start_date" class="form-label">Fecha de inicio:</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="end_date" class="form-label">Fecha final:</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" required>
                    </div>

                    <label for="status" class="form-label">Estado:</label>
                    <select id="status" name="status" class="form-select" required>
                    <option value="Pendiente">Pendiente</option>
                        <option value="Generado">Generado</option>
                        <option value="Cancelado">Cancelado</option>
                    </select>

                    <div class="mb-3">
                        <label for="company_id">empresa: </label>
                        <select class="form-select form-control" name="company_id" id="company_id" required>
                            <option value="">Seleccione una empresa</option>
                        </select>
                    </div>
                   
                    <div class="mb-3">
                        <label for="user_id">usuario: </label>
                        <select class="form-select form-control" name="user_id" id="user_id" required>
                            <option value="">Seleccione un usuario</option>
                        </select>
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