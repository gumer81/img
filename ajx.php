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
            $relativePath = str_replace('/home/www', '', $imagePath);

            // Obtener las dimensiones de la imagen
            list($x, $y) = getimagesize($imagePath);

            // Almacenar en la matriz
            $mtx[] = [
                'nombre' => htmlspecialchars($relativePath),
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
    $result.= "<input type='button' value='+' onclick='javascript:jva1($pas);'>";
    return $result;
}

// Función para visualiar la imagen.
function fnc2($vrx1, $vrx2, $vrx3, $vrx4) {
    // Ruta del archivo que se intenta cargar

    $plf = strrpos($vrx4, '/'); // Encontrar la posición de la última barra
    if ($plf !== false) {
        $vrx4 = substr($vrx4, $plf + 1); // Asignar solo el nombre del archivo a vrx4
    }

    $filePath = $vrx1.'/'. $vrx4; // Construir la ruta completa del archivo

    // Verificar si el archivo existe
    if (file_exists($filePath)) {
        // Convertir la ruta del sistema de archivos a una URL relativa
        $relativePath = str_replace('/home/www', '', $filePath);
        return "<img src='".htmlspecialchars($relativePath)."' alt='".htmlspecialchars($vrx4)."' width='600'>";
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
            $relativePath = str_replace('/home/www', '', trim($largestImagePath));
            return "<img src='" . htmlspecialchars($relativePath) . "' alt='Imagen más grande' width='80%'>";
        } else {
            return "No hay imágenes disponibles en esta carpeta.";
        }
    }
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
