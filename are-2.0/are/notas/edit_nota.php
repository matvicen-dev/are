<?php

session_start(); // Iniciar a sessão

// Limpara o buffer de redirecionamento
ob_start();

// Inclusão da conexão com o BD
include '../../conexao.php';

$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
//var_dump($id);

if(empty($id)){
    // Criar a mensagem de erro e atribuir para variável global
    $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Necessário realizar o login para acessar a página!</p>";

    // Redireciona o o usuário para o arquivo index.php
    header("Location: index.php");

    // Pausar o processamento da página
    exit();
}

$query_nota = "SELECT id_nota, campo_nota FROM notas WHERE id_nota = $id LIMIT 1";
$result_nota = $conn->prepare($query_nota);
$result_nota->execute();

if(($result_nota) AND ($result_nota->rowCount() != 0)){
    $row_nota = $result_nota->fetch(PDO::FETCH_ASSOC);
    //var_dump($row_nota);
}else{
    //Criar a mensagem de erro e atribuir para variável global
    $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Nota não encontrada!</p>";

    // Redireciona o o usuário para o arquivo index.php
    header("Location: index.php");

    // Pausar o processamento da página
    exit();
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
  <!-- <script src="./js/scripts.js" defer></script> -->
  <title>ARE</title>  
</head>
<style>
  body {
    background-color: #23355F;
  }

  /* Cabeçalho */

  header {
    border-bottom: 1px solid #17181a;
    padding: 1rem 2rem;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: space-around;
    box-shadow: 0px 2px 5px #17181a;
  }

  #search-container {
    display: flex;
    align-items: center;
    gap: 1rem;
  }

  #input-busca {
    background-color: white;
    border-radius: 5px;
    border: none;
    padding: 10px;
    font-weight: bold;
    color: black;
    width: 400px;
    outline-offset: -3px;
  }

  #input-busca::placeholder {
    color: black;
  }


  .export-notes,
  #export-notes {
    border: none;
    background-color: #2E3E68;
    border-radius: 4px;
    color: #fff;
    padding: 5px 15px;
    cursor: pointer;
    transform: 0.4s;
  }

  .export-notes:hover {
    background-color: #fff;
    color: #333;
  }

  /* Formulário de nova nota */

  #add-note-container {
    display: flex;
    width: 400px;
    margin: 1rem auto 0;
    gap: 1rem;
  }

  #add-note-container input,
  #add-note-container button {
    padding: 10px;
    border-radius: 5px;
  }

  #add-note-container input {
    flex: 1;
    background-color: transparent;
    border: 1px solid #525356;
    color: #fff;
  }

  #add-note-container button {
    cursor: pointer;
    background-color: #333;
    color: #fff;
  }

  #add-note-container button:hover {
    background-color: #fff;
    color: #333;
  }

  /* Notas */

  #notes-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, 250px);
    padding: 2rem;
    gap: 2rem;
  }

  .note {
    min-height: 200px;
    padding: 1rem;
    border: 1px solid #ccc;
    background-color: #202124;
    border-radius: 10px;
    color: #fff;
    position: relative;
  }

  .note:focus-within {
    border-color: #FBBC04;
    box-shadow: 0px 0px 8px #FBBC04;
    transition: 0.2s;
  }

  .note textarea {
    background-color: transparent;
    resize: none;
    color: #fff;
    border: none;
    height: 100%;
    outline: none;
  }

  .note .bi-pin {
    position: absolute;
    left: 10px;
    bottom: 10px;
    font-size: 1.2rem;
    background-color: #333;
    cursor: pointer;
    padding: 5px 8px;
    border-radius: 3px;
  }

  .note:hover i {
    opacity: 1;
  }

  .note .bi-x-lg,
  .note .bi-file-earmark-plus {
    position: absolute;
    right: 10px;
    top: 10px;
    font-size: 0.9rem;
    padding: 5px;
    transition: 0.3s;
    color: #555;
    cursor: pointer;
    opacity: 0;
  }

  .note .bi-file-earmark-plus {
    top: 40px;
  }

  .note .bi-x-lg:hover,
  .note .bi-file-earmark-plus:hover {
    color: #fff;
  }

  .note.fixed {
    background-color: #1d1b15;
    border-color: #503c02;
  }

  .return {
    text-decoration: none;
  }
   .form{
    /* background-color: yellow; */
    display: flex;
    flex-direction: row;  
    justify-content: center;
    align-items: center;
  } 
  .form form{
    /* background-color: white; */
    /* border: 1px solid blue; */
    padding: 10px 10px 10px 10px;
    flex-direction: row;
  }
  .note_content{
    border-radius: 5px;
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
</style>

<body>
  <header>
    <h1>NOTAS</h1>
    <div id="search-container">
      <i class="bi bi-search"></i>
      <input type="text" id="input-busca" name="search-input" placeholder="Busque por uma nota" />
    </div>
    <!-- <button id="export-notes">
      Exportar CSV <i class="bi bi-download"></i>
    </button> -->
    <a class="export-notes return" href="../menu/index.php">Voltar</a>
  </header>

  <div class="form">

  <?php
    //Recebe os dados do formulário
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    //var_dump($dados);

    //Verifica se o usuário clicou no botão
    if(!empty($dados['ok'])){
        var_dump($dados);
        $empty_input = false;
        $dados = array_map('trim', $dados);
        if(in_array("", $dados)){            
            $empty_input = true;
            echo "<p style='color: #f00;'>Erro: Necessário preencer todos os campos!</p>";
        }

        if(!$empty_input){
            $query_up_nota = "UPDATE notas SET campo_nota=:campo_nota WHERE id_nota=:id_nota";
            $edit_nota = $conn->prepare($query_up_nota);
            $edit_nota->bindParam(':campo_nota', $dados['note-content']);
            $edit_nota->bindParam(':id_nota', $id);
            if($edit_nota->execute()){
                $_SESSION['msg'] = "<p style='color: green;'Nota editada com sucesso!</p>";  
                header('Location: index.php');
            }else{
                $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Nota não editada com sucesso!</p>";    
            }
        }        
    }
  ?>

    <form  class="formulario" method="POST">  
      <input class="note_content" type="text" id="note-content" name="note-content" placeholder="Sua nota" 
            value="<?php 
            if(isset($dados['note-content'])){
                echo $dados['note-content'];
            }elseif(isset($row_nota['campo_nota'])) {echo $row_nota['campo_nota'];} ?>" required>
      <input class="enviar" type="submit" name="ok" value="ok" id="ok">
    </form>
  </div>
    <div class="table-div">
  
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
            
                $resultado_msg_cont = $conn->prepare($selecionaLogado);
                $resultado_msg_cont->execute();

                while($row_msg_count = $resultado_msg_cont->fetch(PDO::FETCH_ASSOC)) {                  
                          echo "<tr>";
                          echo "<td>".$row_msg_count['id_nota']."</td>";
                          echo "<td>".$row_msg_count['campo_nota']."</td>";
                          echo "<td>".$row_msg_count['usuario_id']."</td>";
                          echo "<td>
                            <a href='edit.php?id=$row_msg_count[id_nota]'>Editar</a>
                            <a href='delete.php?id=$row_msg_count[id_nota]'>Apagar</a>
                          </td>";
                          echo "</tr>";
                    
                     }                         
                                                                    
                ?>
                <script src="teste.js"></script>
                <!-- <script src="./js/scripts.js" defer></script> -->
            </tbody>
        </table>  
     </div>
</body>
</html>