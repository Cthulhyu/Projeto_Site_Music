<?php
$apiKey = '1ff27e8a9540dcbebe9aeb2b41709852';

$urlGenero = "http://ws.audioscrobbler.com/2.0/?method=tag.getTopTags&api_key=$apiKey&format=json";
$urlMusica = "http://ws.audioscrobbler.com/2.0/?method=track.getInfo&api_key=$apiKey&artist=Slash&track=Believe&format=json";


$response = file_get_contents($urlMusica);
if ($response === FALSE) {
    die("Erro ao acessar a API");
}

$data = json_decode($response, true);

var_dump($data);
?>