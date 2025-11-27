<?php
$host = "db";          // Nome do serviço do MySQL no docker-compose
$user = "root";        // Usuário que você configurou
$port = "3306";
$password = "musik";   // Senha definida no docker-compose
$dbname = "musik";     // Nome do banco criado

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
