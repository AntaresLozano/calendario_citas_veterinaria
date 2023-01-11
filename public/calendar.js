document.addEventListener('DOMContentLoaded', function() {
    var nuevoEvento;
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridWeek',
        locale: "es",
        headerToolbar: {
            left: 'prev,next,miboton',
            center: 'title',
            right: 'dayGridDay,dayGridWeek,dayGridMonth'
        },
        // droppable: true,
        customButtons: {
            miboton: {
                text: "mi boton",
                click: function() {
                    alert('clicked')
                }
            }
        },
        dateClick: function(date, jsEvent, view) {
            // console.log(date.date)
            var vFecha = date.date;

            var vDia = vFecha.getDate();
            var vMes = vFecha.getMonth() + 1;
            var vAnio = vFecha.getFullYear();
            // var vHour = vFecha.getHours();

            vDia = vDia < 10 ? "0" + vDia : vDia;
            vMes = vMes < 10 ? "0" + vMes : vMes;

            console.log("día:", vDia)
            console.log("mes:", vMes)
            console.log("año:", vAnio)
            // console.log("hora:", vHour)

            var formatedDate = vAnio + "-" + vMes + "-" + vDia;
            // console.log("fecha :", formatedDate)
            $("#fecha").val(formatedDate);
            $("#EventsModal").modal('show');

        },
        events: 'http://127.0.0.1:8000/eventos',
        eventClick: function(calEvent, jsEvent, view) {
            console.log(calEvent)
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
            $("#EventsModal").modal('show');

            $("#btnBorrar").click(function(id) {
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
                    },
                    error: function(error) {
                        console.error(error)
                        // alert('ha habido un error')
                    }

                })
            });
            // Actual
            var fechaActual = new Date();
            var horaActual = fechaActual.getHours()
            var minutosH = fechaActual.getMinutes() / 60;

            /*variable de comparación*/
            var horasMasMinutosActual = parseFloat(horaActual + minutosH);

            // cita
            var horaCitaArray = HoraCompleta[0].split(":");
            var horaCita = parseFloat(horaCitaArray[0]);
            var MinutosHCita = parseFloat(horaCitaArray[1] / 60);

            /*variable de comparación*/
            var horasMasMinutosCita = horaCita + MinutosHCita;

            // console.log("cita", horasMasMinutosCita)
            // console.log("actual",horasMasMinutosActual)
            console.log("fechaActual", fechaActual)

            // if( >= horasMasMinutosActual + 2  ){
            //     modificar()
            // }else{
            //     if(horasMasMinutosCita >= horasMasMinutosActual + 2  ){
            //     modificar()
            // }
            // }

            // function modificar (){
            $("#btnModificar").click(function() {
                recolectarDatosGUI();

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content')
                    },
                    type: "PUT",
                    url: "http://127.0.0.1:8000/update" + "/" + calEvent.event
                        .id,
                    data: nuevoEvento,
                    success: () => {
                        $("#EventsModal").modal('hide');
                        calendar.refetchEvents();
                    },
                    error: function(error) {
                        console.error(error)
                        // alert('ha habido un error')
                    }

                })
            });
            // }


        }

    });
    calendar.render();
    $("#btnAgregar").click(function() {
        recolectarDatosGUI();
        // console.log(nuevoEvento)
        enviarDatos(nuevoEvento);
    });


    function recolectarDatosGUI() {
        nuevoEvento = {
            // id: $("#txtId").val(),
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

    function enviarDatos(eventInfo) {
        console.log(eventInfo)
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
            },
            error: function(error) {
                console.error(error)
                error.responseJSON.message.includes("Duplicate entry") ? alert(
                        'La fecha asignada no se encuentra disponible, por favor asigna otra') :
                    null;
                error.responseJSON.message.includes("The given data was invalid") ? alert(
                    'Todos los campos son necesarios') : null;
            }
        })
    }
})