<div class="modal fade" id="modalEditar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="index.php?controller=Usuario&action=actualizar" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_id">
                    <div class="mb-3">
                        <label for="edit_nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="edit_nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_rol" class="form-label">
                            Rol
                        </label>
                        <select id="edit_rol" name="rol" class="form-control" required disabled>
                            <option value="">-- Seleccione un rol --</option>
                            <option value="Estudiante" selected>👨‍🎓 Estudiante</option>
                            <option value="Conductor">🚌 Conductor</option>
                            <option value="Administrador">👨‍💼 Administrador</option>
                        </select>
                        <input type="hidden" name="rol2" id="edit_rol_hidden">
                    </div>
                    <div class="mb-3">
                        <label for="edit_estado" class="form-label">
                            Estado
                        </label>
                        <select id="edit_estado" class="form-control" name="estado" required>
                            <option value="">-- Seleccione un estado --</option>
                            <option value="Activo" selected>Activo</option>
                            <option value="Inactivo">Inactivo</option>
                        </select>
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