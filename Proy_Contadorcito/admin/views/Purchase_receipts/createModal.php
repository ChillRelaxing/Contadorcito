<!-- modal agregar -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="createModalLabel">Agregar recibo de compra</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="post" enctype="multipart/form-data">
                    <!-- Campos del formulario -->
                    <label for="receipt_type" class="form-label">Tipo de recibo:</label>
                    <select id="receipt_type" name="receipt_type" class="form-select" required>
                        <option value="Crédito Fiscal">Crédito Fiscal</option>
                        <option value="Consumidor Final">Consumidor Final</option>
                    </select>

                    <div class="mb-3">
                        <label for="purchase_date" class="form-label">Fecha de compra:</label>
                        <input type="date" name="purchase_date" id="purchase_date" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="total" class="form-label">Total:</label>
                        <input type="number" name="total" id="total" class="form-control" step="0.01" required>
                    </div>

                    <div class="mb-3">
                        <label for="pdf_path" class="form-label">Archivo PDF:</label>
                        <input type="file" name="pdf_path" id="pdf_path" class="form-control" accept="application/pdf" required>
                    </div>

                    <div class="mb-3">
                        <label for="json_path" class="form-label">Archivo JSON:</label>
                        <input type="file" name="json_path" id="json_path" class="form-control" accept="application/json" required>
                    </div>

                    <div class="mb-3">
                        <label for="supplier_id" class="form-label">Proveedor:</label>
                        <select class="form-select form-control" name="supplier_id" id="supplier_id" required>
                            <option value="">Seleccione un proveedor</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="user_id" class="form-label">Usuario:</label>
                        <select class="form-select form-control" name="user_id" id="user_id" required>
                            <option value="">Seleccione un usuario</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="company_id" class="form-label">Empresa:</label>
                        <select class="form-select form-control" name="company_id" id="company_id" required>
                            <option value="">Seleccione una empresa</option>
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