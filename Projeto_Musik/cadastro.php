<?php
include_once("conexao_bd.php");

$nome = $_REQUEST['nome'];
$senha = $_REQUEST['senha'];
$email = $_REQUEST['email'];
$data = $_REQUEST['data'];

// Verifica se o email já existe
$verifica = $conexao->prepare("SELECT COUNT(*) FROM Usuario WHERE email = :email");
$verifica->bindParam(':email', $email);
$verifica->execute();
$existe = $verifica->fetchColumn();

if ($existe > 0) {
    // Redireciona com mensagem de erro
    header("Location: cadastro.html?mensagem=Erro: E-mail já cadastrado.");
    exit;
}

// Insere novo usuário
$sql = "INSERT INTO Usuario (nome, data_nasc, email, senha) 
        VALUES (:nome, :data, :email, :senha)";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':senha', $senha);
$stmt->bindParam(':nome', $nome);
$stmt->bindParam(':data', $data);
$stmt->execute();

$mensagem = "Registro salvo com sucesso.";
header("Location: login.html?mensagem=$mensagem");
?>