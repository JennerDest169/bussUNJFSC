$(document).ready(function () {
  // ============================================
  // DATATABLES RESPONSIVE - INCIDENCIAS
  // ============================================
  if ($("#incidencias-table").length > 0) {
    if ($.fn.DataTable.isDataTable("#incidencias-table")) {
      $("#incidencias-table").DataTable().destroy();
    }

    $("#incidencias-table").DataTable({
      language: {
        url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json",
      },
      order: [[2, "desc"]],
      responsive: {
        details: {
          type: "column",
          target: 0,
        },
      },
      autoWidth: false, // ⭐ Deshabilitar auto-width
      columnDefs: [
        {
          className: "dtr-control",
          orderable: false,
          targets: 0,
          width: "30px", // ⭐ Ancho fijo para la columna de control
        },
        {
          responsivePriority: 1,
          targets: 1, // Tipo
        },
        {
          responsivePriority: 2,
          targets: 5, // Acciones
        },
        {
          responsivePriority: 3,
          targets: 4, // Estado
        },
        {
          responsivePriority: 4,
          targets: 2, // Fecha
        },
        {
          responsivePriority: 5,
          targets: 3, // Autor
        },
      ],
      // ⭐ Callback para recalcular después de cada redibujado
      drawCallback: function () {
        $(this).css("width", "100%");
      },
    });
  }

  // Sistema de ordenamiento para incidencias de usuarios
  let ordenAscendente = false; // false = descendente (más reciente primero)

  function ordenarIncidencias() {
    const criterio = $("#ordenar").val();
    const contenedor = $("#incidenciasContainer");
    const items = $(".incidencia-item").get();

    items.sort(function (a, b) {
      let valA, valB;

      switch (criterio) {
        case "fecha":
          valA = parseInt($(a).data("fecha"));
          valB = parseInt($(b).data("fecha"));
          break;
        case "estado":
          // Orden: Pendiente > En proceso > Resuelto
          const estadoOrden = { Pendiente: 1, "En proceso": 2, Resuelto: 3 };
          valA = estadoOrden[$(a).data("estado")];
          valB = estadoOrden[$(b).data("estado")];
          break;
        case "tipo":
          valA = $(a).data("tipo").toLowerCase();
          valB = $(b).data("tipo").toLowerCase();
          break;
      }

      if (criterio === "tipo") {
        // Para strings
        if (ordenAscendente) {
          return valA > valB ? 1 : -1;
        } else {
          return valA < valB ? 1 : -1;
        }
      } else {
        // Para números
        if (ordenAscendente) {
          return valA - valB;
        } else {
          return valB - valA;
        }
      }
    });

    // Reinsertar los elementos ordenados
    $.each(items, function (index, item) {
      contenedor.append(item);
    });
  }

  // Evento: cambiar criterio de ordenamiento
  $("#ordenar").on("change", function () {
    ordenarIncidencias();
  });

  // Evento: alternar orden ascendente/descendente
  $("#toggleOrden").on("click", function () {
    ordenAscendente = !ordenAscendente;

    // Cambiar icono
    if (ordenAscendente) {
      $("#iconOrden")
        .removeClass("fa-sort-amount-down")
        .addClass("fa-sort-amount-up");
    } else {
      $("#iconOrden")
        .removeClass("fa-sort-amount-up")
        .addClass("fa-sort-amount-down");
    }

    ordenarIncidencias();
  });

  // Ordenar por defecto al cargar (por fecha descendente)
  ordenarIncidencias();
});

$(document).ready(function () {
  // ============================================
  // DATATABLES RESPONSIVE - COMUNICADOS
  // ============================================
  if ($("#comunicados-table").length > 0) {
    if ($.fn.DataTable.isDataTable("#comunicados-table")) {
      $("#comunicados-table").DataTable().destroy();
    }

    $("#comunicados-table").DataTable({
      language: {
        url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json",
      },
      order: [[3, "desc"]], // ⭐ Ordenar por Fecha (columna 3)
      responsive: {
        details: {
          type: "column",
          target: 0, // ⭐ Columna 0 = columna de control
        },
      },
      autoWidth: false,
      columnDefs: [
        {
          className: "dtr-control",
          orderable: false,
          targets: 0, // ⭐ Columna de control
          width: "30px",
        },
        {
          responsivePriority: 1, // Siempre visible
          targets: 1, // Tipo
        },
        {
          responsivePriority: 2, // Segunda prioridad
          targets: 5, // Acciones
        },
        {
          responsivePriority: 3,
          targets: 2, // Prioridad
        },
        {
          responsivePriority: 4,
          targets: 4, // Estado
        },
        {
          responsivePriority: 5, // Se oculta primero
          targets: 3, // Fecha
        },
      ],
      drawCallback: function () {
        $(this).css("width", "100%");
      },
    });
  }

  // Sistema de ordenamiento para estudiantes
  let ordenAscendenteCom = false;

  function ordenarComunicados() {
    const criterio = $("#ordenarCom").val();
    const contenedor = $("#comunicadosContainer");
    const items = $(".comunicado-item").get();

    items.sort(function (a, b) {
      let valA, valB;

      switch (criterio) {
        case "fecha":
          valA = parseInt($(a).data("fecha"));
          valB = parseInt($(b).data("fecha"));
          break;
        case "prioridad":
          const prioridadOrden = {
            Normal: 3,
            Importante: 2,
            Urgente: 1,
          };
          valA = prioridadOrden[$(a).data("prioridad")];
          valB = prioridadOrden[$(b).data("prioridad")];
          break;
        case "tipo":
          valA = $(a).data("tipo").toLowerCase();
          valB = $(b).data("tipo").toLowerCase();
          break;
      }

      if (criterio === "tipo") {
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

    $.each(items, function (index, item) {
      contenedor.append(item);
    });
  }

  $("#ordenarCom").on("change", function () {
    ordenarComunicados();
  });

  $("#toggleOrdenCom").on("click", function () {
    ordenAscendenteCom = !ordenAscendenteCom;

    if (ordenAscendenteCom) {
      $("#iconOrdenCom")
        .removeClass("fa-sort-amount-down")
        .addClass("fa-sort-amount-up");
    } else {
      $("#iconOrdenCom")
        .removeClass("fa-sort-amount-up")
        .addClass("fa-sort-amount-down");
    }

    ordenarComunicados();
  });

  ordenarComunicados();
});

$(document).ready(function () {
  // Inicializar tooltips
  var tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Variables
  const descripcionField = $("#descripcion");
  const tipoField = $("#tipo");
  const charCount = $("#charCount");
  const qualityBar = $("#qualityBar");
  const qualityMessage = $("#qualityMessage");
  const storageKey = 'incidencia_draft_<?= $usuario["id"] ?? "guest" ?>';

  // ============================================
  // CONTADOR DE CARACTERES Y BARRA DE CALIDAD
  // ============================================
  function updateCharacterCount() {
    const length = descripcionField.val().length;
    charCount.text(length);

    // Calcular porcentaje (100 caracteres = 100%)
    let percentage = Math.min((length / 100) * 100, 100);
    qualityBar.css("width", percentage + "%");

    // Cambiar color según longitud
    qualityBar.removeClass("bg-danger bg-warning bg-info bg-success");

    if (length < 10) {
      qualityBar.addClass("bg-danger");
      qualityMessage.text("⚠️ Necesitas al menos 10 caracteres");
      qualityMessage
        .removeClass("text-success text-info text-warning")
        .addClass("text-danger");
    } else if (length < 50) {
      qualityBar.addClass("bg-warning");
      qualityMessage.text(
        "📝 Reporte básico - Agrega más detalles para mejorar"
      );
      qualityMessage
        .removeClass("text-success text-info text-danger")
        .addClass("text-warning");
    } else if (length < 100) {
      qualityBar.addClass("bg-info");
      qualityMessage.text("👍 Buen reporte - Cuanto más detallado, mejor");
      qualityMessage
        .removeClass("text-success text-warning text-danger")
        .addClass("text-info");
    } else {
      qualityBar.addClass("bg-success");
      qualityMessage.text("✅ Excelente reporte");
      qualityMessage
        .removeClass("text-info text-warning text-danger")
        .addClass("text-success");
    }
  }

  descripcionField.on("input", updateCharacterCount);

  // ============================================
  // PREVIEW DEL MENSAJE
  // ============================================
  function updatePreview() {
    const tipo = tipoField.val();
    const descripcion = descripcionField.val();

    if (tipo) {
      $("#previewTipo").text(tipo);
    } else {
      $("#previewTipo").text("No seleccionado");
    }

    if (descripcion.trim()) {
      $("#previewDescripcion").html(descripcion.replace(/\n/g, "<br>"));
    } else {
      $("#previewDescripcion").html(
        '<em class="text-muted">Aún no has escrito nada...</em>'
      );
    }
  }

  descripcionField.on("input", updatePreview);

  // ============================================
  // ADJUNTAR IMÁGENES
  // ============================================
  $("#imagenes").on("change", function (e) {
    const files = e.target.files;
    const container = $("#imagePreviewContainer");
    container.empty();

    if (files.length > 3) {
      alert("Solo puedes subir un máximo de 3 imágenes");
      $(this).val("");
      return;
    }

    Array.from(files).forEach((file, index) => {
      if (file.size > 5 * 1024 * 1024) {
        alert("La imagen " + file.name + " excede el tamaño máximo de 5MB");
        return;
      }

      const reader = new FileReader();
      reader.onload = function (e) {
        const preview = $(
          '<div class="image-preview-item">' +
            '<img src="' +
            e.target.result +
            '" alt="Preview">' +
            '<button type="button" class="btn-remove-image" data-index="' +
            index +
            '">' +
            '<i class="fas fa-times"></i>' +
            "</button>" +
            "</div>"
        );
        container.append(preview);
      };
      reader.readAsDataURL(file);
    });
  });

  // Remover imagen
  $(document).on("click", ".btn-remove-image", function () {
    $(this).closest(".image-preview-item").remove();
    $("#imagenes").val("");
  });

  // ============================================
  // BOTÓN LIMPIAR CON CONFIRMACIÓN
  // ============================================
  $("#btnLimpiar").on("click", function () {
    if (
      confirm(
        "¿Estás seguro de que deseas limpiar el formulario? Se perderá el borrador guardado."
      )
    ) {
      $("#formIncidencia")[0].reset();
      localStorage.removeItem(storageKey);
      updateCharacterCount();
      updatePreview();
      $("#imagePreviewContainer").empty();
      tipoField.removeClass("is-valid is-invalid");
      descripcionField.removeClass("is-valid is-invalid");
    }
  });

  // ============================================
  // VALIDACIÓN ANTES DE ENVIAR
  // ============================================
  $("#formIncidencia").on("submit", function (e) {
    const descripcion = descripcionField.val().trim();

    if (descripcion.length < 10) {
      e.preventDefault();
      alert("La descripción debe tener al menos 10 caracteres");
      descripcionField.focus();
      return false;
    }

    // Limpiar localStorage al enviar exitosamente
    localStorage.removeItem(storageKey);
  });
});

$(document).ready(function () {
  // Inicializar tooltips
  var tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });
});

// Navegación con teclado
document.addEventListener("keydown", function (e) {
  const lightbox = document.getElementById("lightbox");
  if (lightbox.classList.contains("active")) {
    if (e.key === "Escape") {
      lightbox.classList.remove("active");
      document.body.style.overflow = "auto";
    } else if (e.key === "ArrowRight") {
      nextImage(e);
    } else if (e.key === "ArrowLeft") {
      prevImage(e);
    }
  }
});

// ============================================
// FORMULARIO DE COMUNICADOS
// ============================================
$(document).ready(function () {
  const tituloField = $("#titulo");
  const tipoFieldCom = $("#tipo");
  const prioridadField = $("#prioridad");
  const contenidoField = $("#contenido");
  const fechaVigenciaField = $("#fecha_vigencia");
  const charCountCom = $("#charCountCom");
  const qualityBarCom = $("#qualityBarCom");
  const qualityMessageCom = $("#qualityMessageCom");

  // ============================================
  // CONTADOR DE CARACTERES
  // ============================================

  function updateCharacterCountCom() {
    if (contenidoField.length === 0) return;

    const length = contenidoField.val().length;
    charCountCom.text(length);

    // Calcular porcentaje (200 caracteres = 100%)
    let percentage = Math.min((length / 200) * 100, 100);
    qualityBarCom.css("width", percentage + "%");
    // Cambiar color según longitud
    qualityBarCom.removeClass("bg-danger bg-warning bg-info bg-success");

    if (length < 20) {
      qualityBarCom.addClass("bg-danger");
      qualityMessageCom.text("⚠️ Necesitas al menos 20 caracteres");
      qualityMessageCom
        .removeClass("text-success text-info text-warning")
        .addClass("text-danger");
    } else if (length < 100) {
      qualityBarCom.addClass("bg-warning");
      qualityMessageCom.text("📝 Contenido básico - Agrega más detalles");
      qualityMessageCom
        .removeClass("text-success text-info text-danger")
        .addClass("text-warning");
    } else if (length < 200) {
      qualityBarCom.addClass("bg-info");
      qualityMessageCom.text(
        "👍 Buen comunicado - Cuanto más detallado, mejor"
      );
      qualityMessageCom
        .removeClass("text-success text-warning text-danger")
        .addClass("text-info");
    } else {
      qualityBarCom.addClass("bg-success");
      qualityMessageCom.text("✅ Buen contenido");
      qualityMessageCom
        .removeClass("text-info text-warning text-danger")
        .addClass("text-success");
    }
  }

  contenidoField.on("input", updateCharacterCountCom);

  // ============================================
  // PREVIEW DEL COMUNICADO
  // ============================================
  function updatePreviewCom() {
    const titulo = tituloField.val();
    const tipo = tipoFieldCom.val();
    const prioridad = prioridadField.val();
    const contenido = contenidoField.val();
    const vigencia = fechaVigenciaField.val();

    // Actualizar título
    if (titulo.trim()) {
      $("#previewTitulo").html(titulo);
    } else {
      $("#previewTitulo").html('<em class="text-muted">Sin título</em>');
    }

    // Actualizar tipo
    if (tipo) {
      $("#previewTipoCom").text(tipo);
    } else {
      $("#previewTipoCom").text("No seleccionado");
    }

    // Actualizar prioridad con colores
    const prioridadColors = {
      Normal: "#6c757d",
      Importante: "#FF9800",
      Urgente: "#F44336",
    };

    if (prioridad) {
      $("#previewPrioridad")
        .text(prioridad)
        .css("background-color", prioridadColors[prioridad]);
    } else {
      $("#previewPrioridad")
        .text("No seleccionada")
        .css("background-color", "#6c757d");
    }

    // Actualizar contenido
    if (contenido.trim()) {
      $("#previewContenido").html(contenido.replace(/\n/g, "<br>"));
    } else {
      $("#previewContenido").html(
        '<em class="text-muted">Aún no has escrito nada...</em>'
      );
    }

    // Actualizar vigencia
    if (vigencia) {
      const fecha = new Date(vigencia);
      const opciones = { year: "numeric", month: "long", day: "numeric" };
      $("#previewVigencia").text(fecha.toLocaleDateString("es-ES", opciones));
    } else {
      $("#previewVigencia").text("Sin fecha límite");
    }
  }

  tituloField.on("input", updatePreviewCom);
  tipoFieldCom.on("change", updatePreviewCom);
  prioridadField.on("change", updatePreviewCom);
  contenidoField.on("input", updatePreviewCom);
  fechaVigenciaField.on("change", updatePreviewCom);

  // ============================================
  // BOTÓN LIMPIAR
  // ============================================
  $("#btnLimpiarCom").on("click", function () {
    if (confirm("¿Estás seguro de que deseas limpiar el formulario?")) {
      $("#formComunicado")[0].reset();
      updateCharacterCountCom();
      updatePreviewCom();
      tituloField.removeClass("is-valid is-invalid");
      tipoFieldCom.removeClass("is-valid is-invalid");
      prioridadField.removeClass("is-valid is-invalid");
      contenidoField.removeClass("is-valid is-invalid");
    }
  });

  // ============================================
  // VALIDACIÓN ANTES DE ENVIAR
  // ============================================
  $("#formComunicado").on("submit", function (e) {
    if (contenidoField.length === 0) return true;

    const contenido = contenidoField.val().trim();
    const titulo = tituloField.val().trim();

    if (titulo.length < 5) {
      e.preventDefault();
      alert("El título debe tener al menos 5 caracteres");
      tituloField.focus();
      return false;
    }

    if (contenido.length < 20) {
      e.preventDefault();
      alert("El contenido debe tener al menos 20 caracteres");
      contenidoField.focus();
      return false;
    }
  });
});

// ============================================
// FORMULARIO DE EDITAR COMUNICADOS
// ============================================
$(document).ready(function () {
  const tituloFieldEdit = $("#titulo");
  const tipoFieldEdit = $("#tipo");
  const prioridadFieldEdit = $("#prioridad");
  const estadoFieldEdit = $("#estado");
  const contenidoFieldEdit = $("#contenido");
  const fechaVigenciaFieldEdit = $("#fecha_vigencia");
  const charCountEdit = $("#charCountEdit");
  const qualityBarEdit = $("#qualityBarEdit");
  const qualityMessageEdit = $("#qualityMessageEdit");

  // ============================================
  // CONTADOR DE CARACTERES
  // ============================================
  function updateCharacterCountEdit() {
    if (contenidoFieldEdit.length === 0) return;

    const length = contenidoFieldEdit.val().length;
    charCountEdit.text(length);

    // Calcular porcentaje (200 caracteres = 100%)
    let percentage = Math.min((length / 200) * 100, 100);
    qualityBarEdit.css("width", percentage + "%");

    qualityBarEdit.removeClass("bg-danger bg-warning bg-info bg-success");

    if (length < 20) {
      qualityBarEdit.addClass("bg-danger");
      qualityMessageEdit.text("⚠️ Necesitas al menos 20 caracteres");
      qualityMessageEdit
        .removeClass("text-success text-info text-warning")
        .addClass("text-danger");
    } else if (length < 100) {
      qualityBarEdit.addClass("bg-warning");
      qualityMessageEdit.text("📝 Contenido básico - Agrega más detalles");
      qualityMessageEdit
        .removeClass("text-success text-info text-danger")
        .addClass("text-warning");
    } else if (length < 200) {
      qualityBarEdit.addClass("bg-info");
      qualityMessageEdit.text(
        "👍 Buen comunicado - Cuanto más detallado, mejor"
      );
      qualityMessageEdit
        .removeClass("text-success text-warning text-danger")
        .addClass("text-info");
    } else {
      qualityBarEdit.addClass("bg-success");
      qualityMessageEdit.text("✅ Buen contenido");
      qualityMessageEdit
        .removeClass("text-info text-warning text-danger")
        .addClass("text-success");
    }
  }

  // Inicializar contador con el contenido actual
  updateCharacterCountEdit();
  contenidoFieldEdit.on("input", updateCharacterCountEdit);

  // ============================================
  // PREVIEW DEL COMUNICADO
  // ============================================
  function updatePreviewEdit() {
    const titulo = tituloFieldEdit.val();
    const tipo = tipoFieldEdit.val();
    const prioridad = prioridadFieldEdit.val();
    const estado = estadoFieldEdit.val();
    const contenido = contenidoFieldEdit.val();
    const vigencia = fechaVigenciaFieldEdit.val();

    // Actualizar título
    if (titulo.trim()) {
      $("#previewTituloEdit").html(titulo);
    } else {
      $("#previewTituloEdit").html('<em class="text-muted">Sin título</em>');
    }

    // Actualizar tipo
    if (tipo) {
      $("#previewTipoEdit").text(tipo);
    } else {
      $("#previewTipoEdit").text("No seleccionado");
    }

    // Actualizar prioridad con colores
    const prioridadColors = {
      Normal: "#6c757d",
      Importante: "#FF9800",
      Urgente: "#F44336",
    };

    if (prioridad) {
      $("#previewPrioridadEdit")
        .text(prioridad)
        .css("background-color", prioridadColors[prioridad]);
    }

    // Actualizar estado
    if (estado === "Activo") {
      $("#previewEstadoEdit")
        .text("Activo")
        .removeClass("bg-danger")
        .addClass("bg-success");
    } else {
      $("#previewEstadoEdit")
        .text("Inactivo")
        .removeClass("bg-success")
        .addClass("bg-danger");
    }

    // Actualizar contenido
    if (contenido.trim()) {
      $("#previewContenidoEdit").html(contenido.replace(/\n/g, "<br>"));
    } else {
      $("#previewContenidoEdit").html(
        '<em class="text-muted">Sin contenido</em>'
      );
    }

    // Actualizar vigencia
    if (vigencia) {
      const fecha = new Date(vigencia);
      const opciones = { year: "numeric", month: "long", day: "numeric" };
      $("#previewVigenciaEdit").text(
        fecha.toLocaleDateString("es-ES", opciones)
      );
    } else {
      $("#previewVigenciaEdit").text("Sin fecha límite");
    }
  }

  // Inicializar preview con datos actuales
  updatePreviewEdit();

  // Listeners
  tituloFieldEdit.on("input", updatePreviewEdit);
  tipoFieldEdit.on("change", updatePreviewEdit);
  prioridadFieldEdit.on("change", updatePreviewEdit);
  estadoFieldEdit.on("change", updatePreviewEdit);
  contenidoFieldEdit.on("input", updatePreviewEdit);
  fechaVigenciaFieldEdit.on("change", updatePreviewEdit);

  // ============================================
  // VALIDACIÓN ANTES DE ENVIAR
  // ============================================
  $("#formEditarComunicado").on("submit", function (e) {
    if (contenidoFieldEdit.length === 0) return true;

    const contenido = contenidoFieldEdit.val().trim();
    const titulo = tituloFieldEdit.val().trim();

    if (titulo.length < 5) {
      e.preventDefault();
      alert("El título debe tener al menos 5 caracteres");
      tituloFieldEdit.focus();
      return false;
    }

    if (contenido.length < 20) {
      e.preventDefault();
      alert("El contenido debe tener al menos 20 caracteres");
      contenidoFieldEdit.focus();
      return false;
    }
  });
});

/* ============================================
SISTEMA DE NOTIFICACIONES EN TIEMPO REAL
 ============================================ */
    $(document).ready(function() {
        // Función para actualizar el conteo de notificaciones
        function actualizarNotificaciones() {
            $.ajax({
                url: 'index.php?controller=Notificacion&action=obtenerConteo',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Actualizar badge de incidencias (solo para administradores)
                    if (data.incidencias_nuevas !== undefined) {
                        const badgeIncidencias = $('#badge-incidencias');
                        if (data.incidencias_nuevas > 0) {
                            badgeIncidencias.text(data.incidencias_nuevas).show();
                        } else {
                            badgeIncidencias.hide();
                        }
                    }

                    // Actualizar badge de comunicados
                    if (data.comunicados_nuevos !== undefined) {
                        const badgeComunicados = $('#badge-comunicados');
                        if (data.comunicados_nuevos > 0) {
                            badgeComunicados.text(data.comunicados_nuevos).show();
                        } else {
                            badgeComunicados.hide();
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error al obtener notificaciones:', error);
                }
            });
        }

        // Actualizar inmediatamente al cargar la página
        actualizarNotificaciones();

        // Actualizar cada 5 segundos (5000 ms)
        setInterval(actualizarNotificaciones, 5000);

        // Marcar como visto cuando el usuario hace clic en Incidencias
        $('#nav-incidencias a').on('click', function() {
            $.ajax({
                url: 'index.php?controller=Notificacion&action=marcarComoVisto',
                method: 'POST',
                data: { tipo: 'incidencia' },
                dataType: 'json',
                success: function(data) {
                    // Ocultar el badge inmediatamente
                    $('#badge-incidencias').hide();
                }
            });
        });

        // Marcar como visto cuando el usuario hace clic en Comunicados
        $('#nav-comunicados a').on('click', function() {
            $.ajax({
                url: 'index.php?controller=Notificacion&action=marcarComoVisto',
                method: 'POST',
                data: { tipo: 'comunicado' },
                dataType: 'json',
                success: function(data) {
                    // Ocultar el badge inmediatamente
                    $('#badge-comunicados').hide();
                }
            });
        });
    });



    // ============================================
// LIGHTBOX PARA GALERÍA DE IMÁGENES
// ============================================
let currentImageIndex = 0;
let totalImages = 0;

function openLightbox(index) {
  const lightbox = document.getElementById("lightbox");
  const images = document.querySelectorAll(".imagen-item img");
  
  if (!lightbox || images.length === 0) {
    console.error("No se encontró el lightbox o no hay imágenes");
    return;
  }
  
  totalImages = images.length;
  currentImageIndex = index;
  
  // Actualizar la imagen en el lightbox
  updateLightboxImage(images[currentImageIndex]);
  
  // Mostrar el lightbox
  lightbox.classList.add("active");
  document.body.style.overflow = "hidden";
  
  // Actualizar contador
  document.getElementById("current-image").textContent = currentImageIndex + 1;
  document.getElementById("total-images").textContent = totalImages;
}

function closeLightbox(event) {
  if (event) event.stopPropagation();
  
  const lightbox = document.getElementById("lightbox");
  if (!lightbox) return;
  
  lightbox.classList.remove("active");
  document.body.style.overflow = "auto";
}

function nextImage(event) {
  if (event) event.stopPropagation();
  
  const images = document.querySelectorAll(".imagen-item img");
  if (images.length === 0) return;
  
  currentImageIndex = (currentImageIndex + 1) % totalImages;
  updateLightboxImage(images[currentImageIndex]);
  document.getElementById("current-image").textContent = currentImageIndex + 1;
}

function prevImage(event) {
  if (event) event.stopPropagation();
  
  const images = document.querySelectorAll(".imagen-item img");
  if (images.length === 0) return;
  
  currentImageIndex = (currentImageIndex - 1 + totalImages) % totalImages;
  updateLightboxImage(images[currentImageIndex]);
  document.getElementById("current-image").textContent = currentImageIndex + 1;
}

function updateLightboxImage(img) {
  const lightboxImg = document.getElementById("lightbox-img");
  if (!lightboxImg || !img) return;
  
  lightboxImg.src = img.src;
  lightboxImg.alt = img.alt;
}

// Navegación con teclado
document.addEventListener("keydown", function (e) {
  const lightbox = document.getElementById("lightbox");
  if (lightbox && lightbox.classList.contains("active")) {
    if (e.key === "Escape") {
      closeLightbox(e);
    } else if (e.key === "ArrowRight") {
      nextImage(e);
    } else if (e.key === "ArrowLeft") {
      prevImage(e);
    }
  }
});