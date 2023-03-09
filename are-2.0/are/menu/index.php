<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="style.css"> -->
    <title>ARE</title>
</head>

<style>

body {
    background-color: #23355F;
}

header {
    display: flex;
    flex-direction: row;
    justify-content: space-around;
    align-items: center;
}

.link-button {
    appearance: button;
    padding: 10px 40px;
    color: #85A4F5;
    background: #2E3E68;
    border: none;
    border-radius: 4px;
    text-align: center;
    user-select: none;
    cursor: pointer;
    text-decoration: none;
    transform: 0.4s;
}

.link-button:hover {
    background-color: #fff;
    color: #333;
    transition: backgrouns 1s;
    font-weight: bold;
}

/* .two {
    background: #fdfdfd;
    color: black;
    border: 2px solid #333;
}

.two:hover {
    background-color: #000000;
    color: #ffffff;
    transition: backgrouns 1s;
} */
header{
    display: flex;
    flex-direction: row;
    justify-content: end;
}
.btn-sair{
    margin-top: 10px;
    border-radius: 4px;
    color: white;
    text-decoration: none;
}
.btn-sair:hover{
    text-decoration: underline;
    border: 1px solid white;

}
.main{
    height: 800px;
    display: flex;
    flex-direction: row;
    justify-content: space-around;
    align-items: center;

}


</style>

<body>
    <header>
        
        <div>
            <?php
            
            // Link para sair e apagar cookie token
            echo "<a class='btn-sair' href='../../logout.php'>Sair</a><br>";

            ?>
        </div>
    </header>
    <div class="main">
        <a class="link-button" href="../flashcards/flashcards.php">Flashcards</a>
        <a class="link-button" href="../notas/index.php">Notas</a>
    </div>
</body>

</html>