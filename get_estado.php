<?php
$db = getDB();
$consulta = $dv->prepare(
"SELECT e.nombre, e.hora_entrada, e.hora_salida, n.fecha, n.hora FROM empleados e
INNER JOIN novedades n ON n.empleado_id=e.id_empleado
WHERE DATE(n.fecha) >= CURRENT_DATE() -1"
);
$consulta->execute([
    'empleado_id' => 'empleado_id',
    'hoy'         => date('Y-m-d'),
    'ayer'        => date('Y-m-d', strtotime('-1 day')),
    'hoy_sub'     => date('Y-m-d'),
    'ayer_sub'    => date('Y-m-d', strtotime('-1 day')),
    'hora_actual' => date('H:i:s'),
]);
echo json_encode($consulta->fetchAll());
?>