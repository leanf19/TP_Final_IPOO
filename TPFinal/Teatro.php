<?php

include_once 'BaseDatos.php';
include 'FuncionCine.php';
include 'FuncionTeatro.php';
include 'FuncionMusical.php';



class Teatro
{
    private $idTeatro;
    private $nombre;
    private $direccion;
    private $funciones;


    public function __construct()
    {
        $this->idTeatro = 0;
        $this->nombre = "";
        $this->direccion = "";
        $this->funciones = array();
    }

    public function cargar($idteatro, $nombre, $direccion){
        $this->setIdteatro($idteatro);
        $this->setNombre($nombre);
        $this->setDireccion($direccion);
    }

    public function getNombre()
    {
        return $this->nombre;
    }
    public function getDireccion()
    {
        return $this->direccion;
    }
    public function getFunciones()
    {
        return $this->funciones;

    }


    public function getIdTeatro()
    {
        return $this->idTeatro;
    }



    public function setIdTeatro($idTeatro)
    {
        $this->idTeatro = $idTeatro;
    }
    public function setNombre($nom)
    {
        $this->nombre = $nom;
    }
    public function setDireccion($dir)
    {
        $this->direccion = $dir;
    }
    public function setmensajeoperacion($mensajeoperacion){
        $this->mensajeoperacion=$mensajeoperacion;
    }


    public function setFunciones(array $func)
    {
        $this->funciones = $func;
    }


        public function Buscar($idteatro){
            $base=new BaseDatos();
            $consultaTeatro = "SELECT * FROM teatro WHERE idteatro={$idteatro}";
            $exito= false;
            if($base->Iniciar()){
                if($base->Ejecutar($consultaTeatro)){

                    if($row2=$base->Registro()){
                        $this->cargar($idteatro, $row2['nombre'], $row2['direccion']);
                        $exito= true;
                    }

                }	else {
                    $this->setmensajeoperacion($base->getError());

                }
            }	else {
                $this->setmensajeoperacion($base->getError());

            }
            return $exito;
        }

    public function listar($condicion=""){
        $auxTeatros = null;
        $base=new BaseDatos();
        //obtengo toda la tabla teatro
        $consulta="SELECT * FROM teatro ";
        if ($condicion!=""){
            $consulta = "{$consulta} WHERE {$condicion}";
        }

        if($base->Iniciar()){
            if($base->Ejecutar($consulta)){
                $auxTeatros= array();
                while($row2=$base->Registro()){
                    $id=$row2['idteatro'];
                    $nombre=$row2['nombre'];
                    $direccion=$row2['direccion'];

                    $unTeatro=new Teatro();
                    $unTeatro->cargar($id,$nombre,$direccion);
                    array_push($auxTeatros,$unTeatro);
                }

            }	else {
                $this->setmensajeoperacion($base->getError());

            }
        }	else {
            $this->setmensajeoperacion($base->getError());

        }
        return $auxTeatros;
    }

    public function insertar()
    {
        $base = new BaseDatos();
        $exito = false;
        $consulta = "INSERT INTO teatro(idteatro,nombre,direccion) VALUES(null,'{$this->getNombre()}','{$this->getDireccion()}')";

        if ($base->Iniciar()) {
            if ($id = $base->devuelveIDInsercion($consulta)) {
                $this->setIdteatro($id);
                $exito = true;
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $exito;
    }

    public function modificar()
    {
        $exito = false;
        $base = new BaseDatos();
        $consulta = "UPDATE teatro SET nombre='{$this->getNombre()}',direccion='{$this->getDireccion()}' WHERE idteatro = {$this->getIdteatro()}";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                $exito = true;
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $exito;
    }

    public function eliminar()
    {

        $base = new BaseDatos();
        $exito = false;
        if ($base->Iniciar()) {
            $auxTeatro = $this->getFunciones();

            if (count($auxTeatro) != 0) {
            //elimino primero las funciones de mismo idteatro
                foreach ($auxTeatro as $funcion) {
                    $funcion->eliminar();
                }
            }
            $consulta = "DELETE FROM teatro WHERE idTeatro= {$this->getIdTeatro()}";
            if ($base->Ejecutar($consulta)) {
                $exito = true;
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $exito;
    }




    public function agregarFunciones($index, $unaFuncion)
    {
        //devuelve false si no se pudo agregar la funcion dentro del arreglo de funciones
        $exito = false;
        $horaFuncion = $unaFuncion->getHorainicio();
        $duracionFuncion = $unaFuncion->getDuracion();
        //Se comprueba que no se solapen los horarios con otra funcion
        if ($this->comprobarHorario($index,$horaFuncion, $duracionFuncion)) {
            $tempFunciones = $this->getFunciones();
            $tempFunciones[] = $unaFuncion;
            $this->setFunciones($tempFunciones);
            $exito = true;
        }
        return $exito;
    }

    public function modificarFunciones($opcion, $indice, $valor)
    {
        $aux = $this->funciones[$indice];
        switch($opcion) {

            case 1:
                //cambiar nombre
                $aux->setNombre($valor);
                break;

            case 2:
                //cambiar hora inicio
                $aux=$this->funciones[$indice];
                $duracion = $aux->getDuracion();
                $existe = $this->comprobarHorario($indice,$valor,$duracion);
                if($existe)
                {

                    $aux->setHoraInicio($valor);
                }
                else
                {
                    echo "El horario elegido se superpone con el horario de otra funcion \n";
                }
                break;

            case 3:
                //cambiar duracion
                $aux=$this->funciones[$indice];
                $horaFuncion = $aux->getHorainicio();
                $existe = $this->comprobarHorario($indice,$horaFuncion,$valor);
                if($existe)
                {
                    $aux->setDuracion($valor);
                }
                else
                {
                    echo "La duracion de la funcion superpone el horario con otra funcion, por favor primero modifica el horario de la funcion \n";
                }
                break;

            case 4:
                //cambiar precio
                $aux->setPrecio($valor);
                break;
        }
    }
    //Transforma una hora en formato hh:mm en minutos
    public function aMinutos($horario){
        $horas = (int)substr($horario,0,2);
        $mins = (int)substr($horario,3,2);
        $enMinutos = $horas*60+$mins;

        return $enMinutos;
    }
    //Este metodo se encarga de comprobar si el horario o duracion a modificar no superpone los horarios entre Funciones
    public function comprobarHorario($id,$horaInicio,$duracion)
    {
        //Hora Inicio y Fin de la funcion nueva pasadas a minutos para comprobar disponibilidad de horarios
        $auxInicio = $this->aMinutos($horaInicio);
        $auxFin = $auxInicio+$duracion;
        $disponible = true;
        $i = 0;

        //Mientras no se solape o se compruebe en todos los horarios la disponibilidad
        while($disponible && $i < count($this->funciones))
        {
             $aux= $this->funciones[$i];
            //No realiza la comprobacion sobre la funcion que se desea modificar
            if($aux->getIdfuncion() != $id)
            {

                //Comprueba que la hora INICIO de la funcion del indice actual no este entre la hora de Inicio y Fin de la nueva funcion

                $otroHorario = $this->aMinutos($aux->getHorainicio());
                if($auxInicio <= $otroHorario && $otroHorario <= $auxFin)
                {

                    $disponible = false;
                }
                //Comprueba que la hora FIN de la funcion del indice actual no este entre la hora de Inicio y Fin de la nueva funcion
                $otroHorario = $otroHorario + $aux->getDuracion();

                if($auxInicio <= $otroHorario && $otroHorario <= $auxFin)
                {

                    $disponible = false;
                }


            }
            $i++;

        }

        return $disponible;
    }

    public function recorrerFunciones()
    {
        $salida = "";
        foreach ($this->getFunciones() as $funcion) {
            $salida .= "----------------------------------------\n";
            $salida .= $funcion->__toString();
            $salida .= "----------------------------------------\n";
        }
        return $salida;
    }


    public function __toString()
    {
        $salida = "\nIdTeatro: {$this->getIdTeatro()}
                   \nNombre teatro: {$this->getNombre()}
                   \nDireccion: {$this->getDireccion()}
                    \nFunciones disponibles:\n";
        //a continuacion se muestra cada funcion del arreglo
        $salida .= $this->concatenarFunciones();

        return $salida;
    }
    //Recorre tod* el arreglo, concatena y devuelve al __toString
    public function concatenarFunciones()
    {
        $i=0;
        $salida = "";
        foreach ($this->funciones as $funcion) {
            $salida .= "-----------------------------------------------\n";
            $salida .= "Funcion $i:";
            $salida .= $funcion->__toString();
            $salida .= "-----------------------------------------------\n";
            $i++;
        }
        return $salida;
    }

    public function darCosto()
    {
        $costo = 0;
        //se recorre el arreglo de funciones del teatro guardando y sumando el valor del costo total por el uso del mismo
        foreach ($this->getFunciones() as $funcion) {
            $costo += $funcion->calcularCosto();
        }
        return $costo;
    }
    //comprueba que el horario entre funciones no se solape
    public function comprobarFuncion($funcion)
    {
        $exito = false;
        $idfuncion = $funcion->getIdfuncion();
        $horaIni = $funcion->getHorainicio();
        $dur =  $funcion->getDuracion();
        if ($this->comprobarHorario($idfuncion,$horaIni,$dur)) {
            //$tempFunciones = $this->getFunciones();
           // $tempFunciones[] = $funcion;
           // $this->setFunciones($tempFunciones);
            $exito = true;
        }
        return $exito;
    }
}