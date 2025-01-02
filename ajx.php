<?php
// Función para cargar archivos de una carpeta
function fnc1($vrx1, $vrx2, $vrx3, $vrx4) {
    // Número de imágenes por grupo
    //var1 carpeta global var2, usuario , varx3 clave, varx4 desde que orden de archivo mas grande.
    $var001 = 10;

    // Calcular el índice inicial según $vrx4
    $var002 = $vrx4 * $var001;

    // Comando para encontrar las imágenes y ordenar por tamaño
    $cmd = sprintf(
        "curl -u %s -s %s | find %s -type f \\( -iname '*.jpg' -o -iname '*.jpeg' -o -iname '*.png' -o -iname '*.gif' \\) -exec du -b {} + | sort -n -r | awk 'NR > %d && NR <= (%d + %d)'",
        escapeshellarg($vrx2 . ':' . $vrx3),
        escapeshellarg($vrx1),
        escapeshellarg($vrx1),
        $var002,
        $var002,
        $var001
    );

    // Ejecutar el comando
    $out = shell_exec($cmd);

    // Verificar si hay salida
    if (empty($out)) {
        return "No hay imágenes disponibles en este rango.";
    }

    // Procesar la salida y almacenar en una matriz
    $lns = explode("\n", trim($out));
    $mtx = []; // Matriz para almacenar información de las imágenes

    foreach ($lns as $lin) {
        if (preg_match('/(\d+)\s+(.*)/', $lin, $mat)) {
            $peso = round($mat[1] / 1024, 2); // Tamaño en KB
            $imagePath = trim($mat[2]);

            // Convertir la ruta del sistema de archivos a una URL relativa
            $pth = str_replace('/home/www', '', $imagePath);

            // Obtener las dimensiones de la imagen
            list($x, $y) = getimagesize($imagePath);

            // Almacenar en la matriz
            $mtx[] = [
                'nombre' => htmlspecialchars($pth),
                'peso' => $peso,
                'dimensiones' => "$x x $y"
            ];
        }
    }

    // Generar la salida HTML
    $result = "<table valign='top'>";
    $result .= "<tr><th rowspan='3'>Imagen</th><th>Tamaño (KB)</th></tr>";
    $result .= "<tr><th>AltxAnc</th></tr>";
    $result .= "<tr><th>Chkbox</th></tr>";

        // Verificar similitud con anterior
    $nmr = 0; //Numero de checkbox.
   for ($i = 0; $i < count($mtx); $i++) {
    // Generar la fila de la tabla HTML
    $result .= "<tr>";
    $result .= "<td rowspan='3'><a href=\"javascript:jva2('".$mtx[$i]['nombre']."')\">";
    $result .= "<img src='".$mtx[$i]['nombre']."' width='70'></a></td>";
    $result .= "<td>" . $mtx[$i]['peso'] . " KB</td></tr><tr>";
    $result .= "<td>" . $mtx[$i]['dimensiones'] . "</td></tr>"; // Agregar dimensiones

    // Inicializar el checkbox como un guion
    $checkbox = "<td>-</td>";

    // Verificar similitud con anterior
    if ($i > 0 &&
        $mtx[$i]['peso'] == $mtx[$i - 1]['peso'] &&
        $mtx[$i]['dimensiones'] == $mtx[$i - 1]['dimensiones']) {
        // Checkbox si es similar al anterior
        $checkbox = "<td><input type='checkbox' name='chk0' value='".$mtx[$i]['nombre']."' ></td>";
        $nmr++;
    } elseif ($i < count($mtx) - 1 &&
              $mtx[$i]['peso'] == $mtx[$i + 1]['peso'] &&
              $mtx[$i]['dimensiones'] == $mtx[$i + 1]['dimensiones']) {
        // Checkbox si es similar al siguiente
        $checkbox = "<td><input type='checkbox' name='chk0' value='".$mtx[$i]['nombre']."' ></td>";
        $nmr++;
    }

    // Añadir el checkbox a la fila
        $result .= $checkbox;
        $result .= "</tr>";
    }
    $pas= $vrx4+$var001;
    $result.= "</table>";
    if($nmr>0) {
        $result.= "<input type='button' value='X' onclick='javascript:jva4();'>";    //SELECCIONA TODOS LOS Checkbox
        $result.= "<input type='button' value='UNIR' onclick='javascript:jva3($pas);'>";    //Crea enlaces simbolicos de archivos iguales.
    }

    if($pas>30){
        for($i=-3;$i<6;$i++){
         $p1 = $pas+$i*10;
         $result.= "<input type='button' value='$p1' onclick='javascript:jva1($p1);'>";
        }

    } else
        $result.= "<input type='button' value='+' onclick='javascript:jva1($pas);'>";
    return $result;
}

// Función para visualiar la imagen.
function fnc2($vrx1, $vrx2, $vrx3, $vrx4) {
    // Ruta del archivo que se intenta cargar
    $vrx4 = basename($vrx4); // Encontrar la posición de la última barra
    $fle = $vrx1.'/'. $vrx4; // Construir la ruta completa del archivo
    // Verificar si el archivo existe
    if (file_exists($fle)) {
    // Convertir la ruta del sistema de archivos a una URL relativa
        $pth = str_replace('/home/www', '', $fle);
    } else {
        // Si el archivo no existe, buscar la imagen más grande en la carpeta
        $cmd = sprintf(
        "find %s -type f \\( -iname '*.jpg' -o -iname '*.jpeg' -o -iname '*.png' -o -iname '*.gif' \\) -exec du -b {} + | sort -n -r | head -n 1 | awk '{print $2}'",
        escapeshellarg($vrx1)
        );

        // Ejecutar el comando para obtener la imagen más grande
        $largestImagePath = shell_exec($cmd);

        // Verificar si se encontró una imagen
        if ($largestImagePath) {
            // Convertir la ruta del sistema de archivos a una URL relativa
            $pth = str_replace('/home/www', '', trim($largestImagePath));
        } else {
            $rtn000 = "No hay imágenes disponibles en esta carpeta.";
            echo $rtn000; // Mostrar mensaje si no hay imágenes
            return; // Terminar la ejecución si no hay imágenes
        }
    }

    // Asignar el HTML de la imagen una sola vez
    //$pth; es la imagen.
    $dim = getimagesize($fle);
    $x = floor(800*$dim[1]/$dim[0]);
    $dmn =$dim[0]."x".$dim[1]."->800x$x";
    //Vamos a ver densidad de imagen.
    $d1 =max($dim[0]/$dim['bits'],$dim[1]/$dim['bits']);
    if($d1>75){
        //Densidad mayor de 75 entonces
        $d1=$d1."<input type='number'  id='cmp' value='75' min = '75' max='$d1'></input>";
    }
$rtn000 = "<img src='".htmlspecialchars($pth)."?v".date("YmdHis")."'
id='img' alt='".htmlspecialchars($vrx4)."' width='80%' onClick='jva6(event)' >";

    $rtn001 = "<table>
<tr><td ROWSPAN=2><label><INPUT TYPE='RADIO' NAME='EDC' VALUE='1' checked>ROTAR</label></td>
    <TD><a href='javascript:jva10(1)'>90ºi</a></TD></tr>
<TR><TD><a href='javascript:jva10(2)'>90ºd</a></TD></TR>
<tr><td><label><INPUT TYPE='RADIO' NAME='EDC' VALUE='1A' onChange='jva10()' >Redimensionar</label></td>
<TD>$dmn</TD></tr>
<tr><td><label><INPUT TYPE='RADIO' NAME='EDC' VALUE='1B' onChange='jva10()' >Comprimir</label></td>
<TD>$d1</TD></tr>
<TR><TD><label><INPUT TYPE='RADIO' NAME='EDC' VALUE='2' onChange='jva5()'>GIRAR</label></TD>
<TD><input type='number' id='rtr' onchange='jva11();' value='0' min='-90' max='90' /></TD></TR>
<TR><TD ROWSPAN=4>
<label><INPUT TYPE='RADIO' NAME='EDC' VALUE='3' onChange='jva5()'>RECORTAR</label></TD>
<TD><input type='number' id='rct1' value='0' readonly /></TD></TR>
<TR><TD><input type='number' id='rct2' value='0' readonly /></TD></TR>
<TR><TD><input type='number' id='rct3' value='0' readonly /></TD></TR>
<TR><TD><input type='number' id='rct4' value='0' readonly /></TD></TR>
<TR><TD ROWSPAN=4><INPUT TYPE='RADIO' NAME='EDC' VALUE='4' onChange='jva5()'>PERSPECTIVA</label></TD>
    <TD><input type='text' id='prs1' value='0' readonly /></TD></TR>
<TR><TD><input type='text' id='prs2' value='0' readonly /></TD></TR>
<TR><TD><input type='text' id='prs3' value='0' readonly /></TD></TR>
<TR><TD><input type='text' id='prs4' value='0' readonly /></TD></TR>
    </table><a href='javascript:jva9();'>PROCESAR</a>";
    $rtn000 = "<TABLE>
<TR>
<TH>EDICION</TH>
<TH>IMAGEN</TH>
<TH>TEMPORALES</TH>
</TR>
<TR>
<TD>$rtn001</TD>
<TD><div id='div'>$rtn000<canvas id='cnv'></canvas></div></TD>
<TD></TD>
</TR>
<TR>
<TD></TD>
<TD></TD>
<TD></TD>
</TR>
    </TABLE>";
    return $rtn000;
}

function fnc3($vrx1, $vrx2, $vrx3, $vrx4) {
    // Separar las imágenes
    $img = explode('@', $vrx4);

    if (count($img) < 2) {
        return "Se necesitan al menos dos imágenes para crear enlaces simbólicos.";
    }

    $vrx4 = basename($img[0]); // La primera imagen será el origen
    $errores = [];

    // Iterar sobre las imágenes restantes
    for ($i = 1; $i < count($img); $i++) {
        $vrx5 = basename($img[$i]);

        // Crear el comando para cada par de imágenes
        $cmd = sprintf('rm -f %s%s && ln -s %s%s %s%s',
                       $vrx1, $vrx5,
                       $vrx1, $vrx4,
                       $vrx1, $vrx5);

        $output = shell_exec($cmd);

        if ($output !== null) {
            $errores[] = "Error al crear enlace simbólico para $vrx5: $output";
        }
    }

    if (empty($errores)) {
        return fnc1($vrx1, $vrx2, $vrx3, 10);
    } else {
        return "Hubo errores al crear los enlaces simbólicos: " . implode(", ", $errores);
    }
}

function fnc4($vrx1, $vrx2,$vrx3,$vrx4,$vrx5){
    // $vrx1: carpeta global, $vrx2: usuario, $vrx3: contraseña,
    // $vrx4: nombre de la imagen, $vrx5: arreglo de tamaño y coordenadas
    // Función para recortar una imagen.
    $msn = "NOMBRE ARCHIVO ORIGINAL ".$vrx4."<br>";
    // Dividir $vrx5 en un array usando 'R' como separador
    $var001 = explode("R", $vrx5);
    // Subdividir cada elemento usando 'x' como separador
    for($i = 0; $i < 3; $i++) {
        $var001[$i] = explode("x", $var001[$i]);
    }
    // var001[0]: tamaño del canvas
    // var001[1]: punto uno en canvas
    // var001[2]: punto dos en canvas

    // Construir la ruta completa de la imagen
    $var004 = $vrx1.strtok($vrx4, '?'); //El nombre viene con una huella de fecha, se quita para que funcione.

    // Verificar si la imagen existe
    if (!file_exists($var004)) {
        return "Error: La imagen no existe en la ruta especificada.".$msn;
    }

    // Cargar la imagen
    $var005 = imagecreatefromjpeg($var004);
    if (!$var005) {
        return "Error: No se pudo cargar la imagen.";
    }

    // Obtener dimensiones de la imagen original
    $var006 = imagesx($var005);
    $var007 = imagesy($var005);

    // Calcular la escala entre la imagen original y el canvas
    $var008 = $var006 / $var001[0][0];
    $var009 = $var007 / $var001[0][1];

    // Calcular las coordenadas de recorte en la imagen original
    $var010 = round($var001[1][0] * $var008);
    $var011 = round($var001[1][1] * $var009);
    $var012 = round($var001[2][0] * $var008);
    $var013 = round($var001[2][1] * $var009);

    // Calcular las dimensiones del recorte
    $var014 = $var012 - $var010;
    $var015 = $var013 - $var011;

    // Crear una nueva imagen para el recorte
    $var016 = imagecreatetruecolor($var014, $var015);

    // Copiar la parte seleccionada de la imagen original a la nueva imagen
    imagecopy($var016, $var005, 0, 0, $var010, $var011, $var014, $var015);

    // Guardar la imagen recortada sobrescribiendo la original
    if (!imagejpeg($var016, $var004)) {
        return "Error: No se pudo guardar la imagen recortada.";
    }

    // Liberar memoria
    imagedestroy($var005);
    imagedestroy($var016);

    //return "Imagen recortada y guardada como: " . $var004;
    $var004 = str_replace('/home/www', '', $var004);
    $msn.= "Imagen: $var004<br>";
    return $msn.fnc2($vrx1,$vrx2,$vrx3,$var004);

}

function fnc5($vrx1, $vrx2, $vrx3, $vrx4, $vrx5) {
    // $vrx1: carpeta global, $vrx2: usuario, $vrx3: contraseña,
    // $vrx4: nombre del archivo
    // $vrx5: matriz de parámetros para ejecutar lo que pide.
    $msn = ""; // Mensaje sin novedad.
    $var004 = $vrx1 . basename(strtok($vrx4, '?')); // El nombre viene con una huella de fecha, se quita para que funcione.
    $c = null;//Constante de compresion.
    // Cargar la imagen
    $img = imagecreatefromjpeg($var004);
    if (!$img) {
        $msn = "$var004 Error: No se pudo cargar la imagen (LN302).";
        return $msn;
    }
    $msn .= "LN 305 ".$vrx5."<BR>";
    switch ($vrx5) {
        case "i":
            // Girar la imagen 90º a la izquierda.
            $img = imagerotate($img, 90, 0);
            break;
        case "d":
            // Girar la imagen 90º a la derecha.
            $img = imagerotate($img, -90, 0);
            break;
        case "R":
            // Redimensionar a 800px de ancho manteniendo la proporción.
            $width = imagesx($img);
            $height = imagesy($img);
            $new_width = 800;
            $new_height = floor($height * ($new_width / $width));
            $new_image = imagecreatetruecolor($new_width, $new_height);
            imagecopyresampled($new_image, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            imagedestroy($img); // Liberar memoria de la imagen original
            $img = $new_image; // Asignar la nueva imagen redimensionada
            break;
        default:
            $tmp = explode("/", $vrx5);
            $msn .= "tmp : $c ln 328 ".$tmp[0]." / ".$tmp[1];
            switch ($tmp[0]) {
                case "C":
                    // Obtener el ancho original para calcular la compresión
                    $c =$tmp[1];
                    $var004 = escapeshellarg($var004); // Usamos $var004 que es la ruta de la imagen original
                    $cmd ="convert $var004 -density $c"."x"."$c $var004";
                    $msn .= "<br>No se manda la compresion: $cmd";
                    /*$sld = shell_exec($cmd);
                    if ( $sld === null) {
                        $msn .= "La imagen se ha comprimido exitosamente.";
                        $c=true;
                    } else {
                        $msn .= "<br>La compresión de la imagen falló: $sld<br>$cmd";
                    }*/
                break; case "G":
                    // Girar según el ángulo especificado
                    if (isset($tmp[1]) && is_numeric($tmp[1])) {
                        $g = intval($tmp[1]); // Asegurarse de que sea un número entero
                        $img = imagerotate($img, -$g, 0);
                    } else {
                        $msn.= "Error: Ángulo no válido para rotación.";
                    }
                    break;
                default:
                    $msn.= "<br>No hace nada ln 359";
            }
            break;
    }

    // Guardar la imagen modificada (si no se ha guardado ya en caso de compresión)
    if (!$c) {
        if(!imagejpeg($img, $var004)) $msn.="Error: No se pudo guardar la imagen modificada. ln 348";
        else $msn .="<br>Se volvio a guardar";
    }

    // Liberar memoria
    imagedestroy($img);
    return "$msn<br>var004: $var004<br>".fnc2($vrx1, $vrx2, $vrx3, $var004);
}

function fnc6($vrx1, $vrx2, $vrx3, $vrx4, $vrx5){
    //Para hacer los giros.
    // $vrx1: carpeta global, $vrx2: usuario, $vrx3: contraseña,
    // $vrx4: nombre del archivo
    // $vrx5: Angulo inverso a girar.
    $msn = "Img: $vrx4 Giro: $vrx5"; // Mensaje sin novedad.
    $var004 = $vrx1 . basename(strtok($vrx4, '?')); // El nombre viene con una huella de fecha, se quita para que funcione.

    // Verificar si el archivo existe
    if (!file_exists($var004)) {
        $msn.= "Error: El archivo no existe: $var004";
    }

    // Cargar la imagen
    $img = @imagecreatefromjpeg($var004); // Cambia a imagecreatefrompng o imagecreatefromgif según el formato
    if (!$img) {
        $msn.=  "Error: No se pudo cargar la imagen $var004.";
    }

    // Aplicar el giro
    $img1 = imagerotate($img, $vrx5, 0);
    if (!$img1) {
        imagedestroy($img);
        $msn.=  "Error: No se pudo girar la imagen $var004.";
    }

    // Guardar la imagen girada
    if (!imagejpeg($img1, $var004)) { // Cambia el formato de guardado si es necesario
        $msn.= "Error: No se pudo guardar la imagen modificada.";
    } else {
        $msn.= "La imagen se giró y guardó correctamente.";
    }

    // Liberar memoria
    imagedestroy($img);
    imagedestroy($img1);

    // Llamar a fnc2 con el nombre de archivo limpio
    return "$msn<br>".fnc2($vrx1, $vrx2, $vrx3, $var004);
}

function fnc7($vrx1, $vrx2, $vrx3, $vrx4, $vrx5) {
    $msn = "";
    $var002 = $vrx1 . basename(strtok($vrx4, '?'));

    $var003 = array_map(fn($point) => array_map('floatval', explode("x", $point)), explode("R", $vrx5));

    $cnW = $var003[0][0];
    $cnH = $var003[0][1];

    if (!file_exists($var002)) {
        $msn = "Error: El archivo no existe: $var002";
    }

    if ($msn === "") {
        $var004 = @imagecreatefromjpeg($var002);
        if (!$var004) {
            $msn = "Error: No se pudo cargar la imagen $var002.";
        } else {
            $msn .= "Imagen cargada correctamente. ";
        }
    }

    if ($msn === "" || strpos($msn, "Imagen cargada correctamente") !== false) {
        if ($cnW <= 0 || $cnH <= 0) {
            $msn .= "Error: Dimensiones de lienzo inválidas ($cnW x $cnH). ";
        } else {
            $var005 = imagecreatetruecolor($cnW, $cnH);
            if (!$var005) {
                $msn .= "Error: No se pudo crear el nuevo lienzo. ";
            } else {
                $result = imagecopyresampled(
                    $var005,
                    $var004,
                    0, 0, 0, 0,
                    $cnW,
                    $cnH,
                    imagesx($var004),
                    imagesy($var004)
                );
                if (!$result) {
                    $msn .= "Error: Fallo en imagecopyresampled. ";
                } else {
                    $msn .= "Perspectiva ajustada correctamente. ";
                }
            }
        }
    }

    if (strpos($msn, "Perspectiva ajustada correctamente") !== false) {
        if (!imagejpeg($var005, $var002)) {
            $msn .= "Error: No se pudo guardar la imagen ajustada. ";
        } else {
            $msn .= "Imagen guardada correctamente en: $var002. ";
        }

        imagedestroy($var004);
        imagedestroy($var005);
    }

    if (strpos($msn, "Error") === false) {
        $msn = "La perspectiva se ajustó correctamente y se guardó en: $var002";
    }

    if ($msn != "") return $msn;
    else return fnc2($vrx1, $vrx2, $vrx3, $var002);
}

// Otras funciones necesarias...

// Verificar si la variable 'funcion' está presente en $_POST
if (isset($_POST['funcion'])) {
    // Obtener el nombre de la función
    $fun00X = $_POST['funcion'];

    // Verificar si la función existe
    if (function_exists($fun00X)) {
        // Obtener los parámetros de $_POST, excluyendo el último que es el nombre de la función
        $prm = $_POST;
        unset($prm['funcion']); // Eliminar el nombre de la función del array

        // Llamar a la función con los parámetros restantes
        // Usamos 'call_user_func_array' para pasar los parámetros como un array
        $output = call_user_func_array($fun00X, array_values($prm));

        // Devolver la salida de la función
        echo $output;
    } else {
        echo "Error: La función '$fun00X' no existe.";
    }
} else {
    echo "Error: No se ha recibido la variable 'funcion'.";
}
?>
