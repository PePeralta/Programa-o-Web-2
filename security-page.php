<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user'])){
    header('Location: login.php');
    exit;
}

if(isset($_POST['submit'])) {

    if (empty($_POST['atual']) && empty($_POST['nova']) && empty($_POST['confirmarNova'])) {
        echo "<script>alert('Preencha todos os campos.');</script>";
    } else {
        if ($_POST['nova'] != $_POST['confirmarNova']) {
            echo "<script>alert('As palavras-passe não coincidem.');</script>";
        } else {

            $atual = sha1($_POST['atual']);
            $nova = sha1($_POST['nova']);

            $sql = "SELECT password FROM users WHERE id=" . $_SESSION['user'];
            $verificarpassword = mysqli_query($conexao, $sql);

            if ($verificarpassword && mysqli_num_rows($verificarpassword) == 1) {
                $row = mysqli_fetch_assoc($verificarpassword);

                if ($atual != $row['password']) {
                    echo "<script>alert('Password atual incorreta.');</script>";
                } else {
                    $sql_update = "UPDATE users SET password='$nova' WHERE id=" . $_SESSION['user'];
                    mysqli_query($conexao, $sql_update);
                    echo "<script>alert('Password alterada com sucesso');</script>";
                }
            }

        }
    }
}

if(isset($_POST['delete'])){
    header('Location: account-delete.php');
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
        <div class="container-xl px-4 mt-4">
        <hr class="mt-0 mb-4">
        <div class="row">
            <div class="col-lg-8">
                <!-- Change password card-->
                <div class="card mb-4">
                    <div class="card-header">Trocar Palavra-Passe</div>
                    <div class="card-body">
                        <form method= "POST">
                            <!-- Form Group (current password)-->
                            <div class="mb-3">
                                <label class="small mb-1" for="currentPassword">Palavra-Passe Atual</label>
                                <input class="form-control" name="atual" type="password" placeholder="Introduz a tua palavra-passe">
                            </div>
                            <!-- Form Group (new password)-->
                            <div class="mb-3">
                                <label class="small mb-1" for="newPassword">Nova Palavra-Passe</label>
                                <input class="form-control" name="nova" type="password" placeholder="Introduz a nova palavra-passe">
                            </div>
                            <!-- Form Group (confirm password)-->
                            <div class="mb-3">
                                <label class="small mb-1" for="confirmPassword">Confirmar Palavra-Passe</label>
                                <input class="form-control" name="confirmarNova" type="password" placeholder="Confirma a nova palavra-passe">
                            </div>
                            <button class="btn btn-primary" type="submit" name="submit">Salvar</button>
                        </form>
                    </div>
                </div> 
            </div>
            <div class="col-lg-4">
                <!-- Delete account card-->
                <div class="card mb-4">
                    <div class="card-header">Eliminar a conta</div>
                    <form method="post">
                      <div class="card-body">
                          <p>Apagar a tua conta é permanente e não pode ser desfeita. Se tiver a certeza que deseja apagar a sua conta clique no botão em baixo.</p>
                          <button class="btn btn-danger-soft text-danger" name="delete" type="submit">Eu compreendo, quero apagar a minha conta</button>
                      </div>
                  </form>
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