<!DOCTYPE html>
<html>

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- Bootstrap Material Design -->
        <link rel="stylesheet"
              href="https://unpkg.com/bootstrap-material-design@4.1.1/dist/css/bootstrap-material-design.min.css"
              integrity="sha384-wXznGJNEXNG1NFsbm0ugrLFMQPWswR3lds2VeinahP8N0zJw9VWSopbjv2x7WCvX"
              crossorigin="anonymous">
        <script src="https://www.gstatic.com/firebasejs/5.6.0/firebase.js"></script>

        <title>Prototipo</title>

        <!------------------------------------------------------------->

        <style type = "text/css">
          h3{
            position: absolute;
            top: 15px;
            right: 500px;
            left: 500px;
          }
          .back{
            left: 35px;
            -webkit-transform: scaleX(-1);
            transform: scaleX(-1);
          }
          .musguillo{
            position: absolute;
            right: 35px;
          }
          div.conteo{
            margin-left: auto;
            margin-right: auto;
            text-align: left;
            padding-left: 40px;
          }
          div.estadisticas1{
            margin-left: auto;
            margin-right: auto;
            text-align: left;
            padding-left: 40px;
          }
          table.humeT{
            border-collapse: collapse;
            width: 30%;
            color: #588c7e;
            font-family: monospace;
            font-size: 13px;
            text-align: center;
            padding-left: 40px;
          }
          div.estadisticas2{
            margin-left: auto;
            margin-right: auto;
            text-align: left;
            padding-left: 40px;
          }
          table.tempT{
            border-collapse: collapse;
            width: 30%;
            color: #588c7e;
            font-family: monospace;
            font-size: 13px;
            text-align: center;
            padding-left: 40px;
          }
          div.AT{
            text-align: left;
            padding-left: 40px;
          }
          div.AH{
            text-align: left;
            padding-left: 40px;
          }
          div.revision{
            text-align: left;
            padding-left: 40px;
          }
          th {
            background-color: #588c7e;
            color: white;
          }
          tr:nth-child(even){background-color: #f2f2f2}

        </style>

        <!------------------------------------------------------------->

        <?php
          // 1. Conectarse al servidor
          $host = 'localhost';
          $usuario = 'root';
          $password = '';
          $db = 'iot';

          // 2. Conectarse a la Base de Datos
          $enlacedb = mysqli_connect($host, $usuario, $password, $db);

          // 3. Hacer consultas

          // Conteo de alarmas. ----------------------------------------
          $query = "SELECT COUNT(*) AS Conteo FROM data WHERE AlarmaH != 'Estable'";
          $conteoH = mysqli_query($enlacedb, $query);

          $query = "SELECT COUNT(*) AS Conteo FROM data WHERE AlarmaT != 'Estable'";
          $conteoT = mysqli_query($enlacedb, $query);

          // Horas activación de alarmas. (Últimos 4 registros) --------
          $query = "SELECT Tiempo, AlarmaH FROM data
            WHERE AlarmaH != 'Estable' ORDER BY Tiempo DESC LIMIT 4";
          $ahT = mysqli_query($enlacedb, $query);

          $query = "SELECT Tiempo, AlarmaT FROM data
            WHERE AlarmaT != 'Estable' ORDER BY Tiempo DESC LIMIT 4";
          $atT = mysqli_query($enlacedb, $query);

          // Máximo, mínimo y promedio de temperatura y humedad. -------
          $query = "(SELECT Tiempo, Humedad FROM data WHERE Humedad = (
            SELECT MAX( Humedad ) FROM data) ORDER BY Tiempo DESC LIMIT 1)
            UNION (SELECT Tiempo, Humedad FROM data WHERE Humedad = (
            SELECT MIN( Humedad ) FROM data) ORDER BY Tiempo DESC LIMIT 1)";
          $statsH = mysqli_query($enlacedb, $query);

          $query = "(SELECT Tiempo, Temperatura FROM data WHERE Temperatura =
            ( SELECT MAX( Temperatura ) FROM data) ORDER BY Tiempo DESC LIMIT 1)
            UNION (SELECT Tiempo, Temperatura FROM data WHERE Temperatura = (
            SELECT MIN( Temperatura ) FROM data) ORDER BY Tiempo DESC LIMIT 1)";
          $statsT = mysqli_query($enlacedb, $query);

          $query = "SELECT ROUND(AVG(Humedad), 2) AS Promedio FROM data
            UNION SELECT ROUND(AVG(Temperatura), 2) AS Promedio FROM data";
          $promedio = mysqli_query($enlacedb, $query);

          // Registros de las últimas 24 horas. -------------------------
          $query = "SELECT Tiempo, Humedad, Temperatura FROM data";
          $registros = mysqli_query($enlacedb, $query);

          // Hora del último registro. ---------------------------------
            $query = "SELECT Tiempo FROM data ORDER BY Tiempo DESC LIMIT 1";
            $last = mysqli_query($enlacedb, $query);
        ?>

        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script type="text/javascript">
          google.charts.load('current', {'packages':['corechart']});
          google.charts.setOnLoadCallback(drawChart);

          function drawChart() {
            var data = google.visualization.arrayToDataTable([
              ['Tiempo', 'Humedad', 'Temperatura'],
              <?php
                while ($data = mysqli_fetch_array($registros)){
                  $tiempo = $data['Tiempo'];
                  $humedad = $data['Humedad'];
                  $temperatura = $data['Temperatura'];
              ?>
              ['<?php echo $tiempo;?>', <?php echo $humedad;?>, <?php echo $temperatura;?>],
              <?php
                }
              ?>
            ]);

            var options = {
              title: 'Humedad y Temperatura',
              curveType: 'function',
              legend: { position: 'bottom' }
            };

            var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
            chart.draw(data, options);
          }
        </script>

        <!------------------------------------------------------------->

    </head>

    <body>

        <header class="navbar navbar-expand navbar-dark flex-column flex-md-row bd-navbar" style="background-color: rgb(55, 167, 115);">

            <a href="proto.html"><img src="https://media1.giphy.com/media/vyAjrXVwdqDbKr8e1N/giphy.gif?cid=790b7611e1504a37c8b01e1d7731aa2d0301662667318538&rid=giphy.gif&ct=s" width="90" class="back"></a>
            <h3 style="text-align: center; font-size: 30px; color:cornsilk"> Musguillo Longuillo</h3>
            <img src="https://media3.giphy.com/media/ooGJRyoy47V5QYhoHv/giphy.gif?cid=790b761111158b44e41beb15c0629b422991fb4cb2f3b869&rid=giphy.gif&ct=s" width="100" class="musguillo">


        </header>

        <!--- 4. Recuperar datos de las consultas e imprimirlos --->
        <div class="conteo">
        <?php
          $cH = mysqli_fetch_row($conteoH);
          $cT = mysqli_fetch_row($conteoT);
          echo "<br/>Conteo de activación de alarmas<br/>";
          echo "&emsp;Alarma de Humedad: " . $cH[0];
          echo "<br/>&emsp;Alarma de Temperatura: " . $cT[0] . "<br/>";
        ?>
        </div>

        <!------------------------------------------------------------->
        <div class="AH">
        <br/>Activación de Alarma de Humedad:
        <table class="humeT">
          <tr>
            <th>Tiempo</th>
            <th>Mensaje</th>
          </tr>

          <?php
            while ($row = $ahT->fetch_assoc()) {
              echo "<tr><td>" . $row['Tiempo'] . "</td><td>" . $row['AlarmaH'] .
              "</td></tr>";
            }
          ?>
        </table>
        </div>
        <!------------------------------------------------------------->

        <div class="estadisticas1">
        <?php
          echo "<br/>Estadísticas de Humedad:";
          $maxH = mysqli_fetch_array($statsH);
          echo "<br/>&emsp; Máximo: " . $maxH[1] . "&emsp; Hora: " . $maxH[0];
          $minH = mysqli_fetch_array($statsH);
          echo "<br/>&emsp; Mínimo: " . $minH[1] .  "&emsp; Hora: " . $minH[0];
          $promH = mysqli_fetch_array($promedio);
          echo "<br/>&emsp; Promedio: " . $promH['Promedio'];
          ?>
        </div>

        <!------------------------------------------------------------->

        <div class="AT">
        <br/>Activación de Alarma de Temperatura:
        <table class="tempT">
          <tr>
            <th>Tiempo</th>
            <th>Mensaje</th>
          </tr>

          <?php
          while ($row = $atT->fetch_assoc()) {
            echo "<tr><td>" . $row['Tiempo'] . "</td><td>" . $row['AlarmaT'] .
            "</td></tr>";
          }
          ?>
        </table>
        </div>

        <!------------------------------------------------------------->

        <div class="estadisticas2">
        <?php
          echo "<br/><br/>Estadísticas de Temperatura:";
          $maxT = mysqli_fetch_array($statsT);
          echo "<br/>&emsp; Máximo: " . $maxT[1] . "&emsp; Hora: " . $maxT[0];
          $minT = mysqli_fetch_array($statsT);
          echo "<br/>&emsp; Mínimo: " . $minT[1] .  "&emsp; Hora: " . $minT[0];
          $promT = mysqli_fetch_array($promedio);
          echo "<br/>&emsp; Promedio: " . $promT['Promedio'] . "<br/><br/>";
        ?>
        </div>

        <!------------------------------------------------------------->

        <div class="revision">
        <?php
          $row = mysqli_fetch_array($last);
          $tiempo = $row['Tiempo'];
          echo "Última revisión: " . $tiempo;
        ?>
        </div>

        <!------------------------------------------------------------->

        <div id="curve_chart" style="width: 700px; height: 400px; position: relative; bottom: 500px; left: 700px;"></div>

        <!------------------------------------------------------------->

    </body>

    <?php
      // 5. Cerrar la coneción al servidor
      $enlacedb->close();
    ?>

</html>
