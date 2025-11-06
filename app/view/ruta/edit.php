<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <h2>Editar Ruta</h2>
    <form method="POST" action="index.php?controller=Ruta&action=update" class="mt-3">
        <input type="hidden" name="id_ruta" value="<?= htmlspecialchars($ruta['id_ruta']) ?>">

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Nombre de la Ruta:</label>
                <input type="text" name="nombre" value="<?= htmlspecialchars($ruta['nombre']) ?>" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Horario:</label>
                <input type="text" name="horario" value="<?= htmlspecialchars($ruta['horario']) ?>" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Origen:</label>
                <input type="text" name="origen" value="<?= htmlspecialchars($ruta['origen']) ?>" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Destino:</label>
                <input type="text" name="destino" value="<?= htmlspecialchars($ruta['destino']) ?>" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Bus Asignado:</label>
                <select name="id_bus" class="form-control" required>
                    <?php foreach ($buses as $bus): ?>
                        <option value="<?= $bus['id_bus'] ?>" <?= $bus['id_bus'] == $ruta['id_bus'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($bus['placa']) ?> - <?= htmlspecialchars($bus['modelo']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label>Conductor Asignado:</label>
                <select name="id_conductor" class="form-control" required>
                    <?php foreach ($conductores as $conductor): ?>
                        <option value="<?= $conductor['id_conductor'] ?>" <?= $conductor['id_conductor'] == $ruta['id_conductor'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($conductor['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="index.php?controller=Ruta&action=index" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>
