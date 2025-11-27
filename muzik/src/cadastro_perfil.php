<?php
$id = $_GET['id'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Cadastro Perfil</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
    <link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
    <link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/main.css">

    <style>
        #generosSelect {
            height: 250px;
            border-radius: 12px;
            padding: 10px;
            font-size: 16px;
        }
    </style>

</head>

<body>

    <div class="limiter ">
        <div class="container-login100">
            <div class="wrap-login100 justify-content-center">
                
                <form class="login100-form" method="post" action="salvar_perfil.php" enctype="multipart/form-data">

                    <input type="hidden" name="idUsuario" value="<?php echo $id; ?>">

                    <div class="form-group row justify-content-center">
                        <img class="img-fluid mb-5 w-50 d-flex" id="imagem-preview" src="#" alt="Prévia da imagem">
                        <input type="file" id="foto" name="foto" class="form-control">
                    </div>

                    <h1 class="login100-form-title mb-4">Selecione seus Gêneros Favoritos</h1>

                    <!-- SELECT MÚLTIPLO -->
                    <select id="generosSelect" name="generos[]" multiple class="form-select">
                        <option>Carregando gêneros...</option>
                    </select>

                    <button type="submit" class="login100-form-btn mt-4">Enviar</button>

                </form>

            </div>
        </div>
    </div>

    <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="vendor/bootstrap/js/popper.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Preview da imagem -->
    <script>
        const inputImagem = document.getElementById('foto');
        const previewImagem = document.getElementById('imagem-preview');

        inputImagem.addEventListener('change', function (event) {
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = e => previewImagem.src = e.target.result;
                reader.readAsDataURL(file);
            } else {
                previewImagem.src = '#';
            }
        });
    </script>

    <!-- Carregar gêneros no SELECT -->
    <script>
        $(document).ready(function () {

            const apiKey = '1ff27e8a9540dcbebe9aeb2b41709852';
            const url = `https://ws.audioscrobbler.com/2.0/?method=tag.getTopTags&api_key=${apiKey}&format=json`;

            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',

                success: function (data) {

                    const tags = data.toptags.tag;
                    const $select = $('#generosSelect');

                    $select.empty();

                    tags.slice(0, 70).forEach(tag => {
                        let nome = tag.name;
                        $select.append(`<option value="${nome}">${nome}</option>`);
                    });
                },

                error: function () {
                    $('#generosSelect').html('<option>Erro ao carregar gêneros.</option>');
                }
            });

        });
    </script>

</body>
</html>
