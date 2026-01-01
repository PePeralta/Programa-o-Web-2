<?php
require 'db.php';

if(isset($_POST['submit'])){;

	if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['confirmPassword'])){
		echo "<script>alert('Preencha todos os dados');</script>";
	}else{
		if($_POST['password'] != $_POST['confirmPassword']){
			echo "<script>alert('As palavavras passes não coincidem.');</script>";
		}else{

			$name = $_POST['name'];
			$email = $_POST['email'];
			$password = sha1($_POST['password']);

			$sql = "SELECT id FROM users WHERE email='$email'";
			$res = mysqli_query($conexao, $sql);

			if(mysqli_num_rows($res) > 0){
				echo "<script>alert('Já existe uma conta com esse email.');</script>";
			}else{

				$sql_insert = "INSERT INTO users (username, email, password) VALUES ('$name', '$email', '$password')";
				if (mysqli_query($conexao, $sql_insert)) {
					header('Location: login.php');
				}else{
					echo "<script>alert('Erro ao criar a conta.');</script>";
				}
			}

		}

	}

}


?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Aroma Shop - Login</title>
	<link rel="icon" href="img/Fevicon.png" type="image/png">
  <link rel="stylesheet" href="vendors/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="vendors/fontawesome/css/all.min.css">
	<link rel="stylesheet" href="vendors/themify-icons/themify-icons.css">
	<link rel="stylesheet" href="vendors/linericon/style.css">
  <link rel="stylesheet" href="vendors/owl-carousel/owl.theme.default.min.css">
  <link rel="stylesheet" href="vendors/owl-carousel/owl.carousel.min.css">
  <link rel="stylesheet" href="vendors/nice-select/nice-select.css">
  <link rel="stylesheet" href="vendors/nouislider/nouislider.min.css">

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
                <?php
                    if(isset($_SESSION['user'])){
                      echo '
                          <li class="nav-item"><a class="nav-link" href="account-details.php">Perfil</a></li>
                          <li class="nav-item"><a class="nav-link" href="billing-page.php">Pagamentos</a></li>
                          <li class="nav-item"><a class="nav-link" href="security-page.php">Segurança</a></li>
                      ';
                    }else{
                      echo '
                          <li class="nav-item"><a class="nav-link" href="login.php">Entrar</a></li>
                          <li class="nav-item"><a class="nav-link" href="register.php">Registar</a></li>
                      ';
                    }
                ?>
                </ul>
              </li>
              <li class="nav-item"><a class="nav-link" href="contact.php">Contactos
              </a></li>
            </ul>

            <ul class="nav-shop">
              <?php
                if(isset($_SESSION['user'])){
                  echo '<li class="nav-item"><button><a href="cart.php"><i class="ti-shopping-cart"></i><span class="nav-shop__circle">3</span></a></button> </li>';
                }
              ?>
            </ul>
          </div>
        </div>
      </nav>
    </div>
  </header>
	<!--================ End Header Menu Area =================-->
  
  <!--================Login Box Area =================-->
	<section class="login_box_area section-margin">
		<div class="container">
			<div class="row">
				<div class="col-lg-6">
					<div class="login_box_img">
						<div class="hover">
							<h4>Já tens uma conta?</h4>
							<p>Se já tens uma conta criada, então clica neste botão neste botão em baixo</p>
							<a class="button button-account" href="login.php">Iniciar sessão</a>
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="login_form_inner register_form_inner">
						<h3>Criar conta</h3>
						<form class="row login_form" action="register.php" method="POST">
							<div class="col-md-12 form-group">
								<input type="text" class="form-control" id="name" name="name" placeholder="Nome de utlizador" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Nome de Utilizador'">
							</div>
							<div class="col-md-12 form-group">
								<input type="email" class="form-control" id="email" name="email" placeholder="Email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Email'">
              				</div>
              				<div class="col-md-12 form-group">
								<input type="text" class="form-control" id="password" name="password" placeholder="Palavra-passe" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Palavra-passe'">
              				</div>
              				<div class="col-md-12 form-group">
								<input type="text" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirmar Palavra-passe" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Confirmar Palavra-passe'">
							</div>
							<div class="col-md-12 form-group">
							</div>
							<div class="col-md-12 form-group">
								<button type="submit" name="submit" class="button button-login w-100">Registar</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!--================End Login Box Area =================-->



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