function validaemail() {
	emailfield = document.getElementById("txtmail");
	email = emailfield.value;
	/* Validando e-mail por regex por motivos de preguiça. */
	var re = /^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
	if(!re.test(email)) {
		// E-mail ruim
		emailfield.className = "form-input is-danger";
		return false;
	}
	// E-mail bom
	emailfield.className = "form-input is-success";
	return true;
}

function valida() {
	/* TO-DO: Validar. Ou não.
		Se você é um candidato ao processo seletivo e está lendo isso,
		provavelmente eu decidi fazer a parte de validação inteiramente
		por PHP. */
	if(!validaemail()) {
		document.getElementById("emailruim").style.display = "block";
		return false;
	}
	if(document.getElementById("filecur").value == "") {
		document.getElementById("semarquivo").style.display = "block";
		return false;
	}
	campos = ["txtname", "txtmatr", "txtcelular", "txtcurso"];
	for(i = 0; i < campos.length; i++) {
		campo = document.getElementById(campos[i]);
		if(campo.value.length <= 0) {
			document.getElementById("campovazio").style.display = "block";
			return false;
		}
	}
	return true;
}

function setvaga(vaga) {
	campo = document.getElementById("txtvaga")
	vagaAntiga = campo.value
	campo.value = vaga;
	document.getElementById("btn" + vagaAntiga).className = "btn";
	document.getElementById("btn" + vaga).className = "btn btn-primary";
}
