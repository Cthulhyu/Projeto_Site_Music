<?php

// URL da API
$url = 'https://spotify23.p.rapidapi.com/genre_view/?id=0JQ5DAqbMKFEC4WFtoNRpw&content_limit=10&limit=20';

// Seus cabeçalhos da RapidAPI
$rapidApiHost = 'spotify23.p.rapidapi.com';
$rapidApiKey = 'SUA_CHAVE_RAPIDAPI'; // Substitua pela sua chave REAL

// Inicializa uma nova sessão cURL
$ch = curl_init();

// Define a URL para a requisição
curl_setopt($ch, CURLOPT_URL, $url);

// Define que a requisição será GET (já é o padrão, mas é bom explicitar)
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

// Retorna o resultado da requisição como uma string em vez de imprimi-la diretamente
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Define os cabeçalhos HTTP
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'x-rapidapi-host: ' . $rapidApiHost,
    'x-rapidapi-key: ' . $rapidApiKey
]);

// Executa a requisição cURL
$response = curl_exec($ch);

// Verifica por erros
if (curl_errno($ch)) {
    echo 'Erro cURL: ' . curl_error($ch);
} else {
    // Decodifica a resposta JSON
    $data = json_decode($response, true);

    // Exemplo: Imprime a resposta para depuração
    echo '<pre>';
    print_r($data);
    echo '</pre>';

    // Agora você pode trabalhar com os dados, por exemplo,
    // passar para o frontend via JSON
    // header('Content-Type: application/json');
    // echo json_encode($data);
}

// Fecha a sessão cURL
curl_close($ch);

?>