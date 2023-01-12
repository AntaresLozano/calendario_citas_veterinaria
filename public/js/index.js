document.addEventListener('DOMContentLoaded', function () {
    let calendarEl = document.getElementById('calendar');
    let calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridWeek',
        locale: "es",
        headerToolbar: {
            left: 'prev,next',
            center: 'title',
            right: 'dayGridDay,dayGridWeek,dayGridMonth'
        },
        dateClick: function (date) {
            limpiarDatos();
            let unformatedDate = date.date;
            formatearFecha(unformatedDate)
            $("#fecha").val(formatedDate);
            $("#EventsModal").modal('show');
        },
        events: 'http://127.0.0.1:8000/eventos',
        eventClick: function (calEvent) {
            getEventInfo(calEvent);
            $("#EventsModal").modal('show');
            // BORRAR EVENTO
            $("#btnBorrar").click(function (id) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content')
                    },
                    type: "DELETE",
                    url: "http://127.0.0.1:8000/delete" + "/" + calEvent.event
                        .id,
                    data: calEvent.event.id,
                    success: () => {
                        $("#EventsModal").modal('hide');
                        calendar.refetchEvents();
                        swal("Evento Eliminado",
                            "Tu evento ha sido eliminado satisfactoriamente",
                            "success")
                    },
                    error: function (error) {
                        console.error(error)
                    }
                })
            });

            // MODIFICAR EVENTO
            let formatedDateEvent = formatearFecha(calEvent.event.start);
            let fechaHora = calEvent.event.startStr.split("T");
            let HoraCompleta = fechaHora[1].split("-");
            $("#btnModificar").click(function () {
                recolectarDatosGUI();
                if (compareDates(formatedDateEvent, HoraCompleta)) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                'content')
                        },
                        type: "PUT",
                        url: "http://127.0.0.1:8000/update" + "/" + calEvent
                            .event
                            .id,
                        data: nuevoEvento,
                        success: () => {
                            $("#EventsModal").modal('hide');
                            calendar.refetchEvents();
                            swal("Evento Modificado",
                                "Tu evento ha sido modificado satisfactoriamente",
                                "success")
                        },
                        error: function (error) {
                            console.error(error)
                        }
                    })
                } else {
                    swal("Oops",
                        "Lo sentimos, para modificar una cita debes hacerlo con dos horas de anterioridad!",
                        "error")
                    $("#EventsModal").modal('hide');
                }
            });
        }
    });
    calendar.render();
    $("#btnAgregar").click(function () {
        recolectarDatosGUI();
        console.log(formatHours(nuevoEvento.hora))
        enviarDatos(nuevoEvento);
    });
    limpiarDatos();

    function compareDates(formatedDate, HoraCompleta) {

        // Actual
        let fechaActual = new Date();

        let fechaActualFormateada = formatearFecha(fechaActual)
        let horaActual = fechaActual.getHours()
        let minutosH = fechaActual.getMinutes() / 60;

        /*letiable de comparación*/
        let horasMasMinutosActual = parseFloat(horaActual + minutosH);

        // cita
        let horaCitaArray = HoraCompleta[0].split(":");
        let horaCita = parseFloat(horaCitaArray[0]);
        let MinutosHCita = parseFloat(horaCitaArray[1] / 60);

        /*letiable de comparación*/
        let horasMasMinutosCita = horaCita + MinutosHCita;

        if (fechaActualFormateada !== formatedDate) {
            return true;
        } else {
            if (horasMasMinutosCita >= horasMasMinutosActual + 2 && fechaActualFormateada ===
                formatedDate) {
                return true;
            } else {
                return false;
            }
        }
    }

    function formatearFecha(date) {
        let vDia = date.getDate();
        let vMes = date.getMonth() + 1;
        let vAnio = date.getFullYear();

        vDia = vDia < 10 ? "0" + vDia : vDia;
        vMes = vMes < 10 ? "0" + vMes : vMes;
        formatedDate = vAnio + "-" + vMes + "-" + vDia;
        return formatedDate;
    }

    function getEventInfo(calEvent) {
        $("#tituloEvento").html(calEvent.event._def.title);
        $("#descripcion").val(calEvent.event._def.extendedProps.descripcion);
        $("#title").val(calEvent.event._def.title);
        $("#color").val(calEvent.event._def.color);
        $("#cedula").val(calEvent.event._def.extendedProps.cedula);
        $("#nombre").val(calEvent.event._def.extendedProps.nombre);
        $("#apellido").val(calEvent.event._def.extendedProps.apellido);
        $("#mascota").val(calEvent.event._def.extendedProps.mascota);
        let fechaHora = calEvent.event.startStr.split("T");
        let HoraCompleta = fechaHora[1].split("-");
        $("#fecha").val(fechaHora[0]);
        $("#hora").val(HoraCompleta[0]);
    }

    function recolectarDatosGUI() {
        return nuevoEvento = {
            title: $("#title").val(),
            cedula: $("#cedula").val(),
            nombre: $("#nombre").val(),
            apellido: $("#apellido").val(),
            mascota: $("#mascota").val(),
            start: $("#fecha").val() + "T" + $("#hora").val(),
            fecha: $("#fecha").val(),
            hora: $("#hora").val(),
            descripcion: $("#descripcion").val(),
            color: $("#color").val(),
        }
    }

    function limpiarDatos() {
        $("#tituloEvento").html("Nueva Cita");
        $("#title").val("");
        $("#cedula").val("");
        $("#nombre").val("");
        $("#apellido").val("");
        $("#mascota").val("");
        $("#descripcion").val("");
    }

    function enviarDatos(eventInfo) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "POST",
            url: "http://127.0.0.1:8000/create",
            data: eventInfo,
            success: () => {
                $("#EventsModal").modal('hide');
                calendar.refetchEvents();
                swal("Evento creado", "tu evento ha sido creado satisfactoriamente", "success");
            },
            error: function (error) {
                console.error(error)
                error.responseJSON.message.includes("Duplicate entry") ? alert(
                    'La fecha asignada no se encuentra disponible, por favor asigne otra') :
                    null;
                error.responseJSON.message.includes("The given data was invalid") ? swal(
                    'Oops', "Todos los campos son necesarios", "error") : null;
            }
        })
    }
})