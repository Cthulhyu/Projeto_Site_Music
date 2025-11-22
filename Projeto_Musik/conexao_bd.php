<?php
$host = "127.0.0.1";
$user = "root";
$port = "3306";
$password = "123456";
$dbname = "sitemusica";

try {
    $conexao = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8",
        $user,
        $password
    );

    // Para mostrar erros do PDO quando algo der errado
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    echo "Erro na conexão: " . $e->getMessage();
}
?>
