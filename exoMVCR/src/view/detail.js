"use strict";
let button_detail = document.getElementsByClassName("detail");
for (let index = 0; index < button_detail.length; index++) {
    const button = button_detail[index];
    button.addEventListener("click", () => {
        button.dataset.toggle = (button.dataset.toggle === "true") ? "false" : "true";
        button.textContent = (button.dataset.toggle === "true") ? "cacher détails" : "afficher détails";
        (button.dataset.toggle === "true") ? getDetail(button.value) : deleteDetail(button.value);
    });
}

function getDetail(id) {
    const request = new XMLHttpRequest();

    request.addEventListener("load", (event) => {
        displayDetail(id,event.target.response);
    });

    request.open("get","json/"+id);
    request.responseType = "json";
    request.send();
}

function displayDetail(id,animal) {
    const li = document.getElementById(id);

    const species = animal["species"];
    const age = animal["age"];

    const detail = document.createElement("p");
    detail.textContent = "Espece : " + species + ", Age : " + age;

    li.appendChild(detail);
}

function deleteDetail(id) {
    const li = document.getElementById(id);
    li.removeChild(li.lastElementChild);
}