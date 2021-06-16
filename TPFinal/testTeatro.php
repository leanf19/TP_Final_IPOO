<?php

//Leandro Fuentes FAI-465
include "Teatro.php";



/******************************************/
/************** PROGRAMA PRINCIPAL *********/
/******************************************/

//Crea una instancia de la clase teatro con un nombre y direccion genericos
//$cantFunciones = 0;
//$unTeatro = new Teatro(01,"Lido", "Corrientes 1885";

// creo un obj Persona
$obj_Teatro =  new Teatro();

//Busco todas las personas almacenadas en la BD
$colTeatro =$obj_Teatro->listar();
foreach ($colTeatro as $unTeatro){

    echo $unTeatro;
    echo "-------------------------------------------------------";
}

$obj_Teatro->cargar(3, "Teatrin", "Suarez 1234");
$respuesta=$obj_Teatro->insertar();
// Inserto el obj Teatro en la base de datos
if ($respuesta==true) {
    echo "\nOP INSERCION;  La persona fue ingresada en la BD \n";
    $colTeatro =$obj_Teatro->listar("");
    foreach ($colTeatro as $unTeatro){

        echo $unTeatro;
        echo "-------------------------------------------------------";
    }
}else
    echo $obj_Teatro->getmensajeoperacion();



do {
    //Se invoca a la funcion seleccionarOpcion
    //Esta funcion despliega un menu y pide al usuario ingresar una opcion
    $opcion = seleccionarOpcion();

    //Se ejecuta el numero de opcion elegido por el usuario
    switch ($opcion)
    {
        case 1: //Mostrar la informacion del teatro
            echo "\n Teatro: " . $unTeatro->getNombre() . " ";
            echo "\n Direccion: " . $unTeatro->getDireccion() . "\n ";
            break;

        case 2: //Cambiar nombre del teatro
            echo "\n Ingrese el nuevo nombre del Teatro: ";
            $nom = trim(fgets(STDIN));
            $unTeatro->setNombre($nom);
            break;

        case 3: //Cambiar direccion del teatro
            echo "\n Ingrese la nueva direccion ";
            $dire = trim(fgets(STDIN));
            $unTeatro->setDireccion($dire);
            break;

        case 4: //Cambiar la cantidad de funciones diarias
            echo "\n Ingrese la nueva cantidad de funciones diarias que admite el teatro ";
            $nroFunciones = trim(fgets(STDIN));
            $unTeatro->setCantFunciones($nroFunciones);
            break;

        case 5: //Ver funciones disponibles
            echo "\n ¡Estas son las funciones disponibles! \n";
            echo $unTeatro->__toString();
            break;

        case 6: //Agregar nueva funcion
            do {
                echo "\n Ingrese el tipo de funcion (Cine,Teatro,Musical): ";
                $tipo = strtolower(trim(fgets(STDIN)));
            } while($tipo != "cine" && $tipo != "musical" && $tipo != "teatro");
            echo "\n Ingrese el nombre de la nueva funcion: ";
            $nomFuncion = trim(fgets(STDIN));
            echo "\n Ingrese la hora de la funcion en formato 24hrs (hh:mm): ";
            $horaIni = trim(fgets(STDIN));
            echo "\n Ingrese la duracion de la funcion en minutos: ";
            $tiempo = trim(fgets(STDIN));
            echo "\n Ingrese el precio de la entrada: ";
            $precio = trim(fgets(STDIN));

            $exito = false;

            //segun el tipo de actividad es el case al que se accede para proceder a agregar la Funcion
            switch ($tipo)
            {
                case "teatro":
                        $unaFuncion = new FuncionTeatro($nomFuncion, $horaIni, $tiempo, $precio);
                        $exito=$unTeatro->agregarFunciones($cantFunciones, $unaFuncion);
                    break;
                case "cine":
                         echo "\n Ingrese el genero de la pelicula: ";
                        $igenero = trim(fgets(STDIN));
                        echo "\n Ingrese el pais de la pelicula: ";
                        $ipais = trim(fgets(STDIN));
                        $unaFuncion = new FuncionCine($nomFuncion, $horaIni, $tiempo, $precio, $igenero, $ipais);
                        $exito=$unTeatro->agregarFunciones($cantFunciones, $unaFuncion);
                    break;
                    case "musical":
                        echo "\n Ingrese el nombre del director: ";
                        $idirector = trim(fgets(STDIN));
                        echo "\n Ingrese la cantidad de personas en escena: ";
                        $icantPersonas = trim(fgets(STDIN));
                        $unaFuncion = new FuncionMusical($nomFuncion, $horaIni, $tiempo, $precio, $idirector, $icantPersonas);
                        $exito=$unTeatro->agregarFunciones($cantFunciones, $unaFuncion);
                        break;
            }
            if (!$exito) {
                echo "\n****El horario de la funcion ingresada se solapa, no fue posible asignarla a la lista****\n";
            }
            else
                {
                    //Se agrega la funcion con exito
                    $cantFunciones++;
        }
        break;

        case 7: //Modificar Funcion
            if (count($unTeatro->getFunciones()) > 0)
            {
                $bucle = true;
                echo "\n Estas son las funciones disponibles, seleccione una a modificar[0-" . ($cantFunciones - 1) . "]\n";

                echo $unTeatro->__toString();

                $nroFuncion = trim(fgets(STDIN));
                if ($nroFuncion <= $cantFunciones)
                {
                    do
                    {
                        $seleccion = menuFuncion();
                        switch ($seleccion)
                        {

                            case 1:
                                echo "\n Ingrese el nuevo nombre de la funcion: ";
                                $nomFuncion = trim(fgets(STDIN));
                                $unTeatro->modificarFunciones($seleccion, $nroFuncion, $nomFuncion);
                                break;

                            case 2:
                                echo "\n Ingrese la hora de la funcion en formato 24hrs (hh:mm) : ";
                                $horaIni = trim(fgets(STDIN));
                                $unTeatro->modificarFunciones($seleccion, $nroFuncion, $horaIni);
                                break;

                            case 3:
                                echo "\n Ingrese la duracion de la funcion en minutos: ";
                                $tiempo = trim(fgets(STDIN));
                                $unTeatro->modificarFunciones($seleccion, $nroFuncion, $tiempo);
                                break;

                            case 4:
                                echo "\n Ingrese el precio de la entrada: ";
                                $precio = trim(fgets(STDIN));
                                $unTeatro->modificarFunciones($seleccion, $nroFuncion, $precio);
                                break;
                        }

                    } while ($seleccion != 5);

                } else
                    {
                    //Solo deja que el usuario acceda a las funciones preexistentes sin posibilidad de modificar un indice vacio
                    echo "\n Error, ingrese una de las funciones disponibles:[0-" . ($cantFunciones - 1) . "] \n";
                    }


            } else

                {
                    echo "\n Aun no hay funciones programadas para modificar \n";
                }
            break;
        case 8:
            echo "\nCosto total: {$unTeatro->darCosto()}\n";
        break;
    }
} while ($opcion != 9);

function seleccionarOpcion()
{
    do {
        echo "--------------------------------------------------------------\n";
        echo "\n ( 1 ) Mostrar la informacion del teatro";
        echo "\n ( 2 ) Cambiar nombre al teatro";
        echo "\n ( 3 ) Cambiar direccion del teatro";
        echo "\n ( 4 ) Cambiar la cantidad de funciones diarias del teatro";
        echo "\n ( 5 ) Ver funciones disponibles";
        echo "\n ( 6 ) Agregar nueva funcion";
        echo "\n ( 7 ) Modificar funcion";
        echo "\n ( 8 ) Calcular costo";

        echo "\n ( 9 ) Salir";
        echo "\n        ";
        $opcion = trim(fgets(STDIN));

        /*>>> Además controlar que la opción elegida es válida. Puede que el usuario se equivoque al elegir una opción <<<*/
        if ($opcion < 1 || $opcion > 9) {
            echo "\n---------- Indique una opcion valida ----------\n";
        }
    } //Si la opcion es invalida muestra una advertencia y vuelve a mostrar el menu
    while (!($opcion >= 1 && $opcion <= 9));

    echo "--------------------------------------------------------------\n";
    return $opcion;


}

function menuFuncion()
{
    do {
        echo "--------------------------------------------------------------\n";
        echo "\n Elija la opcion que desee modificar";
        echo "\n ( 1 ) Cambiar nombre de la funcion";
        echo "\n ( 2 ) Cambiar hora de inicio de la funcion";
        echo "\n ( 3 ) Cambiar duracion de la funcion";
        echo "\n ( 4 ) Cambiar precio de la funcion";
        echo "\n ( 5 ) Volver al menu principal";
        echo "\n        ";
        $opcion = trim(fgets(STDIN));

        /*>>> Además controlar que la opción elegida es válida. Puede que el usuario se equivoque al elegir una opción <<<*/
        if ($opcion < 1 || $opcion > 5) {
            echo "\n---------- Indique una opcion valida ----------\n";
        }
    } //Si la opcion es invalida muestra una advertencia y vuelve a mostrar el menu
    while (!($opcion >= 1 && $opcion <= 5));

    echo "--------------------------------------------------------------\n";
    return $opcion;

}