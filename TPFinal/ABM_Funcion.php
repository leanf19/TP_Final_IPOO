<?php


class ABM_Funcion
{
    public static function altaFuncion($idfuncion,$nombre, $horaInicio, $duracion, $precio, $idteatro)
    {
        $auxFuncion = array('idfuncion' => $idfuncion, 'nombre' => $nombre, 'hora_inicio' => $horaInicio,
            'duracion' => $duracion, 'precio' => $precio, 'idteatro' => $idteatro);

        $unaFuncion = new Funcion();
        $unaFuncion->cargar($auxFuncion);
        $teatroClave = new Teatro();

        if ($teatroClave->buscar($idteatro)) {
            if ($teatroClave->comprobarFuncion($unaFuncion)) {
                $unaFuncion->insertar();
            }
        }
    }

    public static function bajaFuncion($idfuncion)
    {
        $unaFuncion = new Funcion();
        if ($unaFuncion->buscar($idfuncion)) {
            $unaFuncion->eliminar();
        }

    }

    public static function modificarFuncion($idfuncion, $nombre, $horaInicio, $duracion, $precio)
    {
        $unaFuncion = new Funcion();
        if ($unaFuncion->buscar($idfuncion)) {
            $unaFuncion->setNombre($nombre);
            $unaFuncion->setHoraInicio($horaInicio);
            $unaFuncion->setDuracion($duracion);
            $unaFuncion->setPrecio($precio);
            $unaFuncion->modificar();
        }
    }
}