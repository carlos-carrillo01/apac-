<?php

// consulta y resumen de las practicas guardadas en la base de datos
function practicas_por_actividad($usuario_id, $programa)
{
    $con = conexion();
    $consulta = $con->prepare('select actividad, count(*) as veces, max(creado_en) as ultima from practicas where usuario_id = ? and programa = ? group by actividad');
    $consulta->bind_param('is', $usuario_id, $programa);
    $consulta->execute();
    $resultado = $consulta->get_result();
    $lista = [];

    while ($fila = $resultado->fetch_assoc()) {
        $lista[$fila['actividad']] = ['veces' => (int) $fila['veces'], 'ultima' => $fila['ultima']];
    }

    return $lista;
}

// resumen de los programas con alguna practica
function resumen_programas($usuario_id)
{
    $con = conexion();
    $consulta = $con->prepare('select programa, count(*) as sesiones, count(distinct actividad) as distintas, max(creado_en) as ultima from practicas where usuario_id = ? group by programa order by ultima desc');
    $consulta->bind_param('i', $usuario_id);
    $consulta->execute();
    return $consulta->get_result()->fetch_all(MYSQLI_ASSOC);
}

// cuenta practicas, dias distintos y dias seguidos
function resumen_general($usuario_id)
{
    $con = conexion();
    $consulta = $con->prepare('select date(creado_en) as dia, count(*) as veces from practicas where usuario_id = ? group by date(creado_en) order by dia desc');
    $consulta->bind_param('i', $usuario_id);
    $consulta->execute();
    $resultado = $consulta->get_result();
    $dias = [];
    $sesiones = 0;

    while ($fila = $resultado->fetch_assoc()) {
        $dias[$fila['dia']] = (int) $fila['veces'];
        $sesiones += (int) $fila['veces'];
    }

    $racha = 0;
    $dia = date('Y-m-d');

    if (!isset($dias[$dia])) {
        $dia = date('Y-m-d', strtotime('-1 day'));
    }

    while (isset($dias[$dia])) {
        $racha++;
        $dia = date('Y-m-d', strtotime($dia . ' -1 day'));
    }

    $semana = [];
    $nombres = ['d', 'l', 'm', 'm', 'j', 'v', 's'];

    for ($i = 6; $i >= 0; $i--) {
        $fecha = date('Y-m-d', strtotime('-' . $i . ' day'));
        $semana[] = ['letra' => $nombres[(int) date('w', strtotime($fecha))], 'hecho' => isset($dias[$fecha]), 'hoy' => $i === 0];
    }

    return ['sesiones' => $sesiones, 'dias' => count($dias), 'racha' => $racha, 'semana' => $semana];
}

// devuelve las practicas mas recientes
function historial($usuario_id, $limite = 15)
{
    $con = conexion();
    $consulta = $con->prepare('select id, programa, actividad, como_fue, nota, creado_en from practicas where usuario_id = ? order by creado_en desc, id desc limit ?');
    $consulta->bind_param('ii', $usuario_id, $limite);
    $consulta->execute();
    return $consulta->get_result()->fetch_all(MYSQLI_ASSOC);
}

// devuelve el texto elegido en el formulario
function como_fue_texto($valor)
{
    $opciones = ['bien' => 'bien', 'regular' => 'mas o menos', 'molestia' => 'con molestia'];
    return $opciones[$valor] ?? '';
}
