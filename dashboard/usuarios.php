<?php

include_once('funcs.php');
include 'header.html';
?>

<div class="testbox">
    <?php
        $servidor = "localhost";
        $basedatos = "arsdb";
        $usuariodb = "usuario_ars";
        $contrasena = "12345678";
        
        if($_GET) { 
            if (isset($_GET["usuarios"])) { // Listar usuarios
                echo '<form>';
                echo    '<div class="banner">
                            <h1>Usuarios</h1>
                        </div>';
                echo '<br>';
                $conn = mysqli_connect( $servidor, $usuariodb, $contrasena, $basedatos );
                if (!$conn) 
                    error_log( print_r( "Fallo coneccion: " . mysqli_connect_error() ));
                
                echo '<div>
                        <button id="btnNuevo" type="button" onclick="location.href=\'./usuarios.php?nuevo\'">Registrar</button>
                        <button id="btnAuth" type="button" onclick="location.href=\'./usuarios.php?aut\'">Autenticar</button>
                      </div>';
                $list_usuarios = $conn->query( "SELECT * FROM USUARIOS" );
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
                echo '</form>';
                mysqli_close( $conn );
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
                        <button id="btnSalir" type="button" onclick="location.href=\'./usuarios.php?usuarios\'">Salir</button>
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
                        <button id="btnSalir" type="button" onclick="location.href=\'./usuarios.php?usuarios\'">Salir</button>
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

                $conn = mysqli_connect( $servidor, $usuariodb, $contrasena, $basedatos );
                if (!$conn) 
                    error_log( print_r( "Fallo coneccion: " . mysqli_connect_error() ));

                $error = FALSE;
                if (!$usuario || $usuario == "") {
                    $error = TRUE;
                    echo '<div class="item" style="font-size: 18px"> <span>Debe ingresar usuario</span></div>';
                }
                else {
                    $result = $conn->query( "SELECT usuario FROM USUARIOS WHERE usuario = '". $usuario . "'" );
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
                    $salt = salt_random(); // Obtener la salt aleatoria.
                    $hash_pwd = password_hash( $pwd.$salt, PASSWORD_DEFAULT ); // hash password + salt.
                    
                    // insertar registro de nuevo usuario, pwd = hash_pwd + salt.
                    $sql = "INSERT INTO usuarios (usuario,pwd,nombre,apellido) VALUES ('" .$usuario. "','" .$hash_pwd.$salt. "','" .$nombre. "','" .$apellido. "')";
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
                        <button id="btnVolver" type="button" onclick="location.href=\'./usuarios.php?nuevo\'">Volver</button>
                        <button id="btnSalir" type="button" onclick="location.href=\'./usuarios.php?usuarios\'">Salir</button>
                    </div>'; 
                echo '</form>';
                mysqli_close( $conn );
            }
            elseif(isset($_POST["aut"])) {
                $usuario_in = $_POST["usuario"];
                $passwd_in = $_POST["pwd"];

                echo ' <form>';
                echo '<div class="banner">
                        <h1>Autenticar usuario</h1>
                    </div>';

                $conn = mysqli_connect( $servidor, $usuariodb, $contrasena, $basedatos );
                if (!$conn) 
                    error_log( print_r( "Fallo coneccion: " . mysqli_connect_error() ));

                $error = FALSE;
                if (!$usuario_in || $usuario_in == "") {
                    $error = TRUE;
                    echo '<div class="item" style="font-size: 18px"> <span>Error: Debe ingresar usuario</span></div>';
                }
                if (!$passwd_in || $passwd_in == "") {
                    $error = TRUE;
                    echo '<div class="item" style="font-size: 18px"> <span>Error: Debe ingresar una contraseña</span></div>';
                }
                if(!$error) { 
                    // obtener el registro del usuario 
                    $usr = $conn->query( "SELECT usuario,pwd,nombre,apellido FROM usuarios WHERE usuario = '" . $usuario_in . "'");
                    //error_log( print_r( 'usuario recuperado: ' . $usr ));
                    if ($usr === FALSE) {
                        echo '<div class="item" style="font-size: 18px"> <span>Error: ' . $conn->error . ' </span></div>';
                    }
                    elseif ($usr->num_rows == 0) {
                        echo '<div class="item" style="font-size: 18px"> <span>Error: No se encontro usuario ' . $usuario_in . ' </span></div>';
                    }
                    else {
                        // obtener el valor de la columna pwd.
                        $row = mysqli_fetch_row( $usr );
                        
                        // separo la salt del hash desde el campo pwd del registro
                        $pwd_db = substr( $row[1], 0,60 ); 
                        $salt = substr( $row[1], -10 );

                        if (password_verify( $passwd_in.$salt, $pwd_db)) { // autenticado exitosamente
                            echo '<div class="item" style="font-size: 18px"> <span>Usuario: ' . $row[0]. ' autenticado con exito</span></div>';
                            echo '<div class="item" style="font-size: 18px"> <span>Nombre: ' . $row[2]. ' </span></div>';
                            echo '<div class="item" style="font-size: 18px"> <span>Apellido: ' . $row[3]. ' </span></div>';
                        }
                        else {  // no autenticado
                            echo '<div class="item" style="font-size: 18px"> <span>Fallo autenticacion</span></div>';
                        }
                    }
                }
                echo '<div>
                        <button id="btnVolver" type="button" onclick="location.href=\'./usuarios.php?aut\'">Volver</button>
                        <button id="btnSalir" type="button" onclick="location.href=\'./usuarios.php?usuarios\'">Salir</button>
                    </div>'; 
                echo '</form>';
                mysqli_close( $conn );
            }
        }

    ?>

</div>

</body>

<?php

include 'footer.html';

?>
