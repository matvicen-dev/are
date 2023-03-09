function startTimer(duration, display) {

  var timer = duration, minutes, seconds;

  setInterval(function () {

    minutes = parseInt(timer / 60);
    seconds = parseInt(timer % 60);

    minutes = minutes < 10 ? "0" + minutes : minutes;
    seconds = seconds < 10 ? "0" + seconds : seconds;

    display.textContent = minutes + ":" + seconds;

    if (--timer < 0) {
      timer = duration;
    }
  }, 1000);
}

window.onload = function () {

  var duration = 60 * 10; //Conveção para segundos
  var display = document.querySelector("#timer"); //Elemento para exibir o timer

  startTimer(duration, display); //Starta o contador

}

// const container = document.querySelector(".container");
// const addQuestionCard = document.getElementById("add-question-card");
// const cardButton = document.getElementById("save-btn");
// const question = document.getElementById("question");
// const answer = document.getElementById("answer");
// const errorMessage = document.getElementById("error");
// const addQuestion = document.getElementById("add-flashcard");
// const closeBtn = document.getElementById("close-btn");
// let editBool = false;

// //Adicionar pergunta quando o usuário clicar no botão 'Adicionar Flashcard'
// addQuestion.addEventListener("click", () => {
//   container.classList.add("hide");
//   question.value = "";
//   answer.value = "";
//   addQuestionCard.classList.remove("hide");
// });

// //Ocultar criar cartão flashcard
// closeBtn.addEventListener(
//   "click",
//   (hideQuestion = () => {
//     container.classList.remove("hide");
//     addQuestionCard.classList.add("hide");
//     if (editBool) {
//       editBool = false;
//       submitQuestion();
//     }
//   })
// );

// //Enviar pergunta
// cardButton.addEventListener(
//   "click",
//   (submitQuestion = () => {
//     editBool = false;
//     tempQuestion = question.value.trim();
//     tempAnswer = answer.value.trim();
//     if (!tempQuestion || !tempAnswer) {
//       errorMessage.classList.remove("hide");
//     } else {
//       container.classList.remove("hide");
//       errorMessage.classList.add("hide");
//       viewlist();
//       question.value = "";
//       answer.value = "";
//     }
//   })
// );

// //Cartão Gerar
// function viewlist() {
//   var listCard = document.getElementsByClassName("card-list-container");
//   var div = document.createElement("div");
//   div.classList.add("card");
//   //Pergunta
//   div.innerHTML += `
//   <p class="question-div">${question.value}</p>`;
//   //Resposta
//   var displayAnswer = document.createElement("p");
//   displayAnswer.classList.add("answer-div", "hide");
//   displayAnswer.innerText = answer.value;

//   //Botão para mostrar/esconder resposta
//   var link = document.createElement("a");
//   link.setAttribute("href", "#");
//   link.setAttribute("class", "show-hide-btn");
//   link.innerHTML = "Mostrar/Esconder";
//   link.addEventListener("click", () => {
//     displayAnswer.classList.toggle("hide");
//   });

//   div.appendChild(link);
//   div.appendChild(displayAnswer);

//   //Botão editar
//   let buttonsCon = document.createElement("div");
//   buttonsCon.classList.add("buttons-con");
//   var editButton = document.createElement("button");
//   editButton.setAttribute("class", "edit");
//   editButton.innerHTML = `<i class="fa-solid fa-pen-to-square"></i>`;
//   editButton.addEventListener("click", () => {
//     editBool = true;
//     modifyElement(editButton, true);
//     addQuestionCard.classList.remove("hide");
//   });
//   buttonsCon.appendChild(editButton);
//   disableButtons(false);

//   //Botão delete
//   var deleteButton = document.createElement("button");
//   deleteButton.setAttribute("class", "delete");
//   deleteButton.innerHTML = `<i class="fa-solid fa-trash-can"></i>`;
//   deleteButton.addEventListener("click", () => {
//     modifyElement(deleteButton);
//   });
//   buttonsCon.appendChild(deleteButton);

//   div.appendChild(buttonsCon);
//   listCard[0].appendChild(div);
//   hideQuestion();
// }

// //Modificar Elementos
// const modifyElement = (element, edit = false) => {
//   let parentDiv = element.parentElement.parentElement;
//   let parentQuestion = parentDiv.querySelector(".question-div").innerText;
//   if (edit) {
//     let parentAns = parentDiv.querySelector(".answer-div").innerText;
//     answer.value = parentAns;
//     question.value = parentQuestion;
//     disableButtons(true);
//   }
//   parentDiv.remove();
// };

// //Desativa os botões de edição e exclusão
// const disableButtons = (value) => {
//   let editButtons = document.getElementsByClassName("edit");
//   Array.from(editButtons).forEach((element) => {
//     element.disabled = value;
//   });
// };
