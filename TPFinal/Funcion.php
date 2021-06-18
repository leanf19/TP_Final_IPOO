<?php

include_once 'BaseDatos.php';

class Funcion
{
    private $idfuncion;
    private $nombre;
    private $horainicio;
    private $duracion;
    private $precio;
    private $objTeatro;


/*Constructor anterior
    public function __construct($idfuncion, $nombre, $horanicio, $duracion, $costo, $idteatro)
    {
        $this->idfuncion = $idfuncion;
        $this->nombre = $nombre;
        $this->horainicio = $horainicio;
        $this->duracion = $duracion;
        $this->precio = $costo;
        $this->idteatro = $idteatro;

    } */

    public function __construct()
    {
        $this->idfuncion = 0;
        $this->nombre = "";
        $this->horainicio = "";
        $this->duracion = 0;
        $this->precio = 0;
    }

    public function cargar($funcion)
    {
        $this->setIdfuncion($funcion['idfuncion']);
        $this->setNombre($funcion['nombre']);
        $this->setHorainicio($funcion['hora_inicio']);
        $this->setDuracion($funcion['duracion']);
        $this->setPrecio($funcion['precio']);
        $this->setObjTeatro($funcion['objteatro']);
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getIdfuncion(): int
    {
        return $this->idfuncion;
    }

    public function getObjTeatro()
    {
        return $this->objTeatro;
    }

    public function getHorainicio()
    {
        return $this->horainicio;
    }

    public function getDuracion()
    {
        return $this->duracion;
    }

    public function getPrecio()
    {
        return $this->precio;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function setHorainicio($horainicio)
    {
        $this->horainicio = $horainicio;
    }

    public function setDuracion($duracion)
    {
        $this->duracion = $duracion;
    }

    public function setPrecio($costo)
    {
        $this->precio = $costo;
    }

    public function setObjTeatro($objTeatro)
    {
        $this->objTeatro = $objTeatro;
    }

    public function setIdfuncion(int $idfuncion)
    {
        $this->idfuncion = $idfuncion;
    }



    public function __toString(): string
    {
        $horaIni=$this->getHoraInicio();
        $horaIni= substr($horaIni,0,5);
        $cadena = "\nIdFuncion: {$this->idfuncion}\nNombre de la funcion: {$this->getNombre()}\nHora de inicio: {$horaIni}\nDuracion: {$this->getDuracion()}\nPrecio: {$this->getPrecio()}\n";

        return $cadena;
    }

    public function calcularCosto()
    {
        return $this->getPrecio();
    }

    public function buscar($id)
    {
        $base = new BaseDatos();
        $consulta = "SELECT * FROM funcion WHERE idfuncion={$id}";
        $exito = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                if ($row2 = $base->Registro()) {
                    $objTeatro = new Teatro();
                    $objTeatro->buscar($row2['idteatro']);
                    $row2['objteatro'] = $objTeatro;
                    $this->cargar($row2);
                    $exito = true;
                }
            } else {
                $this->setMensajeoperacion($base->getError());
            }
        } else {
            $this->setMensajeoperacion($base->getError());
        }
        return $exito;
    }

    public function listar($condicion = "")
    {
        $colFunciones = null;
        $base = new BaseDatos();
        $consultaFuncion = "SELECT * FROM funcion";
        if ($condicion != "") {
            $consultaFuncion = "{$consultaFuncion} WHERE {$condicion}";
        }
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaFuncion)) {
                $colFunciones = array();
                while ($row2 = $base->Registro()) {
                    //$idTeatro = $row2['idteatro'];
                    $unaFuncion = new Funcion();
                    $unaFuncion->buscar($row2['idfuncion']);
                    array_push($colFunciones, $unaFuncion);
                }
            } else {
                $this->setMensajeoperacion($base->getError());
            }
        } else {
            $this->setMensajeoperacion($base->getError());
        }
        return $colFunciones;
    }

    public function insertar()
    {
        $base = new BaseDatos();
        $exito = false;
        $teatroActual = $this->getObjTeatro();
        $idteatro = $teatroActual->getIdTeatro();
        $consulta = "INSERT INTO funcion(idfuncion,nombre,hora_inicio,duracion,precio,idteatro) 
                                VALUES(null,'{$this->getNombre()}','{$this->getHorainicio()}',
                                {$this->getDuracion()},{$this->getPrecio()},{$idteatro})";

        if ($base->Iniciar()) {
            if ($id = $base->devuelveIDInsercion($consulta)) {
                $this->setIdfuncion($id);
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
        $consulta = "UPDATE funcion SET nombre='{$this->getNombre()}',hora_inicio='{$this->getHorainicio()}',duracion={$this->getDuracion()},precio={$this->getPrecio()} 
        WHERE idfuncion = {$this->getIdfuncion()}";
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
            $consulta = "DELETE FROM funcion WHERE idfuncion= {$this->getIdfuncion()}";
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





}