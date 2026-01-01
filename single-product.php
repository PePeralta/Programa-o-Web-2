<?php

include 'db.php';

session_start();


if(!isset($_GET['produto_id'])){
	header('Location: category.php');
}
$id = $_GET['produto_id'];
$sqlProduto = "SELECT nome, descricao, preco, stock, categoria, peso, altura, largura, comprimento, marca, cor 	FROM produtos WHERE id='$id'";

$produto = mysqli_query($conexao, $sqlProduto);

$row = mysqli_fetch_assoc($produto);

$nomeProduto = $row['nome'];
$descricao = $row['descricao'];
$preco = $row['preco'];
$stock = $row['stock'];
$categoria = $row['categoria'];
$peso = $row['peso'];
$altura = $row['altura'];
$largura = $row['largura'];
$comprimento = $row['comprimento'];
$marca = $row['marca'];
$cor = $row['cor'];

$sqlImagemPrincipal = "SELECT imagem FROM produtos_imagens WHERE produto_id='$id' AND principal = 1 LIMIT 1";
$resultImagem = mysqli_query($conexao, $sqlImagemPrincipal);
$imagemPrincipal = mysqli_fetch_assoc($resultImagem)['imagem'];


if (isset($_POST['review'])) {

    if (!isset($_SESSION['user'])) {
        echo "<script>alert('Tens de ter sessão iniciada');</script>";
    } else {

        $produto_id = $id;
        $user_id = $_SESSION['user'];
        $estrelas = $_POST['estrelas'];
		$comentar = $_POST['texto'];

        if (!empty($estrelas) && !empty($comentar)) {

            $sql = "INSERT INTO avaliacoes (produto_id, user_id, estrelas, comentario)
                    VALUES ('$produto_id', '$user_id', '$estrelas', '$comentar')";

            if (!mysqli_query($conexao, $sql)) {
                die(mysqli_error($conexao));
            }

        }
    }
}


$sqlMedia = "SELECT AVG(estrelas) as media FROM avaliacoes WHERE produto_id='$id'";
$resMedia = mysqli_query($conexao, $sqlMedia);
$media = round(mysqli_fetch_assoc($resMedia)['media'], 1);

$sqlComentarios = "SELECT * FROM avaliacoes WHERE produto_id = '$id'";
$resComentarios = mysqli_query($conexao, $sqlComentarios);
$totalReviews = mysqli_num_rows($resComentarios);
$sqlEstrelas = "
    SELECT
        COUNT(CASE WHEN estrelas = 0 THEN 1 END) AS zero,
        COUNT(CASE WHEN estrelas = 1 THEN 1 END) AS um,
        COUNT(CASE WHEN estrelas = 2 THEN 1 END) AS dois,
        COUNT(CASE WHEN estrelas = 3 THEN 1 END) AS tres,
        COUNT(CASE WHEN estrelas = 4 THEN 1 END) AS quatro,
        COUNT(CASE WHEN estrelas = 5 THEN 1 END) AS cinco
    FROM avaliacoes
    WHERE produto_id = '$id'
";
$resEstrelas = mysqli_query($conexao, $sqlEstrelas);
$contagem = mysqli_fetch_assoc($resEstrelas);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Aroma Shop - Product Details</title>
	<link rel="icon" href="img/Fevicon.png" type="image/png">
  <link rel="stylesheet" href="vendors/bootstrap/bootstrap.min.css">
  <link rel="stylesheet" href="vendors/fontawesome/css/all.min.css">
	<link rel="stylesheet" href="vendors/themify-icons/themify-icons.css">
	<link rel="stylesheet" href="vendors/linericon/style.css">
  <link rel="stylesheet" href="vendors/nice-select/nice-select.css">
  <link rel="stylesheet" href="vendors/owl-carousel/owl.theme.default.min.css">
  <link rel="stylesheet" href="vendors/owl-carousel/owl.carousel.min.css">

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

  <!--================Single Product Area =================-->
  <div class="product_image_area">
		<div class="container">
			<div class="row s_product_inner">
				<div class="col-lg-6">
					<div class="owl-carousel owl-theme s_Product_carousel">
						<div class="single-prd-item">
							<img class="img-fluid" src="imagens-produtos/<?php echo $imagemPrincipal; ?>" style="height="">
						</div>
						<!-- <div class="single-prd-item">
							<img class="img-fluid" src="img/category/s-p1.jpg" alt="">
						</div>
						<div class="single-prd-item">
							<img class="img-fluid" src="img/category/s-p1.jpg" alt="">
						</div> -->
					</div>
				</div>
				<div class="col-lg-5 offset-lg-1">
					<div class="s_product_text">
						<h3><?php echo $nomeProduto; ?></h3>
						<h2><?php echo $preco . '€'; ?></h2>
						<ul class="list">
							<li><span>Categoria</span> : <?php echo $categoria; ?></a></li>
							<li><span>Em stock</span> : <?php echo $stock; ?></a></li>
						</ul>
						<p><?php echo $descricao; ?></p>
						<div class="product_count">
              				<label for="qty">Quantidade:</label>
              				<button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst )) result.value++;return false;" class="increase items-count" type="button"><i class="ti-angle-left"></i></button>
							<input type="text" name="qty" id="sst" size="2" maxlength="12" value="1" title="Quantity:" class="input-text qty">
							<button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst ) &amp;&amp; sst > 0 ) result.value--;return false;" class="reduced items-count" type="button"><i class="ti-angle-right"></i></button>
							<a class="button primary-btn" href="#">Adicionar ao carrinho</a>               
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--================End Single Product Area =================-->

	<!--================Product Description Area =================-->
	<section class="product_description_area">
		<div class="container">
			<ul class="nav nav-tabs" id="myTab" role="tablist">
				<li class="nav-item">
					<a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Descrição</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile"
					 aria-selected="false">Especificações</a>
				</li>
				<li class="nav-item">
					<a class="nav-link active" id="review-tab" data-toggle="tab" href="#review" role="tab" aria-controls="review"
					 aria-selected="false">Avaliações</a>
				</li>
			</ul>
			<div class="tab-content" id="myTabContent">
				<div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
					<p><?php echo $descricao ; ?></p>
				</div>
				<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
					<div class="table-responsive">
						<table class="table">
							<tbody>
								<tr>
									<td>
										<h5>Peso</h5>
									</td>
									<td>
										<h5><?php echo $peso . ' g' ;?></h5>
									</td>
								</tr>
								<tr>
									<td>
										<h5>Altura</h5>
									</td>
									<td>
										<h5><?php echo $altura . 'mm' ; ?></h5>
									</td>
								</tr>
								<tr>
									<td>
										<h5>Largura</h5>
									</td>
									<td>
										<h5><?php echo $largura . 'mm'; ?></h5>
									</td>
								</tr>
								<tr>
									<td>
										<h5>Comprimento</h5>
									</td>
									<td>
										<h5><?php echo $comprimento . 'mm' ; ?></h5>
									</td>
								</tr>
								<tr>
									<td>
										<h5>Marca</h5>
									</td>
									<td>
										<h5><?php echo $marca; ?></h5>
									</td>
								</tr>
								<tr>
									<td>
										<h5>Cor</h5>
									</td>
									<td>
										<h5><?php echo $cor;?></h5>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="tab-pane fade show active" id="review" role="tabpanel" aria-labelledby="review-tab">
					<div class="row">
						<div class="col-lg-6">
							<div class="row total_rate">
								<div class="col-6">
									<div class="box_total">
										<h5>Avaliação Global</h5>
										<h4><?php echo $media;?></h4>
										<h6>(<?php echo $totalReviews . ' Avaliações' ;?>)</h6>
									</div>
								</div>
								<div class="col-6">
									<div class="rating_list">
										<h3>Baseado em <?php echo $totalReviews; ?> avaliações</h3>
										<ul class="list">
											<li><a href="#">1 Estrelas <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i
													 class="fa fa-star"></i><i class="fa fa-star"></i> <?php echo $contagem['um']; ?></a></li>
											<li><a href="#">2 Estrelas <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i
													 class="fa fa-star"></i><i class="fa fa-star"></i> <?php echo $contagem['dois']; ?></a></li>
											<li><a href="#">3 Estrelas <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i
													 class="fa fa-star"></i><i class="fa fa-star"></i> <?php echo $contagem['tres']; ?></a></li>
											<li><a href="#">4 Estrelas <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i
													 class="fa fa-star"></i><i class="fa fa-star"></i> <?php echo $contagem['quatro']; ?></a></li>
											<li><a href="#">5 Estrelas <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i
													 class="fa fa-star"></i><i class="fa fa-star"></i> <?php echo $contagem['cinco']; ?></a></li>
										</ul>
									</div>
								</div>
							</div>
							<div class="review_list">
								<?php while ($comentario = mysqli_fetch_assoc($resComentarios)) { ?>

									<?php
										$user_id = $comentario['user_id'];
										$sqlUser = "SELECT username FROM users WHERE id = '$user_id'";
										$resUser = mysqli_query($conexao, $sqlUser);
										$user = mysqli_fetch_assoc($resUser);
									?>

									<div class="review_item">
										<div class="media">
											<div class="media-body">
												<h4><?php echo $user['username']; ?></h4>

												<?php
												for ($i = 1; $i <= 5; $i++) {
													echo ($i <= $comentario['estrelas']) ? '<i class="fa fa-star"></i>' : '<i class="fa fa-star text-muted"></i>';
												}
												?>
											</div>
										</div>

										<p><?php echo nl2br($comentario['comentario']); ?></p>
									</div>

								<?php } ?>

							</div>


						</div>
						<div class="col-lg-6">
							<div class="review_box">
								<h4>Deixa a avaliação</h4>
								<p>A sua avaliação:</p>
								<ul class="list" id="stars">
									<li><i class="fa fa-star" data-value="1"></i></li>
									<li><i class="fa fa-star" data-value="2"></i></li>
									<li><i class="fa fa-star" data-value="3"></i></li>
									<li><i class="fa fa-star" data-value="4"></i></li>
									<li><i class="fa fa-star" data-value="5"></i></li>
								</ul>
								<form method="POST" class="form-contact form-review mt-3">
									<div class="form-group">
										<textarea class="form-control different-control w-100" name="texto" cols="30" rows="5" placeholder="Escreva a sua mensagem" required></textarea>
									</div>
									<div class="form-group text-center text-md-right mt-3">
										<input type="hidden" name="estrelas" id="estrelas">
										<input type="hidden" name="produto_id" value="<?php echo $id; ?>">
										<button type="submit" name="review" class="button button--active button-review">Comentar</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!--================End Product Description Area =================-->	

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
  <script src="js/java.js"></script>
</body>
</html>