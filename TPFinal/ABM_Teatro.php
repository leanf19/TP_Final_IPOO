<?php


require_once'Teatro.php';
require_once'Funcion.php';

class ABM_Teatro
{   //Alta:Teatro - se encarga de cargar, comprobar e insertar un nuevo Teatro en la BD
    public static function altaTeatro($nombre, $direccion)
    {
        $unTeatro = new Teatro();
        $unTeatro->cargar(0, $nombre, $direccion);
        $exito = $unTeatro->insertar();
        return $exito;
    }
    //Baja:Teatro - se encarga de buscar, comprobar y eliminar el teatro con idteatro de la BD
    public static function eliminarTeatro($idteatro)
    {
        $unTeatro = new Teatro();
        $existe = $unTeatro->buscar($idteatro);

        if ($existe) {
            $unTeatro->eliminar();
        }
        return $existe;
    }

    //Modificar:Teatro - Se encarga de buscar el teatro con idteatro y si existe lo modifica con los valores pasados por parametro y actualiza la BD
    public static function modificarTeatro($idteatro, $nombre, $direccion)
    {
        $unTeatro = new Teatro();
        $exito = $unTeatro->buscar($idteatro);
        if ($exito) {
            $unTeatro->setNombre($nombre);
            $unTeatro->setDireccion($direccion);
            $unTeatro->modificar();
        }
        return $exito;
    }


}