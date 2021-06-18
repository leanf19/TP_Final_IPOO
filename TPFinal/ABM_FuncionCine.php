<?php


require_once'FuncionCine.php';

class ABM_FuncionCine
{
    //Alta:Funcion Cine - Se encarga de recibir,preparar,comprobar e insertar los datos de una nueva FuncionCine
    public static function altaFuncionCine($idfuncion, $nombre, $horainicio,$duracion,$precio,$teatro, $genero, $pais)
    {
        $auxCine = array('idfuncion' => $idfuncion, 'nombre' => $nombre, 'hora_inicio' => $horainicio, 'duracion' => $duracion,
                              'precio' => $precio, 'objteatro'=>$teatro, 'genero' => $genero, 'pais_origen' => $pais);

        $funcionCine = new FuncionCine();
        $funcionCine->cargar($auxCine);
        $teatroClave = $teatro;
        $exito = false;
            if ($teatroClave->comprobarHorario($idfuncion,$horainicio,$duracion)) {
                $exito = $funcionCine->insertar();

            }

        return $exito;
    }
    //Baja:Funcion Cine - se encarga de eliminar una funcion
    public static function bajaFuncionCine($idfuncion)
    {
        $exito = false;
        $funcionCine = new FuncionCine();
        if ($funcionCine->buscar($idfuncion)) {
            $funcionCine->eliminar();
            $exito = true;
        }
        return $exito;
    }
    //Modificar:Funcion Cine - se encarga de cargar, busca y comprobar que los datos de la funcion a modificar no se solapen
    public static function modificarFuncionCine($idfuncion, $nombre, $horainicio, $duracion, $precio,$teatro, $genero, $pais)
    {
        $auxCine = array('idfuncion' => $idfuncion, 'nombre' => $nombre, 'hora_inicio' => $horainicio, 'duracion' => $duracion,
                            'precio' => $precio,'objteatro'=>$teatro, 'genero' => $genero, 'pais_origen' => $pais);
        $teatroClave = $teatro;
        $funcionCine = new FuncionCine();
        $exito = false;
        if ($funcionCine->buscar($idfuncion)) {
            if ($teatroClave->comprobarHorario($idfuncion,$horainicio, $duracion)) {
                $funcionCine->cargar($auxCine);
                $exito = $funcionCine->modificar();
            }
        }
       return $exito;
    }
}