<?php include 'public/layouts/header.php'; ?>

<div class="container mt-5">
    <h2 class="mb-4 text-center">Editar Bus</h2>

    <form action="index.php?controller=bus&action=update&id=<?= $bus['id'] ?>" method="POST" class="shadow p-4 rounded bg-light">
        <div class="mb-3">
            <label for="placa" class="form-label">Placa</label>
            <input type="text" name="placa" id="placa" class="form-control" value="<?= htmlspecialchars($bus['placa']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="capacidad" class="form-label">Capacidad</label>
            <input type="number" name="capacidad" id="capacidad" class="form-control" value="<?= htmlspecialchars($bus['capacidad']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select name="estado" id="estado" class="form-select" required>
                <option value="Activo" <?= $bus['estado'] === 'Activo' ? 'selected' : '' ?>>Activo</option>
                <option value="Inactivo" <?= $bus['estado'] === 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
            </select>
        </div>

        <div class="d-flex justify-content-between">
            <a href="index.php?controller=bus&action=index" class="btn btn-secondary">Volver</a>
            <button type="submit" class="btn btn-primary">Actualizar</button>
        </div>
    </form>
</div>

<?php include 'public/layouts/footer.php'; ?>
