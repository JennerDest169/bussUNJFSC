<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <h2>Editar Conductor</h2>
    <form method="POST" action="index.php?controller=Conductor&action=update" class="mt-3">
        <input type="hidden" name="id_conductor" value="<?= htmlspecialchars($conductor['id_conductor']) ?>">

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Nombre completo:</label>
                <input type="text" name="nombre" value="<?= htmlspecialchars($conductor['nombre']) ?>" class="form-control" required>
            </div>
            <div class="col-md-3 mb-3">
                <label>DNI:</label>
                <input type="text" name="dni" value="<?= htmlspecialchars($conductor['dni']) ?>" class="form-control" required>
            </div>
            <div class="col-md-3 mb-3">
                <label>Teléfono:</label>
                <input type="text" name="telefono" value="<?= htmlspecialchars($conductor['telefono']) ?>" class="form-control">
            </div>
            <div class="col-md-4 mb-3">
                <label>N° Licencia:</label>
                <input type="text" name="licencia" value="<?= htmlspecialchars($conductor['licencia']) ?>" class="form-control" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="index.php?controller=Conductor&action=index" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>
