<?php

class FuncionMusical extends Funcion
{
 private $director;
 private $cantPersonas;
 private $mensajeoperacion;


    public function __construct()
    {   parent:: __construct();
        $this->director = "";
        $this->cantPersonas = 0;
    }

    public function cargar($funcion){
        parent::cargar($funcion);
        $this->setDirector($funcion['director']);
        $this->setCantPersonas($funcion['cantidad_personas']);
    }

    public function getDirector()
    {
        return $this->director;
    }

    public function setDirector($dir): void
    {
        $this->director = $dir;
    }

    public function getCantPersonas()
    {
        return $this->cantPersonas;
    }

    public function setCantPersonas($cantPers): void
    {
        $this->cantPersonas = $cantPers;
    }

    public function getMensajeoperacion()
    {
        return $this->mensajeoperacion;
    }

    public function setMensajeoperacion($mensajeoperacion): void
    {
        $this->mensajeoperacion = $mensajeoperacion;
    }

    public function __toString(): string
    {
        $cadena = parent::__toString();
        $cadena.= "Director:{$this->getDirector()} \nPersonas en Escena:{$this->getCantPersonas()}\n";
        return $cadena;

    }

    public function calcularCosto()
    { //Teniendo en cuenta que el costo es lo que se le aplica al total solo se vera reflejado el interes no la suma de la entrada+interes (no *1.12)
        return parent::calcularCosto() * 0.12;
    }

    public function buscar($idfuncion)
    {
        $base = new BaseDatos();
        $consultaFuncion = "SELECT * FROM funcion_musical WHERE idfuncion={$idfuncion}";
        $exito = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaFuncion)) {
                if ($row2 = $base->Registro()) {
                    parent::buscar($idfuncion);
                    $this->setDirector($row2['director']);
                    $this->setCantPersonas($row2['cantidad_personas']);
                    $exito = true;
                }
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $exito;
    }

    public function listar($condicion = "")
    {
        $auxMusical = null;
        $base = new BaseDatos();
        $consulta = "SELECT * FROM funcion_musical m, funcion f";
        if ($condicion != "") {
            $consulta = "{$consulta} WHERE {$condicion}";
        }
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                $auxMusical = array();
                while ($row2 = $base->Registro()) {
                    $objMusical = new FuncionMusical();
                    $objMusical->cargar($row2);
                    array_push($auxMusical, $objMusical);
                }
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $auxMusical;
    }

    public function insertar()
    {
        $base = new BaseDatos();
        $exito = false;
        if (parent::insertar()) {
            $consulta = "INSERT INTO funcion_musical VALUES(" . parent::getIdFuncion() . ",'{$this->getDirector()}','{$this->getCantPersonas()}')";
            echo $consulta;
            if ($base->Iniciar()) {
                if ($base->Ejecutar($consulta)) {
                    $exito = true;
                } else {
                    $this->setmensajeoperacion($base->getError());
                }
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        }
        return $exito;
    }

    public function modificar()
    {
        $exito = false;
        $base = new BaseDatos();
        $consulta = "UPDATE funcion_musical SET director='{$this->getDirector()}', cantidad_personas={$this->getCantPersonas()}
        WHERE idfuncion =" . parent::getIdfuncion();
        if (parent::modificar()) {
            if ($base->Iniciar()) {
                if ($base->Ejecutar($consulta)) {
                    $exito = true;
                } else {
                    $this->setmensajeoperacion($base->getError());
                }
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        }
        return $exito;
    }

    public function eliminar()
    {
        $base = new BaseDatos();
        $exito = false;
        if ($base->Iniciar()) {
            $consulta = "DELETE FROM funcion_musical WHERE idfuncion=" . parent::getIdfuncion();
            if ($base->Ejecutar($consulta)) {
                $exito = true;
                parent::eliminar();
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $exito;
    }

}