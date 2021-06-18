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
    //implementacion alternativa de la funcion cargar
   /* public function cargarObjFuncion($idfuncion,$nombre,$horaInicio,$duracion,$precio,$objTeatro,$genero,$pais)
    {
        parent::cargarObjFuncion($idfuncion,$nombre,$horaInicio,$duracion,$precio,$objTeatro);
        $this->setGenero($genero);
        $this->setPais($pais);
    }
*/
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
        $cadena.= "Genero: {$this->getGenero()}\nPais: {$this->getPais()}\n";
        return $cadena;

    }

    public function calcularCosto()
    {
        return parent::calcularCosto() * 0.65;
    }

    public function buscar($idfuncion)
    {

        $base = new BaseDatos();
        $consulta = "SELECT * FROM funcion_cine WHERE idfuncion={$idfuncion}";
        $exito = false;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                if ($row2 = $base->Registro()) {
                    parent::buscar($idfuncion);
                    $this->setGenero($row2['genero']);
                    $this->setPais($row2['pais_origen']);
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
        $colFuncionCine = null;
        $base = new BaseDatos();
        $consulta = "SELECT * FROM funcion INNER JOIN funcion_cine c on funcion.idfuncion = c.idfuncion";
        if ($condicion != "") {
            $consulta = "{$consulta} WHERE {$condicion}";
        }
        if ($base->Iniciar()) {
            if ($base->Ejecutar($consulta)) {
                $colFuncionCine = array();
                while ($row2 = $base->Registro()) {
                    $funcionCine = new FuncionCine();
                    $funcionCine->buscar($row2['idfuncion']);
                    array_push($colFuncionCine, $funcionCine);
                }
            } else {
                $this->setmensajeoperacion($base->getError());
            }
        } else {
            $this->setmensajeoperacion($base->getError());
        }
        return $colFuncionCine;
    }

    public function insertar()
    {
        $base = new BaseDatos();
        $exito = false;
        if (parent::insertar()) {
            $consulta = "INSERT INTO funcion_cine(idfuncion,genero,pais_origen)
            VALUES(" . parent::getIdFuncion() . ",'{$this->getGenero()}','{$this->getPais()}')";
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
        if (parent::modificar()) {
            $consulta = "UPDATE funcion_cine SET genero='{$this->getGenero()}', pais_origen='{$this->getPais()}' 
        WHERE idfuncion =".parent::getIdfuncion();
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