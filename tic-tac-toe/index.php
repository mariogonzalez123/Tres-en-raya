<?php



function comprobarFicha($ficha){
    switch($ficha){
        case "Y": 
        return 1;
        case "X": 
        return -1;
    }
}

function comprobar_resultado($tablero, $ficha) {
    global $ganador;
    for ($i = 0; $i < count($tablero); $i++) {
        if (!$ganador) {
            if ($tablero[$i][0] == $ficha && $tablero[$i][1] == $ficha && $tablero[$i][2] == $ficha) {
                $ganador = true;
                return comprobarFicha($ficha);
            }
        }
        if (!$ganador) {
            if ($tablero[0][$i] == $ficha && $tablero[1][$i] == $ficha && $tablero[2][$i] == $ficha) {
                $ganador = true;
                return comprobarFicha($ficha);
            }
        }
    }
    if (!$ganador) {
        if ($tablero[0][0] == $ficha && $tablero[1][1] == $ficha && $tablero[2][2] == $ficha) {
            $ganador = true;
            return comprobarFicha($ficha);
        }
    }
    if (!$ganador) {
        if ($tablero[0][2] == $ficha && $tablero[1][1] == $ficha && $tablero[2][0] == $ficha) {
            $ganador = true;
            return comprobarFicha($ficha);
        }
    }
}


function tableroLleno ($tablero) {
    $arrValores = array_merge(...$tablero);
    return !in_array("", $arrValores);
}
        
require "vendor/autoload.php";
use eftec\bladeone\bladeone;

$Views = __DIR__ . '\Views';
$Cache = __DIR__ . '\Cache';

$Blade = new BladeOne($Views, $Cache);

session_start();

define("BOARD_SIZE", 3);
define("PATH_PLAYER_PIC", "\public\assets\img\circle.jpg");
define("PATH_COMPUTER_PIC", "\public\assets\img\cross.jpeg");


if(empty($_POST)){
    $board = array();
    for($i = 0; $i < BOARD_SIZE; $i++){
        $board[$i] = array();
        for($j = 0; $j < BOARD_SIZE; $j++){
            $board[$i][$j] = "";
        }
    }
    $_SESSION['board'] = $board;
    echo $Blade->run('move');
    
}else{
    
    $board = $_SESSION['board'];
    $result = array();
    $XUsuario = filter_input(INPUT_POST, 'x');
    $YUsuario = filter_input(INPUT_POST, 'y');
    $board[$XUsuario][$YUsuario] = "Y";
    $encontrado = false;
    
    
    
    
    if(!tableroLleno($board)){
        while(!$encontrado){
            $aleat1 = rand(0 , count($board) -1);
            $aleat2 = rand(0 , count($board) -1);
            if(empty($board[$aleat1][$aleat2])){
                $board[$aleat1][$aleat2] = "X";
                $XCompu = $aleat1;
                $YCompu = $aleat2;
                $encontrado = true;
            }
            
        }
    }
     $_SESSION['board'] = $board;

    $ganador = false;
    $tresRayaY = comprobar_resultado($board, "Y");
    $tresRayaX = comprobar_resultado($board, "X");

     if(tableroLleno($board) && !$ganador){
        $result['gameRes'] = 0;
      
    }else{
          if ($ganador) {
        if ($tresRayaY != null) {
            $result['gameRes'] = $tresRayaY;
        } else if ($tresRayaX != null) {
            $result['gameRes'] = $tresRayaX;
        }
    }
  
    if (($ganador && $result["gameRes"] == -1) || !$ganador) {
        $result["x"] = $XCompu;
        $result["y"] = $YCompu;
    }

    }
  
  
    
    
    header('Content-type: application/json');
    echo json_encode($result);
    
}




?>