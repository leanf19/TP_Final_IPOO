<?php

require_once ('ORM/FuncionMusical.php');

class ABM_FuncionMusical
{
    //Alta:Funcion Musical - Se encarga de recibir,preparar,comprobar e insertar los datos de una nueva FuncionMusical
    public static function altaFuncionMusical($idfuncion, $nombre, $horaInicio, $duracion, $precio, $teatro, $director, $cantPersonas) {

        $auxMusical = array('idfuncion' => $idfuncion, 'nombre' => $nombre, 'hora_inicio' => $horaInicio, 'duracion' => $duracion,
                                'precio' => $precio, 'objteatro' => $teatro, 'director' => $director, 'cantidad_personas' => $cantPersonas);

        $funcionMusical = new FuncionMusical();
        $funcionMusical->cargar($auxMusical);
        $teatroClave = $teatro;
        $exito = false;
            if ($teatroClave->comprobarHorario($idfuncion,$horaInicio,$duracion)) {
                $exito =$funcionMusical->insertar();

            }
        return $exito;
    }
    //Baja:Funcion Musical - se encarga de eliminar una funcion
    public static function bajaFuncionMusical($idfuncion)
    {
        $exito = false;
        $funcionMusical = new FuncionMusical();
        if ($funcionMusical->buscar($idfuncion)) {
            $funcionMusical->eliminar();
            $exito = true;
        }
        return $exito;
    }
    //Modificar:Funcion Musical - se encarga de cargar, busca y comprobar que los datos de la funcion a modificar no se solapen
    public static function modificarFuncionMusical($idfuncion, $nombre, $horaInicio, $duracion, $precio, $teatro, $director, $cantPersonas)
    {
        $auxMusical = array('idfuncion' => $idfuncion, 'nombre' => $nombre, 'hora_inicio' => $horaInicio, 'duracion' => $duracion,
                        'precio' => $precio, 'objteatro' => $teatro, 'director' => $director, 'cantidad_personas' => $cantPersonas);

        $teatroClave = $teatro;
        $funcionMusical = new FuncionMusical();
        $exito = false;

        if ($funcionMusical->buscar($idfuncion)) {
            if ($teatroClave->comprobarHorario($idfuncion,$horaInicio, $duracion)) {
                $funcionMusical->cargar($auxMusical);
                $funcionMusical->modificar();
                $exito=true;
            }
        }
        return $exito;
    }
}