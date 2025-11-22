<?php
include_once("conexao_bd.php");

$idUsuario = $_POST['idUsuario'];
$generos = isset($_POST['generos']) ? explode(",", $_POST['generos']) : [];

// --- SALVAR FOTO --- //
$$fotoNome = null;

if (!empty($_FILES['foto']['name'])) {

    $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $fotoNome = "foto_" . $idUsuario . "." . $extensao;

    // salva dentro de /fotoperfil/
    move_uploaded_file($_FILES['foto']['tmp_name'], "fotoperfil/" . $fotoNome);

    $sqlFoto = "UPDATE Usuario SET foto = :foto WHERE idUsuario = :id";
    $stmtFoto = $conexao->prepare($sqlFoto);
    $stmtFoto->bindParam(':foto', $fotoNome);
    $stmtFoto->bindParam(':id', $idUsuario);
    $stmtFoto->execute();
}

// --- SALVAR GÊNEROS --- //
// sua relação N:N usa Usuario_das_Musica
foreach ($generos as $g) {

    // Insert ignorando duplicatas
    $sqlGen = "INSERT INTO Usuario_das_Musica (Usuario_idUsuario, Musica_idMusica)
               VALUES (:idUsuario, :idMusica)";
    $stmtGen = $conexao->prepare($sqlGen);
    $stmtGen->bindParam(':idUsuario', $idUsuario);
    $stmtGen->bindParam(':idMusica', $g);
    $stmtGen->execute();
}

// REDIRECIONA PARA PERFIL FINAL
header("Location: perfil_final.php?id=" . $idUsuario);
exit;
