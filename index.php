<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Visualización y Edición de Imágenes</title>
    <link rel="stylesheet" type="text/css" href="stl.css">
    <script src="jva.js"></script>
</head>
<body>
    <div id="div1">
        <input type="text" id="input1" value="/home/www/intranet/documentos/">
        <input type="text" id="input2" placeholder="colaborador">
        <input type="password" id="input3" value="excelencia">
        <button onclick="jva1(0)">Recargar</button>
    </div>
    <div id="div2">
        <!-- List of files will be loaded here -->
    </div>
    <div id="div3">
        <img id="imageView" src="" alt="Vista de Imagen">
        <button onclick="jva4()">Girar a la derecha</button>
        <button onclick="jva5()">Girar a la izquierda</button>
    </div>
</body>
</html>
<script>
jva1(0);//Carga la lista de archivos.
jva2(0);//Carga la imagen mas grande de la lista de archivos.
</script>
