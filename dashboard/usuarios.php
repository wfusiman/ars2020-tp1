<?php

include 'header.html';
?>

<div class="testbox">
    <?php
        $servidor = "localhost";
        $basedatos = "arsdb";
        $usuariodb = "usuario_ars";
        $contrasena = "12345678";
        
        
        $conn = mysqli_connect( $servidor, $usuariodb, $contrasena, $basedatos );
        if($_GET) { 
            if (isset($_GET["usuarios"])) { // Listar usuarios
                echo '<form>';
                echo    '<div class="banner">
                            <h1>Usuarios</h1>
                        </div>';
                echo '<br>';

                if (!$conn) 
                    error_log( print_r( "Fallo coneccion: " . mysqli_connect_error() ));
                
                echo '<div>
                        <button id="btnNuevo" type="button" onclick="location.href=\'./usuarios.php?nuevo\'">Registrar</button>
                        <button id="btnAuth" type="button" onclick="location.href=\'./usuarios.php?aut\'">Autenticar</button>
                      </div>';
                $list_usuarios = mysqli_query( $conn,  "SELECT * FROM USUARIOS" );
                if ($list_usuarios === FALSE) {
                    error_log( print_r( "Error realizando la consulta: " . $conn->error ));
                } 

                echo '<table style="width: 100%; border: 2px solid #2a5d84;">';
                echo    '<tr style="text-align: left; background: #2a5d84;">';
                echo        '<th style="width: 10%; color:#fff;">Id</th>';
                echo        '<th style="width: 30%; color:#fff;">Usuario</th>';
                echo        '<th style="width: 30%; color:#fff;">Nombre</th>';
                echo        '<th style="width: 30%; color:#fff;">Apellido</th>';
                echo    '</tr>';
 	
                while ($columna = mysqli_fetch_array( $list_usuarios ))
                {
                echo "<tr>";
                echo    '<td style="width: 10%; text-align: left; padding: 0.5em;">' . $columna['id'] . '</td>';
                echo    '<td style="width: 30%; text-align: left; padding: 0.5em;">' . $columna['usuario'] . '</td>';
                echo    '<td style="width: 30%; text-align: left; padding: 0.5em;">' . $columna['nombre'] . '</td>';
                echo    '<td style="width: 30%; text-align: left; padding: 0.5em;">' . $columna['apellido'] . '</td>';
                echo "</tr>";
                }

                echo '</table>';
                mysqli_close( $conn );

                echo '</form>';
            }
            elseif (isset($_GET["nuevo"])){  // Registrar nuevo usuario
                echo ' <form action="./usuarios.php" method="POST">';
                echo '<div class="banner">
                        <h1>Nuevo usuario</h1>
                      </div>';
                echo '<div class="item">
                        <p>Usuario</p>
                        <div class="name-item">
                            <input type="text" name="usuario" placeholder="ingrese usuario" style="width: 200px;"/>
                        </div>
                      </div>';
                echo '<div class="item">
                        <p>Contraseña</p>
                        <div class="name-item">
                            <input type="password" name="pwd" placeholder="ingrese contraseña" />
                        </div>
                      </div>';
                echo '<div class="item">
                        <p>Reingrese contraseña</p>
                        <div class="name-item">
                            <input type="password" name="pwd2" placeholder="reingrese la contraseña" />
                        </div>
                      </div>';
                echo '<div class="item">
                        <p>Nombre</p>
                        <div class="name-item">
                            <input type="text" name="nombre" placeholder="ingrese nombre del usuario" />
                        </div>
                      </div>';
                echo '<div class="item">
                        <p>Contraseña</p>
                        <div class="name-item">
                            <input type="text" name="apellido" placeholder="ingrese contraseña" />
                        </div>
                      </div>';
                echo '<div>
                        <button id="btnGuardar" name="reg">Guardar</button>
                     </div>';
            
                echo '</form>';
            }
            elseif (isset($_GET["aut"])) {  // Autenticar usuario
                echo ' <form action="./usuarios.php" method="POST">';
                echo '<div class="banner">
                        <h1>Autenticar usuario</h1>
                      </div>';
                echo '<div class="item">
                        <p>Usuario</p>
                        <div class="name-item">
                            <input type="text" name="usuario" placeholder="ingrese usuario" style="width: 200px;"/>
                        </div>
                      </div>';
                echo '<div class="item">
                        <p>Contraseña</p>
                        <div class="name-item">
                            <input type="password" name="pwd" placeholder="ingrese contraseña" />
                        </div>
                      </div>';
                echo '<div>
                        <button id="btnAut" name="aut">Autenticar</button>
                      </div>';          
                echo '</form>';
            }
        }
        elseif($_POST) {    // Crear nuevo usuario
            if (isset($_POST["reg"])) {
                $usuario = $_POST["usuario"];
                $pwd = $_POST["pwd"];
                $pwd2 = $_POST["pwd2"];
                $nombre = $_POST["nombre"];
                $apellido = $_POST["apellido"];

                echo ' <form>';
                echo '<div class="banner">
                        <h1>Registro nuevo usuario</h1>
                    </div>';

                //$conn = mysqli_connect( $servidor, $usuariodb, $contrasena, $basedatos );
                if (!$conn) 
                    error_log( print_r( "Fallo coneccion: " . mysqli_connect_error() ));

                $error = FALSE;
                if (!$usuario || $usuario == "") {
                    $error = TRUE;
                    echo '<div class="item" style="font-size: 18px"> <span>Debe ingresar usuario</span></div>';
                }
                else {
                    $result = mysqli_query( $conn, "SELECT usuario FROM USUARIOS WHERE usuario = '". $usuario . "'" );
                    if ($result->num_rows > 0) {
                        $error = TRUE;
                        echo '<div class="item" style="font-size: 18px"> <span>El nombre de usuario ya esta siendo utilizado, ingrese otro distinto</span></div>';
                    }
                }
                if (!$pwd || $pwd == "") {
                    $error = TRUE;
                    echo '<div class="item" style="font-size: 18px"> <span>Debe ingresar una contraseña</span></div>';
                }
                elseif (!$pwd2 || $pwd != $pwd2){
                    $error = TRUE;
                    echo '<div class="item" style="font-size: 18px"> <span>Las contraseña no coinciden</span></div>';
                }
                
                if (!$error) {
                    // guardar usuario
                    $hash_pwd = password_hash( $pwd, PASSWORD_DEFAULT );
                    $sql = "INSERT INTO usuarios (usuario,pwd,nombre,apellido) VALUES ('" 
                            .$usuario. "','" .$hash_pwd . "','" .$nombre. "','" .$apellido. "')";
                    if ($conn->query( $sql ) === TRUE) {    
                        echo '<div class="item" style="font-size: 18px"> 
                                <span>Usuario registrado exitosamente</span>
                            </div>';
                    }
                    else {
                        echo '<div class="item" style="font-size: 18px"> <span>ERROR: ' . $sql . '<br>' .$conn->error .' </span></div>';
                    }
                }
                echo '<div>
                        <button id="btnSalir" type="button" onclick="location.href=\'./usuarios.php?usuarios\'">Salir</button>
                        <button id="btnVolver" type="button" onclick="location.href=\'./usuarios.php?nuevo\'">Volver</button>
                    </div>'; 
                echo '</form>';
            }
            elseif(isset($_POST["aut"])) {
                $usuario = $_PUT["usuario"];
                $pwd = $_PUT["pws"];

                echo ' <form>';
                echo '<div class="banner">
                        <h1>Autenticar usuario</h1>
                    </div>';

                //$conn = mysqli_connect( $servidor, $usuariodb, $contrasena, $basedatos );
                if (!$conn) 
                    error_log( print_r( "Fallo coneccion: " . mysqli_connect_error() ));

                $error = FALSE;
                if (!$usuario || $usuario == "") {
                    $error = TRUE;
                    echo '<div class="item" style="font-size: 18px"> <span>Debe ingresar usuario</span></div>';
                }
                elseif (!$pwd || $pwd == "") {
                    $error = TRUE;
                    echo '<div class="item" style="font-size: 18px"> <span>Debe ingresar una contraseña</span></div>';
                }

                echo '<div>
                        <button id="btnSalir" type="button" onclick="location.href=\'./usuarios.php?usuarios\'">Salir</button>
                        <button id="btnVolver" type="button" onclick="location.href=\'./usuarios.php?aut\'">Volver</button>
                    </div>'; 
                echo '</form>';
            }
        }

    ?>

</div>

</body>

<?php

include 'footer.html';

?>

<script type="text/javascript">

        const className = {
            USUARIO_TEXT: 'usuario-text',
            USUARIO_OK: 'usuario-ok'
        }

        function mensajeAuth( usuario, auth, error ) {
            if (auth) {
                mensaje = 'Usuario ' + usuario + ' autenticado con exito';
            }
            else {
                mensaje = 'La autenticacion fallo, ' + error;
            }
            alert( mensaje );
        }

</script>