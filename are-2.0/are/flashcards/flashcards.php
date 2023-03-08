<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Flashcards</title>
  <!-- Fontes -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" />
  <!-- Google Fontes -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
  <!-- Stylesheet -->
  <link rel="stylesheet" href="style-flashcards.css" />
</head>

<body>
  <div class="container">
    <div class="add-flashcard-con">
      <button id="add-flashcard">Adicionar Flashcard</button>
      <a href="../menu/index.php">Voltar</a>
    </div>

    <!-- Exibir cartão de perguntas e respostas aqui -->
    <div id="card-con">
      <div class="card-list-container"></div>
    </div>
  </div>

  <!-- Formulário de entrada para os usuários preencherem perguntas e respostas -->
  <div class="question-container hide" id="add-question-card">
    <h2>Adicionar Flashcard</h2>
    <div class="wrapper">
      <!-- Mensagem de erro -->
      <div class="error-con">
        <span class="hide" id="error">Os campos de entrada não podem estar vazios!</span>
      </div>
      <!-- Botão Fechar -->
      <i class="fa-solid fa-xmark" id="close-btn"></i>
    </div>

    <label for="question">Pergunta:</label>
    <textarea class="input" id="question" placeholder="Digite a pergunta aqui..." rows="2"></textarea>
    <label for="answer">Resposta:</label>
    <textarea class="input" id="answer" rows="4" placeholder="Digite a resposta aqui..."></textarea>
    <button id="save-btn">Salvar</button>
  </div>

  <!-- Script -->
  <script src="script-flashcards.js"></script>
</body>

</html>