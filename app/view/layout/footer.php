<!--footer.php-->
</div>

        <footer class="footer">
          <div class="container-fluid d-flex justify-content-between">
            <nav class="pull-left">
              <ul class="nav">
                <li class="nav-item">
                  <a class="nav-link" href="#">Sistema de Transporte</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#">UNJFSC</a>
                </li>
              </ul>
            </nav>
            <div class="copyright">
              2025, desarrollado por Equipo de Desarrollo
            </div>
          </div>
        </footer>
      </div>
    </div>

    <!--   Core JS Files   -->
    <script src="<?= BASE_URL ?>public/assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="<?= BASE_URL ?>public/assets/js/core/popper.min.js"></script>
    <script src="<?= BASE_URL ?>public/assets/js/core/bootstrap.min.js"></script>
    <script src="<?= BASE_URL ?>public/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <script src="<?= BASE_URL ?>public/assets/js/plugin/datatables/datatables.min.js"></script>
    <script src="<?= BASE_URL ?>public/assets/js/kaiadmin.min.js"></script>

    <script>
      $(document).ready(function () {
        $("#incidencias-table").DataTable({
          language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
          }
        });

        // ⭐ AGREGAR ESTE CÓDIGO COMPLETO AQUÍ
        // Sistema de ordenamiento para incidencias de usuarios
        let ordenAscendente = false; // false = descendente (más reciente primero)
        
        function ordenarIncidencias() {
          const criterio = $('#ordenar').val();
          const contenedor = $('#incidenciasContainer');
          const items = $('.incidencia-item').get();
          
          items.sort(function(a, b) {
            let valA, valB;
            
            switch(criterio) {
              case 'fecha':
                valA = parseInt($(a).data('fecha'));
                valB = parseInt($(b).data('fecha'));
                break;
              case 'estado':
                // Orden: Pendiente > En proceso > Resuelto
                const estadoOrden = {'Pendiente': 1, 'En proceso': 2, 'Resuelto': 3};
                valA = estadoOrden[$(a).data('estado')];
                valB = estadoOrden[$(b).data('estado')];
                break;
              case 'tipo':
                valA = $(a).data('tipo').toLowerCase();
                valB = $(b).data('tipo').toLowerCase();
                break;
            }
            
            if(criterio === 'tipo') {
              // Para strings
              if(ordenAscendente) {
                return valA > valB ? 1 : -1;
              } else {
                return valA < valB ? 1 : -1;
              }
            } else {
              // Para números
              if(ordenAscendente) {
                return valA - valB;
              } else {
                return valB - valA;
              }
            }
          });
          
          // Reinsertar los elementos ordenados
          $.each(items, function(index, item) {
            contenedor.append(item);
          });
        }
        
        // Evento: cambiar criterio de ordenamiento
        $('#ordenar').on('change', function() {
          ordenarIncidencias();
        });
        
        // Evento: alternar orden ascendente/descendente
        $('#toggleOrden').on('click', function() {
          ordenAscendente = !ordenAscendente;
          
          // Cambiar icono
          if(ordenAscendente) {
            $('#iconOrden').removeClass('fa-sort-amount-down').addClass('fa-sort-amount-up');
          } else {
            $('#iconOrden').removeClass('fa-sort-amount-up').addClass('fa-sort-amount-down');
          }
          
          ordenarIncidencias();
        });
        
        // Ordenar por defecto al cargar (por fecha descendente)
        ordenarIncidencias();
      });
    </script>
  </body>
</html>