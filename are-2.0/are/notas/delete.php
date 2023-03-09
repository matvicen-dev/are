<?php

session_start(); // Iniciar a sessão

// Limpara o buffer de redirecionamento
ob_start();

// Inclusão da conexão com o BD
include '../../conexao.php';

$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
var_dump($id);

if(empty($id)){
    // Criar a mensagem de erro e atribuir para variável global
    $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Necessário realizar o login para acessar a página!</p>";

    // Redireciona o o usuário para o arquivo index.php
    header("Location: index.php");

    // Pausar o processamento da página
    exit();
}

$query_nota = "SELECT id_nota FROM notas WHERE id_nota = $id LIMIT 1";
$result_nota = $conn->prepare($query_nota);
$result_nota->execute();

if(($result_nota) AND ($result_nota->rowCount() != 0)){
    $query_del_nota = "DELETE FROM notas WHERE id_nota = $id";
    $apagar_nota = $conn->prepare($query_del_nota);
    
    if($apagar_nota->execute()){
        // Criar a mensagem de erro e atribuir para variável global
        $_SESSION['msg'] = "<p style='color: green;'>Erro: Nota apagada com sucesso!</p>";

    }else{
        // Criar a mensagem de erro e atribuir para variável global
        $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Nota não foi apagada com sucesso!</p>";

        // Redireciona o o usuário para o arquivo index.php
        header("Location: index.php");

        // Pausar o processamento da página
        exit();
    }
}else{
    // Criar a mensagem de erro e atribuir para variável global
    $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Nota não foi apagada com sucesso!</p>";

    // Redireciona o o usuário para o arquivo index.php
    header("Location: index.php");

    // Pausar o processamento da página
    exit();
}
    header('Location: index.php');
   
?>