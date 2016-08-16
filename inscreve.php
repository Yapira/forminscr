<!DOCTYPE html>
<html>
<head>
	<title>Inscrição Yapira UFPR</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="formulario.css">
</head>
<body>
	<div id="wrapper">
		<p id="header">Equipe Yapira de Robótica</p>
		<div class="box upper-box">
<?php
	// Error log por motivos de: Sou um programador PHP scrub.
	// Comentar para a versão live.
	//ini_set('display_startup_errors', 1);
	//ini_set('display_errors', 1);
	//error_reporting(-1);

	try {
		$inifile = parse_ini_file("conf/database.ini");
		$db = new mysqli($inifile["address"], $inifile["user"], $inifile["pass"], $inifile["database"]);
		if($db->connect_errno) {
			throw new RuntimeException("Conexão com o banco de dados não foi realizada com sucesso (Erro: " . $db->connect_errno . "). Favor contatar kaioaugusto.8 [arroba] gmail.com para reportar esse erro.");
		}
		// Usando glorioso encoding UTF-8 ao invés do pleb Latin-1 default.
		if(!$db->set_charset("utf8")) {
			throw new RuntimeException("Deu ruim por motivos de: " . $db->error);
		}
		// Pre-prepared statement. RIP SQL injection :D
		if(!($iqry = $db->prepare("INSERT INTO inscritos(nome, grr, email, telefone, curso, vaga, motivo, curriculo) VALUES (?, ?, ?, ?, ?, ?, ?, ?);"))) {
			throw new RuntimeException("Erro interno do script (ID #9001).");
		}

		if(!isset($_FILES['filecur']) || !isset($_FILES['filecur']['error'])) {
			throw new RuntimeException("Ops, parece que houve algo errado com o envio do seu currículo. Tente novamente!");
		}

		switch($_FILES['filecur']['error']) {
			case UPLOAD_ERR_OK:
				break;
			case UPLOAD_ERR_NO_FILE:
				throw new RuntimeException("Ops, parece que você não selecionou o seu currículo no formulário.");
			case UPLOAD_ERR_INI_SIZE:
			case UPLOAD_ERR_FORM_SIZE:
				throw new RuntimeException("Ops, o seu currículo é muito grande (> 10MB).");
			default:
				throw new RuntimeException("Ops, algo deu errado enquanto processavamos o seu currículo. Tente novamente.");
		}

		if($_FILES['filecur']['size'] > 10000000) {
			throw new RuntimeException("Ops, o seu currículo é muito grande (> 10MB).");
		}

		$finfo = new finfo(FILEINFO_MIME_TYPE);
		if(false === $ext = array_search($finfo->file($_FILES['filecur']['tmp_name']), array('pdf' => 'application/pdf'), true)) {
			throw new RuntimeException("Ops, o arquivo enviado não aparenta ser um pdf!");
		}

		// Jogando coisas em variáveis para gastar mais memória.
		// Er... Quer dizer, poder bindar as variáveis.
		// Por motivos de: PHP.
		$name = $_POST["txtname"];
		$grr = $_POST["txtmatr"];
		$mail = $_POST["txtmail"];
		$celu = $_POST["txtcelular"];
		$curso = $_POST["txtcurso"];
		$vaga = intval($_POST["txtvaga"]);
		$motivo = $_POST["txtmotivo"];

		$filepath = sprintf('./cupload/%s.pdf', $grr);

		if(file_exists($filepath)) {
			throw new RuntimeException("Ops, parece que alguém já se inscreveu com esse GRR.");
		}

		if(!move_uploaded_file($_FILES['filecur']['tmp_name'], $filepath)) {
			throw new RuntimeException("Ops, não foi possível lidar com o seu arquivo!");
		}
		// Com escape, pra evitar SQL injection. Acho que não precisa, tho.
		// $name = mysqli_real_escape_string($db, $_POST["txtname"]);
		// $grr = mysqli_real_escape_string($db, $_POST["txtmatr"]);
		// $mail = mysqli_real_escape_string($db, $_POST["txtmail"]);
		// $celu = mysqli_real_escape_string($db, $_POST["txtcelular"]);
		// $curso = mysqli_real_escape_string($db, $_POST["txtcurso"]);
		// $vaga = intval(mysqli_real_escape_string($db, $_POST["txtvaga"]));
		// $motivo = mysqli_real_escape_string($db, $_POST["txtmotivo"]);

		if(!($iqry->bind_param("sssssiss", $name, $grr, $mail, $celu, $curso, $vaga, $motivo, $filepath))) {
			throw new RuntimeException("Erro interno do script (ID #9002).");
		}
		// Saiu do script a query, BIRLLLLL.
		if(!$iqry->execute()) {
			throw new RuntimeException("Erro interno do script (ID #9003).");
		}
		$iqry->close();

		echo "<p style='text-align: center;'><img src='success.png' /></p>";
		echo "<p style='text-align: center; color: darkorange; font-weight: bold;'>A sua inscrição foi efetuada com sucesso!</p>";
		// echo "<p style='text-align: center;'><a href='javascript:history.back()'>Voltar para o formulário</a></p>";
	} catch (RuntimeException $e) {
		echo "<p style='text-align: center;'><img src='error.png' /></p>";
		echo "<p style='text-align: center; color: red; font-weight: bold;'>" . $e->getMessage() . "</p><p style='text-align: center;'><a href='javascript:history.back()'>Voltar para o formulário</a></p>";
	}
?>
		</div>
	</div>
</body>
</html>
