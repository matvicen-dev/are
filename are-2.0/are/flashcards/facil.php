<?php

session_start(); // Iniciar a sessão

// Limpara o buffer de redirecionamento
ob_start();

// Inclusão da conexão com o BD
include '../../conexao.php';

$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
//var_dump($id);

$query_up_flashcard = "UPDATE flashcards SET hora = DATE_ADD(CURRENT_TIME, INTERVAL 48 HOUR) WHERE id_flashcard=$id";
            
            

            $edit_flashcard = $conn->prepare($query_up_flashcard);
            // $edit_flashcard->bindParam(':front', $dados['question']);
            // $edit_flashcard->bindParam(':back', $dados['answer']);
            // $edit_flashcard->bindParam(':id_flashcard', $id);
            $edit_flashcard->execute();
            header('Location: estudar.php');           
          
?>
