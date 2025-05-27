<?php
session_start();
include_once("conexao_bd.php");

// Verifica se o usuário está logado (ex: email na sessão)
if (!isset($_SESSION['email'])) {
    // redireciona para login se não estiver logado
    header("Location: login.php");
    exit;
}

$email = $_SESSION['email'];

// Busca o nome do usuário pelo email
$sql = "SELECT nome FROM usuario WHERE email = :email LIMIT 1";
$stmt = $conexao->prepare($sql);
$stmt->bindParam(':email', $email);
$stmt->execute();

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

$nome = $usuario ? $usuario['nome'] : 'Usuário';
?>

<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <title>CodePen - Dark UI - Bank dashboard concept</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">

    <link rel="stylesheet" href="style.css">

</head>
<style>
    .friend-list {
        list-style-type: none;
        padding: 0;
        margin-top: 20px;
        max-width: 600px;
        margin: 0 auto;
    }
    .fund_bot{
        color: #000000;
        background:#0c526c;
    }
    .friend-item {
        background-color:black;
        padding: 15px;
        margin: 10px 0;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>
<body>
<!-- partial:index.partial.html -->
<div class="app">
    <header class="app-header">
        <div class=" col-md-7 row">
            <div class="logo">
                <img class="img-thumbnail" src="Logo2.png" />
                <h1 class="logo-title fs-3">
                    <span>Musik</span>
                    <span>Boaventura</span>
                </h1>
            </div>
        </div>
        <div class="app-header-navigation row">
            <div class="tabs">
                <a href="#">
                    Músicas
                </a>
                <a href="#" class="active">
                    Biblioteca
                </a>
                <a href="#">
                    Amigos
                </a>
                <a href="#">
                    Compor
                </a>
                <a href="#">
                    Chat
                </a>
                <a href="#">
                    Teoria
                </a>
            </div>
        </div>
        <div class="app-header-actions">
            <button class="user-profile">
                <span><?= htmlspecialchars($nome) ?></span>
                <span>
					<img src="https://media.tenor.com/DtOLmKgYVXYAAAAM/pedro.gif" />
				</span>
            </button>
            <div class="app-header-actions-buttons">
                <button class="icon-button large">
                    <i class="ph-magnifying-glass"></i>
                </button>
                <button class="icon-button large">
                    <i class="ph-bell"></i>
                </button>
            </div>
        </div>
        <div class="app-header-mobile">
            <button class="icon-button large">
                <i class="ph-list"></i>
            </button>
        </div>

    </header>
    <div class="app-body">
        <div class="app-body-navigation">
            <nav class="navigation">
                <a href="#">
                    <i class="ph-browsers"></i>
                    <span>Minhas Músicas</span>
                </a>
                <a href="#">
                    <i class="ph-check-square"></i>
                    <span>Minhas Composições</span>
                </a>
                <a href="#">
                    <i class="ph-swap"></i>
                    <span>Playlist</span>
                </a>
                <a href="#">
                    <i class="ph-file-text"></i>
                    <span>Avaliações</span>
                </a>
                <a href="#">
                    <i class="ph-globe"></i>
                    <span>Random</span>
                </a>
                <a href="#">
                    <i class="ph-gear"></i>
                    <span>Configurações</span>
                </a>
            </nav>
            <footer class="footer">
                <h1>Boaventura<small>©</small></h1>
                <div>
                    Boaventura ©<br />
                    All Rights Reserved 2021
                </div>
            </footer>
        </div>
        <div class="app-body-main-content">
            <section class="service-section">
                <div class="service-section-header">
                    <div class="search-field">
                        <i class="ph-magnifying-glass"></i>
                        <input type="text" placeholder="Pesquisa">
                    </div>
                    <button class="flat-button">
                        Buscar
                    </button>
                </div>
                <div class="mobile-only">
                    <button class="flat-button">
                        Toggle search
                    </button>
                </div>
                <div class="tiles">
                    <article class="tile">
                        <div class="tile-header">
                            <i class="ph-lightning-light"></i>
                            <h3>
                                <span>Avaliações</span>
                                <span>Suas músicas</span>
                            </h3>
                        </div>
                        <a href="#">
                            <span>Mais Detalhes</span>
                            <span class="icon-button">
								<i class="ph-caret-right-bold"></i>
							</span>
                        </a>
                    </article>
                    <article class="tile">
                        <div class="tile-header">
                            <i class="ph-fire-simple-light"></i>
                            <h3>
                                <span>Melhores do Momento</span>
                                <span>Brasil</span>
                            </h3>
                        </div>
                        <a href="#">
                            <span>Mais detalhes</span>
                            <span class="icon-button">
								<i class="ph-caret-right-bold"></i>
							</span>
                        </a>
                    </article>
                    <article class="tile">
                        <div class="tile-header">
                            <i class="ph-file-light"></i>
                            <h3>
                                <span>Novas no pedaço</span>
                                <span>Brasil</span>
                            </h3>
                        </div>
                        <a href="#">
                            <span>Mais detalhes</span>
                            <span class="icon-button">
								<i class="ph-caret-right-bold"></i>
							</span>
                        </a>
                    </article>
                </div>
                <div class="service-section-footer">
                </div>
            </section>
            <section class="transfer-section">
                <div class="transfer-section-header">
                    <h2>Últimas Músicas Tocadas</h2>
                    <div class="filter-options">
                        <button class="icon-button">
                            <i class="ph-funnel"></i>
                        </button>
                        <button class="icon-button">
                            <i class="ph-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="transfers">
                    <div class="transfer">
                        <div class="transfer-logo">
                            <img src="https://i.scdn.co/image/ab67616d0000b27362c1f3370811c52ae2d26d24" />
                        </div>
                        <dl class="transfer-details">
                            <div>
                                <dt>Holw Moving Castle</dt>
                                <dd>Hayao Miyazaki</dd>
                            </div>
                            <div>
                                <dd>Ano 2004</dd>
                            </div>
                            <div>
                                <dt>Visualizações</dt>
                                <dd>0000000001</dd>
                            </div>
                        </dl>
                        <div class="transfer-number">
                            2:46
                        </div>
                    </div>
                    <div class="transfer">
                        <div class="transfer-logo">
                            <img src="https://i.scdn.co/image/ab67616d0000b2730f588c6bdb28000656d084bf" />
                        </div>
                        <dl class="transfer-details">
                            <div>
                                <dt>Saylor Song</dt>
                                <dd>Gigi Perez</dd>
                            </div>
                            <div>
                                <dd>Ano 2025</dd>
                            </div>
                            <div>
                                <dt>visualizações</dt>
                                <dd>0000000001</dd>
                            </div>
                        </dl>
                        <div class="transfer-number">
                            3:36
                        </div>
                    </div>
                    <div class="transfer">
                        <div class="transfer-logo">
                            <img src="https://i.scdn.co/image/ab67616d0000b2739d24f74c1e2d8a12b1e591ec" />
                        </div>
                        <dl class="transfer-details">
                            <div>
                                <dt>Back To Friends</dt>
                                <dd>sombr</dd>
                            </div>
                            <div>
                                <dd>Ano 2024</dd>
                            </div>
                            <div>
                                <dt>Visualizações</dt>
                                <dd>0000000001</dd>
                            </div>
                        </dl>
                        <div class="transfer-number">
                            3:20
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <div class="app-body-sidebar">
            <section class="payment-section">
                <h2>Amigos Online</h2>
                <div>
                    <ul class="friend-list">
                        <li class="friend-item">
                            <div>
                                <h3>João Silva</h3>
                            </div>
                            <input class="btn btn-white btn-animate" type="button" value="Ver Mais">
                        </li>
                </div>
            </section>
        </div>
    </div>
</div>
<!-- partial -->
<script src='https://unpkg.com/phosphor-icons'></script><script  src="script.js"></script>

</body>
</html>
