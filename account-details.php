<?php
  session_start();
  include 'db.php';
  if(!isset($_SESSION['user'])){
    header('Location: login.php');
  }

  $id = $_SESSION['user'];

  $sql = "SELECT username, email, primeiro_nome, ultimo_nome, localizacao, telemovel, data_nascimento FROM users WHERE id = '$id'";
  $verificarlogin = mysqli_query($conexao, $sql);

  $row = mysqli_fetch_assoc($verificarlogin);

  $username = $row['username'];
  $email = $row['email'];
  $primeiro_nome = $row['primeiro_nome'];
  $ultimo_nome = $row['ultimo_nome'];
  $localizacao = $row['localizacao'];
  $telemovel = $row['telemovel'];
  $data_nascimento = $row['data_nascimento'];

  if (isset($_POST['save'])) {

    if (empty($_POST['primeiro_nome']) || empty($_POST['ultimo_nome']) || empty($_POST['username']) || empty($_POST['localizacao']) || empty($_POST['telemovel']) || empty($_POST['data_nascimento'])){
        echo "<script>alert('Preencha todos os campos');</script>";
    }else {
    $primeiro_nome = $_POST['primeiro_nome'];
    $ultimo_nome = $_POST['ultimo_nome'];
    $username = $_POST['username'];
    $localizacao = $_POST['localizacao'];
    $telemovel = $_POST['telemovel'];
    $data_nascimento = $_POST['data_nascimento'];

    $sql = "UPDATE users SET primeiro_nome='$primeiro_nome', ultimo_nome='$ultimo_nome', username='$username', localizacao='$localizacao', telemovel='$telemovel', data_nascimento='$data_nascimento' WHERE id='$id'";

    if (mysqli_query($conexao, $sql)) {
        echo "<script>alert('Dados atualizados com sucesso');</script>";
    } else {
        echo "<script>alert('Erro ao atualizar dados');</script>";
    }
}


}

if(isset($_POST['sair'])){
  header('Location: session_destroy.php');
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Aroma Shop - Cart</title>
	<link rel="icon" href="img/Fevicon.png" type="image/png">
  <link rel="stylesheet" href="vendors/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="vendors/fontawesome/css/all.min.css">
	<link rel="stylesheet" href="vendors/themify-icons/themify-icons.css">
	<link rel="stylesheet" href="vendors/linericon/style.css">
  <link rel="stylesheet" href="vendors/owl-carousel/owl.theme.default.min.css">
  <link rel="stylesheet" href="vendors/owl-carousel/owl.carousel.min.css">
  <link rel="stylesheet" href="vendors/nice-select/nice-select.css">
  <link rel="stylesheet" href="vendors/nouislider/nouislider.min.css">
  <link rel="stylesheet" href="css/estilos.css">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <!--================ Start Header Menu Area =================-->
	<header class="header_area">
    <div class="main_menu">
      <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
          <a class="navbar-brand logo_h" href="index.php"><img src="img/logo.png" alt=""></a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <div class="collapse navbar-collapse offset" id="navbarSupportedContent">
            <ul class="nav navbar-nav menu_nav ml-auto mr-auto">
              <li class="nav-item"><a class="nav-link" href="category.php">Loja</a></li>
							<li class="nav-item submenu dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Conta</a>
                <ul class="dropdown-menu">
                  <li class="nav-item"><a class="nav-link" href="account-details.php">Perfil</a></li>
                  <li class="nav-item"><a class="nav-link" href="billing-page.php">Pagamentos</a></li>
                  <li class="nav-item"><a class="nav-link" href="security-page.php">Segurança</a></li>
                </ul>
              </li>
              <li class="nav-item"><a class="nav-link" href="contact.php">Contactos
              </a></li>
            </ul>

            <ul class="nav-shop">
              <li class="nav-item"><button><a href="cart.php"><i class="ti-shopping-cart"></i><span class="nav-shop__circle">3</span></a></button> </li>
            </ul>
          </div>
        </div>
      </nav>
    </div>
  </header>
	<!--================ End Header Menu Area =================-->
        <div style="margin-left:25%;">
            <div class="container-xl px-4 mt-4">
                <div class="col-xl-8">
                    <!-- Account details card-->
                    <div class="card mb-4">
                        <div class="card-header">Detalhes da Conta</div>
                        <div class="card-body">
                            <form method="post">
                                <!-- Form Row-->
                                <div class="row gx-3 mb-3">
                                    <!-- Form Group (first name)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputFirstName">Primeiro Nome</label>
                                        <input class="form-control" name="primeiro_nome" type="text" placeholder="Introduza o seu primeirno nome" value="<?php echo $primeiro_nome;?>">
                                    </div>
                                    <!-- Form Group (last name)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputLastName">Último Nome</label>
                                        <input class="form-control" name="ultimo_nome" type="text" placeholder="Introduza o seu último nome" value="<?php echo $ultimo_nome; ?>">
                                    </div>
                                </div>
                                <!-- Form Row        -->
                                <div class="row gx-3 mb-3">
                                    <!-- Form Group (organization name)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputOrgName">Nome de Utilizador</label>
                                        <input class="form-control" name="username" type="text" placeholder="Introduza o seu nome de utilizador" value="<?php echo $username; ?>">
                                    </div>
                                    <!-- Form Group (location)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputLocation">Localização</label>
                                        <input class="form-control" name="localizacao" type="text" placeholder="Introduza a sua localização" value="<?php echo $localizacao; ?>">
                                    </div>
                                </div>
                                <!-- Form Group (email address)-->
                                <div class="mb-3">
                                    <label class="small mb-1" for="inputEmailAddress">Endereço de Email</label>
                                    <input class="form-control" name="email" type="email" placeholder="Introduza o seu endereço de email" value="<?php echo $email; ?>" disabled>
                                </div>
                                <!-- Form Row-->
                                <div class="row gx-3 mb-3">
                                    <!-- Form Group (phone number)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputPhone">Nº de Telemóvel</label>
                                        <input class="form-control" name="telemovel" type="tel" placeholder="Introduza o seu número de telemóvel" value="<?php echo $telemovel; ?>">
                                    </div>
                                    <!-- Form Group (birthday)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputBirthday">Data de Nascimento</label>
                                        <input class="form-control" name="data_nascimento" type="text" name="birthday" placeholder="Introduza a sua data de nascimento" value="<?php echo $data_nascimento; ?>">
                                    </div>
                                </div>
                                <!-- Save changes button-->
                                <button class="btn btn-primary" name="save" type="submit">Guardar mudanças</button>
                                <button class="btn btn-danger-soft text-danger" type="submit" name="sair">Sair da minha conta</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

  <!--================ Start footer Area  =================-->	
	<footer>
		<div class="footer-area">
			<div class="container">
				<div class="row section_gap">
					<div class="offset-lg-1 col-lg-2 col-md-6 col-sm-6">
						<div class="single-footer-widget tp_widgets">
							<h4 class="footer_title">Ligações Rápidas</h4>
							<ul class="list">
								<li><a href="index.php">Página principal</a></li>
								<li><a href="category.php">Produtos</a></li>
								<li><a href="account-details.php">Conta</a></li>
								<li><a href="contact.php">Contactos</a></li>
							</ul>
						</div>
					</div>
					<div class="offset-lg-1 col-lg-3 col-md-6 col-sm-6">
						<div class="single-footer-widget tp_widgets">
							<h4 class="footer_title">Contacta-nos</h4>
							<div class="ml-40">
								<p class="sm-head">
									<span class="fa fa-location-arrow"></span>
									Alenquer
								</p>
								<p>Rua das Flores, Lote 31, Carregado</p>
	
								<p class="sm-head">
									<span class="fa fa-phone"></span>
									Telemóvel
								</p>
								<p>
									+351 931 545 012 <br>
									+351 963 861 296
								</p>
	
								<p class="sm-head">
									<span class="fa fa-envelope"></span>
									Email
								</p>
								<p>
									240001218@esg.ipsantarem.pt<br>
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
  </footer>

	<!--================ End footer Area  =================-->



  <script src="vendors/jquery/jquery-3.2.1.min.js"></script>
  <script src="vendors/bootstrap/bootstrap.bundle.min.js"></script>
  <script src="vendors/skrollr.min.js"></script>
  <script src="vendors/owl-carousel/owl.carousel.min.js"></script>
  <script src="vendors/nice-select/jquery.nice-select.min.js"></script>
  <script src="vendors/jquery.ajaxchimp.min.js"></script>
  <script src="vendors/mail-script.js"></script>
  <script src="js/main.js"></script>
</body>
</html>