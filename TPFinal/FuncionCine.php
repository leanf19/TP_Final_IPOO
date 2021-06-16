<?php
require 'Funcion.php';


class FuncionCine extends Funcion
{
    private $genero;
    private $pais;



    public function __construct()
    {   parent:: __construct();
        $this->genero = "";
        $this->pais = "";
    }

    public function cargar($funcion)
    {
        parent::cargar($funcion);
        $this->setGenero($funcion['genero']);
        $this->setPais($funcion['pais_origen']);
    }

    public function getGenero()
    {
        return $this->genero;
    }

    public function setGenero($igenero): void
    {
        $this->genero = $igenero;
    }

    public function getPais()
    {
        return $this->pais;
    }

    public function setPais($ipais): void
    {
        $this->pais = $ipais;
    }


    public function setMensajeoperacion($mensajeoperacion): void
    {
        $this->mensajeoperacion = $mensajeoperacion;
    }

    public function __toString(): string
    {
        $cadena = parent::__toString();
        $cadena.= "Genero:{$this->getGenero()}
                 \nPais:{$this->getPais()}\n";
        return $cadena;

    }

    public function calcularCosto()
    { //Teniendo en cuenta que el costo es lo que se le aplica al total solo se vera reflejado el interes no la suma de la entrada+interes (no *1.65)
        return parent::calcularCosto() * 0.65;
    }

    public function buscar($idfuncion)
    {
        $base = new BaseDatos();
        $consulta = "SELECT * FROM funcion_cine WHERE idfuncion={$idfuncion}";
        echo $consulta;
        $resp = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                if ($row2 = $base->Registro()) {

                    parent::buscar($idfuncion);
                    $this->setGenero($row2['genero']);
                    $this->setPais($row2['pais_origen']);
                    $resp = true;
                }
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $resp;
    }

    public function listar($condicion = "")
    {
        $auxCine = null;
        $base = new BaseDatos();
        $consulta = "SELECT * FROM funcion_cine";
        if ($condicion != "") {
            $consulta = "{$consulta} WHERE {$condicion}";
        }
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                $auxCine = array();
                while ($row2 = $base->Registro()) {
                    $objCine = new FuncionCine();
                    $objCine->Buscar($row2['nrodoc']);
                    array_push($auxCine, $objCine);
                }
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $auxCine;
    }

    public function insertar()
    {
        $base = new BaseDatos();
        $exito = false;
        if (parent::insertar()) {
            echo "se inserto en el padre\n";
            $consultaInsertar = "INSERT INTO funcion_cine(idfuncion,genero,pais_origen) VALUES(" . parent::getIdFuncion() . ",'{$this->getGenero()}','{$this->getPais()}')";
            if ($base->Iniciar()) {
                echo $consultaInsertar;
                if ($base->Ejecutar($consultaInsertar)) {
                    echo "se inserto en el hijo";
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
        if (parent::modificar()) {
            $consultaModifica = "UPDATE funcion_cine SET genero='{$this->getGenero()}', pais_origen='{$this->getPais()}' 
        WHERE idfuncion =".parent::getIdfuncion();
            if ($base->Iniciar()) {
                if ($base->Ejecutar($consultaModifica)) {
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
            $consulta = "DELETE FROM funcion_cine WHERE idfuncion=" . parent::getIdfuncion();
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