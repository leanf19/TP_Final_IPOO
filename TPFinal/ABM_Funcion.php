<?php

//.
class ABM_Funcion
{
    public static function altaFuncion($idfuncion,$nombre, $horaInicio, $duracion, $precio, $teatro)
    {
        $auxFuncion = array('idfuncion' => $idfuncion, 'nombre' => $nombre, 'hora_inicio' => $horaInicio,
            'duracion' => $duracion, 'precio' => $precio, 'objteatro' => $teatro);

        $unaFuncion = new Funcion();
        $unaFuncion->cargar($auxFuncion);
        $teatroClave = $teatro;
        $exito = false;
        if ($teatroClave->comprobarFuncion($unaFuncion)) {
                $unaFuncion->insertar();
                $exito = true;
        }
        return $exito;
    }

    public static function bajaFuncion($idfuncion)
    {
        $exito = false;
        $unaFuncion = new Funcion();
        if ($unaFuncion->buscar($idfuncion)) {
            $unaFuncion->eliminar();
            $exito = true;
        }
        return $exito;

    }

    public static function modificarFuncion($idfuncion, $nombre, $horaInicio, $duracion, $precio)
    {
        $exito = false;
        $unaFuncion = new Funcion();
        if ($unaFuncion->buscar($idfuncion)) {
            $unaFuncion->setNombre($nombre);
            $unaFuncion->setHoraInicio($horaInicio);
            $unaFuncion->setDuracion($duracion);
            $unaFuncion->setPrecio($precio);
            $unaFuncion->modificar();
            $exito = true;
        }
        return $exito;
    }

}