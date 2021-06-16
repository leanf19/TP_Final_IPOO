<?php

require_once 'FuncionTeatro.php';

class ABM_FuncionTeatro
{
    //Alta:Funcion Teatro - Se encarga de recibir,preparar,comprobar e insertar los datos de una nueva FuncionTeatro
    public static function altaFuncionTeatro($idfuncion, $nombre, $horaInicio, $duracion, $precio, $idteatro)
    {
        $auxFunTeatro = array('idfuncion' => $idfuncion, 'nombre' => $nombre, 'hora_inicio' => $horaInicio,
                                'duracion' => $duracion, 'precio' => $precio, 'idteatro' => $idteatro);
        $exito=false;
        $funcionTeatro = new FuncionTeatro();
        $funcionTeatro->cargar($auxFunTeatro);
        $teatroClave = new Teatro();
        if ($teatroClave->buscar($idteatro)) {
            if ($teatroClave->comprobarFuncion($funcionTeatro)) {
                $funcionTeatro->insertar();
                $exito=true;
            }
        }
        return $exito;
    }
    //Baja:Funcion Teatro - se encarga de eliminar una funcion
    public static function bajaFuncionTeatro($idfuncion)
    {
        $funcionTeatro = new FuncionTeatro();
        if ($funcionTeatro->buscar($idfuncion)) {
            $funcionTeatro->eliminar();
        }

    }

    //Modificar:Funcion Teatro - se encarga de cargar, busca y comprobar que los datos de la funcion a modificar no se solapen
    public static function modificarFuncionTeatro($idfuncion, $nombre, $horaInicio, $duracion, $precio, $idteatro)
    {
        $auxTeatral = array('idfuncion' => $idfuncion, 'nombre' => $nombre, 'hora_inicio' => $horaInicio,
                        'duracion' => $duracion, 'precio' => $precio, 'idteatro' => $idteatro);
        $teatroClave = new Teatro();
        $teatroClave->buscar($idteatro);
        $funcionMusical = new FuncionTeatro();
        $modifica = false;
        if ($funcionMusical->buscar($idfuncion)) {
            if ($teatroClave->comprobarHorario($idfuncion,$horaInicio, $duracion)) {
                $funcionMusical->cargar($auxTeatral);
                $funcionMusical->modificar();
                $modifica=true;
            }
        }
        return $modifica;
    }
}