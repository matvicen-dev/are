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
 
  $nome = recuperarNomeToken();
  print_r($nome);
  $id = recuperarIdToken();
  print_r($id);

  // Receber os dados do formulario
  $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
  //var_dump($dados);

  // Verificar se o usuario clicou no botão e salva o formulario no BD
  if(!empty($dados['ok'])){
    $query_usuario = "INSERT INTO notas 
                (campo_nota, usuario_id) VALUES
                (:campo_nota, :usuario_id)";
    $cad_nota = $conn->prepare($query_usuario);
    $cad_nota->bindParam(':campo_nota', $dados['note-content']);
    $cad_nota->bindParam(':usuario_id', $id);
    $cad_nota->execute();

  }else{
    echo "Erro";
  }

  
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <!-- CSS local -->
  <link rel="stylesheet" href="./css/styles.css" />
  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" />
  <!-- JavaScript -->
  <script src="./js/scripts.js" defer></script>
  <title>ARE</title>  
</head>
<style>
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
    background-color: red;
    text-align: center;
  }
  .th{
    background-color: green;
  }
</style>

<body>
  <header>
    <h1>NOTAS</h1>
    <div id="search-container">
      <i class="bi bi-search"></i>
      <input type="text" id="search-input" name="search-input" placeholder="Busque por uma nota" />
    </div>
    <!-- <button id="export-notes">
      Exportar CSV <i class="bi bi-download"></i>
    </button> -->
    <a class="export-notes return" href="../menu/index.php">Voltar</a>
  </header>

  <!-- <div id="add-note-container">
    <input type="text" id="note-content" name="note-content" placeholder="O que deseja anotar?" />
    <button class="add-note" id="add-note" name="add-note"><i class="bi bi-plus-lg"></i></button>
    
  </div> -->
  <div class="form">
    <form  class="formulario" method="POST">  
      <input class="note_content" type="text" id="note-content" name="note-content" placeholder="Sua nota" />
      <input class="enviar" type="submit" name="ok" value="ok" id="ok">
    </form>
  </div>
    <div class="table-div">
      <table class="table">
            <thead>
                <tr>
                    <th class="th" scope="col">#</th>
                    <th class="th" scope="col">Nota</th>
                    <th class="th" scope="col">Id do Usuário</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $selecionaLogado = "SELECT * FROM notas WHERE $id = usuario_id";
                try{
                  $result = $conn->prepare($selecionaLogado);
                  //$result->bindParam('')
                  $result->execute();
                  $contar = $result->rowCount();

                  if($contar =1){
                    $loop = $result->fetchAll();
                    foreach ($loop as $show){
                      $id_nota = $show['id_nota'];
                      $nota_salva = $show['campo_nota'];
                      $user_id = $show['usuario_id'];
                    }
                  }
                }catch (PDOWException $erro){ echo $erro;}

                // SQL para selecionar os registros
                //$result_msg_cont = "SELECT * FROM notas ORDER BY id_nota ASC";

                // Seleciona os registros
                $resultado_msg_cont = $conn->prepare($selecionaLogado);
                $resultado_msg_cont->execute();

                while($row_msg_count = $resultado_msg_cont->fetch(PDO::FETCH_ASSOC)) {                  
                          echo "<tr>";
                          echo "<td>".$row_msg_count['id_nota']."</td>";
                          echo "<td>".$row_msg_count['campo_nota']."</td>";
                          echo "<td>".$row_msg_count['usuario_id']."</td>";
                        //echo "<td>
                        //    <a class='btn btn-sm btn-primary' href='edit.php?id=$user_data[id]' title='Editar'>
                        //      <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-pencil' viewBox='0 0 16 16'>
                        //          <path d='M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z'/>
                        //    </svg>
                        //      </a> 
                        //     <a class='btn btn-sm btn-danger' href='delete.php?id=$user_data[id]' title='Deletar'>
                        //          <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash-fill' viewBox='0 0 16 16'>
                        //              <path d='M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z'/>
                        //          </svg>
                        //      </a>
                        //      </td>";
                          echo "</tr>";
                    
                     }                         
                                                                    
                ?>
            </tbody>
        </table>  
     </div>
</body>
</html>