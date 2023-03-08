<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>ARE</title>
</head>

<body>
    <header>
        <div>
            <a class="link-button" href="../flashcards/flashcards.php">Flashcards</a>
            <a class="link-button" href="../notas/index.php">Notas</a>
        </div>
        <div>
            <?php
            
            // Link para sair e apagar cookie token
            echo "<a class='link-button' href='../../logout.php'>Sair</a><br>";

            ?>
        </div>
    </header>
</body>

</html>