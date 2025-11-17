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

      $(document).ready(function() {
    // DataTable para administradores
    $("#comunicados-table").DataTable({
      language: {
        url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
      }
    });

    // Sistema de ordenamiento para estudiantes
    let ordenAscendenteCom = false;

    function ordenarComunicados() {
      const criterio = $('#ordenarCom').val();
      const contenedor = $('#comunicadosContainer');
      const items = $('.comunicado-item').get();

      items.sort(function(a, b) {
        let valA, valB;

        switch (criterio) {
          case 'fecha':
            valA = parseInt($(a).data('fecha'));
            valB = parseInt($(b).data('fecha'));
            break;
          case 'prioridad':
            const prioridadOrden = {
              'Normal': 3,
              'Importante': 2,
              'Urgente': 1
            };
            valA = prioridadOrden[$(a).data('prioridad')];
            valB = prioridadOrden[$(b).data('prioridad')];
            break;
          case 'tipo':
            valA = $(a).data('tipo').toLowerCase();
            valB = $(b).data('tipo').toLowerCase();
            break;
        }

        if (criterio === 'tipo') {
          if (ordenAscendenteCom) {
            return valA > valB ? 1 : -1;
          } else {
            return valA < valB ? 1 : -1;
          }
        } else {
          if (ordenAscendenteCom) {
            return valA - valB;
          } else {
            return valB - valA;
          }
        }
      });

      $.each(items, function(index, item) {
        contenedor.append(item);
      });
    }

    $('#ordenarCom').on('change', function() {
      ordenarComunicados();
    });

    $('#toggleOrdenCom').on('click', function() {
      ordenAscendenteCom = !ordenAscendenteCom;

      if (ordenAscendenteCom) {
        $('#iconOrdenCom').removeClass('fa-sort-amount-down').addClass('fa-sort-amount-up');
      } else {
        $('#iconOrdenCom').removeClass('fa-sort-amount-up').addClass('fa-sort-amount-down');
      }

      ordenarComunicados();
    });

    ordenarComunicados();
  });