const INPUT_BUSCA = document.getElementById('input-busca');
const TABELA_NOTAS = document.getElementById('tabela-notas');

// pegando o que o usuario digita
INPUT_BUSCA.addEventListener('keyup', () => {
    let expressao = INPUT_BUSCA.value.toLowerCase();

    //limitador de busca
    if (expressao.length === 1) {
        return;
    }

    //console.log(expressao);
    let linhas = TABELA_NOTAS.getElementsByTagName('tr');

    //console.log(linhas);
    for (let posicao in linhas) {
        if (true === isNaN(posicao)) {
            continue;
        }
        //console.log(posicao);
        let conteudoDaLinha = linhas[posicao].innerHTML.toLowerCase();

        if (true === conteudoDaLinha.includes(expressao)) {
            linhas[posicao].style.display = '';
        } else {
            linhas[posicao].style.display = 'none';
        }
    }

});
