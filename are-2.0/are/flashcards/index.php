<?php

session_start(); // Iniciar a sessão

// Limpara o buffer de redirecionamento
ob_start();

// Inclusão da conexão com o BD
include '../../conexao.php';

// Incluir o arquivo para validar e recuperar dados do token
include_once '../../validar_token.php';

// Chamar a função validar o token, se a função retornar FALSE significa que o token é inválido e acessa o IF
if(!validarToken()){
    // Criar a mensagem de erro e atribuir para variável global
    $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Necessário realizar o login para acessar a página!</p>";

    // Redireciona o o usuário para o arquivo index.php
    header("Location: index.php");

    // Pausar o processamento da página
    exit();
}

  //$nome = recuperarNomeToken();
  //print_r($nome);
  $id = recuperarIdToken();
  // print_r($timer);

  // Receber os dados do formulario
  $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
  //var_dump($dados);

  // Verificar se o usuario clicou no botão e salva o formulario no BD
  if(!empty($dados['save-btn'])){
    $query_usuario = "INSERT INTO flashcards 
                (front, back, usuario_id, hora) VALUES
                (:front, :back, :usuario_id, DATE_ADD(CURRENT_TIME, INTERVAL 0 HOUR))";
    $cad_flashcard = $conn->prepare($query_usuario);
    $cad_flashcard->bindParam(':front', $dados['question']);
    $cad_flashcard->bindParam(':back', $dados['answer']);
    //$cad_flashcard->bindParam(':timer', $timer);
    $cad_flashcard->bindParam(':usuario_id', $id);
    //$cad_flashcard->bindParam(':hora', $hora);
    $cad_flashcard->execute();

  }else{
    //echo "Erro";
  }  
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Flashcards</title>
  <!-- Fontes -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" />
  <!-- Google Fontes -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
  <!-- Google Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
 
</head>

<style>
  body {
    background-color: #23355F;
  }

  .container {
    width: 90vw;
    max-width: 62.5em;
    position: relative;
    margin: auto;
  }

  .add-flashcard-con {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    padding: 1.2em 1em;    
  }

  button {
    outline: none;
    cursor: pointer;
  }

  .add-flashcard-con button {
    padding: 10px 40px;
    color: #fff;
    border: none;
    border-radius: 4px;
    background: #2E3E68;
    text-align: center;
    font-size: 12pt;
    user-select: none;
    text-decoration: none;
    transform: 0.4s;
  }

  .add-flashcard-con button:hover {
    background-color: #fff;
    color: #333;
    transition: backgrouns 1s;
  }

  .add-flashcard-con a {
    padding: 10px 40px;
    color: #fff;
    border-radius: 4px;
    background: #2E3E68;
    text-align: center;
    font-size: 12pt;
    user-select: none;
    cursor: pointer;
    text-decoration: none;
    transform: 0.4s;
    text-decoration: none;
  }

  .add-flashcard-con a:hover {
    background-color: #fff;
    color: #333;
    transition: backgrouns 1s;
  }

  #card-con {
    margin-top: 1em;
  }

  .question-container {
    width: 90vw;
    max-width: 34em;
    display: flex;
    flex-direction: column;
    justify-content: center;
    background-color: #ffffff;
    position: absolute;
    transform: translate(-50%, -50%);
    top: 50%;
    left: 50%;
    padding: 3em 2em;
    border-radius: 0.6em;
    box-shadow: 0 1em 2em rgba(28, 0, 80, 0.1);
    border: 1px solid black;
  }

  .question-container h2 {
    font-size: 2.2em;
    color: #363d55;
    font-weight: 600;
    text-align: center;
    margin-bottom: 2em;
  }

  .wrapper {
    display: grid;
    grid-template-columns: 11fr 1fr;
    gap: 1em;
    margin-bottom: 1em;
  }

  .error-con {
    align-self: center;
  }

  #error {
    color: #ff5353;
    font-weight: 400;
  }

  .fa-xmark {
    font-size: 1.4em;
    background-color: #333;
    height: 1.8em;
    width: 1.8em;
    display: grid;
    place-items: center;
    color: #ffffff;
    border-radius: 50%;
    cursor: pointer;
    justify-self: flex-end;
  }

  .fa-xmark:hover {
    background-color: #fff;
    color: #333;
    transition: backgrouns 1s;
    border: 1px solid #333;
  }

  label {
    color: #363d55;
    font-weight: 600;
    margin-bottom: 0.3em;
  }

  textarea {
    width: 100%;
    padding: 0.7em 0.5em;
    border: 1px solid #d0d0d0;
    outline: none;
    color: #414a67;
    border-radius: 0.3em;
    resize: none;
  }

  textarea:not(:last-child) {
    margin-bottom: 1.2em;
  }

  textarea:focus {
    border-color: #363d55;
  }

  #save-btn {
    font-size: 1em;
    background-color: #333;
    color: #ffffff;
    padding: 0.6em 0;
    border-radius: 0.3em;
    font-weight: 600;
  }

  #save-btn:hover {
    background-color: #fff;
    color: #333;
    transition: backgrouns 1s;
    border: 1pt solid #202124;
  }

  .card-list-container {
    display: grid;
    padding: 0.2em;
    gap: 1.5em;
    grid-template-columns: 1fr 1fr 1fr;
  }

  .card {
    background-color: #ffffff;
    box-shadow: 0 0.4em 1.2em rgba(28, 0, 80, 0.08);
    padding: 1.2em;
    border-radius: 0.4em;
  }

  .question-div,
  .answer-div {
    text-align: justify;
  }

  .question-div {
    margin-bottom: 0.5em;
    font-weight: 500;
    color: #363d55;
  }

  .answer-div {
    margin-top: 1em;
    font-weight: 400;
    color: #414a67;
  }

  .show-hide-btn {
    display: block;
    background-color: #333;
    color: #ffffff;
    text-decoration: none;
    text-align: center;
    padding: 0.6em 0;
    border-radius: 0.3em;
  }

  .buttons-con {
    display: flex;
    justify-content: flex-end;
  }

  .edit,
  .delete {
    background-color: transparent;
    padding: 0.5em;
    font-size: 1.2em;
  }

  .edit {
    color: #41298f;
  }

  .delete {
    color: #ff5353;
  }

  .hide {
    display: none;
  }

  @media screen and (max-width: 800px) {
    .card-list-container {
      grid-template-columns: 1fr 1fr;
      gap: 0.8em;
    }
  }

  @media screen and (max-width: 450px) {
    body {
      font-size: 14px;
    }

    .card-list-container {
      grid-template-columns: 1fr;
      gap: 1.2em;
    }
  }
   .form{
    background-color: yellow;
    display: flex;
    flex-direction: row;  
    justify-content: center;
    align-items: center;
  } 
  .form form{
    background-color: white;
    border: 1px solid blue;
    padding: 10px 10px 10px 10px;
    flex-direction: row;
  }
  .note_content{
    width: 200px;
    height: 50px;
  }
  .enviar{    
    margin-top: 10px;
    width: 100px;
    height: 25px;

  }
  .table-div{
    display: flex;
    flex-direction: column;
  }
  .table{
    background-color: white;
    text-align: center;
  }
  .th{
    background-color: #85A4F5;
  } 
  .enviar{
    background-color: black;
    color: white;
    display: block;
    border-radius: 5px;
    height: 40px;
    width: 80px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-weight: bold;
  }
  .enviar:hover{
    background-color: white;
    color: black;
    border: 1px solid black;
    cursor:pointer;
  }
</style>

<body>
  <div class="container">
    <div class="add-flashcard-con">
      <button id="add-flashcard">Adicionar Flashcard</button>
      <a href="estudar.php">Estudar</a>
      <a href="../menu/index.php">Voltar</a>
    </div>

    <!-- Exibir cartão de perguntas e respostas aqui -->
    <div id="card-con">
      <div class="card-list-container"></div>
    </div>
  </div>

  <!-- Formulário de entrada para os usuários preencherem perguntas e respostas -->
  <form  class="formulario" method="POST">
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
      <textarea class="input" id="question" name="question" placeholder="Digite a pergunta aqui..." rows="4"></textarea>
      <label for="answer">Resposta:</label>
      <textarea class="input" id="answer" name="answer" rows="4" placeholder="Digite a resposta aqui..."></textarea>
      <!-- <button id="save-btn">Salvar</button> -->
      <!-- <div id="timer" name="timer"></div> -->
      <!-- <button onclick="startTimer()" id="timer" name="timer">Timer</button> -->
      <input class="enviar" type="submit" name="save-btn" value="Salvar" id="ok">
    </div>
  </form>

  <div class="table-div">
      <table class="table">
            <thead>
                <tr>
                    <!-- <th class="th" scope="col">#</th> -->
                    <th class="th" scope="col">Pergunta</th>
                    <th class="th" scope="col">Resposta</th>
                    <!-- <th class="th" scope="col">Id do Usuário</th> -->
                    <th class="th" scope="col">Menu</th>
                </tr>
            </thead>
            <tbody id="tabela-flashcards">
                <?php
                $selecionaLogado = "SELECT * FROM flashcards WHERE $id = usuario_id";
                try{
                  $result = $conn->prepare($selecionaLogado);
                  //$result->bindParam('')
                  $result->execute();
                  $contar = $result->rowCount();

                  if($contar =1){
                    $loop = $result->fetchAll();
                    foreach ($loop as $show){
                      // $id_flashcard = $show['id_flashcard'];
                      $front = $show['front'];
                      $back = $show['back'];
                      // $user_id = $show['usuario_id'];
                    }
                  }
                }catch (PDOWException $erro){ echo $erro;}
                
                $resultado_msg_cont = $conn->prepare($selecionaLogado);
                $resultado_msg_cont->execute();

                while($row_msg_count = $resultado_msg_cont->fetch(PDO::FETCH_ASSOC)) {                  
                          echo "<tr>";
                          // echo "<td>".$row_msg_count['id_flashcard']."</td>";
                          echo "<td>".$row_msg_count['front']."</td>";
                          echo "<td>".$row_msg_count['back']."</td>";
                          // echo "<td>".$row_msg_count['usuario_id']."</td>";
                          echo "<td>
                            <a href='edit_fc.php?id=$row_msg_count[id_flashcard]' title='Editar'><span class='material-icons'>mode_edit</span></a>
                            <a href='delete_fc.php?id=$row_msg_count[id_flashcard]' title='Apagar'><span class='material-icons'>delete_forever</span></a>
                          </td>";
                          echo "</tr>";                    
                     }                         
                                                                    
                ?>
                <!-- <script src="teste.js"></script> -->
                <!-- <script src="./js/scripts.js" defer></script> -->
            </tbody>
        </table>  
     </div> <!-- end table-div -->

  <!-- Script -->
  <script src="script-flashcards.js"></script>
</body>

</html>