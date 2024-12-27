<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Visualización y Edición de Imágenes</title>
    <link rel="stylesheet" type="text/css" href="stl.css?<? echo date("ymdhis");?>">
    <script src="jva.js?<? echo date("ymdhis");?>"></script>
</head>
<body>
<table>
<tr><td colspan=2>
    <div id="div1">
        <input type="text" id="input1" value="/home/www/intranet/documentos/">
        <input type="text" id="input2" value="colaborador" placeholder="colaborador">
        <input type="password" id="input3" value="excelencia">
        <button onclick="jva1(0)">Recargar</button>
    </div>
</td></tr><tr><td>
    <div id="div2">
        LISTA DE ARCHIVOS
        <!-- List of files will be loaded here -->
    </div></td><td>
    <div id="div3">
        <button onclick="jva2(0)">Cargar imagen</button>
    </div></td>
</tr>
</table>
</body>
</html>
<script>
jva1(0);//Carga la lista de archivos.
//jva2(0);//Carga la imagen mas grande de la lista de archivos.
</script>
