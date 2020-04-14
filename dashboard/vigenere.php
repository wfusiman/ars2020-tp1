<?php 

include_once( 'funcs.php' );

include './header.html'; 

?>

<div class="testbox">
  <?PHP
      // form handler
      $alfabeto = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

      if($_POST && isset($_POST['clave'], $_POST['mensaje'], $_POST['oper'])) {
          $clave = $_POST['clave'];
          $mensaje = $_POST['mensaje'];
          $oper = $_POST['oper'];

          echo '
              <form>
                <div class="banner">
                    <h1>Vigenere resultado</h1>
                </div>';
          if(!$mensaje) {
              $errorMsg = "Error: No se ingreso mensaje";
              echo '<div class="item" style="font-size: 18px"> <span>' . $errorMsg . '</span></div>';
          }
          if(!$clave) {
              $errorMsg = "Error: No se ingreso clave";
              echo '<div class="item" style="font-size: 18px"> <span>' . $errorMsg . '</span></div>';
          } elseif (!validar_clave( $clave, $alfabeto )) {
              $errorMsg = "Error: clave con valor no valido";
              echo '<div class="item" style="font-size: 18px"> <span>' . $errorMsg . '</span></div>';
          } elseif ($mensaje) {
              $result = vigenere( $mensaje, $clave, $alfabeto, ($oper == 'C') );
              echo '<div class="item">';
              echo '<p>Mensaje original:</p>';
              echo '<div class="name-item" style="font-size: 18px">';
              echo '<span>' . $mensaje . '</span>';
              echo '</div></div>';
      
              echo '<div class="item">';
              echo '<p>Mensaje '. ( ($oper == "C") ? 'cifrado':'descifrado') . '</p>';
              echo '<div class="name-item" style="font-size: 18px">';
              echo '<span>' . $result . '</span>';
              echo '</div></div>';
          }
              echo '
                    <div>
                      <button id="btnVolver">Volver</button>
                    </div>';
              echo '</form>';
      }
      else {
          echo '<form action="vigenere.php" method="POST">';
          echo '
                <div class="banner">
                  <h1>Metodo Vigenere</h1>
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
                      <input type="text" name="clave" placeholder="ingrese clave alfabetica" />
                    </div>
                </div>';
          echo '
                <div class="item">
                    <p>Mensaje</p>
                  <div class="name-item">
                    <input type="text" name="mensaje" placeholder="ingrese mensaje" />
                  </div>
                </div>';
          echo '
                <div>
                  <button id="buttonOper">Cifrar</button>
                </div> ';
          echo '</form>';
      }

      function validar_clave( $clave,$alfabeto ) {
          $array_clave = str_split( $clave );
          foreach ($array_clave as $ch) {
              $index = strpos( $alfabeto, $ch );
                  if ($index === FALSE)
                      return FALSE;
          }
          return TRUE;
      }
  ?>

</div>
</body>

<?php include './footer.html'; ?>

<script type="text/javascript"> 
    function showButton( obj ) {
        var selectIndex = obj.selectedIndex;
        var selectValue = obj.options[selectIndex].text;
        console.log('showButon: ' + selectValue );
        if (selectValue == "Cifrar") {
            document.getElementById('buttonOper').innerHTML  = "Cifrar";
        }
        else if (selectValue == "Descifrar") {
            document.getElementById('buttonOper').innerHTML  = "Descifrar";
        }            
    }
</script>



 