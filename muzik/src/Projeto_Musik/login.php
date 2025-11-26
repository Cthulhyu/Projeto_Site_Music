<?php
session_start();
include_once("conexao_bd.php");

// RECEBE DADOS DO FORMULÁRIO
$email = $_POST['email'];
$senha = $_POST['pass'];

// PROCURA NO BANCO
$sql = "SELECT * FROM Usuario WHERE email = :email AND senha = :senha LIMIT 1";

$stmt = $conexao->prepare($sql);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':senha', $senha);
$stmt->execute();

// SE ENCONTROU USUÁRIO
if ($stmt->rowCount() > 0) {

    $_SESSION['email'] = $email;

    header("Location: perfil.php");
    exit;

} else {

    header("Location: login.html?erro=1");
    exit;

}
?>
