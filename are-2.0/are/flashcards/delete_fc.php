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
    header("Location: flashcards.php");

    // Pausar o processamento da página
    exit();
}

$query_flashcard = "SELECT id_flashcard FROM flashcards WHERE id_flashcard = $id LIMIT 1";
$result_flashcard = $conn->prepare($query_flashcard);
$result_flashcard->execute();

if(($result_flashcard) AND ($result_flashcard->rowCount() != 0)){
    $query_del_flashcard = "DELETE FROM flashcards WHERE id_flashcard = $id";
    $apagar_flashcard = $conn->prepare($query_del_flashcard);
    
    if($apagar_flashcard->execute()){
        // Criar a mensagem de erro e atribuir para variável global
        $_SESSION['msg'] = "<p style='color: green;'>Erro: Flashcard apagada com sucesso!</p>";

    }else{
        // Criar a mensagem de erro e atribuir para variável global
        $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Flashcard não foi apagada com sucesso!</p>";

        // Redireciona o o usuário para o arquivo index.php
        header("Location: flashcards.php");

        // Pausar o processamento da página
        exit();
    }
}else{
    // Criar a mensagem de erro e atribuir para variável global
    $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Flashcard não foi apagada com sucesso!</p>";

    // Redireciona o o usuário para o arquivo index.php
    header("Location: flashcards.php");

    // Pausar o processamento da página
    exit();
}
    header('Location: flashcards.php');
   
?>