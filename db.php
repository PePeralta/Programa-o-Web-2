<?php
function estabelecerConexao()
{
    // $hostname = 'localhost'; 
    // $dbname   = 'u506280443_pedperDB';
    // $username = 'u506280443_pedperdbUser';
    // $password = 'Vy8*/NdyaFbO';

    $hostname = 'localhost'; 
    $dbname   = 'test';
    $username = 'root';
    $password = '';

    $conexao = mysqli_connect($hostname, $username, $password, $dbname);

    if (!$conexao) {
        die("Erro na conexão: " . mysqli_connect_error());
    }

    return $conexao;
}

$conexao = estabelecerConexao();
?>
