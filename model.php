<?php 

/* Implementar com PDO PHP Data Objects */ 
	
/* Estabelecer conexão com a base de dados */    
function estabelecerConexao()
{
    $hostname = 'localhost';
    $dbname = 'galeriaFotos';
    $username = 'pw2';
    $password = '1234';

    try {
      $conexao = new PDO("mysql:host=$hostname;dbname=$dbname;charset=utf8mb4", $username, $password);
    }
    catch ( PDOException $e){
      echo $e->getMessage();
    }

   return $conexao;
}

/* 
   Obter todas as as fotos num array   
*/ 
function getFotos() 
{
   // Estabelecer a conexão com a BD
   
   $con = estabelecerConexao();

   //Contruir uma Query 'SELECT * FROM fotos'

   $res = $con->query( 'SELECT * FROM fotos' );

   return $res->fetchAll();
}

/* 
   Verifica se um dado username existe 
   Retorna booleano
*/
function usernameExists( $username )
{
   $con = estabelecerConexao();
   
   $res = $con->query( 'SELECT users from WHERE username = "PEDRO"' );

   return $res->fetchAll(PDO::FETCH_KEY_PAIR);

}

/* 
   Adiciona um novo utilizador 
   Insere um novo utilizador na tabela users
*/ 
function adicionarUser( $username )
{
  

}

/* 
   Retorna um array com os likes associados a um user 
*/ 
function getLikes( $username )
{
    
    
}

/* 
   Adiciona um Like aos Likes de um utilizador   tabela 'userlikes'
   cujo 'username' é passado no primeiro parâmetro,
   e a fotoId passada no segundo parâmetro  
*/ 
function adicionarLike($username, $fotoId )
{
   

}

/* 
   Remove um Like dos Likes de um utilizador   tabela 'userlikes'
   cujo 'username' é passado no primeiro parâmetro,
    e a fotoId passada no segundo parâmetro  
*/ 
function removerLike($username, $fotoId ) {
   
}

 ?>