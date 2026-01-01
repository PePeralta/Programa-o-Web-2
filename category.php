<?php
include 'db.php';
session_start();

function contarProdutos($conexao, $campo, $valores, $condicoesAtuais = []) {
    $sql = "SELECT ";
    foreach ($valores as $valor) {
        $sql .= "COUNT(CASE WHEN $campo = '".mysqli_real_escape_string($conexao, $valor)."' THEN 1 END) AS `$valor`, ";
    }
    $sql = rtrim($sql, ", ");
    $sql .= " FROM produtos p WHERE p.ativo = '1'";

    if (!empty($condicoesAtuais)) {
        $sql .= " AND " . implode(" AND ", $condicoesAtuais);
    }

    $res = mysqli_query($conexao, $sql);
    return mysqli_fetch_assoc($res);
}

$condicoes = [];

if (isset($_GET['categorias']) && !empty($_GET['categorias'])) {
    $categoria = mysqli_real_escape_string($conexao, $_GET['categorias']);
    $condicoes['categorias'] = "p.categoria = '$categoria'";
}

if (isset($_GET['cor']) && !empty($_GET['cor'])) {
    $cor = mysqli_real_escape_string($conexao, $_GET['cor']);
    $condicoes['cor'] = "p.cor = '$cor'";
}

if (isset($_GET['marca']) && !empty($_GET['marca'])) {
    $marca = mysqli_real_escape_string($conexao, $_GET['marca']);
    $condicoes['marca'] = "p.marca = '$marca'";
}

$categorias = ['Eletronica', 'Informatica', 'Telemoveis', 'Acessorios', 'Gaming', 'Casas'];
$marcas = ['Apple', 'Microsoft', 'Google', 'Samsung', 'Asus', 'Acer', 'Sony'];
$cores = ['Amarelo','Laranja','Branco','Azul','Castanho','Cinzento','Rosa'];

$condicoesCategoria = $condicoes;
unset($condicoesCategoria['categorias']);

$condicoesMarca = $condicoes;
unset($condicoesMarca['marca']);

$condicoesCor = $condicoes;
unset($condicoesCor['cor']);

$contagemCategoria = contarProdutos($conexao, 'categoria', $categorias, $condicoesMarca + $condicoesCor);
$contagemMarcas    = contarProdutos($conexao, 'marca', $marcas, $condicoesCategoria + $condicoesCor);
$contagemCores     = contarProdutos($conexao, 'cor', $cores, $condicoesCategoria + $condicoesMarca);

$sql = "
    SELECT p.id, p.categoria, p.nome, p.preco, pi.imagem
    FROM produtos p
    LEFT JOIN produtos_imagens pi
    ON p.id = pi.produto_id AND pi.principal = 1
    WHERE p.ativo = '1'
";

if (!empty($condicoes)) {
    $sql .= " AND " . implode(" AND ", $condicoes);
}

$result = mysqli_query($conexao, $sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Aroma Shop - Category</title>
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

	<!-- ================ category section start ================= -->		  
  <section class="section-margin--small mb-5">
    <div class="container">
      <div class="row">
          <div class="col-xl-3 col-lg-4 col-md-5">
            <form method="GET" id="filtros">
            <div class="sidebar-categories">
              <div class="head">Procurar por categoria</div>
              <ul class="main-categories">
                <li class="common-filter">
                  <ul>
                    <li class="filter-list">
                      <input onchange="this.form.submit()" class="pixel-radio" type="radio" id="eletronica" name="categorias" value="Eletronica" <?php if (isset($_GET['categorias']) && $_GET['categorias'] == 'Eletronica') echo 'checked'; ?>>
                      <label for="eletronica">Eletrónica<span> <?php echo $contagemCategoria['Eletronica']; ?></span></label>
                    </li>
                    <li class="filter-list">
                      <input onchange="this.form.submit()" class="pixel-radio" type="radio" id="informatica" name="categorias" value="Informatica" <?php if (isset($_GET['categorias']) && $_GET['categorias'] == 'Informatica') echo 'checked'; ?>>
                      <label for="informatica">Informática<span> <?php echo $contagemCategoria['Informatica']; ?></span></label>
                    </li>
                    <li class="filter-list">
                      <input onchange="this.form.submit()" class="pixel-radio" type="radio" id="telemoveis" name="categorias" value="Telemoveis" <?php if (isset($_GET['categorias']) && $_GET['categorias'] == 'Telemoveis') echo 'checked'; ?>>
                      <label for="telemoveis">Telemóveis<span> <?php echo $contagemCategoria['Telemoveis']; ?></span></label>
                    </li>
                    <li class="filter-list">
                      <input onchange="this.form.submit()" class="pixel-radio" type="radio" id="acessorios" name="categorias" value="Acessorios" <?php if (isset($_GET['categorias']) && $_GET['categorias'] == 'Acessorios') echo 'checked'; ?>>
                      <label for="acessorios">Acessórios<span> <?php echo $contagemCategoria['Acessorios'] ?></span></label>
                    </li>
                    <li class="filter-list">
                      <input onchange="this.form.submit()" class="pixel-radio" type="radio" id="gaming" name="categorias" value="Gaming" <?php if (isset($_GET['categorias']) && $_GET['categorias'] == 'Gaming') echo 'checked'; ?>>
                      <label for="gaming">Gaming<span> <?php echo $contagemCategoria['Gaming']; ?></span></label>
                    </li>
                    <li class="filter-list">
                      <input onchange="this.form.submit()" class="pixel-radio" type="radio" id="casa" name="categorias" value="Casas" <?php if (isset($_GET['categorias']) && $_GET['categorias'] == 'Casas') echo 'checked'; ?>>
                      <label for="casa">Casa & Escritório<span> <?php echo $contagemCategoria['Casas'] ?></span></label>
                    </li>
                  </ul>
                </li>
              </ul>
            </div>
            <div class="sidebar-filter">
              <div class="top-filter-head">Filtros</div>
              <div class="common-filter">
                <div class="head">Marcas</div>
                  <ul>
                    <li class="filter-list"><input onchange="this.form.submit()" class="pixel-radio" type="radio" id="apple" name="marca" value="Apple" <?php if (isset($_GET['marca']) && $_GET['marca'] == 'Apple') echo 'checked'; ?>><label for="apple">Apple<span> <?php echo $contagemMarcas['Apple']; ?></span></label></li>
                    <li class="filter-list"><input onchange="this.form.submit()" class="pixel-radio" type="radio" id="microsoft" name="marca" value="Microsoft" <?php if (isset($_GET['marca']) && $_GET['marca'] == 'Microsoft') echo 'checked'; ?>><label for="microsoft">Microsoft<span> <?php echo $contagemMarcas['Microsoft']; ?></span></label></li>
                    <li class="filter-list"><input onchange="this.form.submit()" class="pixel-radio" type="radio" id="google" name="marca" value="Google" <?php if (isset($_GET['marca']) && $_GET['marca'] == 'Google') echo 'checked'; ?>><label for="google">Google<span> <?php echo $contagemMarcas['Google']; ?></span></label></li>
                    <li class="filter-list"><input onchange="this.form.submit()" class="pixel-radio" type="radio" id="samsung" name="marca" value="Samsung" <?php if (isset($_GET['marca']) && $_GET['marca'] == 'Samsung') echo 'checked'; ?>><label for="samsung">Samsung<span> <?php echo $contagemMarcas['Samsung']; ?></span></label></li>
                    <li class="filter-list"><input onchange="this.form.submit()" class="pixel-radio" type="radio" id="asus" name="marca" value="Asus" <?php if (isset($_GET['marca']) && $_GET['marca'] == 'Asus') echo 'checked'; ?>><label for="asus">Asus<span> <?php echo $contagemMarcas['Asus']; ?></span></label></li>
                    <li class="filter-list"><input onchange="this.form.submit()" class="pixel-radio" type="radio" id="acer" name="marca" value="Acer" <?php if (isset($_GET['marca']) && $_GET['marca'] == 'Acer') echo 'checked'; ?>><label for="acer">Acer<span> <?php echo $contagemMarcas['Acer']; ?></span></label></li>
                    <li class="filter-list"><input onchange="this.form.submit()" class="pixel-radio" type="radio" id="sony" name="marca" value="Sony" <?php if (isset($_GET['marca']) && $_GET['marca'] == 'Sony') echo 'checked'; ?>><label for="sony">Sony<span> <?php echo $contagemMarcas['Sony']; ?></span></label></li>
                  </ul>
                </div>
              <div class="common-filter">
                <div class="head">Cor</div>
                  <ul name="color">
                    <li class="filter-list"><input onchange="this.form.submit()" class="pixel-radio" type="radio" id="amarelo" name="cor" value="Amarelo" <?php if (isset($_GET['cor']) && $_GET['cor'] == 'Amarelo') echo 'checked'; ?>><label for="amarelo">Amarelo<span> <?php echo $contagemCores['Amarelo']; ?></span></label></li>
                    <li class="filter-list"><input onchange="this.form.submit()" class="pixel-radio" type="radio" id="laranja" name="cor" value="Laranja" <?php if (isset($_GET['cor']) && $_GET['cor'] == 'Laranja') echo 'checked'; ?>><label for="Laranja">Laranja<span> <?php echo $contagemCores['Laranja']; ?></span></label></li>
                    <li class="filter-list"><input onchange="this.form.submit()" class="pixel-radio" type="radio" id="branco" name="cor" value="Branco" <?php if (isset($_GET['cor']) && $_GET['cor'] == 'Branco') echo 'checked'; ?>><label for="branco">Branco<span> <?php echo $contagemCores['Branco']; ?></span></label></li>
                    <li class="filter-list"><input onchange="this.form.submit()" class="pixel-radio" type="radio" id="azul" name="cor" value="Azul" <?php if (isset($_GET['cor']) && $_GET['cor'] == 'Azul') echo 'checked'; ?>><label for="azul">Azul<span> <?php echo $contagemCores['Azul']; ?></span></label></li>
                    <li class="filter-list"><input onchange="this.form.submit()" class="pixel-radio" type="radio" id="castanho" name="cor" value="Castanho" <?php if (isset($_GET['cor']) && $_GET['cor'] == 'Castanho') echo 'checked'; ?>><label for="castanho">Castanho<span> <?php echo $contagemCores['Castanho']; ?></span></label></li>
                    <li class="filter-list"><input onchange="this.form.submit()" class="pixel-radio" type="radio" id="cinzento" name="cor" value="Cinzento" <?php if (isset($_GET['cor']) && $_GET['cor'] == 'Cinzento') echo 'checked'; ?>><label for="cinzento">Cinzento<span> <?php echo $contagemCores['Cinzento']; ?></span></label></li>
                    <li class="filter-list"><input onchange="this.form.submit()" class="pixel-radio" type="radio" id="rosa" name="cor" value="Rosa" <?php if (isset($_GET['cor']) && $_GET['cor'] == 'Rosa') echo 'checked'; ?>><label for="rosa">Rosa<span> <?php echo $contagemCores['Rosa']; ?></span></label></li>
                  </ul>
                </div>
              <div class="common-filter">
                <div class="head">Preço</div>
                <div class="price-range-area">
                  <div id="price-range"></div>
                  <div class="value-wrapper d-flex">
                    <div class="price">Preço:</div>
                    <span>€</span>
                    <div id="lower-value"></div>
                    <div class="to">to</div>
                    <span>€</span>
                    <div id="upper-value"></div>
                  </div>
                </div>
              </div>
            </div>
            </form>
          </div>
        <div class="col-xl-9 col-lg-8 col-md-7">
          <!-- Start Filter Bar -->
          <div class="filter-bar d-flex flex-wrap align-items-center">
            <div class="sorting">
              <select>
                <option value="1">Relevância</option>
                <option value="1">Preço (mais alto)</option>
                <option value="1">Preço (mais baixo)</option>
              </select>
            </div>
            <div class="sorting mr-auto">
              <select>
                <option value="1">Mostrar 12</option>
                <option value="1">Mostrar 24</option>
                <option value="1">Mostrar 48</option>
              </select>
            </div>
            <div>
              <div class="input-group filter-bar-search">
                <input type="text" placeholder="Pesquisar...">
                <div class="input-group-append">
                  <button type="button"><i class="ti-search"></i></button>
                </div>
              </div>
            </div>
          </div>
          <!-- End Filter Bar -->
          <!-- Start Best Seller -->
          <section class="lattest-product-area pb-40 category-list">
            <div class="row">
                <?php 
                $sql = "
                    SELECT p.id, p.categoria, p.nome, p.preco, pi.imagem
                    FROM produtos p
                    LEFT JOIN produtos_imagens pi
                    ON p.id = pi.produto_id AND pi.principal = 1
                    WHERE p.ativo = '1'
                ";

                if (!empty($condicoes)) {
                    $sql .= " AND " . implode(" AND ", $condicoes);
                }

                $result = mysqli_query($conexao, $sql);


                if(mysqli_num_rows($result) > 0){
                    while($produto = mysqli_fetch_assoc($result)){
                        ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card text-center card-product">
                                <div class="card-product__img">
                                    <img class="card-img" src="imagens-produtos/<?php echo $produto['imagem']; ?>" alt="<?php echo $produto['nome']; ?>" style="height:200px;">
                                    <ul class="card-product__imgOverlay">
                                        <li><button><i class="ti-search"></i></button></li>
                                        <li><button><i class="ti-shopping-cart"></i></button></li>
                                    </ul>
                                </div>
                                <div class="card-body">
                                    <p><?php echo $produto['categoria']; ?></p>
                                    <h4 class="card-product__title">
                                        <a href="single-product.php?produto_id=<?php echo $produto['id']; ?>">
                                            <?php echo $produto['nome']; ?>
                                        </a>
                                    </h4>
                                    <p class="card-product__price">$<?php echo number_format($produto['preco'], 2); ?></p>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p>Nenhum produto ativo encontrado.</p>";
                }
                ?>
                </div>
          </section>
          <!-- End Best Seller -->
        </div>
      </div>
    </div>
  </section>
	<!-- ================ category section end ================= -->		  			  

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
	<!--================ End footer Area  =================-->
  
  <script src="vendors/jquery/jquery-3.2.1.min.js"></script>
  <script src="vendors/bootstrap/bootstrap.bundle.min.js"></script>
  <script src="vendors/skrollr.min.js"></script>
  <script src="vendors/owl-carousel/owl.carousel.min.js"></script>
  <script src="vendors/nice-select/jquery.nice-select.min.js"></script>
  <script src="vendors/nouislider/nouislider.min.js"></script>
  <script src="vendors/jquery.ajaxchimp.min.js"></script>
  <script src="vendors/mail-script.js"></script>
  <script src="js/main.js"></script>
</body>
</html>