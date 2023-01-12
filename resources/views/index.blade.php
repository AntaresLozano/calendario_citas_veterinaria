<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='utf-8' />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Urbanist:wght@100;500&display=swap"
        rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.6.3.js" integrity="sha256-nQLuAZGRRcILA+6dMBOvcRh5Pe310sBpanc6+QBmyVM="
        crossorigin="anonymous"></script>
    {{-- <script src="https://code.jquery.com/jquery-3.6.3.min.js"
        integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script> --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"
        integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofN4zfuZxLkoj1gXtW8ANNCe9d5Y3eG5eD" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.0.2/index.global.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/bootstrap-clockpicker.min.css') }}">
    <script src="{{ asset('js/bootstrap-clockpicker.js') }}"></script>
    <link href="{{ asset('css/app.scss') }}" rel="stylesheet">

</head>

<body>
    <div class="titleHead">
        <div class="left">
            <h1 class="tituloVeterinaria">Citas Veterinaria</h1>
            <div class="pContainer">
                <p>En esta página web tendrás la posibilidad de acceder al calendario de tu veterinaria en el que podrás
                    agendar tu próxima cita!</p>
            </div>
        </div>
        <div class="right">
        </div>
    </div>
    <div class="seccionDos">
        <h2 class="secundaryTitle">Calendario</h2>
        <p class="secundaryP" >
            Con el calendario podrás crear eventos con toda la información necesaria para tus  citas veterinarias como
            también asignarles un color a cada una.   <br>
            Ten en cuenta que si 
        </p>
    </div>
    <div class="container">
        <div class="col-md-10 offset-md-1">
            <div id='calendar'></div>
        </div>
    </div>
    <script src=" {{ asset('js/index.js') }} "></script>
    {{-- Modal (Agregar, modificar, eliminar)  --}}
    <div class="modal fade" id="EventsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="tituloEvento">Agendar Cita</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label> Título:</label>
                    <input type="text" id="title" name="title" class="form-control"
                        placeholder="Título del evento" />
                    Cédula: <input type="number" id="cedula" name="cedula" class="form-control"  placeholder="Cédula del dueño" />
                    Nombre: <input type="text" id="nombre" name="nombre" class="form-control"  placeholder="Nombre del dueño" />
                    Apellido: <input type="text" id="apellido" name="apellido" class="form-control"  placeholder="Apellido del dueño" />
                    Nombre Mascota: <input type="text" id="mascota" name="mascota" class="form-control"  placeholder="Nombre de la mascota" />
                    Fecha: <input type="text" id="fecha" name="fecha" class="form-control"  placeholder="Fecha de la cita" /> <br>

                    <div class="input-group clockpicker">
                        <label for="" class="form-control">Hora: 24Hrs </label> <br />
                        <input type="text" id="hora" value="20:48:00" data-default="20:48"
                            class="form-control" />
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-time"></span>
                        </span>
                    </div><br>

                    Descripción:
                    <textarea id="descripcion" rows="3" class="form-control" placeholder="Descripción de la cita"></textarea>
                    Color: <input type="color" value="#ff0000" id="color" class="form-control"
                        style="height: 36px" />
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