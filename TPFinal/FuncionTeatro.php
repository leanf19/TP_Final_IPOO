<?php

class FuncionTeatro extends Funcion
{

    /**
     * FuncionTeatro constructor.
     */
    public function __construct()
    {
        parent:: __construct();
    }

    public function cargar($funcion)
    {
        parent::cargar($funcion);
     }

    public function buscar($idfuncion)
    {
        $base = new BaseDatos();
        $consultaFuncion = "SELECT * FROM funcion_teatro WHERE idfuncion={$idfuncion}";
        $exito = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consultaFuncion)) {
                if ($base->Registro()) {
                    parent::buscar($idfuncion);
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
        $auxFuncTeatro = null;
        $base = new BaseDatos();
        $consulta = "SELECT * FROM funcion_teatro t, funcion f";
        if ($condicion != "") {
            $consulta = "{$consulta} WHERE {$condicion}";
        }
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                $auxFuncTeatro = array();
                while ($row2 = $base->Registro()) {
                    $funcionTeatro = new FuncionTeatro();
                    $funcionTeatro->cargar($row2);
                    array_push($auxFuncTeatro, $funcionTeatro);
                }
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $auxFuncTeatro;
    }

    public function insertar()
    {
        $base = new BaseDatos();
        $exito = false;
        if (parent::insertar()) {
            $consulta = "INSERT INTO funcion_teatro(idfuncion) VALUES(" . parent::getIdFuncion() . ")";
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
        parent::modificar();
    }

    public function eliminar()
    {
        $base = new BaseDatos();
        $exito = false;
        if ($base->Iniciar()) {
            $consulta = "DELETE FROM funcion_teatro WHERE idfuncion=" . parent::getIdfuncion();

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


    public function __toString(): string
    {
        return parent::__toString();
    }
    public function calcularCosto()
    { //Teniendo en cuenta que el costo es lo que se le aplica al total solo se vera reflejado el interes no la suma de la entrada+interes (no *1.45)
        return parent::calcularCosto() * 0.45;
    }

}