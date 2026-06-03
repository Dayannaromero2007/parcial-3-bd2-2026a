<?php
require_once 'conexion.php';

try {
    // 1. CONSULTA PARA VEHÍCULOS
    $sql = "SELECT v.placa, v.marca, v.color, t.nombre_tipo as tipo_vehiculo, i.fecha_hora_ingreso, i.Espacio_idEspacio
            FROM vehiculo v
            JOIN tipo_vehiculo t ON v.Tipo_vehiculo_idTipo_vehiculo = t.idTipo_vehiculo
            JOIN ingresos i ON v.idVehiculo = i.Vehiculo_idVehiculo
            WHERE i.fecha_hora_salida IS NULL";
    $stmt = $conexion->query($sql);
    $vehiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 2. CONSULTA PARA EL MAPA DE ESPACIOS
    $sql_espacios = "SELECT i.Espacio_idEspacio, v.placa, v.marca, v.color, t.nombre_tipo
                     FROM ingresos i
                     JOIN vehiculo v ON i.Vehiculo_idVehiculo = v.idVehiculo
                     JOIN tipo_vehiculo t ON v.Tipo_vehiculo_idTipo_vehiculo = t.idTipo_vehiculo
                     WHERE i.fecha_hora_salida IS NULL AND i.Espacio_idEspacio IS NOT NULL";
    $stmt_espacios = $conexion->query($sql_espacios);
    
    $detalles_ocupados = [];
    while ($row = $stmt_espacios->fetch(PDO::FETCH_ASSOC)) {
        $detalles_ocupados[$row['Espacio_idEspacio']] = $row; 
    }

    // 3. CONSULTA PARA CARGAR LAS TARIFAS
    $sql_tarifas = "SELECT * FROM tarifa";
    $stmt_tarifas = $conexion->query($sql_tarifas);
    $tarifas = [];
    while ($row = $stmt_tarifas->fetch(PDO::FETCH_ASSOC)) {
        $tarifas[$row['Tipo_vehiculo_idTipo_vehiculo']] = $row;
    } // <--- AQUÍ FALTABA ESTA LLAVE DE CIERRE

    // 4. CONSULTA ACTUALIZADA: Con JOIN para unir Cliente, Vehículo y Tipo
    $sql_mensuales = "SELECT c.nombre, c.telefono, c.correo, c.fecha_inicio, c.fecha_final, v.placa, t.nombre_tipo
                      FROM clientes_mensuales c
                      LEFT JOIN vehiculo v ON c.idClientes_mensuales = v.idClientes_mensuales
                      LEFT JOIN tipo_vehiculo t ON v.Tipo_vehiculo_idTipo_vehiculo = t.idTipo_vehiculo";
    $stmt_mensuales = $conexion->query($sql_mensuales);
    $lista_mensuales = $stmt_mensuales->fetchAll(PDO::FETCH_ASSOC);

    // 5. CONSULTA HISTORIAL DE INGRESOS Y SALIDAS (Usando LEFT JOIN)
    $sql_historial = "SELECT v.placa, t.nombre_tipo, i.fecha_hora_ingreso, i.fecha_hora_salida, i.tipo_cliente as regimen, i.Total_pago
                      FROM ingresos i
                      LEFT JOIN vehiculo v ON i.Vehiculo_idVehiculo = v.idVehiculo
                      LEFT JOIN tipo_vehiculo t ON v.Tipo_vehiculo_idTipo_vehiculo = t.idTipo_vehiculo
                      ORDER BY i.fecha_hora_ingreso DESC";
    $stmt_historial = $conexion->query($sql_historial);
    $lista_historial = $stmt_historial->fetchAll(PDO::FETCH_ASSOC);

    

} catch(PDOException $e) {
    // Si algo falla, el catch lo atrapa aquí abajo
    echo "Error: " . $e->getMessage();
    $vehiculos = [];
    $detalles_ocupados = [];
    $tarifas = [];
    $lista_mensuales = []; 
    $lista_historial = [];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ParkControl — Sistema de Parqueadero</title>

    <!-- CSS -->
    <link rel="stylesheet" href="style.css">
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="brand">
        <span class="brand-icon">🚗</span>
        <span>ParkControl</span>
    </div>

    <div class="sidebar-menu">

        <button class="sidebar-link active" onclick="showPage('dashboard', event)">
            📍 Panel Principal
        </button>

        <button class="sidebar-link" onclick="showPage('ingresos', event)">
            🔄 Ingresos / Salidas
        </button>

        <button class="sidebar-link" onclick="showPage('mensuales', event)">
            👥 Clientes Mensuales
        </button>

        <button class="sidebar-link" onclick="showPage('espacios', event)">
            📋 Mapa de Espacios
        </button>

        <button class="sidebar-link" onclick="showPage('tarifas', event)">
            💲 Configurar Tarifas
        </button>

        <button class="sidebar-link" onclick="showPage('reportes', event)">
            📈 Reporte de Ingresos
        </button>

    </div>
</div>

<!-- MAIN -->
<div class="main-container">

    <!-- DASHBOARD -->
    <div id="page-dashboard" class="page active">

        <div class="header">
            <h1>Dashboard del Parqueadero</h1>
            <p>Monitoreo en tiempo real — conectado a MySQL</p>
        </div>

        <div class="metrics-grid">

            <div class="metric-card">
                <div class="metric-info">
                    <h3>Espacios Disponibles</h3>
                    <div class="value" id="m-disponibles">—</div>
                </div>
                <span class="metric-icon">🟢</span>
            </div>

            <div class="metric-card">
                <div class="metric-info">
                    <h3>Vehículos Activos</h3>
                    <div class="value" id="m-activos">—</div>
                </div>
                <span class="metric-icon">🚘</span>
            </div>

            <div class="metric-card">
                <div class="metric-info">
                    <h3>Mensualidades Activas</h3>
                    <div class="value" id="m-mensuales">—</div>
                </div>
                <span class="metric-icon">📆</span>
            </div>

            <div class="metric-card">
                <div class="metric-info">
                    <h3>Ingresos Hoy</h3>
                    <div class="value" id="m-ingresos-hoy">—</div>
                </div>
                <span class="metric-icon">💰</span>
            </div>

        </div>

        <div class="workspace-grid">

        <!-- FORMULARIO -->
           <div class="panel">
           <h2 class="registar">Registrar Entrada</h2>
           <div id="alert-entrada" class="alert"></div>

        <form id="form-entrada" method="POST" action="guardar_entrada.php">
        
          <div class="form-group">
              <label>Placa del Vehículo</label>
              <input type="text" name="placa" class="form-control" placeholder="Ej. ABC123" required>
            </div>

            <div class="form-group">
              <label>Tipo de Vehículo</label>
              <select name="tipo_vehiculo" class="form-control">
                <option value="1">Automóvil</option>
                <option value="2">Automóvil eléctrico</option>
                <option value="3">Bicicleta</option>
                <option value="4">Moto</option>
                <option value="5">Camión</option>
              </select>
            </div>

            <div class="form-group">
                 <label>Tipo de Régimen</label>
                 <select name="regimen" class="form-control">
                   <option value="Ocasional">Ocasional</option>
                   <option value="Mensual">Mensual</option>
                </select>
            </div>

            <div class="from-group">
               <label>Marca</label>
               <input type="text" name="marca" class="form-control" placeholder="Marca del vehículo">
            </div>

            <div class="form-group">
               <label>Color</label>
               <input type="text" name="color" class="form-control" placeholder="Color del vehículo">
            </div>
            <div class="form-group">
               <label>Espacio Asignado</label>
               <input type="text" name="espacio_asignado" id="input-espacio" class="form-control" placeholder="Haz clic en un espacio del mapa..." readonly>
            </div>

            <button type="submit" class="btn-submit">⚡ Ingresar Vehículo</button>
        </form>
    </div>

            <!-- TABLA -->
            <div class="panel">

                <h2>Vehículos Dentro del Parqueadero</h2>

                <div class="table-responsive">

                    <table>

                        <thead>
                            <tr>
                                <th>PLACA</th>
                                <th>TIPO</th>
                                <th>HORA INGRESO</th>
                                <th>REGIMEN</th>
                                <th>ACCION</th>    
                                <th>MARCA</th>
                                <th>COLOR</th>
                                <th>ESPACIO</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-activos">

                        <tbody id="tabla-activos">
<?php 
  // Comprobamos si la variable $vehiculos tiene información
  if (!empty($vehiculos)): 
    foreach ($vehiculos as $v): 
  ?>
    <tr>
      <td><?php echo htmlspecialchars($v['placa']); ?></td>
      <td><?php echo htmlspecialchars($v['tipo_vehiculo']); ?></td>
      <td><?php echo htmlspecialchars($v['fecha_hora_ingreso']); ?></td>
      <td>Ocasional</td>
      <td>
          <form action="procesar_salida.php" method="POST" style="margin:0;">
              <input type="hidden" name="placa" value="<?php echo htmlspecialchars($v['placa']); ?>">
              <button type="submit" class="btn-submit" style="padding: 5px 10px; font-size: 12px; background: #ee5d50;">
                  Salida
              </button>
          </form>
      </td>
      <td><?php echo htmlspecialchars($v['marca']); ?></td>
      <td><?php echo htmlspecialchars($v['color']); ?></td>
      <td><?php echo htmlspecialchars($v['Espacio_idEspacio'] ? $v['Espacio_idEspacio'] : 'Sin asignar'); ?></td>
    </tr>
  <?php 
    endforeach; 
  else: 
  ?>
    <tr>
       <td colspan="7">No hay vehículos registrados en la base de datos.</td>
    </tr>
  <?php endif; ?>
                        </tbody>
                    </table>

                </div>

            </div>

        </div>

    </div>

    <!-- CLIENTES MENSUALES -->
    <div id="page-mensuales" class="page">

        <div class="header">
            <h1>Clientes Mensuales</h1>
            <p>Gestión de Mensualidades</p>
        </div>

        <div class="workspace-grid">

            <!-- FORMULARIO -->
            <div class="panel">

                <h2>Nuevo cliente</h2>

                <div id="alert-mensual" class="alert"></div>

                <form id="form-mensual" method="POST" action="guardar_mensual.php">

                <div class="form-group">
                    <label>Nombre Completo</label>

                    <input
                        type="text"
                        name="nombre"
                        class="form-control"
                        placeholder="Nombre del cliente"
                    >
                </div>

                <div class="form-group">
                    <label>Placa del Vehículo</label>

                    <input
                        type="text"
                        name="placa"
                        class="form-control"
                        placeholder="Ej. ABC123"
                    >
                </div>

                <div class="form-group">
                    <label>Tipo de Vehículo</label>

                    <select id="inp-tipo-mensual" name="tipo_vehiculo" class="form-control">
                        <option value="1">Automóvil</option>
                        <option value="2">Automóvil eléctrico</option>
                        <option value="3">Bicicleta</option>
                        <option value="4">Moto</option>
                        <option value="5">Camión</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Teléfono</label>

                    <input
                        type="text"
                        name="telefono"
                        class="form-control"
                        placeholder="Ej. 3001234567"
                    >
                </div>

                <div class="form-group">
                    <label>Email</label>

                    <input
                        type="email"
                        name="correo"
                        class="form-control"
                        placeholder="correo@mail.com"
                    >
                </div>

                <div class="form-group">
                    <label>Fecha de Inicio</label>

                    <input
                        type="date"
                        name="fecha_inicio"
                        class="form-control"
                    >
                </div>

                <div class="form-group">
                    <label>Fecha de Fin</label>

                    <input
                        type="date"
                        name="fecha_fin"
                        class="form-control"
                    >
                </div>

                <button class="btn-submit">
                    💳 Registrar Mensualidad
                </button>

            </div>

            <!-- TABLA -->
            <div class="panel">

                <h2>Clientes Mensuales Activos</h2>

                <div class="table-responsive">

                    <table>

                        <thead>
                            <tr>
                                <th>NOMBRE</th>
                                <th>PLACA</th>
                                <th>TIPO</th>
                                <th>TELEFONO</th>
                                <th>CORREO</th>
                                <th>INICIO</th>
                                <th>FIN</th>
                            </tr>
                        </thead>

                        <tbody id="tabla-mensuales">
<?php 
  if (!empty($lista_mensuales)): 
    foreach ($lista_mensuales as $c): 
?>
    <tr>
        <td><?php echo htmlspecialchars($c['nombre']); ?></td>
        <td><?php echo htmlspecialchars($c['placa'] ?? 'Sin placa'); ?></td>
        <td><?php echo htmlspecialchars($c['nombre_tipo'] ?? 'N/A'); ?></td>
        <td><?php echo htmlspecialchars($c['telefono']); ?></td>
        <td><?php echo htmlspecialchars($c['correo']); ?></td>
        <td><?php echo htmlspecialchars($c['fecha_inicio']); ?></td>
        <td><?php echo htmlspecialchars($c['fecha_final']); ?></td>
    </tr>
<?php 
    endforeach; 
  else: 
?>
    <tr>
        <td colspan="7">No hay clientes mensuales registrados.</td>
    </tr>
<?php endif; ?>
</tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

    <!-- INGRESOS / SALIDAS -->
    <div id="page-ingresos" class="page">

        <div class="header">
            <h1>Ingresos y Salidas</h1>
            <p>Registrar movimientos manualmente</p>
        </div>

        <div class="panel">

            <h2 class="registar">Historial de Movimientos</h2>

            <div class="table-responsive">

                <table>

                    <thead>
                        <tr>
                            <th>PLACA</th>
                            <th>TIPO</th>
                            <th>HORA INGRESO</th>
                            <th>HORA SALIDA</th>
                            <th>DURACION</th>
                            <th>REGIMEN</th>
                            <th>COSTO</th>
                        </tr>
                    </thead>

                    <tbody id="tabla-ingresos">
                    <?php 
                      // Verificamos si la consulta trajo datos
                      if (!empty($lista_historial)): 
                        foreach ($lista_historial as $h): 
                            
                            // Valores por defecto si el vehículo aún no sale
                            $duracion = "En parqueadero";
                            $costo = "Pendiente";
                            $salida = "---";

                            // Si ya tiene hora de salida, hacemos la matemática
                            if (!empty($h['fecha_hora_salida'])) {
                                $salida = $h['fecha_hora_salida'];
                                
                                $fecha1 = new DateTime($h['fecha_hora_ingreso']);
                                $fecha2 = new DateTime($h['fecha_hora_salida']);
                                $intervalo = $fecha1->diff($fecha2);
                                $duracion = $intervalo->format('%Hh %Im'); // Ej: 02h 15m
                                
                                $total_pago = $h['Total_pago'] ?? 0;
                                $costo = "$" . number_format($total_pago, 0, ',', '.');
                            }
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($h['placa']); ?></td>
                            <td><?php echo htmlspecialchars($h['nombre_tipo'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($h['fecha_hora_ingreso']); ?></td>
                            <td><?php echo htmlspecialchars($salida); ?></td>
                            <td><?php echo $duracion; ?></td>
                            <td><?php echo htmlspecialchars($h['regimen'] ?? 'Ocasional'); ?></td>
                            <td style="font-weight: bold; color: #4caf50;"><?php echo $costo; ?></td>
                        </tr>
                    <?php 
                        endforeach; 
                      // Si la base de datos está vacía, mostramos el mensaje original
                      else: 
                    ?>
                        <tr>
                            <td colspan="7" style="text-align: center;">
                                No hay ingresos registrados
                            </td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                    </table>

                <button class="btn-submit" >
                    🚪 Registrar Salida
                </button>

            </div>

        </div>

    </div>
   
<!-- MAPA DE ESPACIOS -->
<div id="page-espacios" class="page">

    <div class="header">
        <h1>Distribución Física de Celdas</h1>
        <p>Mapa en vivo — 100 espacios organizados por bloques</p>
    </div>

    <div class="espacios-layout">

        <!-- MAPA -->
        <div class="parking-panel">

            <div class="parking-top">

                <h2>🅿 Parking Lot</h2>

                <div class="parking-stats">
                    <div class="stat-box">
                        <span id="total-espacios">100</span>
                        <small>TOTAL</small>
                    </div>

                    <div class="stat-box">
                        <span id="ocupados">0</span>
                        <small>OCUPADOS</small>
                    </div>

                    <div class="stat-box">
                        <span id="libres">100</span>
                        <small>LIBRES</small>
                    </div>
                </div>

            </div>
             <div class="legend">
                <div class="legend-item">
                    <span class="legend-color ocupado"></span>
                    Ocupado
                </div>

                <div class="legend-item">
                    <span class="legend-color libre"></span>
                    Disponible
                </div>
            </div>

            <div class="parking-grid">

             <!-- 100 ESPACIOS -->
                <!-- Puedes copiar desde aquí -->

                <div class="space libre">A1</div>
                <div class="space libre">A2</div>
                <div class="space libre">A3</div>
                <div class="space libre">A4</div>
                <div class="space libre">A5</div>
                <div class="space libre">A6</div>
                <div class="space libre">A7</div>
                <div class="space libre">A8</div>
                <div class="space libre">A9</div>
                <div class="space libre">A10</div>

                <div class="space libre">B11</div>
                <div class="space libre">B12</div>
                <div class="space libre">B13</div>
                <div class="space libre">B14</div>
                <div class="space libre">B15</div>
                <div class="space libre">B16</div>
                <div class="space libre">B17</div>
                <div class="space libre">B18</div>
                <div class="space libre">B19</div>
                <div class="space libre">B20</div>

                <div class="space libre">C21</div>
                <div class="space libre">C22</div>
                <div class="space libre">C23</div>
                <div class="space libre">C24</div>
                <div class="space libre">C25</div>
                <div class="space libre">C26</div>
                <div class="space libre">C27</div>
                <div class="space libre">C28</div>
                <div class="space libre">C29</div>
                <div class="space libre">C30</div>

                <div class="space libre">D31</div>
                <div class="space libre">D32</div>
                <div class="space libre">D33</div>
                <div class="space libre">D34</div>
                <div class="space libre">D35</div>
                <div class="space libre">D36</div>
                <div class="space libre">D37</div>
                <div class="space libre">D38</div>
                <div class="space libre">D39</div>
                <div class="space libre">D40</div>

                <div class="space libre">E41</div>
                <div class="space libre">E42</div>
                <div class="space libre">E43</div>
                <div class="space libre">E44</div>
                <div class="space libre">E45</div>
                <div class="space libre">E46</div>
                <div class="space libre">E47</div>
                <div class="space libre">E48</div>
                <div class="space libre">E49</div>
                <div class="space libre">E50</div>

                <div class="space libre">F51</div>
                <div class="space libre">F52</div>
                <div class="space libre">F53</div>
                <div class="space libre">F54</div>
                <div class="space libre">F55</div>
                <div class="space libre">F56</div>
                <div class="space libre">F57</div>
                <div class="space libre">F58</div>
                <div class="space libre">F59</div>
                <div class="space libre">F60</div>

                <div class="space libre">G61</div>
                <div class="space libre">G62</div>
                <div class="space libre">G63</div>
                <div class="space libre">G64</div>
                <div class="space libre">G65</div>
                <div class="space libre">G66</div>
                <div class="space libre">G67</div>
                <div class="space libre">G68</div>
                <div class="space libre">G69</div>
                <div class="space libre">G70</div>

                <div class="space libre">H71</div>
                <div class="space libre">H72</div>
                <div class="space libre">H73</div>
                <div class="space libre">H74</div>
                <div class="space libre">H75</div>
                <div class="space libre">H76</div>
                <div class="space libre">H77</div>
                <div class="space libre">H78</div>
                <div class="space libre">H79</div>
                <div class="space libre">H80</div>

                <div class="space libre">I81</div>
                <div class="space libre">I82</div>
                <div class="space libre">I83</div>
                <div class="space libre">I84</div>
                <div class="space libre">I85</div>
                <div class="space libre">I86</div>
                <div class="space libre">I87</div>
                <div class="space libre">I88</div>
                <div class="space libre">I89</div>
                <div class="space libre">I90</div>

                <div class="space libre">J91</div>
                <div class="space libre">J92</div>
                <div class="space libre">J93</div>
                <div class="space libre">J94</div>
                <div class="space libre">J95</div>
                <div class="space libre">J96</div>
                <div class="space libre">J97</div>
                <div class="space libre">J98</div>
                <div class="space libre">J99</div>
                <div class="space libre">J100</div>

            </div>

        </div>

    </div>
   <!-- PANEL DERECHO -->
<div class="space-detail">

     <h3 id="detalle-titulo">Espacio —</h3>

    <div class="detail-icon">🅿</div>

    <p id=detalle-info>
        Haz clic en un espacio del mapa
        para ver sus detalles
    </p>

    </div>

</div>

</div>
    <!-- TARIFAS -->
 
    <div id="page-tarifas" class="page">

        <div class="header">
            <h1>Configurar Tarifas</h1>
            <p>Administrar precios del parqueadero</p>
        </div>
        
    <div class="tarifa-grid">

            <div class="tarifa-item">
                <form action="actualizar_tarifas.php" method="POST">
                <input type="hidden" name="id_tipo" value="1">

                <h4>🚗 Automóvil</h4>

                <div class="form-group">
                    <label>Precio Hora</label>
                    <input type="text" name="precio_hora_1" class="form-control" oninput="formatearMoneda(this)">
                </div>

                <div class="form-group">
                    <label>Precio Día</label>
                    <input type="number" name="precio_dia_1" id="automovil_dia" class="form-control">
                </div>
                <div class="form-group">
                    <label>Precio Noche</label>
                    <input type="text" name="precio_noche_1" class="form-control" oninput="formatearMoneda(this)">
                </div>

                <div class="form-group">
                    <label>Precio Fin de Semana</label>
                    <input type="text" name="precio_fin_semana_1" class="form-control" oninput="formatearMoneda(this)">
                </div>

                <div class="form-group">
                    <label>Precio Hora Pico</label>
                    <input type="text" name="precio_hora_pico_1" class="form-control" oninput="formatearMoneda(this)">
                </div>

                <div class="form-group">
                    <label>Precio Dias Festivos </label>
                    <input type="text" name="precio_dias_festivos_1" class="form-control" oninput="formatearMoneda(this)">
                </div>

                <div class="form-group">
                    <label>Precio Mensual</label>
                    <input type="text" name="precio_mensual_1" class="form-control" oninput="formatearMoneda(this)">
                </div>

                <button type="submit" class="btn-submit">
                    Guardar Tarifa
                </button>

            </div>
            <div class="tarifa-item">
    
                <h4>🔌 Automóvil Eléctrico</h4>
    
                <div class="form-group">    
                    <label>Precio Hora</label>
                    <input type="text" name="precio_hora_2" class="form-control" oninput="formatearMoneda(this)">
                </div>  
                <div class="form-group">
                    <label>Precio Día</label>
                    <input type="text" name="precio_dia_2" class="form-control" oninput="formatearMoneda(this)">
                </div>  

                <div class="form-group">
                    <label>Precio Noche</label>
                    <input type="text" name="precio_noche_2" class="form-control" oninput="formatearMoneda(this)">
                </div>

                <div class="form-group">
                    <label>Precio Fin de Semana</label>
                    <input type="text" name="precio_fin_semana_2" class="form-control" oninput="formatearMoneda(this)">
                </div>

                <div class="form-group">
                    <label>Precio Hora Pico</label>
                    <input type="text" name="precio_hora_pico_2" class="form-control" oninput="formatearMoneda(this)">
                </div>

                <div class="form-group">
                    <label>Precio Dias Festivos </label>
                    <input type="text" name="precio_dias_festivos_2" class="form-control" oninput="formatearMoneda(this)">
                </div>

                <div class="form-group">
                    <label>Precio Mensual</label>
                    <input type="text" name="precio_mensual_2" class="form-control" oninput="formatearMoneda(this)">
                </div>

                <button type="submit" class="btn-submit">
                    Guardar Tarifa
                </button>
            </div>
            <div class="tarifa-item">
                <h4>🏍 Moto</h4>

                <div class="form-group">
                    <label>Precio Hora</label>
                    <input type="text" name="precio_hora_3" class="form-control" oninput="formatearMoneda(this)">
                </div>

                <div class="form-group">
                    <label>Precio Día</label>
                    <input type="text" name="precio_dia_3" class="form-control" oninput="formatearMoneda(this)">
                </div>

                <div class="form-group">
                    <label>Precio Noche</label>
                    <input type="text" name="precio_noche_3" class="form-control" oninput="formatearMoneda(this)">
                </div>

                <div class="form-group">
                    <label>Precio Fin de Semana</label>
                    <input type="text" name="precio_fin_semana_3" class="form-control" oninput="formatearMoneda(this)">
                </div>

                <div class="form-group">
                    <label>Precio Hora Pico</label>
                    <input type="text" name="precio_hora_pico_3" class="form-control" oninput="formatearMoneda(this)">
                </div>

                <div class="form-group">
                    <label>Precio Dias Festivos </label>
                    <input type="text" name="precio_dias_festivos_3" class="form-control" oninput="formatearMoneda(this)">
                </div>

                <div class="form-group">
                    <label>Precio Mensual</label>
                    <input type="text" name="precio_mensual_3" class="form-control" oninput="formatearMoneda(this)">
                </div>

                <button type="submit" class="btn-submit">
                    Guardar Tarifa
                </button>

            </div>
        
            <div class="tarifa-item">

                <h4>🚲 Bicicleta</h4>

                <div class="form-group">
                    <label>Precio Hora</label>
                    <input type="text" name="precio_hora_4" class="form-control" oninput="formatearMoneda(this)">
                </div>

                <div class="form-group">
                    <label>Precio Día</label>
                    <input type="text" name="precio_dia_4" class="form-control" oninput="formatearMoneda(this)">
                </div>
                <div class="form-group">
                    <label>Precio Noche</label>
                    <input type="text" name="precio_noche_4" class="form-control" oninput="formatearMoneda(this)">
                </div>

                <div class="form-group">
                    <label>Precio Fin de Semana</label>
                    <input type="text" name="precio_fin_semana_4" class="form-control" oninput="formatearMoneda(this)">
                </div>

                <div class="form-group">
                    <label>Precio Hora Pico</label>
                    <input type="text" name="precio_hora_pico_4" class="form-control" oninput="formatearMoneda(this)">
                </div>

                <div class="form-group">
                    <label>Precio Dias Festivos </label>
                    <input type="text" name="precio_dias_festivos_4" class="form-control" oninput="formatearMoneda(this)">
                </div>

                <div class="form-group">
                    <label>Precio Mensual</label>
                    <input type="text" name="precio_mensual_4" class="form-control" oninput="formatearMoneda(this)">
                </div>

                <button type="submit" class="btn-submit">
                    Guardar Tarifa
                </button>

            </div>

            <div class="tarifa-item">

                <h4>🚚 Camión</h4>

                <div class="form-group">
                    <label>Precio Hora</label>
                    <input type="text" name="precio_hora_5" class="form-control" oninput="formatearMoneda(this)">  
                </div>
                <div class="form-group">
                    <label>Precio Día</label>
                    <input type="text" name="precio_dia_5" class="form-control" oninput="formatearMoneda(this)">
                </div>

                <div class="form-group">
                    <label>Precio Noche</label>
                    <input type="text" name="precio_noche_5" class="form-control" oninput="formatearMoneda(this)">
                </div>

                <div class="form-group">
                    <label>Precio Fin de Semana</label>
                    <input type="text" name="precio_fin_semana_5" class="form-control" oninput="formatearMoneda(this)">
                </div>

                <div class="form-group">
                    <label>Precio Hora Pico</label>
                    <input type="text" name="precio_hora_pico_5" class="form-control" oninput="formatearMoneda(this)">
                </div>

                <div class="form-group">
                    <label>Precio Dias Festivos </label>
                    <input type="text" name="precio_dias_festivos_5" class="form-control" oninput="formatearMoneda(this)">
                </div>

                <div class="form-group">
                    <label>Precio Mensual</label>
                    <input type="text" name="precio_mensual_5" class="form-control" oninput="formatearMoneda(this)">
                </div>

                <button type="submit" class="btn-submit">
                 Guardar Tarifa  
                </button>
            </div>
        </div>
    </div>

   <!-- REPORTES -->
<div id="page-reportes" class="page">

    <div class="header">
        <h1>Reporte de Ingresos</h1>
        <p>Resumen general del parqueadero</p>
    </div>

    <!-- FILTROS -->
    <div class="panel reporte-panel">

        <h2>Filtros de Reporte</h2>

        <div class="reporte-filtros">

            <!-- POR DIA -->
            <input type="date" class="form-control reporte-input">

            <button class="btn-reporte">
                Reporte Día
            </button>

            <!-- POR SEMANA -->
            <input type="week" class="form-control reporte-input">

            <button class="btn-reporte">
                Reporte Semana
            </button>

            <!-- POR MES -->
            <select class="form-control reporte-input">
                <option>Enero</option>
                <option>Febrero</option>
                <option>Marzo</option>
                <option>Abril</option>
                <option>Mayo</option>
                <option>Junio</option>
                <option>Julio</option>
                <option>Agosto</option>
                <option>Septiembre</option>
                <option>Octubre</option>
                <option>Noviembre</option>
                <option>Diciembre</option>
            </select>

            <button class="btn-reporte">
                Reporte Mes
            </button>

            <!-- POR AÑO -->
            <input
                type="number"
                value="2026"
                class="form-control reporte-input"
            >

            <button class="btn-reporte">
                Reporte Año
            </button>

            <!-- POR REGIMEN -->
            <select class="form-control reporte-input">
                <option>Todos</option>
                <option>Ocasional</option>
                <option>Mensual</option>
            </select>

            <button class="btn-reporte">
                Filtrar Régimen
            </button>

        </div>

    </div>

    <!-- TARJETAS -->
    <div class="reporte-resumen">

        <div class="reporte-card">
            <div class="rc-value">$ 0</div>
            <div class="rc-label">Ingresos Totales</div>
        </div>

        <div class="reporte-card">
            <div class="rc-value">0</div>
            <div class="rc-label">Vehículos Atendidos</div>
        </div>

    </div>

    <!-- TABLA -->
    <div class="panel reporte-panel">

        <h2>Historial de Reportes</h2>

        <div class="table-responsive">

            <table>

                <thead>
                    <tr>
                        <th>PLACA</th>
                        <th>TIPO</th>
                        <th>ENTRADA</th>
                        <th>SALIDA</th>
                        <th>TIEMPO</th>
                        <th>RÉGIMEN</th>
                        <th>COBRO</th>
                    </tr>
                </thead>

                <tbody>

                    <tr>
                        <td colspan="7">
                            Sin registros disponibles
                        </td>
                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div> 
<script>
    const detallesOcupadosDB = <?php echo json_encode($detalles_ocupados); ?>;
</script>
<!-- JS -->
<script src="./app.js?v=1"></script>

</body>
</html>