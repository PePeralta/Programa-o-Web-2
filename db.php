<?php
function estabelecerConexao()
{
    $hostname = 'localhost'; 
    $dbname   = 'u506280443_pedperDB';
    $username = 'u506280443_pedperdbUser';
    $password = 'Vy8*/NdyaFbO';

    // Criar conexão
    $conexao = mysqli_connect($hostname, $username, $password, $dbname);

    // Verificar erros
    if (!$conexao) {
        die("Erro na conexão: " . mysqli_connect_error());
    }

    return $conexao;
}

// Chamar a função
$conexao = estabelecerConexao();
?>
