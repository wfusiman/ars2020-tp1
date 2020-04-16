<?php

// Cifra / Descifra un mensaje con el metodo Caesar.
function caesar( $mensaje, $clave, $alfabeto, $cifrar ) {
    $array_msj = str_split( $mensaje ); // separa el mensaje en caracteres
    $array_alfabeto = str_split( $alfabeto ); // separa el alfabeto en caracteres

    $array_result = array(); // inicia el array resultado.
    foreach( $array_msj as $ch) { // para cada caracter del mensaje
        $index = array_search( $ch, $array_alfabeto ); // busca el caracter en el alfabeto
        if ($index === FALSE) // si no se encuentra el caracter, se copia el mismo valor
          $ch_cifrad = $ch;
        else { // si se encuentra el caracter en el alfabeto
          if ($cifrar)  // Cifrar
            $new_index = modulo( $index + (int)$clave, strlen( $alfabeto ) );
          else  // Descifrar
            $new_index = modulo( $index - (int)$clave, strlen( $alfabeto ) );

          $ch_cifrad = $array_alfabeto[$new_index]; // se busca el caracter que corresponde al nuevo indice
        }
        
        array_push( $array_result, $ch_cifrad ); // se coloca el caracter en el array resultado.
  }
  return implode( $array_result ); // retorna la cadena resultado.
}

// Cifra / Descifra un mensaje con el metodo Vigenere.
function vigenere( $mensaje, $clave, $alfabeto, $cifrar ) {
    $array_msj = str_split( $mensaje );
    $array_alfabeto = str_split( $alfabeto );
    $array_clave = str_split( $clave );

    $array_result = array();
    $i = 0;
    foreach( $array_msj as $ch) {
        $index = array_search( $ch, $array_alfabeto );                          
        if ($index === FALSE) // el caracter no esta en el alfabeto, se copia el mismo valor
          $ch_cifrad = $ch;
        else {
          $index_clave = array_search( $array_clave[$i], $array_alfabeto ); // buscar el indice del caracter clave en el alfabeto
          if ($cifrar)  // Cifrar
            $new_index = modulo( $index + $index_clave, strlen( $alfabeto ) );
          else  // Descifrar
            $new_index = modulo( $index - $index_clave, strlen( $alfabeto ) );

          $ch_cifrad = $array_alfabeto[$new_index]; // se busca el caracter cifrado/descifrado
        }
        array_push( $array_result, $ch_cifrad );
        $i = ($i + 1) % strlen( $clave );
    }
    return implode( $array_result );
}

// Calcula el modulo de dividir dividendo por divisor
function modulo($dividend, $divisor) {
  $result = $dividend % $divisor;
  return $result < 0 ? $result + $divisor : $result;
}


// Retorna una cadena alfanumerica aletaria de 10 caracteres que se utiliza como salt.
function salt_random() {
  $valores = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
  $longitud = 10;
  $cadena = "";
  for ($i=0; $i < $longitud; $i++){
    $cadena .= $valores[mt_rand(0, strlen( $valores )-1 )];
  }
  return $cadena;
}

?>
