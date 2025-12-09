<div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="index.php?controller=Usuario&action=crear" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo</label>
                        <input type="text" class="form-control" id="correo" name="correo" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="rol" class="form-label">
                            Rol
                        </label>
                        <select id="rol" name="rol" class="form-control" required>
                            <option value="">-- Seleccione un rol --</option>
                            <option value="Estudiante" selected>👨‍🎓 Estudiante</option>
                            <option value="Conductor">🚌 Conductor</option>
                            <option value="Administrador">👨‍💼 Administrador</option>
                        </select>
                    </div>
                    <div class="mb-3 extra" style="display: none;">
                        <label for="dni" class="form-label">DNI</label>
                        <input type="number" class="form-control" id="dni" name="dni">
                    </div>
                    <div class="mb-3 extra" style="display: none;">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="number" class="form-control" id="telefono" name="telefono">
                    </div>
                    <div class="mb-3 extra" style="display: none;">
                        <label for="licencia" class="form-label">Licencia</label>
                        <input type="text" class="form-control" id="licencia" name="licencia">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Función para mostrar/ocultar campos según el rol
function toggleExtraFields() {
    const rol = document.getElementById('rol').value;
    const extraFields = document.querySelectorAll('.extra');
    
    if (rol === 'Conductor') {
        // Mostrar campos para conductor
        extraFields.forEach(field => {
            field.style.display = 'block';
        });
    } else {
        // Ocultar campos para otros roles
        extraFields.forEach(field => {
            field.style.display = 'none';
        });
    }
}

// Ejecutar al cargar la página y cuando cambie el select
document.addEventListener('DOMContentLoaded', function() {
    // Configurar evento al cambiar el rol
    document.getElementById('rol').addEventListener('change', toggleExtraFields);
    
    // Ejecutar al cargar para establecer estado inicial
    toggleExtraFields();
});
</script>
