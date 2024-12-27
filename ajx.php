<?php
// Función para cargar archivos de una carpeta
function fnc1($vrx1, $vrx2, $vrx3, $vrx4) {
    // Número de imágenes por grupo
    $var001 = 20;

    // Calcular el índice inicial según $vrx4
    $var002 = $vrx4 * $var001;

    // Comando para encontrar las imágenes y ordenar por tamaño
    // Usamos curl para autenticar y luego find para listar los archivos
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
    $output = shell_exec($cmd);

    // Verificar si hay salida
    if (empty($output)) {
        return "No hay imágenes disponibles en este rango.";
    }

    // Procesar la salida y generar la tabla HTML
    $lines = explode("\n", trim($output));
    $result = "<table valign='top'>";
    $result .= "<tr><th>Imagen</th><th>Tamaño (KB)</th></tr>";

    foreach ($lines as $line) {
        if (preg_match('/(\d+)\s+(.*)/', $line, $matches)) {
            $size = round($matches[1] / 1024, 2); // Tamaño en KB
            $imagePath = trim($matches[2]);

            // Convertir la ruta del sistema de archivos a una URL relativa
            // Suponiendo que el root del servidor es '/home/www/' y el acceso es '/intranet/documentos/'
            $relativePath = str_replace('/home/www', '', $imagePath);

            // Generar la fila de la tabla HTML
            $result .= "<tr>";
            $result .= "<td><img src='" . htmlspecialchars($relativePath) . "' width='100'></td>";
            $result .= "<td>$size KB</td>";
            $result .= "</tr>";
        }
    }

    $result .= "</table>";

    return $result;
}


// Función para rotar imágenes
function fnc2($vrx4, $vrx5) {
    // Implementación para rotar la imagen $vrx4 en dirección $vrx5 (derecha/izquierda)
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
