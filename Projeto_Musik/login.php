<?php
include_once("conexao_bd.php");
    $senha = $_REQUEST['pass'];
    $email = $_REQUEST['email'];

    $sql = "SELECT * FROM Usuario WHERE email = :email AND senha = :senha";

$stmt = $conexao->prepare($sql);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':senha', ($senha));
$stmt->execute();
if ($stmt->rowCount() > 0) {
    session_start();
    $_SESSION['email'] = $email;
    header("Location: perfil.html");
} else {
    header("Location: index.php?m=Usuário burro e\ou senha invalida!");
}
?>
