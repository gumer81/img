/**
 * jva000: Realiza una solicitud AJAX.
 * @param {string} var000 - URL de la solicitud.
 * @param {Object|null} var001 - Datos a enviar (null para GET, objeto para POST).
 * @returns {Promise<string>} Respuesta del servidor como texto.
 */

const url = "ajx.php"; // Declaración global de url

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
 */
function jva001(var000, var001, var002 = null) {
  // Obtener el elemento HTML objetivo
  const var003 = document.getElementById(var001);
  if (!var003) {
    console.error(`Elemento con id "${var001}" no encontrado`);
    return;
  }

  // Mostrar mensaje de carga
  var003.innerHTML = 'Cargando...';

  // Configurar opciones para la solicitud fetch
  const fetchOptions = {
    method: var002 ? 'POST' : 'GET',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
  };

  // Si hay datos para enviar, prepararlos para POST
  if (var002) {
    fetchOptions.body = typeof var002 === 'string'
      ? var002
      : new URLSearchParams(var002).toString();
  }

  // Realizar la solicitud fetch
  fetch(var000, fetchOptions)
    .then(response => {
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      return response.text();
    })
    .then(var004 => {
      // Actualizar el contenido del elemento HTML con la respuesta
      var003.innerHTML = var004;
    })
    .catch(var005 => {
      // Mostrar mensaje de error en caso de fallo
      console.error('Error en la solicitud:', var005);
      var003.innerHTML = `Error: ${var005.message}`;
    });
}

// Recargar la lista de archivos

function jva1(var0) {
    const var1 = document.getElementById('input1').value; // Carpeta
    const var2 = document.getElementById('input2').value; // Usuario
    const var3 = document.getElementById('input3').value; // Contraseña

    // Crear objeto de datos
    const var5 ="crp="+var1+"&usr="+var2+"&psw="+var3+"&lmt="+var0+"&funcion=fnc1"; // Especificar la función PHP a llamar
    // Llamada a jva001
    jva001(url, "div2", var5);
}

//Carga la imagen var0.
function jva2(var0){
  const var1 = document.getElementById('input1').value; // Carpeta
  const var2 = document.getElementById('input2').value; // Usuario
  const var3 = document.getElementById('input3').value; // Contraseña

    // Crear objeto de datos
  const var5 ="crp="+var1+"&usr="+var2+"&psw="+var3+"&img="+var0+"&funcion=fnc2"; // Especificar la función PHP a llamar
    // Llamada a jva001
  jva001(url, "div3", var5);
}

function jva3(){
  //Si hay mas de uncheckbox en checked del nombre chk0 se ejecuta
  const checkboxes = document.querySelectorAll('input[name="chk0"]:checked');
  // Contar la cantidad de checkboxes seleccionados
  const count = checkboxes.length;
  if(count>1){
    //Si hay 2 checkboxes seleccionados.
    let img1 = "";
    for (let i = 0; i < checkboxes.length; i++) {
      if (i > 0) img1 += "@";
      img1 += checkboxes[i].value;
    }
    const var1 = document.getElementById('input1').value; // Carpeta
    const var2 = document.getElementById('input2').value; // Usuario
    const var3 = document.getElementById('input3').value; // Contraseña
    const var5 = "crp="+var1+"&usr="+var2+"&psw="+var3+"&im1="+img1+"&funcion=fnc3"; // Especificar la función
    jva001(url, "div2", var5);
  }
}

function jva4(){
  const chk= document.querySelectorAll('input[name="chk0"]');
  chk.forEach(chk => {
    chk.checked = true;
  });
}

