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
 // creacion del calendario //
 document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth'
        });
        calendar.render();
      });
      </script>

<div class="container">
      <div class="jumbotron text-center">
      <h1>Calendario</h1>
      </div>    
      <div class="text-center">
        <h3><?php echo $nomApe;?></h3>
        <p><a href="logout.php">Cerrar sesión</a></p>
      </div> 
</div>

<div id='calendar' style="border:1px solid #000; padding:2px"></div>
