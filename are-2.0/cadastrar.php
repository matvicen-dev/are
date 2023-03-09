<?php

session_start(); // Iniciar a sessão

// Limpara o buffer de redirecionamento
ob_start();

// Incluir o arquivo com a conexão com banco de dados
include_once 'conexao.php';

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Are - Login com token e cookie - Cadastrar</title>
</head>
<style>
    body{
        background: #23355F;
        text-align: center;
        margin: 12% auto;
    }
    label{
        color: #85A4F5;
        font-size: 20px;
        /* background: green; */
        display: flex;
        /* align-items: start;*/
        justify-content: center;
        position: relative;
        border-radius: 10px;
        text-align: center;
        margin: auto;
    }
    .inp{height: 20px;
        width: 250px;
        color: black;
        font-size: 13px;
        display: flex;
        justify-content: center;
        position: relative;
        border-radius: 10px;
        text-align: start;
        margin: auto;
    }
    .sub{
        height: 30px;
        width: 100px;
        font-size: 15px;
        border-radius: 5px;
    }
    .sub:hover{
        weight: bold;
        background-color: #85A4F5;
        color: white;
        border: 3px solid black;
    }
    a{
        color: #85A4F5;
    }
</style>
<body>
    <h1>Cadastrar</h1>

    <?php

    // Receber os dados do formulário
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);    

    // Acessa o IF quando o usuário clicar no botão cadastrar
    if(!empty($dados['SendCadUser'])){
        //var_dump($dados);

        // Criar a QUERY para cadastrar no banco de dados
        $query_usuario = "INSERT INTO usuarios (nome, email, usuario, senha) VALUES (:nome, :email, :usuario, :senha)";

        // Preparar a QUERY
        $cad_usuario = $conn->prepare($query_usuario);

        // Substituir o link pelo valor que vem do formulário
        $cad_usuario->bindParam(':nome', $dados['nome']);
        $cad_usuario->bindParam(':email', $dados['email']);
        $cad_usuario->bindParam(':usuario', $dados['email']);
        
        // Criptografar a senha
        $senha_criptografada = password_hash($dados['senha'], PASSWORD_DEFAULT);
        $cad_usuario->bindParam(':senha', $senha_criptografada);

        // Executar a QUERY
        $cad_usuario->execute();

        // Acessa o IF quando cadastrar o registro no banco de dados
        if($cad_usuario->rowCount()){
            // Criar a mensagem e atribuir para variável global
            $_SESSION['msg'] = "<p style='color: green;'>Usuário cadastrado com sucesso!</p>";

            // Redirecionar o usuário para a página de login
            header("Location: index.php");
        }else{
            echo "<p style='color: #f00;'>Erro: Usuário não cadastrado com sucesso!</p>";
        }
    }

    ?>

    <!-- Início do formulário cadastrar usuário -->
    <form method="POST" action="">
        <label>Nome: </label>
        <input class="inp" type="text" name="nome" placeholder="Nome completo"><br>
        
        <label>E-mail: </label>
        <input class="inp" type="email" name="email" placeholder="E-mail"><br>
        
        <label>Senha: </label>
        <input class="inp" type="password" name="senha" placeholder="Mínimo 6 caracteres"><br><br>

        <input class="sub" type="submit" name="SendCadUser" value="Cadastrar"><br><br>
    </form>
    <!-- Fim do formulário cadastrar usuário -->

    <a href="index.php">Login</a><br><br>
    
</body>
</html>