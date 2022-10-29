<?php
require 'clases/Usuario.php';
session_start();
if (isset($_SESSION['usuario'])) {
  $usuario = unserialize($_SESSION['usuario']);
  $nomApe = $usuario->getNombreApellido();
} else {
  header('Location: index.php');
}



?>


<!DOCTYPE html>
<html>

<head>
  
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <title>Calendario</title>
  <!-- Scripts css -->
  <link rel="stylesheet" href="Assets/css/style.css">
  <link rel="stylesheet" href="Assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="Assets/css/fullcalendar.main.min.css">

  <!-- Scripts js -->
  <script src='Assets/js/jquery.min.js'></script>
  <script src='Assets/js/bootstrap.min.js'></script>
  <script src='Assets/js/moment-with-locales.js'></script>
  <script src='Assets/js/fullcalendar.main.min.js'></script>


</head>


<script>
  document.addEventListener('DOMContentLoaded', async function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      events: 'api.php?Accion=listar',
      dateClick: function(info) {
        limpiarFormulario();
        $('#BotonAgregar').show();
        $('#BotonModificar').hide();
        $('#BotonBorrar').hide();

        if (info.allDay) {
          $('#FechaInicio').val(info.dateStr);
          $('FechaFin').val(info.dateStr);
        } else {
          let fechaHora = info.dateStr.split("T");
          $('#FechaInicio').val(fechaHora[0]);
          $('#FechaFin').val(fechaHora[0]);
          $('#HoraInicio').val(fechaHora[1].substring(0, 5));
        }

        $("#FormularioEventos").modal('show');
      },
      eventClick: function(info) {
      
        $('#BotonAgregar').hide();
        $('#BotonModificar').show();
        $('#BotonBorrar').show();

       
        $('#Id').val(info.event.id);
        $('#Titulo').val(info.event.title);
        $('#Descripcion').val(info.event.extendedProps.description);
        $('#FechaInicio').val(moment(info.event.start).format("YYYY-MM-DD"));
        $('#FechaFin').val(moment(info.event.end).format("YYYY-MM-DD"));
        $('#HoraInicio').val(moment(info.event.start).format("HH:mm"));
        $('#HoraFin').val(moment(info.event.end).format("HH:mm"));
        $('#ColorFondo').val(info.event.backgroundColor);
        $('#ColorTexto').val(info.event.textColor);


        $("#FormularioEventos").modal('show');

      }



    });



    calendar.render();

    //eventos de botones
    $('#BotonAgregar').click(function() {
      let registro = recuperarDatosFormulario();
      agregarRegistro(registro);
      $('#FormularioEventos').modal('hide');
    });

    $('#BotonModificar').click(function() {
      let registro = recuperarDatosFormulario();
      modificarRegistro(registro);
      $('#FormularioEventos').modal('hide');
    });

    $('#BotonBorrar').click(function() {
      let registro = recuperarDatosFormulario();
      borrarRegistro(registro);
      $('#FormularioEventos').modal('hide');
    });

    function agregarRegistro(registro) {
      $.ajax({
        type: 'POST',
        url: 'api.php?Accion=crear',
        data: registro,
        success: function(msg) {
          calendar.refetchEvents();
        },
        error: function(error) {
          alert("Hubo un error al agregar el evento");
        }
      })
    }

    function modificarRegistro(registro) {
      $.ajax({
        type: 'POST',
        url: 'api.php?Accion=modificar',
        data: registro,
        success: function(msg) {
          calendar.refetchEvents();
        },
        error: function(error) {
          alert("Hubo un error al modificar el evento");
        }
      });
    }

    function borrarRegistro(registro) {
      $.ajax({
        type: 'POST',
        url: 'api.php?Accion=borrar',
        data: registro,
        success: function(msg) {
          calendar.refetchEvents();
        },
        error: function(error) {
          alert("Hubo un error al borrar el evento");
        }
      });
    }

    // funciones que interactuan con el formulario
    function limpiarFormulario() {
      $('#Id').val('');
      $('#Titulo').val('');
      $('#Descripcion').val('');
      $('#FechaInicio').val('');
      $('#FechaFin').val('');
      $('#HoraInicio').val('');
      $('#HoraFin').val('');
      $('#ColorFondo').val('#3788D8');
      $('#ColorTexto').val('#ffffff');

    }

    function recuperarDatosFormulario() {
      let registro = {
        id: $("#Id").val(),
        titulo: $('#Titulo').val(),
        descripcion: $('#Descripcion').val(),
        inicio: $('#FechaInicio').val() + ' ' + $('#HoraInicio').val(),
        fin: $('#FechaFin').val() + ' ' + $('#HoraFin').val(),
        colorfondo: $('#ColorFondo').val(),
        colortexto: $('#ColorTexto').val()
      }
      return registro;
    }

  });
</script>


<!-- creacion del calendario -->

<body>
<div class="container">
      <div class="jumbotron text-center">
      <h1>Calendario</h1>
      </div>    
      <div class="text-center">
        <h3><?php echo $nomApe;?></h3>
        <p><a href="logout.php">Cerrar sesión</a></p>
      </div>
        <div class="text-center">
        <p>DATOS ID<p>
        <?php
        //muestra de los datos de la bases de datos
        try {
        $mbd = new PDO('mysql:host=localhost;dbname=calendario', "root", "");
        $sth = $mbd->query('SELECT * FROM eventos');
        foreach($sth as $fila) {
        echo $fila ["id"];
        echo "<br>";
        } 
        } catch (PDOException $e) {
        print "¡Error!: " . $e->getMessage() . "<br/>";
        die();
        }
        ?>
</div>


  <div id='calendar' style="border:1px solid #000; padding:2px"></div>



  <!-- formulario de eventos -->
  <div class="modal fade" id="FormularioEventos" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">

          <button type="button" class="close" data-bs-dismiss="modal" aira-label="Close">
            <span aria-hidden="true">x</span>
          </button>

        </div>

        <form action="./api.php" method="POST">


          <div class="modal=body">
            <input type="hidden" name="Accion" value="crear">
            <input type="hidden" id="Id" name="Id" value="">


            <div class="form-row">
              <div class="form-group col-12">
                <label for="">Titulo del Evento:</label>
                <input type="text" id="Titulo" class="form-control" placeholder="">
              </div>
            </div>


            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="">Fecha de inicio:</label>
                <div class="input-group" data-autoclose="true">
                  <input type="date" id="FechaInicio" class="form-control" value="">
                </div>
              </div>
              <div class="form-group col-md-6" id="TituloHoraInicio">
                <label for="">Hora de inicio:</label>
                <div class="input-group " data-autoclose="true">
                  <input type="time" id="HoraInicio" class="form-control" autocomplete="off">
                </div>
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-6">
                <label for="">Fecha de fin:</label>
                <div class="input-group" data-autoclose="true">
                  <input type="date" id="FechaFin" class="form-control" value="">
                </div>
              </div>
              <div class="form-group col-md-6" id="TituloHoraFin">
                <label for="">Hora de fin:</label>
                <div class="input-group " data-autoclose="true">
                  <input type="time" id="HoraFin" class="form-control" autocomplete="off">
                </div>
              </div>
            </div>

            <div class="form-row">
              <label for="">Descripcion:</label>
              <textarea id="Descripcion" class="form-control" rows"3"></textarea>
            </div>
            <div class="form-row">
              <label for="">Color de fondo:</label>
              <input type="color" value="#3788D8" id="ColorFondo" class="form-control" style="height:36px;">
            </div>
            <div class="form-row">
              <label for="">Color de Texto:</label>
              <input type="color" value="#ffffff" id="ColorTexto" class="form-control" style="height:36px;">
            </div>

          </div>

          <div class="modal-footer">
            <button type="button" id="BotonAgregar" class="btn btn-success">Agregar</button>
            <button type="button" id="BotonModificar" class="btn btn-success">Modificar</button>
            <button type="button" id="BotonBorrar" class="btn btn-success">Borrar</button>
            <button type="button" class="btn btn-success" data-bs-dismiss="modal">Cancelar</button>
          </div>


        </form>
      </div>
    </div>
  </div>
</body>
  </html>
