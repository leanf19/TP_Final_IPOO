<?php

require_once 'FuncionMusical.php';

class ABM_FuncionMusical
{
    //Alta:Funcion Musical - Se encarga de recibir,preparar,comprobar e insertar los datos de una nueva FuncionMusical
    public static function altaFuncionMusical($idfuncion, $nombre, $horaInicio, $duracion, $precio, $idteatro, $director, $cantPersonas) {

        $auxMusical = array('idfuncion' => $idfuncion, 'nombre' => $nombre, 'hora_inicio' => $horaInicio, 'duracion' => $duracion,
                                'precio' => $precio, 'idteatro' => $idteatro, 'director' => $director, 'cantidad_personas' => $cantPersonas);
        $exito = false;
        $funcionMusical = new FuncionMusical();
        $funcionMusical->cargar($auxMusical);
        $teatroClave = new Teatro();
        if ($teatroClave->buscar($idteatro)) {
            if ($teatroClave->comprobarFuncion($funcionMusical)) {
                $funcionMusical->insertar();
                $exito = true;

            }
        }
        return $exito;
    }
    //Baja:Funcion Musical - se encarga de eliminar una funcion
    public static function bajaFuncionMusical($idfuncion)
    {
        $funcionMusical = new FuncionMusical();
        if ($funcionMusical->buscar($idfuncion)) {
            $funcionMusical->eliminar();
        }
    }
    //Modificar:Funcion Musical - se encarga de cargar, busca y comprobar que los datos de la funcion a modificar no se solapen
    public static function modificarFuncionMusical($idfuncion, $nombre, $horaInicio, $duracion, $precio, $idteatro, $director, $cantPersonas)
    {
        $auxMusical = array('idfuncion' => $idfuncion, 'nombre' => $nombre, 'hora_inicio' => $horaInicio, 'duracion' => $duracion,
                        'precio' => $precio, 'idteatro' => $idteatro, 'director' => $director, 'cantidad_personas' => $cantPersonas);

        $teatroClave = new Teatro();
        $teatroClave->buscar($idteatro);
        $funcionMusical = new FuncionMusical();
        $modifica = false;

        if ($funcionMusical->buscar($idfuncion)) {
            if ($teatroClave->comprobarHorario($idfuncion,$horaInicio, $duracion)) {
                $funcionMusical->cargar($auxMusical);
                $funcionMusical->modificar();
                $modifica=true;
            }
        }
        return $modifica;
    }
}