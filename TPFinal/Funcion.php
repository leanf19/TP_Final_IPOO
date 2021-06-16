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
        $this->setObjTeatro($funcion['idteatro']);
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
        $cadena = "\nNombre de la funcion: {$this->getNombre()}\n Hora de inicio: {$this->getHorainicio()}\n Duracion: {$this->getDuracion()} \nPrecio: {$this->getPrecio()}\n";

        return $cadena;
    }

    public function calcularCosto()
    {
        return $this->getPrecio();
    }

    public function buscar($id)
    {
        $base = new BaseDatos();
        $consulta = "SELECT * FROM funcion WHERE idfuncion=$id";
        $exito = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                if ($row2 = $base->Registro()) {
                    $objTeatro = new Teatro();
                    $objTeatro->buscar($row2['idteatro']);
                    $this->cargar($row2);
                    $exito = true;
                }
            } else {
                $aux = $base->getError();
                $this->setMensajeoperacion($aux);
            }
        } else {
            $aux = $base->getError();
            $this->setMensajeoperacion($aux);
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
                    $idTeatro = $row2['idteatro'];

                    $funcion = new Funcion();
                    $objTeatro = new Teatro();
                    $objTeatro->buscar($idTeatro);
                    $row2[] = ['objteatro' => $objTeatro];
                    $funcion->cargar($row2);
                    array_push($colFunciones, $funcion);
                }
            } else {
                $aux = $base->getError();
                $this->setMensajeoperacion($aux);
            }
        } else {
            $aux = $base->getError();
            $this->setMensajeoperacion($aux);
        }
        return $colFunciones;
    }

    public function insertar()
    {
        $base = new BaseDatos();
        $exito = false;
        $consultaInsertar = "INSERT INTO funcion(idfuncion,nombre,hora_inicio,duracion,precio,idteatro) 
                                VALUES(null,'{$this->getNombre()}','{$this->getHorainicio()}',
                                {$this->getDuracion()},{$this->getPrecio()},{$this->getObjTeatro()})";
        echo "\n".$consultaInsertar."\n";
        if ($base->Iniciar()) {
            if ($id = $base->devuelveIDInsercion($consultaInsertar)) {
                $this->setIdfuncion($id);
                $unTeatro = new Teatro();
                $unTeatro->buscar($this->getObjTeatro());
                $this->setObjTeatro($unTeatro);
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
        echo $consulta."\n";
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
            $qryDelete = "DELETE FROM funcion WHERE idfuncion= {$this->getIdfuncion()}";
            if ($base->Ejecutar($qryDelete)) {
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