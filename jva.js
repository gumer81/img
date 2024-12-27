/**
 * jva000: Realiza una solicitud AJAX.
 * @param {string} var000 - URL de la solicitud.
 * @param {Object|null} var001 - Datos a enviar (null para GET, objeto para POST).
 * @returns {Promise<string>} Respuesta del servidor como texto.
 */
url = "ajx.php";
async function jva000(var000, var001 = null) {
  // var002: Método HTTP (GET o POST)
  const var002 = var001 ? 'POST' : 'GET';
  // var003: Opciones para la solicitud fetch
  const var003 = {
    method: var002,
    headers: {
      'Content-Type': 'application/json',
    },
  };

  if (var001) {
    var003.body = JSON.stringify(var001);
  }

  try {
    // var004: Respuesta de la solicitud fetch
    const var004 = await fetch(var000, var003);
    if (!var004.ok) {
      throw new Error(`HTTP error! status: ${var004.status}`);
    }
    return await var004.text();
  } catch (var005) {
    // var005: Error capturado
    console.error('Fetch error:', var005);
    return `Error: ${var005.message}`;
  }
}

/**
 * jva001: Realiza una solicitud AJAX y actualiza el contenido de un elemento HTML.
 * @param {string} var000 - URL del archivo PHP.
 * @param {string} var001 - ID del elemento HTML a actualizar.
 * @param {Object|string|null} var002 - Datos a enviar (objeto, cadena de consulta o null).
 * @param {string} [var003='fnc000'] - Nombre de la función PHP a llamar.
 */
function jva001(var000, var001, var002 = null, var003 = 'fnc000') {
  // Obtener el elemento HTML objetivo
  const var004 = document.getElementById(var001);
  if (!var004) {
    console.error(`Elemento con id "${var001}" no encontrado`);
    return;
  }

  // Mostrar mensaje de carga
  var004.innerHTML = 'Cargando...';

  // Procesar los datos de entrada
  let var005 = var002 ? {...var002} : {};
  if (typeof var002 === 'string' && var002.includes('=')) {
    var005 = Object.fromEntries(new URLSearchParams(var002));
  }

  // Asignar la función PHP a llamar
  var005.function = var003 || `fnc${Object.keys(var005).length.toString().padStart(3, '0')}`;

  // Realizar la solicitud fetch
  fetch(var000, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify(var005)
  })
  .then(response => {
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    return response.text();
  })
  .then(var006 => {
    // Actualizar el contenido del elemento HTML con la respuesta
    var004.innerHTML = var006;
  })
  .catch(var007 => {
    // Mostrar mensaje de error en caso de fallo
    console.error('Error en la solicitud:', var007);
    var004.innerHTML = `Error: ${var007.message}`;
  });
}

// Recargar la lista de archivos

function jva1(var0) {
    //var0 es el limite de lista de archivos. desde el 0, hasta el numero total de archivos de imagenes / 20 (2 por pagina.)
    var var1 = document.getElementById('input1').value;//Carpeta
    var var2 = document.getElementById('input2').value;//Usuario
    var var3 = document.getElementById('input3').value;//Contraseña
    // Llamada AJAX para obtener la lista de los archivos archivos
    var000 = "crp="+var1+"&usr="+var2+"&psw="+var3+"&lmt="+var0;
    jva2(url, "div2",var000,"fnc1");
}

// Función AJAX para obtener archivos
function jva2(var0, var1) {
    // Implementación de llamada AJAX carga la imagen
    // var0 es el div donde se carga.
    // var1
    jva001(url,var5)
}

// Función para llamar a ajx.php para rotar imagen
function jva3(var9, var10) {
    // Implementación de llamada AJAX para rotar imagen
}

// Girar imagen a la derecha
function jva4() {
    var var7 = document.getElementById('imageView').src;
    jva3(var7, 'derecha');
}

// Girar imagen a la izquierda
function jva5() {
    var var8 = document.getElementById('imageView').src;
    jva3(var8, 'izquierda');
}
