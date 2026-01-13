var peticionUnica1 = null;


function verSiEsIntro_Login_usuario(e,valor) //js_Index
{
	if (e.keyCode === 13 && !e.shiftKey) 
	{		
		document.getElementById("contrasena").value = "";					
		document.getElementById("contrasena").focus();
	}

}


function verSiEsIntro_Login_contrasena(e,valor) //js_Index
{
	if (e.keyCode === 13 && !e.shiftKey) 
	{		
		comprobarLogin();
	}
}


function comprobarLogin() //js_Index
{
	peticionUnica1=crearComunicacion(peticionUnica1);
							
	if(peticionUnica1)
	{							
		peticionUnica1.onreadystatechange = mostrarComprobarLogin;
		peticionUnica1.open("POST","ajax/comprobarLogin.php",false);
		peticionUnica1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		var query_string = consultaComprobarLogin();
		peticionUnica1.send(query_string);						
	}
}

function consultaComprobarLogin()
{	
	var consulta = "accion=comprobarLogin";
	consulta += "&usuario="+document.getElementById("usuario").value;
	consulta += "&contrasena="+document.getElementById("contrasena").value;	
	return consulta;	
}

function mostrarComprobarLogin()
{
	if (peticionUnica1.readyState == 4)
	{
		if(peticionUnica1.status == 200)
		{
			if (peticionUnica1.responseText.substr(0,5)=="Error")
			{
				alert(peticionUnica1.responseText);
			}
			else
			{				
				if (peticionUnica1.responseText == "pda")
				{
					window.location.href = "pda_produccion.php";	
				}
				else if (peticionUnica1.responseText == "pdaConductor")
				{
					window.location.href = "pda_Conductor.php";	
				}
				else
				{
					window.location.href = "principal.php";
				}				
			}
			peticionUnica1=null;
		}
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