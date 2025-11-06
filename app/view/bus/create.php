<?php include 'public/layouts/header.php'; ?>

<div class="container mt-5">
    <h2 class="mb-4 text-center">Registrar Nuevo Bus</h2>

    <form action="index.php?controller=bus&action=store" method="POST" class="shadow p-4 rounded bg-light">
        <div class="mb-3">
            <label for="placa" class="form-label">Placa</label>
            <input type="text" name="placa" id="placa" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="capacidad" class="form-label">Capacidad</label>
            <input type="number" name="capacidad" id="capacidad" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select name="estado" id="estado" class="form-select" required>
                <option value="Activo">Activo</option>
                <option value="Inactivo">Inactivo</option>
            </select>
        </div>

        <div class="d-flex justify-content-between">
            <a href="index.php?controller=bus&action=index" class="btn btn-secondary">Volver</a>
            <button type="submit" class="btn btn-success">Guardar</button>
        </div>
    </form>
</div>

<?php include 'public/layouts/footer.php'; ?>
