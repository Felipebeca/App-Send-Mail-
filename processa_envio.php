<?php

	//print_r($_POST);

	require "./bibliotecas/PHPMailer/Exception.php";
	require "./bibliotecas/PHPMailer/OAuth.php";
	require "./bibliotecas/PHPMailer/PHPMailer.php";
	require "./bibliotecas/PHPMailer/POP3.php";
	require "./bibliotecas/PHPMailer/SMTP.php";

	//Importar classes do PHPMailer para o namespace global.
		use PHPMailer\PHPMailer\PHPMailer;
		use PHPMailer\PHPMailer\Exception;
	

	class Mensagem {
		private $para = null;
		private $assunto = null;
		private $mensagem = null;
		public $status = array('codigo_status' => null, 'descricao_status' => '');

		public function __get($atributo){
			return $this->$atributo;

		}

		public function __set($atributo, $valor){
			$this->$atributo = $valor;
		}

		public function mensagemValida(){
			if(empty($this->para)|| empty($this->assunto) || empty($this->assunto)){
				return false;
			}

			return true;

		}
	}

	$mensagem = new Mensagem();

	$mensagem-> __set('para', $_POST['para']);
	$mensagem-> __set('assunto', $_POST['assunto']);
	$mensagem-> __set('mensagem', $_POST['mensagem']);

	//print_r($mensagem);

	if(!$mensagem->mensagemValida() ){
		echo 'mensagem não é valida';
		header('Location: index.php');
	}

	$mail = new PHPMailer(true);
	try {
   
	// Configurações do servidor
    $mail->SMTPDebug = false;                                 // Ativar saída de depuração detalhada
    $mail->isSMTP();                                      // Definir mailer para usar o SMTP
    $mail->Host = 'smtp.gmail.com';  // Especifique servidores SMTP principais e de backup
    $mail->SMTPAuth = true;                               // Ativar autenticação SMTP
    $mail->Username = 'felipeesousa.92@gmail.com';                 // Nome de usuário SMTP
    $mail->Password = 'privado';                           // Senha SMTP
    $mail->SMTPSecure = 'tls';                            // Ativar criptografia TLS, `ssl` também aceita
    $mail->Port = 587;                                    // Porta TCP para conectar

    //Destinatários
    $mail->setFrom('felipeesousa.92@gmail.com', 'Mailer');
	$mail->addAddress($mensagem-> __get('para'));     // Adicionar um destinatári    
	//$mail->addReplyTo('info@example.com', 'Information');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');

    //Anexos
    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Adicionar Anexos
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Nome opcional

    //Conteúdo
    $mail->isHTML(true);                                  // Definir formato de email para HTML
    $mail->Subject = $mensagem-> __get('assunto');
    $mail->Body    = $mensagem-> __get('mensagem');;
    $mail->AltBody = 'É necessário utilizar um client que suporte HTML';

	$mail->send();
	$mensagem->status ['codigo_status'] = 1;
	$mensagem->status ['descricao_status'] = 'E-mail enviado com sucesso';
    
} 	catch (Exception $e) {
	$mensagem->status  ['codigo_status'] = 2;
	$mensagem->status  ['descricao_status'] = 'Não foi possível enviar este e-mail! Por favor tente novamente mis tarde.' . $mail->ErrorInfo;
	
} 
?>


<html>
	<head>
		<meta charset="utf-8" />
    	<title>App Mail Send</title>

    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

	</head>

	<body>

		<div class="container"> 

			<div class="py-3 text-center">
				<img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
				<h2>Send Mail</h2>
				<p class="lead">Seu app de envio de e-mails particular!</p>
			</div>

			<div class="row">
				<div class="col-md-12"> 

				<?php  if($mensagem->status['codigo_status'] == 1){  ?>
					<div class="container">
						<h1 class="display-4 text-success"> Sucesso </h1>
						<p> <?php $mensagem->status['descricao_status'] ?> </p>
						<a href="index.php" class="btn btn-success btn-lg mt-5 text-white"> Voltar </a>
					</div>
				<?php } ?>

				<?php  if($mensagem->status['codigo_status'] == 2){  ?>
					<div class="container">
						<h1 class="display-4 text-danger"> Ops! </h1>
						<p> <?php $mensagem->status['descricao_status'] ?> </p>
						<a href="index.php" class="btn btn-success btn-lg mt-5 text-white"> Voltar </a>
					</div>
				<?php } ?>
				
				</div>

			
			 </div>
		
		
		
		
		</div>

	</body>
</html>
		

	