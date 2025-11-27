document.addEventListener('DOMContentLoaded', () => {
    const genreListDiv = document.getElementById('genre-list');
    const saveGenresButton = document.getElementById('save-genres');
    const selectedGenresOutput = document.getElementById('selected-genres-output');
    let selectedGenres = new Set(); // Usaremos um Set para armazenar gêneros únicos

    // Função para carregar os gêneros do seu backend PHP
    async function loadGenres() {
        try {
            const response = await fetch('get_genres.php'); // URL do seu script PHP
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const genres = await response.json();
            renderGenres(genres);
        } catch (error) {
            console.error('Erro ao carregar gêneros:', error);
            genreListDiv.innerHTML = '<p>Erro ao carregar gêneros. Tente novamente mais tarde.</p>';
        }
    }

    // Função para renderizar os gêneros na interface
    function renderGenres(genres) {
        genreListDiv.innerHTML = ''; // Limpa a mensagem de carregamento
        if (genres.length === 0) {
            genreListDiv.innerHTML = '<p>Nenhum gênero disponível.</p>';
            return;
        }

        genres.forEach(genre => {
            const genreItem = document.createElement('div');
            genreItem.classList.add('genre-item');
            genreItem.textContent = genre.charAt(0).toUpperCase() + genre.slice(1); // Capitaliza o primeiro caractere
            genreItem.dataset.genreName = genre; // Armazena o nome do gênero no dataset

            genreItem.addEventListener('click', () => {
                genreItem.classList.toggle('selected');
                if (genreItem.classList.contains('selected')) {
                    selectedGenres.add(genre);
                } else {
                    selectedGenres.delete(genre);
                }
                updateSelectedGenresOutput();
            });
            genreListDiv.appendChild(genreItem);
        });
    }

    // Função para atualizar a exibição dos gêneros selecionados
    function updateSelectedGenresOutput() {
        if (selectedGenres.size === 0) {
            selectedGenresOutput.textContent = 'Nenhum';
        } else {
            selectedGenresOutput.textContent = Array.from(selectedGenres).join(', ');
        }
    }

    // Evento para o botão de salvar
    saveGenresButton.addEventListener('click', () => {
        if (selectedGenres.size > 0) {
            // Aqui você enviaria os gêneros selecionados para o seu backend PHP
            // para serem atribuídos ao perfil do usuário no banco de dados.
            console.log('Gêneros a serem salvos:', Array.from(selectedGenres));
            alert('Gêneros salvos (simulado)! Veja no console.');

            // Exemplo de como você enviaria para um endpoint PHP:
            /*
            fetch('save_user_genres.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ genres: Array.from(selectedGenres) }),
            })
            .then(response => response.json())
            .then(data => {
                console.log('Resposta do servidor:', data);
                if (data.success) {
                    alert('Gêneros do usuário atualizados com sucesso!');
                } else {
                    alert('Erro ao atualizar gêneros: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erro ao enviar gêneros:', error);
                alert('Erro ao comunicar com o servidor.');
            });
            */

        } else {
            alert('Selecione pelo menos um gênero para salvar.');
        }
    });

    // Carrega os gêneros ao iniciar a página
    loadGenres();
});