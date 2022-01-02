// JS de la navbar
const navbarLink = document.querySelectorAll("nav a.nav-link");
navbarLink.forEach(function(link) { link.classList.remove("active"); });
document.querySelector(".visually-hidden")?.remove();

const url = window.location.search;
const queryStatut = new URLSearchParams(url);
const statut = queryStatut.get("statut");

function createHiddenSpan() {
  let span = document.createElement("span");
  span.classList.add("visually-hidden");
  span.textContent = "(current)";
  return span;
}

if (statut != "") {
  let elem = document.getElementById(statut); 
  elem.classList.add("active");
  elem.appendChild(createHiddenSpan());
} else {
  let accueil = document.getElementById("accueil"); 
  accueil.classList.add("active");
  accueil.appendChild(createHiddenSpan());
}

// JS pour les inputs de connexion
const allInput = document.querySelectorAll("input.form-control");

allInput.forEach(function(input) {
  input.addEventListener('focusout', function() {
    let divErr = document.createElement("div");
    divErr.classList.add("invalid-feedback");
    divErr.textContent = "Vous devez remplir ce champ obligatoire.";
    if (input.value == "") {
      input.classList.add("is-invalid");
      input.insertAdjacentElement("afterend", divErr);
    } else if (input.value != "") {
      input.classList.remove("is-invalid");
      try { document.querySelectorAll(".invalid-feedback")?.remove(); }
      catch (error) {}
    }
  });
});