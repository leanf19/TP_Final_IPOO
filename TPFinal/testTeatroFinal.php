<?php
require_once "Teatro.php";
require_once "ABM_Teatro.php";
require_once "ABM_FuncionMusical.php";
require_once "ABM_FuncionCine.php";
require_once "ABM_FuncionTeatro.php";

function menu()
{
    do {
        $opcionTeatro = -1;
        echo "************INGRESE UNA OPCION DEL MENU***********\n";
        echo "1.- Visualizar la informacion de los teatros de la base de datos \n";
        echo "2.- Agregar un nuevo teatro a la base de datos\n";
        echo "3.- Eliminar un teatro de la base de datos\n";
        echo "4.- Salir.\n";

        $opcionTeatro = trim(fgets(STDIN));
        switch ($opcionTeatro) {
            case 1:
                menuTeatros();
                break;
            case 2:
                altaTeatro();
                break;
            case 3:
                bajaTeatro();
                break;
            case 4:
                echo "*************Terminando Sesion*************\n";
                break;
            default:
                echo "Ingrese una opcion correcta (1-4)\n";
                break;
        }
    } while ($opcionTeatro <> 4);
}

function menuTeatros()
{
    $unTeatro = new Teatro();
    $auxTeatros = $unTeatro->listar();
    if (count($auxTeatros) != 0) {
        foreach ($auxTeatros as $teatro) {
            $teatro->__toString();
        }
        do {
            echo "***Seleccione el id de uno de estos teatros***\n";
            $idteatro = trim(fgets(STDIN));
            $exito = $unTeatro->buscar($idteatro);
        } while (!$exito);

        echo "***Teatro obtenido, seleccione una de las siguientes opciones***\n";
        do {
            echo "1.- Mostrar Informacion del teatro\n";
            echo "2.- Modificar datos del teatro\n";
            echo "3.- Calcular costo total por uso del teatro\n";
            echo "4.- Volver al menu de inicio\n";
            echo "5.- Salir\n";
            $opcion = trim(fgets(STDIN));
            switch ($opcion) {
                case 1:
                    $unTeatro->__toString();
                    break;
                case 2:
                    modificarDatos($unTeatro);
                    break;
                case 3:
                    echo "Costo total: {$unTeatro->darCosto()}\n";
                    break;
                case 4:
                    menu();
                    break;
                default:
                    echo "Ingrese una opción correcta(1-5)\n";
            }
        } while ($opcion <> 5);

    } else {
        echo "***No hay teatros para mostrar***\n";
    }
}

function modificarDatos($unTeatro)
{
    $id = $unTeatro->getIdTeatro();
    $op = -1;
    do {

        echo "***Seleccione una de las siguientes opciones***\n";
        echo "1.- Cambiar datos del teatro(nombre y direccion)\n";
        echo "2.- Agregar una nueva funcion\n";
        echo "3.- Modificar una funcion\n";
        echo "4.- Eliminar una funcion\n";
        echo "5.- Volver al menú anterior\n";
        $op = trim(fgets(STDIN));
        switch ($op) {
            case 1:
                modificarTeatro($id);
                break;
            case 2:
                altaFuncion($id);
                break;
            case 3:
                modificarFuncion($unTeatro);
                break;
            case 4:
                bajaFuncion($unTeatro);
                break;
            default:
                echo "Ingrese una opción correcta(1-5)\n";
        }
    } while ($op <> 5);
}

//Modifica el nombre y la direccion del teatro con la id pasada por parametro
function modificarTeatro($idTeatro)
{

    echo "Ingrese el nuevo nombre del teatro seleccionado: \n";
    $nombre = trim(fgets(STDIN));
    echo "Ingrese la nueva direccion del teatro seleccionado: \n";
    $direccion = trim(fgets(STDIN));

    ABM_Teatro::modificarTeatro($idTeatro, $nombre, $direccion);
}

function altaFuncion($idteatro)
{

    echo "Ingrese el nombre de la función a agregar:\n";
    $nombreFuncion = strtolower(trim(fgets(STDIN)));

    do {
        echo "\n Ingrese el tipo de funcion (Cine,Teatro,Musical):\n";
        $tipo = strtolower(trim(fgets(STDIN)));
    } while($tipo != "cine" && $tipo != "musical" && $tipo != "teatro");
    do {
        echo "Ingrese el precio de la funcion:\n";
        $precio = trim(fgets(STDIN));
    }   while (!is_numeric($precio));

    echo "Ingrese el horario de la funcion en formato 24hrs (HH:MM):\n";
    $horaInicio = trim(fgets(STDIN));

    do {
        echo "Ingrese la duracion de la funcion en minutos:\n";
        $duracion = trim(fgets(STDIN));
    } while (!is_numeric($duracion));

    switch ($tipo) {
        case "teatro":
            $exito = ABM_FuncionTeatro::altaFuncionTeatro(0, $nombreFuncion, $horaInicio, $duracion, $precio, $idteatro);
            break;
        case "cine":
            echo "Ingrese el pais de origen:\n";
            $pais = trim(fgets(STDIN));
            echo "Ingrese el genero:\n";
            $genero = trim(fgets(STDIN));
            $exito = ABM_FuncionCine::altaFuncionCine(0, $nombreFuncion, $horaInicio, $duracion, $precio, $idteatro, $genero, $pais);
            break;
        case "musical":
            echo "Ingrese el director:\n";
            $director = trim(fgets(STDIN));
            echo "Ingrese la cantidad de espectadores:\n";
            $espectadores = trim(fgets(STDIN));
            $exito = ABM_FuncionMusical::altaFuncionMusical(0, $nombreFuncion, $horaInicio, $duracion, $precio, $idteatro, $director, $espectadores);
            break;
    }
    if (!$exito) {
        echo "No es posible agregar la funcion, el horario de la funcion ingresada se solapa con otra funcion\n";
    }
}

function modificarFuncion($unTeatro)
{

    $unaFuncion = new Funcion();


    echo "Funciones disponibles en este teatro :\n";
    $cadenaFunciones = $unTeatro->recorrerFunciones();
    do {
        echo $cadenaFunciones;
        echo "Ingrese el id de la función a modificar: \n";
        $idfuncion = trim(fgets(STDIN));
        $exito= $unaFuncion->buscar($idfuncion);
    } while (!is_numeric($idfuncion) || !$exito );

    echo "Ingrese el nuevo nombre de la función: \n";
    $nombreFuncion = trim(fgets(STDIN));
    do {

        echo "Ingrese el nuevo precio de la función: \n";
        $precioFuncion = trim(fgets(STDIN));
    } while (!is_numeric($precioFuncion));

    echo "Ingrese la hora de la funcion en formato 24hrs (HH:MM):\n";
    $horaInicio = trim(fgets(STDIN));

    do {
        echo "Ingrese la duracion de la funcion en minutos:\n";
        $duracion = trim(fgets(STDIN));
    } while (!is_numeric($duracion));

    //$unaFuncion = $unTeatro->buscar($idfuncion);

    $exito = false;
    switch (get_class($unaFuncion)) {
        case "FuncionCine":
            echo "Ingrese el pais de origen de la funcion de cine:\n";
            $pais = trim(fgets(STDIN));
            echo "Ingrese el genero de la funcion de cine:\n";
            $genero = trim(fgets(STDIN));
            $exito = ABM_FuncionCine::modificarFuncionCine($idfuncion, $nombreFuncion, $horaInicio, $duracion, $precioFuncion, $unTeatro->getIdTeatro(), $pais, $genero);
            break;
        case "FuncionMusical":
            echo "Ingrese el director:\n";
            $director = trim(fgets(STDIN));
            echo "Ingrese la cantidad de espectadores:\n";
            $reparto = trim(fgets(STDIN));
            $exito = ABM_FuncionMusical::modificarFuncionMusical($idfuncion, $nombreFuncion, $horaInicio, $duracion, $precioFuncion, $unTeatro->getIdTeatro(), $director, $reparto);
            break;
        case "FuncionTeatro":
            $exito = ABM_FuncionTeatro::modificarFuncionTeatro($idfuncion, $nombreFuncion, $horaInicio, $duracion, $precioFuncion, $unTeatro->getIdTeatro());
    }
    if (!$exito) {
        echo "No se modificó la función porque el horario se solapa con el de otra funcion existente\n";
    }
}

function bajaFuncion($unTeatro)
{
    $unaFuncion = new Funcion();
    echo "Funciones disponibles en este teatro :\n";
    $cadenaFunciones = $unTeatro->recorrerFunciones();

    do{
        echo $cadenaFunciones;
    echo "Seleccione el id de la función a eliminar:\n";
    $idfuncion = trim(fgets(STDIN));
        $exito= $unaFuncion->buscar($idfuncion);
    } while (!is_numeric($idfuncion) || !$exito );

       // $unaFuncion = $unTeatro->buscarFuncion($idfuncion);
        switch (get_class($unaFuncion)) {
            case "FuncionCine":
                ABM_FuncionCine::bajaFuncionCine($idfuncion);
                break;
            case "FuncionMusical":
                ABM_FuncionMusical::bajaFuncionMusical($idfuncion);
                break;
            case "FuncionTeatro":
                ABM_FuncionTeatro::bajaFuncionTeatro($idfuncion);
        }


}


function altaTeatro()
{

    echo "Ingrese el nombre del teatro:\n";
    $nombre = trim(fgets(STDIN));
    echo "Ingrese la dirección del teatro:\n";
    $direccion = trim(fgets(STDIN));
    $exito = ABM_Teatro::altaTeatro($nombre, $direccion);
    if($exito)
    {
        echo "***Teatro agregado con exito a la base de datos***\n";
    } else {
        echo "***Fallo la carga del teatro a la base de datos***\n";
    }

}

function bajaTeatro()
{


    do {
        $exito = false;
        echo "Seleccione el id del teatro que desea eliminar o 0(cero) para volver al menu:\n";
        $idteatro = trim(fgets(STDIN));
        $exito = ABM_Teatro::eliminarTeatro($idteatro);

        if($exito){
                  echo "Se ha eliminado con exito el teatro de la BD\n";
                 }
    } while ($idteatro <> 0);

}



function main()
{
    menu();
}


main();