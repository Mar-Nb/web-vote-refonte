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

if (statut != null && statut != "") {
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

// JS pour le toast de la page de vote
let toastTrigger = document.getElementById('btnVote');
let voteToast = document.getElementById('voteToast');
let ajax, dataPost , tabDataPost = [];

if (toastTrigger) {
  toastTrigger.onclick = function() {
    choix = document.querySelectorAll("select[name*='vote-']");
    choix.forEach(function(v) {
      const nom = v.name;
      const valeur = v.value;  
      tabDataPost.push(encodeURIComponent(nom) + "=" + encodeURIComponent(valeur));
    });
  
    dataPost = tabDataPost.join("&");
    makeRequest(dataPost);
  }
  
  function makeRequest(data) {
    ajax = new XMLHttpRequest();
    if (! ajax) { alert("Impossible de créer un objet XMLHTTP."); return false; }

    ajax.onreadystatechange = toastContents;
    ajax.open('POST', 'actionVote.php');
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    ajax.send(data);
  };

  function toastContents() {
    if (ajax.readyState === XMLHttpRequest.DONE) {
      // En cas de votes successifs
      document.querySelector("img[src*='assets'")?.remove();
      voteToast.classList.remove("bg-success", "bg-danger");

      let img = document.createElement("img");
      img.classList.add("me-2");
      img.style = "width: 7%;";

      if (ajax.status === 200) {
        voteToast.classList.add("bg-success");
        img.alt = "Image vectorielle de bulletin de vote valide, de couleur vert, tiré du site FontAwesome.com";
        document.getElementById("toastTitle").textContent = "À voter !";
        document.getElementById("toastBody").textContent = "Votre vote a bien été transmis.";

        img.src = "assets/img/vote-yea-solid.svg";
        document.getElementsByClassName("toast-header")[0].insertBefore(img, document.getElementById("toastTitle"));

        let toast = new bootstrap.Toast(voteToast);
        toast.show();
        // let response = JSON.parse(ajax.responseText);
      } else {
        voteToast.classList.add("bg-danger");
        img.alt = "Image vectorielle de croix d'invalidation, de couleur rouge, tiré du site FontAwesome.com";
        document.getElementById("toastTitle").textContent = "Erreur !";
        document.getElementById("toastBody").textContent = "Votre vote n'a pas pu être transmis.";

        img.src = "assets/img/times-circle-solid.svg";
        document.getElementsByClassName("toast-header")[0].insertBefore(img, document.getElementById("toastTitle"));

        let toast = new bootstrap.Toast(voteToast);
        toast.show();
      }
    }
  }
}