<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Captura de Coordenadas con Líneas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        img {
            max-width: 100%; /* Asegura que la imagen no exceda el ancho del contenedor */
            height: auto; /* Mantiene la relación de aspecto */
            cursor: pointer; /* Cambia el cursor al pasar sobre la imagen */
        }
        input {
            margin-top: 10px;
            width: 100px; /* Ancho fijo para los inputs */
        }
        #miCanvas {
            position: absolute; /* Coloca el canvas sobre la imagen */
            top: 0;
            left: 0;
            pointer-events: none; /* Permite clics a través del canvas */
        }
    </style>
</head>
<body>

<h2>Haz clic en la imagen para capturar las coordenadas:</h2>

<div style="position: relative;">
    <img src="/intranet/documentos/44047B.jpeg" id="miImagen" alt="Imagen para capturar coordenadas">
    <canvas id="miCanvas"></canvas>
</div>

<!-- Inputs para mostrar las coordenadas -->
<input type="text" id="inputX1" placeholder="X1">
<input type="text" id="inputY1" placeholder="Y1">
<input type="text" id="inputX2" placeholder="X2" value="0">
<input type="text" id="inputY2" placeholder="Y2" value="0">

<script>
let clickCount = 0; // Contador de clics
const canvas = document.getElementById('miCanvas');
const ctx = canvas.getContext('2d');
const img = document.getElementById('miImagen');

// Esperar a que la imagen se cargue antes de establecer el tamaño del canvas
img.onload = function() {
    canvas.width = img.clientWidth; // Establece el ancho del canvas igual al de la imagen
    canvas.height = img.clientHeight; // Establece la altura del canvas igual a la de la imagen
};

document.getElementById('miImagen').addEventListener('click', function(event) {
    const x = event.offsetX; // Coordenada X relativa a la imagen
    const y = event.offsetY; // Coordenada Y relativa a la imagen

    if (clickCount === 0) {
        // Primer clic: guardar en input 1 y 2
        document.getElementById('inputX1').value = x;
        document.getElementById('inputY1').value = y;

        // Borrar el canvas y dibujar líneas
        ctx.clearRect(0, 0, canvas.width, canvas.height); // Limpiar el canvas

        // Dibujar líneas en la posición del primer clic
        ctx.beginPath();

        // Línea horizontal en Y
        ctx.moveTo(0, y); // Desde el borde izquierdo
        ctx.lineTo(canvas.width, y); // Hasta el borde derecho

        // Línea vertical en X
        ctx.moveTo(x, 0); // Desde el borde superior
        ctx.lineTo(x, canvas.height); // Hasta el borde inferior

        ctx.lineWidth = 5; // Grosor de línea
        ctx.strokeStyle = 'black'; // Color negro
        ctx.stroke();

    } else if (clickCount === 1) {
        // Segundo clic: guardar en input 3 y 4
        document.getElementById('inputX2').value = x;
        document.getElementById('inputY2').value = y;

        // Dibujar líneas en la posición del segundo clic
        ctx.beginPath();

        // Línea horizontal en Y desde el segundo clic
        ctx.moveTo(0, y); // Desde el borde izquierdo
        ctx.lineTo(canvas.width, y); // Hasta el borde derecho

        // Línea vertical en X desde el segundo clic
        ctx.moveTo(x, 0); // Desde el borde superior
        ctx.lineTo(x, canvas.height); // Hasta el borde inferior

        ctx.lineWidth = 5; // Grosor de línea
        ctx.strokeStyle = 'black'; // Color negro
        ctx.stroke();

        // Reiniciar el contador después del segundo clic
        clickCount = 0;

    }

    clickCount++; // Incrementar contador de clics después de procesar

    if (clickCount > 2) {
      clickCount = 0;
      document.getElementById('inputX2').value = "0";
      document.getElementById('inputY2').value = "0";
      document.getElementById('inputX1').value = x;
      document.getElementById('inputY1').value = y;

      ctx.clearRect(0, 0, canvas.width, canvas.height); // Limpiar el canvas

      // Dibujar líneas en la posición del primer clic
      ctx.beginPath();

      // Línea horizontal en Y
      ctx.moveTo(0, y);
      ctx.lineTo(canvas.width, y);

      // Línea vertical en X
      ctx.moveTo(x, 0);
      ctx.lineTo(x, canvas.height);

      ctx.lineWidth = 5;
      ctx.strokeStyle = 'black';
      ctx.stroke();
    }
});
</script>

</body>
</html>
