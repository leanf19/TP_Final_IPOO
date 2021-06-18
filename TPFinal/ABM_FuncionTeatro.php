<?php

require_once 'FuncionTeatro.php';

class ABM_FuncionTeatro
{
    //Alta:Funcion Teatro - Se encarga de recibir,preparar,comprobar e insertar los datos de una nueva FuncionTeatro
    public static function altaFuncionTeatro($idfuncion, $nombre, $horaInicio, $duracion, $precio, $teatro)
    {
        $auxFunTeatro = array('idfuncion' => $idfuncion, 'nombre' => $nombre, 'hora_inicio' => $horaInicio,
                                'duracion' => $duracion, 'precio' => $precio, 'objteatro' => $teatro);
        $exito=false;
        $funcionTeatro = new FuncionTeatro();
        $funcionTeatro->cargar($auxFunTeatro);
        $teatroClave = new Teatro();
        //if ($teatroClave->buscar($idteatro)) {
            if ($teatroClave->comprobarHorario($idfuncion,$horaInicio,$duracion)) {
                $exito = $funcionTeatro->insertar();

            }
       // }
        return $exito;
    }
    //Baja:Funcion Teatro - se encarga de eliminar una funcion
    public static function bajaFuncionTeatro($idfuncion)
    {
        $exito = false;
        $funcionTeatro = new FuncionTeatro();
        if ($funcionTeatro->buscar($idfuncion)) {
            $funcionTeatro->eliminar();
            $exito = true;
        }
    return $exito;
    }

    //Modificar:Funcion Teatro - se encarga de cargar, busca y comprobar que los datos de la funcion a modificar no se solapen
    public static function modificarFuncionTeatro($idfuncion, $nombre, $horaInicio, $duracion, $precio, $teatro)
    {
        $auxTeatral = array('idfuncion' => $idfuncion, 'nombre' => $nombre, 'hora_inicio' => $horaInicio,
                        'duracion' => $duracion, 'precio' => $precio, 'objteatro' => $teatro);
        $teatroClave= $teatro;
        $funcionTeatro = new FuncionTeatro();
        $exito = false;
        if ($funcionTeatro->buscar($idfuncion)) {
            if ($teatroClave->comprobarHorario($idfuncion,$horaInicio, $duracion)) {
                $funcionTeatro->cargar($auxTeatral);
                $funcionTeatro->modificar();
                $exito=true;
            }
        }
        return $exito;
    }
}