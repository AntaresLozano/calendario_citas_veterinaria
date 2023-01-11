<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='utf-8' />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM="
        crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"
        integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"
        integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofN4zfuZxLkoj1gXtW8ANNCe9d5Y3eG5eD" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.0.2/index.global.min.js"></script>
</head>

<body>
    <div class="container">
        <div class="col-md-10 offset-md-1">
            <div id='calendar'></div>
        </div>
    </div>
    <script>
        // droppable: true,
        // customButtons: {
        //     miboton: {
        //         text: "mi boton",
        //         click: function() {
        //             alert('clicked')
        //         }
        //     }
        // },
        document.addEventListener('DOMContentLoaded', function() {
            // let nuevoEvento;
            let calendarEl = document.getElementById('calendar');
            let calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridWeek',
                locale: "es",
                headerToolbar: {
                    left: 'prev,next,miboton',
                    center: 'title',
                    right: 'dayGridDay,dayGridWeek,dayGridMonth'
                },
                dateClick: function(date) {
                    // console.log(date)
                    let unformatedDate = date.date;
                    formatearFecha(unformatedDate)
                    $("#fecha").val(formatedDate);
                    $("#EventsModal").modal('show');
                },
                events: 'http://127.0.0.1:8000/eventos',
                eventClick: function(calEvent) {
                    getEventInfo(calEvent);
                    $("#EventsModal").modal('show');

                    // AGREGAR EVENTO

                    // BORRAR EVENTO
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

                    // MODIFICAR EVENTO
                    let formatedDateEvent = formatearFecha(calEvent.event.start);
                    let fechaHora = calEvent.event.startStr.split("T");
                    let HoraCompleta = fechaHora[1].split("-");
                    // console.log(HoraCompleta)
                    $("#btnModificar").click(function() {
                        // console.log("entró!!")   
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
                                },
                                error: function(error) {
                                    console.error(error)
                                }
                            })
                        } else {
                            alert(
                                "Lo sentimos, para modificar una cita debes hacerlo con dos horas de anterioridad")
                            $("#EventsModal").modal('hide');
                        }
                    });

                }


            });

            calendar.render();
            $("#btnAgregar").click(function() {
                // recolectarDatosGUI();
                enviarDatos(nuevoEvento);
            });

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

                // console.log("cita", horasMasMinutosCita)
                // console.log("actual",horasMasMinutosActual)
                // console.log("fechaActual", fechaActual)

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
                // console.log(date.getDate())
                let vDia = date.getDate();
                let vMes = date.getMonth() + 1;
                let vAnio = date.getFullYear();
                // // let vHour = date.getHours();

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
                nuevoEvento = {
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
                // console.log(eventInfo)
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
                                'La fecha asignada no se encuentra disponible, por favor asigne otra') :
                            null;
                        error.responseJSON.message.includes("The given data was invalid") ? alert(
                            'Todos los campos son necesarios') : null;
                    }
                })
            }
        })
    </script>

    {{-- Modal (Agregar, modificar, eliminar)  --}}

    <div class="modal fade" id="EventsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="tituloEvento">Agendar Cita</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- Id:<input type="text" id="txtId" name="txtId" /> <br /> --}}
                    Título: <input type="text" id="title" name="title" /> <br />
                    Cédula: <input type="number" id="cedula" name="cedula" /> <br />
                    Nombre: <input type="text" id="nombre" name="nombre" /> <br />
                    Apellido: <input type="text" id="apellido" name="apellido" /> <br />
                    Nombre Mascota: <input type="text" id="mascota" name="mascota" /> <br />
                    Fecha: <input type="text" id="fecha" name="fecha" /> <br />
                    Hora: <input type="text" id="hora" value="10:30:00" /> <br />
                    Descripción:
                    <textarea id="descripcion" rows="3"></textarea><br />
                    Color: <input type="color" value="#ff0000" id="color" /> <br />
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnAgregar" class="btn btn-success">Agregar</button>
                    <button type="button" id="btnModificar" class="btn btn-success">Modificar</button>
                    <button type="button" id="btnBorrar" class="btn btn-danger">Borrar</button>
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
