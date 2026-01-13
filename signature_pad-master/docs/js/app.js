var wrapper = document.getElementById("signature-pad");
var clearButton = wrapper.querySelector("[data-action=clear]");
var changeColorButton = wrapper.querySelector("[data-action=change-color]");
var undoButton = wrapper.querySelector("[data-action=undo]");
var savePNGButton = wrapper.querySelector("[data-action=save-png]");
var saveJPGButton = wrapper.querySelector("[data-action=save-jpg]");
var saveSVGButton = wrapper.querySelector("[data-action=save-svg]");
var canvas = wrapper.querySelector("canvas");
var signaturePad = new SignaturePad(canvas, {
  // It's Necessary to use an opaque color when saving image as JPEG;
  // this option can be omitted if only saving as PNG or SVG
  backgroundColor: 'rgb(255, 255, 255)'
});

// Adjust canvas coordinate space taking into account pixel ratio,
// to make it look crisp on mobile devices.
// This also causes canvas to be cleared.

function resizeCanvas() {
  // When zoomed out to less than 100%, for some very strange reason,
  // some browsers report devicePixelRatio as less than 1
  // and only part of the canvas is cleared then.
  var ratio =  Math.max(window.devicePixelRatio || 1, 1);

  // This part causes the canvas to be cleared
  canvas.width = canvas.offsetWidth * ratio;
  canvas.height = canvas.offsetHeight * ratio;
  canvas.getContext("2d").scale(ratio, ratio);

  // This library does not listen for canvas changes, so after the canvas is automatically
  // cleared by the browser, SignaturePad#isEmpty might still return false, even though the
  // canvas looks empty, because the internal data of this library wasn't cleared. To make sure
  // that the state of this library is consistent with visual state of the canvas, you
  // have to clear it manually.
  signaturePad.clear();
}

// On mobile devices it might make more sense to listen to orientation change,
// rather than window resize events.
window.onresize = resizeCanvas;
resizeCanvas();
var peticionUnica = null;
var idCliente2="";
var idCliente3="";
var rutaHora2="";
var rutaHora3="";

function download(dataURL, filename) {
  if (navigator.userAgent.indexOf("Safari") > -1 && navigator.userAgent.indexOf("Chrome") === -1) 
  {
    window.open(dataURL);
  } else {
    var blob = dataURLToBlob(dataURL);
    var url = window.URL.createObjectURL(blob);

    var a = document.createElement("a");
    a.style = "display: none";
    a.href = url;
    a.download = filename;

    document.body.appendChild(a);
    //a.click();
///////////////////
    
	  //alert("3: ");
	//file = inputFileImage.files[0];
	
 	var imagen = dataURL.replace(/^data:image\/(png|jpg);base64,/, "");
	  
	  
	var data = new FormData();
 	
	//data.append('archivo','''');	
	data.append('url',imagen);
	data.append('accion',"subir");
	data.append('idCliente3',idCliente2);
	data.append('horaRuta3',horaRuta2);
	 
	idCliente3=idCliente2;
	horaRuta3=horaRuta2;
	  peticionUnica=crearComunicacion(peticionUnica);
	//peticion5=new XMLHttpRequest();
	 
	peticionUnica.onreadystatechange = mostrarSubirArchivo;
	peticionUnica.open("POST","../../ajax/subirFirma.php",false);
	//peticion27.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	
	peticionUnica.send(data);
	  
	
	  //////////////////////////
	  
    window.URL.revokeObjectURL(url);
  }
}




function mostrarSubirArchivo()
{			
	if (peticionUnica.readyState == 4)
	{
		if(peticionUnica.status == 200)
		{
			if (peticionUnica.responseText.substr(0,5)=="Error")
			{
				alert(peticionUnica.responseText);
			}
			else
			{
				alert(peticionUnica.responseText);
				//$("#firma").modal('hide');
				//alert(idCliente2);
				//document.getElementById(idCliente2+'_firmaIdCliente').src = "../../../imagenes/01_checkVerde.png";
				//idCliente2="";
				
				
				signaturePad.clear();
				peticionUnica=null;
				guardarHistoricoRuta();
				
			}
		}
	}
}

function guardarHistoricoRuta()
{
	peticionUnica=crearComunicacion(peticionUnica);

	if(peticionUnica)
	{							
		peticionUnica.onreadystatechange = mostrarGuardarHistoricoRuta;
		peticionUnica.open("POST","../../ajax/guardarHistorico.php",false);
		peticionUnica.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");		
		var query_string = consultaGuardarHistoricoRuta();
		peticionUnica.send(query_string);						
	}	
}
function consultaGuardarHistoricoRuta()
{	
	var consulta = "accion=guardarHistoricoRuta";
	consulta += "&idCliente="+idCliente3;
	consulta += "&horaRuta="+horaRuta2;
	consulta += "&firma=si";
	consulta += "&nombre=''&dni=''";
	
	return consulta;	
}

function mostrarGuardarHistoricoRuta()
{
	if (peticionUnica.readyState == 4)
	{
		if(peticionUnica.status == 200)
		{
			if (peticionUnica.responseText.substr(0,5)=="Error")
			{
				alert(peticionUnica.responseText);
			}
			else
			{			
				idCliente3=0;	
				horaRuta3=0;	
			}
		}
		peticionUnica=null;		
	}						
}

function crearComunicacion(laPeticion)
{				
	try
	{
		// Creacion del objeto AJAX para navegadores no IE
		laPeticion=new ActiveXObject("Msxml2e.XMLHTTP");
	}
	catch(e)
	{
		try
		{
			// Creacion del objet AJAX para IE
			//laPeticion=new ActiveXObject("Microsoft.XMLHTTP");
			laPeticion=new XMLHttpRequest();
			}
		catch(E)
		{
			if (!laPeticion && typeof XMLHttpRequest!='undefined') laPeticion=new XMLHttpRequest();
		}
	}
	
	return laPeticion;
}


// One could simply use Canvas#toBlob method instead, but it's just to show
// that it can be done using result of SignaturePad#toDataURL.
function dataURLToBlob(dataURL) {
  // Code taken from https://github.com/ebidel/filer.js
  var parts = dataURL.split(';base64,');
  var contentType = parts[0].split(":")[1];
  var raw = window.atob(parts[1]);
  var rawLength = raw.length;
  var uInt8Array = new Uint8Array(rawLength);

  for (var i = 0; i < rawLength; ++i) {
    uInt8Array[i] = raw.charCodeAt(i);
  }

  return new Blob([uInt8Array], { type: contentType });
}

clearButton.addEventListener("click", function (event) {
  signaturePad.clear();
});

undoButton.addEventListener("click", function (event) {
  var data = signaturePad.toData();

  if (data) {
    data.pop(); // remove the last dot or line
    signaturePad.fromData(data);
  }
});

changeColorButton.addEventListener("click", function (event) {
  var r = Math.round(Math.random() * 255);
  var g = Math.round(Math.random() * 255);
  var b = Math.round(Math.random() * 255);
  var color = "rgb(" + r + "," + g + "," + b +")";

  signaturePad.penColor = color;
});

savePNGButton.addEventListener("click", function (event) {
  if (signaturePad.isEmpty()) {
    alert("Please provide a signature first.");
  } else {
    var dataURL = signaturePad.toDataURL();	 
    download(dataURL, "signature.png");
	  
	 
  }
});

saveJPGButton.addEventListener("click", function (event) {
  if (signaturePad.isEmpty()) {
    alert("Por favor, inserte una firma.");
  } else {
	
    var dataURL = signaturePad.toDataURL();	
    download(dataURL, "signature.jpg");
	//signaturePad.clear();
  }
});

saveSVGButton.addEventListener("click", function (event) {
  if (signaturePad.isEmpty()) {
    alert("Please provide a signature first.");
  } else {
    var dataURL = signaturePad.toDataURL('image/svg+xml');
    download(dataURL, "signature.svg");
  }
});
