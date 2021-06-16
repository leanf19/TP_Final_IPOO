<?php


require_once'FuncionCine.php';

class ABM_FuncionCine
{
    //Alta:Funcion Cine - Se encarga de recibir,preparar,comprobar e insertar los datos de una nueva FuncionCine
    public static function altaFuncionCine($idfuncion, $nombre, $horainicio, $duracion, $precio, $idteatro, $genero, $pais)
    {
        $auxCine = array('idfuncion' => $idfuncion, 'nombre' => $nombre, 'hora_inicio' => $horainicio, 'duracion' => $duracion,
                              'precio' => $precio, 'idteatro' => $idteatro, 'genero' => $genero, 'pais_origen' => $pais);
        $funcionCine = new FuncionCine();
        $funcionCine->cargar($auxCine);
        $exito = false;
        $teatroClave = new Teatro();
        if ($teatroClave->buscar($idteatro)) {
            if ($teatroClave->comprobarFuncion($funcionCine)) {
                $funcionCine->insertar();
                $exito = true;
            }
        }
        return $exito;
    }
    //Baja:Funcion Cine - se encarga de eliminar una funcion
    public static function bajaFuncionCine($idfuncion)
    {
        $funcionCine = new FuncionCine();
        if ($funcionCine->buscar($idfuncion)) {
            $funcionCine->eliminar();
        }
    }
    //Modificar:Funcion Cine - se encarga de cargar, busca y comprobar que los datos de la funcion a modificar no se solapen
    public static function modificarFuncionCine($idfuncion, $nombre, $horainicio, $duracion, $precio, $idteatro, $genero, $paisOrigen)
    {
        $auxCine = array('idfuncion' => $idfuncion, 'nombre' => $nombre, 'hora_inicio' => $horainicio, 'duracion' => $duracion,
                            'precio' => $precio, 'idteatro' => $idteatro, 'genero' => $genero, 'pais_origen' => $paisOrigen);

        $teatroClave = new Teatro();
        $teatroClave->buscar($idteatro);
        $funcionCine = new FuncionCine();
        $modifica = false;
        if ($funcionCine->buscar($idfuncion)) {

            if ($teatroClave->comprobarHorario($idfuncion,$horainicio, $duracion)) {
                $funcionCine->cargar($auxCine);
                $funcionCine->modificar();
                $modifica = true;
            }
        }
       return $modifica;
    }
}