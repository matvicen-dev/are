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
    <title>Are - Login</title>
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
    .inp{
        height: 20px;
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
    <?php
    // Exemplo criptografar a senha
    // echo password_hash('123456', PASSWORD_DEFAULT);
?>
    <h1>ARE</h1>

    <?php
    // Receber os dados do formulário
$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

// Acessa o IF quando o usuário clicou no botão "Acessar" do formulário
if (!empty($dados['SendLogin'])) {
    //var_dump($dados);

    // QUERY para recuperar o usuário do banco de dados
    $query_usuario = "SELECT id, nome, usuario, email, senha 
                FROM usuarios
                WHERE usuario =:usuario
                LIMIT 1";

    // Preparar a QUERY
    $result_usuario = $conn->prepare($query_usuario);

    // Substitui o link ":usuario" pelo valor que vem do formulário
    $result_usuario->bindParam(':usuario', $dados['usuario']);

    // Executar a QUERY
    $result_usuario->execute();

    // Acessa o IF quando encontrou usuário no banco de dados
    if (($result_usuario) and ($result_usuario->rowCount() != 0)) {
        // Ler o resultado retornado do banco de dados
        $row_usuario = $result_usuario->fetch(PDO::FETCH_ASSOC);
        //var_dump($row_usuario);

        // Verificar se a senha digitada pelo usuário no formulário é igual a senha salva no banco de dados
        if (password_verify($dados['senha'], $row_usuario['senha'])) {
            // O JWT é divido em três partes separadas por ponto ".": um header, um payload e uma signature

            // Header indica o tipo do token "JWT", e o algoritmo utilizado "HS256"
            $header = [
                'alg' => 'HS256',
                'typ' => 'JWT'
                ];
            //var_dump($header);

            // Converter o array em objeto
            $header = json_encode($header);
            //var_dump($header);

            // Codificar dados em base64
            $header = base64_encode($header);

            // Imprimir o header
            //var_dump($header);

            // O payload é o corpo do JWT, recebe as informações que precisa armazenar
            // iss - O domínio da aplicação que gera o token
            // aud - Define o domínio que pode usar o token
            // exp - Data de vencimento do token
            // 7 days; 24 hours; 60 mins; 60secs
            $duracao = time() + (7 * 24 * 60 * 60);
            // 5 segundos
            // $duracao = time() + (5);

            $payload = [
                /*'iss' => 'localhost',
                'aud' => 'localhost',*/
                'exp' => $duracao,
                'id' => $row_usuario['id'],
                'nome' => $row_usuario['nome'],
                'email' => $row_usuario['email']
            ];

            // Converter o array em objeto
            $payload = json_encode($payload);
            //var_dump($payload);

            // Codificar dados em base64
            $payload = base64_encode($payload);

            // Imprimir o payload
            //var_dump($payload);

            // O signature é a assinatura. 
            // Chave secreta e única
            $chave = "DGBU85S46H9M5W4X6OD7";
            
            // Pegar o header e o payload e codificar com o algoritmo sha256, junto com a chave
            // Gera um valor de hash com chave usando o método HMAC
            $signature = hash_hmac('sha256', "$header.$payload", $chave, true);

            // Codificar dados em base64
            $signature = base64_encode($signature);

            // Imprimir o signature
            //var_dump($signature);

            // Imprimir o token
            //echo "Token: $header.$payload.$signature <br>";

            // Salvar o token em cookies
            // Cria o cookie com duração 7 dias
            setcookie('token', "$header.$payload.$signature", (time() + (7 * 24 * 60 * 60)));

            // Redirecionar o usuário para página dashboard
            header("Location: are/menu/index.php");

        } else {
            // Criar a mensagem de erro e atribuir para variável global "msg"
            $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Usuário ou senha inválida!</p>";
        }
    } else {
        // Criar a mensagem de erro e atribuir para variável global "msg"
        $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Usuário ou senha inválida!</p>";
    }
}

// Verificar se existe a variável global "msg" e acessa o IF
if (isset($_SESSION['msg'])) {
    // Imprimir o valor da variável global "msg"
    echo $_SESSION['msg'];

    // Destruir a variável globar "msg"
    unset($_SESSION['msg']);
}
?>

    <!-- Início do formulário de login -->
    <form method="POST" action="">

        <?php
            $usuario = "";
if (isset($dados['usuario'])) {
    $usuario = $dados['usuario'];
}
?>
        <label >Usuário: </label>
        <input class="inp" type="text" name="usuario" placeholder="Digite o usuário" value="<?php echo $usuario; ?>"><br>

        <?php
    $senha = "";
if (isset($dados['senha'])) {
    $senha = $dados['senha'];
}
?>        
        <label>Senha: </label>
        <input class="inp" type="password" name="senha" placeholder="Digite a senha" value="<?php echo $senha; ?>"><br><br>

        <input class="sub" type="submit" name="SendLogin" value="Acessar"><br><br>

    </form>
    <!-- Fim do formulário de login -->

    <span style="color: white;">Não possui conta?</span> <a href="cadastrar.php">Cadastrar</a>

    <br><br>
    
</body>
</html>