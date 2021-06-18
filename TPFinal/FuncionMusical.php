<?php

class FuncionMusical extends Funcion
{
 private $director;
 private $cantPersonas;


    public function __construct()
    {   parent:: __construct();
        $this->director = "";
        $this->cantPersonas = 0;
    }

    public function cargar($funcion)
    {
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


    public function setMensajeoperacion($mensajeoperacion): void
    {
        $this->mensajeoperacion = $mensajeoperacion;
    }

    public function __toString(): string
    {
        $cadena = parent::__toString();
        $cadena.= "Director: {$this->getDirector()}\nPersonas en Escena: {$this->getCantPersonas()}\n";
        return $cadena;

    }

    public function calcularCosto()
    {
        return parent::calcularCosto() * 0.12;
    }

    public function buscar($idfuncion)
    {
        $base = new BaseDatos();
        $consulta = "SELECT * FROM funcion_musical WHERE idfuncion={$idfuncion}";
        $exito = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
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
        $colFuncMusical = null;
        $base = new BaseDatos();
        $consulta = "SELECT * FROM funcion INNER JOIN funcion_musical m on funcion.idfuncion = m.idfuncion";
        if ($condicion != "") {
            $consulta = "{$consulta} WHERE {$condicion}";
        }
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                $colFuncMusical = array();
                while ($row2 = $base->Registro()) {
                    $funcionMusical = new FuncionMusical();
                    $funcionMusical->buscar($row2['idfuncion']);
                    array_push($colFuncMusical, $funcionMusical);
                }
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $colFuncMusical;
    }

    public function insertar()
    {
        $base = new BaseDatos();
        $exito = false;
        if (parent::insertar()) {
            $consulta = "INSERT INTO funcion_musical(idfuncion,director,cantidad_personas) 
                VALUES(" . parent::getIdFuncion() . ",'{$this->getDirector()}','{$this->getCantPersonas()}')";
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