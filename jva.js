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

function jva3(pas){
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
    const var5 = "crp="+var1+"&usr="+var2+"&psw="+var3+"&img="+img1+"&pas="+pas+"&funcion=fnc3"; // Especificar la función
    jva001(url, "div2", var5);
  }
}

function jva4(){
  //Pasa todos los checkboxes a ok.
  const chk= document.querySelectorAll('input[name="chk0"]');
  chk.forEach(chk => {
    chk.checked = true;
  });
}

function jva5() {
  // Se ejecuta al cambiar de radio de edición de imagen.
  const div1 = document.getElementById("div");
  div1.style.position = "relative";
  const cnv1 = document.getElementById("cnv");
  cnv1.style.position = "absolute"; // Cambia la posición a absoluta
  cnv1.style.top = "0"; // Establece la posición superior
  cnv1.style.left = "0"; // Establece la posición izquierda
  cnv1.style.pointerEvents = "none"; // Permite clics a través del canvas

  const img = document.getElementById('img');

  // Esperar a que la imagen se cargue antes de establecer el tamaño del canvas
  if (img.complete) {
      // Si la imagen ya está cargada
      cnv1.width = img.clientWidth; // Establece el ancho del canvas igual al de la imagen
      cnv1.height = img.clientHeight; // Establece la altura del canvas igual a la de la imagen
  } else {
      // Si la imagen no está cargada, agregar un evento para cuando se cargue
      img.onload = function() {
          cnv1.width = img.clientWidth;
          cnv1.height = img.clientHeight;
      };
  }
  document.getElementById("prs1").value=0;
  document.getElementById("prs2").value=0;
  document.getElementById("prs3").value=0;
  document.getElementById("prs4").value=0;
  document.getElementById("rct1").value=0;
  document.getElementById("rct2").value=0;
  document.getElementById("rct3").value=0;
  document.getElementById("rct4").value=0;
}

function jva6(event) {
    // Al darle click a la imagen.
  const x = event.offsetX; // Coordenada X relativa a la imagen
  const y = event.offsetY; // Coordenada Y relativa a la imagen
  const var1 = document.querySelector('input[name="EDC"]:checked').value;
  if(var1==3) {
    jva7(x,y);
    document.getElementById("prs1").value=0;
    document.getElementById("prs2").value=0;
    document.getElementById("prs3").value=0;
    document.getElementById("prs4").value=0;
  }
  if(var1==4) {
    jva8(x,y);
    document.getElementById("rct1").value=0;
    document.getElementById("rct1").value=0;
    document.getElementById("rct1").value=0;
    document.getElementById("rct1").value=0;
  }

}

function jva7(x,y){
  const cnv1 = document.getElementById('cnv');
  const ctx1 = cnv1.getContext('2d');

  const a = document.getElementById("rct1").value;
  const c = document.getElementById("rct3").value;

  if (a != 0 && c != 0) {
      document.getElementById("rct1").value = 0;
      document.getElementById("rct2").value = 0;
      document.getElementById("rct3").value = 0;
      document.getElementById("rct4").value = 0;
      ctx1.clearRect(0, 0, cnv1.width, cnv1.height); // Limpiar el canvas
  }

  if (document.getElementById("rct1").value == 0) {
      // Se escribe en 0.
      document.getElementById("rct1").value = x;
      document.getElementById("rct2").value = y;
      const v1=0;
  } else {
      document.getElementById("rct3").value = x;
      document.getElementById("rct4").value = y; // Cambiado de x a y para que sea coherente
      const v1=3;
  }

  // Hora de dibujar las rectas.
  ctx1.beginPath();

  // Línea horizontal en Y
  ctx1.moveTo(0, y); // Desde el borde izquierdo
  ctx1.lineTo(cnv1.width, y); // Hasta el borde derecho

  // Línea vertical en X
  ctx1.moveTo(x, 0); // Desde el borde superior
  ctx1.lineTo(x, cnv1.height); // Hasta el borde inferior

  ctx1.lineWidth = 1; // Grosor de línea
  ctx1.strokeStyle = 'black'; // Color negro
  ctx1.stroke();
}

function jva8(x,y){
  const cnv1 = document.getElementById('cnv');
  const ctx1 = cnv1.getContext('2d');
  let cnd = false; let i=1;
  do {
    if (document.getElementById("prs"+i).value==0) {
        document.getElementById("prs"+i).value=x+"x"+y;
        cnd=true;
    } else {
        i++;
        if(i>4){
          i=1;
          document.getElementById("prs1").value="0";
          document.getElementById("prs2").value="0";
          document.getElementById("prs3").value="0";
          document.getElementById("prs4").value="0";
          ctx1.clearRect(0, 0, cnv1.width, cnv1.height);
        }
    }
  } while (!cnd);

  //Dibujar un aspa negra, y una cruz blanca.
  //punto x,y
  ctx1.beginPath();

  // Cruz en negro.
  ctx1.moveTo(x-10, y); // Desde el borde izquierdo
  ctx1.lineTo(x+10, y); // Hasta el borde derecho
  ctx1.moveTo(x, y-10); // Desde el borde superior
  ctx1.lineTo(x, y+10); // Hasta el borde inferior
  ctx1.lineWidth = 1; // Grosor de línea
  ctx1.strokeStyle = 'black'; // Color negro
  ctx1.stroke();
  // Aspa en blanco.
  ctx1.moveTo(x-10, y-10); // Desde el borde izquierdo
  ctx1.lineTo(x+10, y+10); // Hasta el borde derecho
  ctx1.moveTo(x+10, y-10); // Desde el borde superior
  ctx1.lineTo(x-10, y+10); // Hasta el borde inferior
  ctx1.lineWidth = 1; // Grosor de línea
  ctx1.strokeStyle = 'white'; // Color negro
  ctx1.stroke();
}

function jva9(){
  //Carga los valores para procesar los cortes,
  const var1 = document.getElementById('input1').value; // Carpeta
  const var2 = document.getElementById('input2').value; // Usuario
  const var3 = document.getElementById('input3').value; // Contraseña
  edt = document.querySelector('input[name="EDC"]:checked').value;
  let img = document.getElementById("img").src;   //Nombre de la imagen.
  img = img.split('/').pop();
  if(edt==2){
    //Manda los datos a girar.
    const var5 = document.getElementById("rtr").value;
    const var6 ="crp="+var1+"&usr="+var2+"&psw="+var3+"&img="+img+"&var5="+var5+"&funcion=fnc6";
    jva001(url, "div3", var6);
  }

  if(edt==3){
    //Recortar la imagen.
    const cnv = document.getElementById('cnv');  //Dibujo en canva.
    const dim = cnv.width+"x"+cnv.height;         //Tamaño del canva que lo contiene.
    //Punto 1 y 2
    const p1 = document.getElementById("rct1").value+"x"+document.getElementById("rct2").value;
    const p2 = document.getElementById("rct3").value+"x"+document.getElementById("rct4").value;
    const var5 = dim+"R"+p1+"R"+p2;
    //SE MANDA EL ARREGLO
    const var6 ="crp="+var1+"&usr="+var2+"&psw="+var3+"&img="+img+"&var5="+var5+"&funcion=fnc4";
    // Especificar la función PHP a llamar
    // Llamada a jva001
    jva001(url, "div3", var6);
  }
  if(edt==4){
    //Perspectiva.
    const cnv = document.getElementById('cnv');  //Dibujo en canva.
    const dim = cnv.width+"x"+cnv.height;         //Tamaño del canva que lo contiene.
    //Punto 1, 2, 3, 4
    const p1 = document.getElementById("prs1").value+"R"+document.getElementById("prs2").value;
    const p2 = document.getElementById("prs3").value+"R"+document.getElementById("prs4").value;
    const var5 = dim+"R"+p1+"R"+p2;
    //SE MANDA EL ARREGLO
    const var6 ="crp="+var1+"&usr="+var2+"&psw="+var3+"&img="+img+"&var5="+var5+"&funcion=fnc7";
    // Especificar la función PHP a llamar
    // Llamada a jva001
    jva001(url, "div3", var6);
  }
}

function jva10(var0 = "0"){
  //Para redimensionar la imagen.
  if(var0=="0")  edt = document.querySelector('input[name="EDC"]:checked').value;
  else edt = var0;
  const var1 = document.getElementById('input1').value; // Carpeta
  const var2 = document.getElementById('input2').value; // Usuario
  const var3 = document.getElementById('input3').value; // Contraseña
  let img = document.getElementById("img").src;   //Nombre de la imagen.
  let var6="var1="+var1+"&var2="+var2+"&var3="+var3+"&var4="+img;
  img = img.split('/').pop();
  let var5="";
  switch(edt){
    case 1:
      //Gira a la izquierda.
      var5 = "var5=i";
    break; case 2:
      //Gira a la derecha.
      var5 = "var5=d";
    break; case "1A":
      //Redimensiona
      var5 = "var5=R";
    break; case "1B":
      //comprime
      var5 = "var5=C/"+document.getElementById("cmp").value; //Comprimir a esta densida.
    break; case 2:
      //Gira segun el angulo indicado.
      const var7=document.getElementById("rtr").value;  //Angulo a girar.
      if(var7==0) alert("debe poner un angulo diferente de cero para cambiar.");
      else var5 = "var5=G/"+document.getElementById("cmp").value; //Comprimir a esta densida.
    break;
  }
  if(var5!=""){
    var5+="&funcion=fnc5";
    jva001(url, "div3", var6+"&"+var5);
  }
}

function jva11(){
  const cnv1 = document.getElementById('cnv');
  const ctx1 = cnv1.getContext('2d');
  const ang = document.getElementById("rtr").value;
  ctx1.clearRect(0, 0, cnv1.width, cnv1.height);
  // Obtener el centro del canvas
  const centerX = cnv1.width / 2;
  const centerY = cnv1.height / 2;

  // Convertir el ángulo de grados a radianes
  const angleInRadians = (ang * Math.PI) / 180;

  // Calcular la longitud de la línea (usamos la diagonal del canvas)
  const lineLength = Math.sqrt(cnv1.width * cnv1.width + cnv1.height * cnv1.height);

  // Calcular los puntos finales de la línea
  const endX = centerX + lineLength * Math.cos(angleInRadians);
  const endY = centerY + lineLength * Math.sin(angleInRadians);

  // Dibujar la línea
  ctx1.beginPath();
  ctx1.moveTo(centerX - lineLength * Math.cos(angleInRadians), centerY - lineLength * Math.sin(angleInRadians));
  ctx1.lineTo(endX, endY);
  ctx1.strokeStyle = 'red';
  ctx1.lineWidth = 2;
  ctx1.stroke();
}
