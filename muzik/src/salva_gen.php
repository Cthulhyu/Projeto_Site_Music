<?php
header('Content-Type: application/json');

// Valida o método da requisição
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método de requisição inválido.']);
    exit();
}

// Recebe os dados JSON do corpo da requisição
$input = file_get_contents('php://input');
$data = json_decode($input, true);

$selectedGenres = $data['genres'] ?? [];

if (empty($selectedGenres)) {
    echo json_encode(['success' => false, 'message' => 'Nenhum gênero selecionado.']);
    exit();
}

// --- AQUI VOCÊ FARIA A LÓGICA PARA ATRIBUIR AO PERFIL DO USUÁRIO ---
// Exemplo: Conectar ao banco de dados e salvar os gêneros.
// Lembre-se de sanitizar e validar todos os dados de entrada!

// Exemplo de como você poderia salvar em um banco de dados (pseudocódigo):
/*
$userId = 123; // Assuma que você tem o ID do usuário logado
$genreString = implode(',', $selectedGenres); // Converte array para string para salvar

// Conexão e query SQL (exemplo, use PDO para segurança)
$db = new PDO('mysql:host=localhost;dbname=your_db', 'username', 'password');
$stmt = $db->prepare("UPDATE users SET genres = ? WHERE id = ?");
$stmt->execute([$genreString, $userId]);
*/

// Para este exemplo, apenas retornamos sucesso.
echo json_encode(['success' => true, 'message' => 'Gêneros salvos com sucesso (simulado)!', 'genres_saved' => $selectedGenres]);

?>