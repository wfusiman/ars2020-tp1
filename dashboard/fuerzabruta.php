<?php 
include_once( './funcs.php' );

include './header.html'; 
?>

<div class="testbox">

    <?php 
        if($_POST && isset($_POST['mensaje'])) {
            echo '<form>';
            echo '<div class="banner">
                    <h1>Caesar resultado</h1>
                  </div>';
            $mensaje = $_POST['mensaje'];
            //error_log( print_r( 'mensaje: '.$mensaje ));
            echo '<br>';
            if(!$mensaje) {
                $errorMsg = "Error: No se ingreso mensaje";
                echo '<div class="item" style="font-size: 18px"> <span>' . $errorMsg . '</span></div>';  
            }
            else {
                $alfabeto = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
                $num_test = strlen( $alfabeto );
                $mensaje_descifrado = "";
                $clave_sugerida = 0;
                $max_count = 0;
                for ($i = 1; $i <= $num_test; $i++) {
                    $msjdesc = caesar( $mensaje, $i, $alfabeto,FALSE );
                    $array_palabras = explode( " ",$msjdesc );
                    $count = 0;
                    foreach($array_palabras as $palabra) {
                        $fp = fopen( './dic/'.substr( $palabra, 0,1 ).'.txt', 'r' );
                        while(!feof($fp)) {
                            $linea = fgets($fp);
                            $index = contiene_palabra( $linea, $palabra );
                            if ($index == 1) {
                                $count = $count + 1; 
                                //error_log( print_r( 'encontro palabra: '.$palabra.' en linea: '.$linea .' indice: '. $i .', contador: '. $count .'<br>'));
                                break;
                            }
                        }
                        fclose( $fp );
                    }
                    if ($count > $max_count) {
                        $max_count = $count;
                        $clave_sugerida = $i;
                        $mensaje_descifrado = $msjdesc;
                    }
                }
                echo '<div class="item">';
                echo '<p>Mensaje original cifrado:</p>';
                echo '<div class="name-item" style="font-size: 18px">';
                echo '<span>' . $mensaje . '</span>';
                echo '</div></div>';

                echo '<div class="item">';
                echo '<p>clave sugerida:</p>';
                echo '<div class="name-item" style="font-size: 18px">';
                echo '<span>' . $clave_sugerida . '</span>';
                echo '</div></div>';

                echo '<div class="item">';
                echo '<p>Mensaje descifrado:</p>';
                echo '<div class="name-item" style="font-size: 18px">';
                echo '<span>' . $mensaje_descifrado . '</span>';
                echo '</div></div>';
            }
            echo '
                <div>
                    <button id="btnVolver">Volver</button>
                </div>';
            echo '
                </form>';
        }
        else {
            echo '
            <form action="./fuerzabruta.php" method="POST">';
            echo '<div class="banner">
                    <h1>Buscar clave metodo Caesar</h1>
                </div>';
            echo '
                <div class="item">
                    <p>Mensaje</p>
                    <div class="name-item">
                        <input type="text" name="mensaje" placeholder="ingrese mensaje cifrado" />
                    </div>
                </div>
                <div>
                    <button id="btnOper">Buscar</button>
                </div>';
            echo '
            </form>';
        }

        function contiene_palabra($texto, $palabra){
            //return preg_match('*\b' . preg_quote($palabra) . '\b*i', $texto);
            $pals = multiexplode( array(",","-"," ","1","2","\n"), $texto );
            foreach( $pals as $pal) {
                if (strcmp( $pal, $palabra ) === 0)
                    return TRUE;
            }    
            return FALSE;
        }

        function multiexplode ($delimiters,$string) {
            $ready = str_replace($delimiters, $delimiters[0], $string);
            $launch = explode($delimiters[0], $ready);
            return  $launch;
        }
    ?>
</div>
</body>

<?php include './footer.html'; ?>