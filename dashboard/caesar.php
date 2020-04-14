
<?php 

  require_once( 'funcs.php');
  include './header.html'; 

?>
<div class="testbox">
    <?PHP
        // form handler
        $alfabeto = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

        if($_POST && isset($_POST['clave'], $_POST['mensaje'], $_POST['oper'])) {
            echo '<form>';
            echo '<div class="banner">
                      <h1>Caesar resultado</h1>
                  </div>';
            $clave = $_POST['clave'];
            $mensaje = $_POST['mensaje'];
            $oper = $_POST['oper'];

            if(!$mensaje) {
                $errorMsg = "Error: No se ingreso mensaje";
                echo '<div class="item" style="font-size: 18px"> <span>' . $errorMsg . '</span></div>';
            }
            if(!$clave) {
                $errorMsg = "Error: No se ingreso clave";
                echo '<div class="item" style="font-size: 18px"> <span>' . $errorMsg . '</span></div>';
            } elseif ($clave < 0 || $clave > strlen( $alfabeto )) {
                $errorMsg = "Error: clave con valor no valido, 0 <= clave <= " . strlen( $alfabeto );
                echo '<div class="item" style="font-size: 18px"> <span>' . $errorMsg . '</span></div>';
            } elseif ($mensaje) {
                $new_mensaje = caesar( $mensaje, $clave, $alfabeto, ($oper == 'C') );
                echo '<div class="item">';
                echo '<p>Mensaje original:</p>';
                echo '<div class="name-item" style="font-size: 18px">';
                echo '<span>' . $mensaje . '</span>';
                echo '</div></div>';
      
                echo '<div class="item">';
                echo '<p>Mensaje '. ( ($oper == "C") ? 'cifrado':'descifrado') . '</p>';
                echo '<div class="name-item" style="font-size: 18px">';
                echo '<span>' . $new_mensaje . '</span>';
                echo '</div></div>';
            }
                echo '<div>
                        <button id="btnVolver">Volver</button>
                      </div>';
                echo '</form>';
        }
        else {
            echo ' <form action="./caesar.php" method="POST">';
            echo '<div class="banner">
                    <h1>Metodo Caesar</h1>
                  </div>';
            echo '
                  <div class="item">
                    <p>Operacion</p>
                    <select id="oper" name="oper" onchange="showButton(this);" style="width: 200px;">
                        <option value="C">Cifrar</option>
                        <option value="D">Descifrar</option>
                    </select>    
                  </div>';
            echo '
                  <div class="item">
                    <p>Clave</p>
                    <div class="name-item">
                      <input type="text" name="clave" placeholder="ingrese clave numerica" style="width: 200px;"
                            oninput="this.value = this.value.replace(/[^0-9.]/g, \'\').replace(/(\..*)\./g, \'$1\');"/>
                    </div>
                  </div>';
            echo '
                  <div class="item">
                    <p>Mensaje</p>
                    <div class="name-item">
                      <input type="text" name="mensaje" placeholder="ingrese mensaje" />
                    </div>
                  </div>
                  <div>
                    <button id="btnOper">Cifrar</button>
                  </div>';
            echo '</form>';
        }
      ?>
          
</div>
</body>

<?php include './footer.html'; ?>

<script type="text/javascript"> 
    function showButton( obj ) {
        var selectIndex = obj.selectedIndex;
        var selectValue = obj.options[selectIndex].text;
        if (selectValue == "Cifrar") {
            document.getElementById('btnOper').innerHTML  = "Cifrar";
        }
        else if (selectValue == "Descifrar") {
            document.getElementById('btnOper').innerHTML  = "Descifrar";
        }            
    }
</script>

 