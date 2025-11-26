<?php
include_once("conexao_bd.php");

$idUsuario = $_POST['idUsuario'];
$generos = isset($_POST['generos']) ? $_POST['generos'] : [];


$fotoNome = null;

// ---------------------------------------------------
// SALVAR FOTO
// ---------------------------------------------------
if (!empty($_FILES['foto']['name'])) {

    $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $fotoNome = "foto_" . $idUsuario . "." . $extensao;

    move_uploaded_file($_FILES['foto']['tmp_name'], "fotoperfil/" . $fotoNome);

    // Atualizar no banco
    $sqlFoto = "UPDATE Usuario SET foto = :foto WHERE idUsuario = :id";
    $stmtFoto = $conexao->prepare($sqlFoto);
    $stmtFoto->bindParam(':foto', $fotoNome);
    $stmtFoto->bindParam(':id', $idUsuario);
    $stmtFoto->execute();
}

// ---------------------------------------------------
// SALVAR GÊNEROS (cria se não existir)
// ---------------------------------------------------
foreach ($generos as $nomeGenero) {

    // 1. Verificar se existe
    $sqlBusca = "SELECT idMusica FROM Musica WHERE genero = :g LIMIT 1";
    $stmtBusca = $conexao->prepare($sqlBusca);
    $stmtBusca->bindParam(':g', $nomeGenero);
    $stmtBusca->execute();

    $resultado = $stmtBusca->fetch(PDO::FETCH_ASSOC);

    if ($resultado) {
        $idGenero = $resultado['idMusica'];
    } else {
        // 2. Criar novo gênero
        $sqlInsere = "INSERT INTO Musica (genero, data, artista)
                      VALUES (:g, NOW(), 'Desconhecido')";
        $stmtInsere = $conexao->prepare($sqlInsere);
        $stmtInsere->bindParam(':g', $nomeGenero);
        $stmtInsere->execute();

        $idGenero = $conexao->lastInsertId();
    }

    // 3. Criar relação N:N
    $sqlRelacao = "
        INSERT IGNORE INTO Usuario_das_Musica (Usuario_idUsuario, Musica_idMusica)
        VALUES (:idUsuario, :idMusica)
    ";
    $stmtRelacao = $conexao->prepare($sqlRelacao);
    $stmtRelacao->bindParam(':idUsuario', $idUsuario);
    $stmtRelacao->bindParam(':idMusica', $idGenero);
    $stmtRelacao->execute();
}

header("Location: perfil.php?id=" . $idUsuario);
exit;
?>
