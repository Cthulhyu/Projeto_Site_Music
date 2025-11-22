<?php
$id = $_GET['id'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login V1</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="images/icons/favicon.ico" />
    <!--===============================================================================================-->

    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
    <!--===============================================================================================-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <!--===============================================================================================-->
</head>

<body>

    <div class="limiter ">
        <div class="container-login100">
            <div class="wrap-login100 justify-content-center ">
                <form class="login100-form" method="post" action="salvar_perfil.php" enctype="multipart/form-data">
                    <input type="hidden" name="idUsuario" value="<?php echo $id; ?>">
                    <div class="form-group row justify-content-center">
                        <img class="img-fluid mb-5 w-50 d-flex " id="imagem-preview" src="#" alt="Prévia da imagem">
                        <input type="file" id="foto" class="form-control">
                    </div>
                    <h1 class="login100-form-title">Selecione seus Gêneros Favoritos</h1>


                    <select id="genres" onchange="teste()">
                        <option>Carregando...</option>
                    </select>

                    <table id="resultado">

                    </table>

                    <button id="save-genres">Salvar Gêneros Selecionados</button>

                    <p>Gêneros selecionados: <span id="selected-genres-output">Nenhum</span></p>

                    <script src="app_sal_gen.js"></script>
                    <button type="submit " class="login100-form-btn">Enviar</button>
                </form>
            </div>
        </div>
    </div>


    <!--===============================================================================================-->
    <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
    <!--===============================================================================================-->
    <script src="vendor/bootstrap/js/popper.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <!--===============================================================================================-->
    <script src="vendor/select2/select2.min.js"></script>
    <!--===============================================================================================-->
    <script src="vendor/tilt/tilt.jquery.min.js"></script>
    <script>
        $('.js-tilt').tilt({
            scale: 1.1
        });
    </script>
    <!--===============================================================================================-->
    <script src="js/main.js"></script>
    <script>
        const inputImagem = document.getElementById('foto');
        const previewImagem = document.getElementById('imagem-preview');

        inputImagem.addEventListener('change', function (event) {
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    previewImagem.src = e.target.result;
                };

                reader.readAsDataURL(file);
            } else {
                previewImagem.src = '#'; // Limpa a imagem caso nenhum ficheiro seja selecionado
            }
        });


    </script>

    <script>
        $(document).ready(function () {
            var apiKey = '1ff27e8a9540dcbebe9aeb2b41709852'; // substitua pela sua API key do Last.fm
            var url = 'https://ws.audioscrobbler.com/2.0/?method=tag.getTopTags&api_key=' + apiKey + '&format=json';

            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function (data) {
                    var tags = data.toptags.tag;
                    var $select = $('#genres');
                    $select.empty(); // limpa o combobox

                    if (tags && tags.length > 0) {
                        $.each(tags, function (index, tag) {
                            // adiciona opção ao combobox
                            $select.append($('<option></option>').val(tag.name).text(tag.name));
                        });
                    } else {
                        $select.append($('<option></option>').text('Nenhum gênero encontrado'));
                    }
                },
                error: function () {
                    $('#genres').empty().append($('<option></option>').text('Erro ao carregar gêneros'));
                }
            });
        });

        function teste() {
            alert(0);
        }
    </script>

</body>

</html>