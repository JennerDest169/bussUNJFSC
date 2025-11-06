<?php include_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <h2>Registrar Nueva Ruta</h2>
    <form method="POST" action="index.php?controller=Ruta&action=store" class="mt-3">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Nombre de la Ruta:</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Horario:</label>
                <input type="text" name="horario" class="form-control" placeholder="Ej: 07:00 - 09:00" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Origen:</label>
                <input type="text" name="origen" class="form-control" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Destino:</label>
                <input type="text" name="destino" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label>Bus Asignado:</label>
                <select name="id_bus" class="form-control" required>
                    <option value="">-- Seleccionar bus --</option>
                    <?php foreach ($buses as $bus): ?>
                        <option value="<?= $bus['id_bus'] ?>"><?= htmlspecialchars($bus['placa']) ?> - <?= htmlspecialchars($bus['modelo']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label>Conductor Asignado:</label>
                <select name="id_conductor" class="form-control" required>
                    <option value="">-- Seleccionar conductor --</option>
                    <?php foreach ($conductores as $conductor): ?>
                        <option value="<?= $conductor['id_conductor'] ?>"><?= htmlspecialchars($conductor['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="index.php?controller=Ruta&action=index" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php include_once __DIR__ . '/../layouts/footer.php'; ?>
