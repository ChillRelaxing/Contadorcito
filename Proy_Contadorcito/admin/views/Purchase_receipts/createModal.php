<?php
require_once(__DIR__ . '/../../models/Supplier.php');
require_once(__DIR__ . '/../../Models/company.php');

try {
    $supplier = new Supplier();
    $company = new Company();

    // Obtener proveedores
    $suppliers = $supplier->getAllSuppliers();

    // Obtener empresas
    $companies = $company->getAllCompanies();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

?>


<!-- Modal: Crear Comprobante -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Nuevo Comprobante de Compra</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="receiptType">Tipo de Comprobante</label>
                        <select class="form-control" name="receiptType" id="receiptType" required>
                            <option value="">Seleccione...</option>
                            <option value="Crédito Fiscal">Crédito Fiscal</option>
                            <option value="Consumidor Final">Consumidor Final</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="receiptNumber">Número de Comprobante</label>
                        <input type="text" class="form-control" name="receiptNumber" id="receiptNumber" required>
                    </div>
                    <div class="form-group">
                        <label for="purchase_date">Fecha de Compra</label>
                        <input type="date" class="form-control" name="purchase_date" id="purchase_date" required>
                    </div>
                    <div class="form-group">
                        <label for="total">Total ($)</label>
                        <input type="number" step="0.01" class="form-control" name="total" id="total" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="supplier_id">Proveedor:</label>
                        <select id="supplier_id" name="supplier_id" class="form-control">
                            <option value="">Seleccione un proveedor</option>
                            <?php foreach ($suppliers as $supplier): ?>
                                <option value="<?= $supplier['id']; ?>"><?= $supplier['supplierName']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="company_id">Empresa:</label>
                        <select id="company_id" name="company_id" class="form-control">
                            <option value="">Seleccione una empresa</option>
                            <?php foreach ($companies as $company): ?>
                                <option value="<?= $company['id']; ?>"><?= $company['company_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="pdf_path">Archivo PDF</label>
                        <input type="file" class="form-control" name="pdf_path" id="pdf_path" accept="application/pdf" required>
                    </div>
                    <div class="form-group">
                        <label for="json_path">Archivo JSON</label>
                        <input type="file" class="form-control" name="json_path" id="json_path" accept="application/json" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
