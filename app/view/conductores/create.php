<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <h2>Registrar Nuevo Conductor</h2>
    <form method="POST" action="index.php?controller=Conductor&action=store" class="mt-3">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Nombre completo:</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="col-md-3 mb-3">
                <label>DNI:</label>
                <input type="text" name="dni" class="form-control" required>
            </div>
            <div class="col-md-3 mb-3">
                <label>Teléfono:</label>
                <input type="text" name="telefono" class="form-control">
            </div>
            <div class="col-md-4 mb-3">
                <label>N° Licencia:</label>
                <input type="text" name="licencia" class="form-control" required>
            </div>
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="index.php?controller=Conductor&action=index" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>
