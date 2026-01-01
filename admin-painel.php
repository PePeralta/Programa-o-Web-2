<?php
  session_start();
  include 'db.php';
  if(!isset($_SESSION['user'])){
    header('Location: login.php');
  }

  if(!isset($_SESSION['tipo'])){
    header('Location: login.php');
  }

  if($_SESSION['tipo'] != "administrador"){
    header('Location: login.php');
  }



  if(isset($_POST['save'])){
      // Parte dos produtos
      if(!empty($_POST['categoria_id'])){

        $nomeProduto = $_POST['nome'];
        $categoria = $_POST['categoria_id'];
        $preco = $_POST['preco'];
        $precopromocional = $_POST['precopromocional'];
        $altura = $_POST['altura'];
        $largura = $_POST['largura'];
        $comprimento = $_POST['comprimento'];
        $peso = $_POST['peso'];
        $descricao = $_POST['descricao'];
        $ativo = $_POST['ativo'];
        $stock = $_POST['stock'];
        $marca = $_POST['marca'];
        $cor = $_POST['cor'];

        $sql = "INSERT INTO produtos (nome, descricao, preco, preco_promocional, stock, categoria, peso, altura, largura, comprimento, ativo, marca, cor) VALUES ('$nomeProduto', '$descricao', '$preco', '$precopromocional', '$stock', '$categoria', '$peso', '$altura', '$largura', '$comprimento', '$ativo', '$marca', '$cor')";

        if (mysqli_query($conexao, $sql)){

          $produto_id = mysqli_insert_id($conexao);

          $pasta = 'imagens-produtos/';

          if (isset($_FILES['imagem_principal'])) {
            $nomeImagem = time() . '_' . $_FILES['imagem_principal']['name'];
            move_uploaded_file(
              $_FILES['imagem_principal']['tmp_name'],
              $pasta . $nomeImagem
            );

            $sqlImg = "INSERT INTO produtos_imagens (produto_id, imagem, principal) VALUES ('$produto_id', '$nomeImagem', 1)";
            mysqli_query($conexao, $sqlImg);

          }


          if(!empty($_FILES['imagens_secundarias']['name'][0])){

            foreach($_FILES['imagens_secundarias']['name'] as $i => $img) {

                $nomeImagem = time() . '_' . $img; // Corrigido aqui

                move_uploaded_file(
                    $_FILES['imagens_secundarias']['tmp_name'][$i],
                    $pasta . $nomeImagem // Corrigido aqui
                );

                $sqlImg = "INSERT INTO produtos_imagens (produto_id, imagem, principal) VALUES ('$produto_id', '$nomeImagem', 0)";
                mysqli_query($conexao, $sqlImg);

            }
        }






          echo "<script>alert('Produto e Imagens introduzidos com sucesso!');</script>";

        }else{
          echo "<script>alert('Deu errado ao introduzir o produto');</script>";
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
              <?php if($_SESSION['tipo'] == 'administrador'){ echo '<li class="nav-item"><a href="admin-painel.php">⚙️</a></li>';}?>
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
                        <div class="card-header">Administração</div>
                        <div class="card-body">
                            <form method="post" enctype="multipart/form-data">
                              <!-- Form Row-->
                                <div class="row gx-3 mb-3">
                                    <!-- Form Group (first name)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputFirstName">Nome</label>
                                        <input class="form-control" name="nome" type="text" placeholder="Nome do produto" required>
                                    </div>
                                    <!-- Form Group (last name)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputOrgName">Categoria</label>
                                        <select name="categoria_id" class="form-control" required>
                                            <option value="">Escolha uma categoria</option>
                                            <option value="Eletronica">Eletrónica</option>
                                            <option value="Informatica">Informática</option>
                                            <option value="Telemoveis">Telemóveis</option>
                                            <option value="Acessorios">Acessórios</option>
                                            <option value="Gaming">Gaming</option>
                                            <option value="Casas">Casa & Escritório</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Form Row        -->
                                <div class="row gx-3 mb-3">
                                    <!-- Form Group (organization name)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputLastName">Preço</label>
                                        <input class="form-control" name="preco" type="number" placeholder="Preço do produto" required>
                                    </div>                                    <!-- Form Group (location)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputBirthday">Preço Promocional</label>
                                        <input class="form-control" name="precopromocional" type="number" placeholder="Preço Promocional">
                                    </div>
                                </div>
                                <!-- Form Row-->
                                <div class="row gx-3 mb-3">
                                    <!-- Form Group (phone number)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputPhone">Altura</label>
                                        <input class="form-control" name="altura" type="number" placeholder="Altura">
                                    </div>
                                    <!-- Form Group (birthday)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputBirthday">Largura</label>
                                        <input class="form-control" name="largura" type="number" placeholder="Largura">
                                    </div>
                                </div>
                                <div class="row gx-3 mb-3">
                                    <!-- Form Group (phone number)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputPhone">Comprimento</label>
                                        <input class="form-control" name="comprimento" type="number" placeholder="Comprimento">
                                    </div>
                                    <!-- Form Group (birthday)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputLocation">Peso</label>
                                        <input class="form-control" name="peso" type="number" placeholder="Peso">
                                    </div>
                                </div>
                                <div class="row gx-3 mb-3">
                                    <!-- Form Group (phone number)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputPhone">Ativo ( 1- Sim | 2- Não )</label>
                                        <input class="form-control" name="ativo" type="number" placeholder="Ativo">
                                    </div>
                                    <!-- Form Group (birthday)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputLocation">Stock</label>
                                        <input class="form-control" name="stock" type="number" placeholder="Stock">
                                    </div>
                                </div>
                                <div class="row gx-3 mb-3">
                                    <!-- Form Group (phone number)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputOrgName">Marca</label>
                                        <select name="marca" class="form-control" required>
                                            <option value="Apple">Apple</option>
                                            <option value="Microsoft">Microsoft</option>
                                            <option value="Google">Google</option>
                                            <option value="Samsung">Samsung</option>
                                            <option value="Asus">Asus</option>
                                            <option value="Acer">Acer</option>
                                            <option value="Sony">Sony</option>
                                        </select>
                                    </div>
                                    <!-- Form Group (birthday)-->
                                    <div class="col-md-6">
                                        <label class="small mb-1" for="inputOrgName">Cores</label>
                                        <select name="cor" class="form-control" required>
                                            <option value="Amarelo">Amarelo</option>
                                            <option value="Laranja">Laranja</option>
                                            <option value="Branco">Branco</option>
                                            <option value="Preto">Azul</option>
                                            <option value="Castanho">Castanho</option>
                                            <option value="Cinzento">Cinzento</option>
                                            <option value="Rosa">Rosa</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Form Group (email address)-->
                                <div class="mb-3">
                                    <label class="small mb-1" for="inputEmailAddress">Descrição</label>
                                    <input class="form-control" name="descricao" type="text" placeholder="Descrição" required>
                                </div>
                                <div class="mb-3">
                                    <label class="small mb-1" for="inputEmailAddress">Imagem Principal</label>
                                    <input class="form-control" name="imagem_principal" type="file" required>
                                </div>
                                <div class="mb-3">
                                    <label class="small mb-1" for="inputEmailAddress">Imagens Secundárias</label>
                                    <input class="form-control" name="imagens_secundarias[]" type="file" multiple>
                                </div>
                                    <button class="btn btn-primary" name="save" type="submit">Guardar Produto</button> 
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