// JS de la navbar
const navbarLink = document.querySelectorAll("nav a.nav-link");
navbarLink.forEach(function(link) { link.classList.remove("active"); });
document.querySelector(".visually-hidden")?.remove();

const url = window.location.search;
const queryStatut = new URLSearchParams(url);
const statut = queryStatut.get("statut");

/**
 * Create a span element with a class of "visually-hidden" and a text content of "(current)"
 * @returns a span element with a class of "visually-hidden" and text content of "(current)".
 */
function createHiddenSpan() {
  let span = document.createElement("span");
  span.classList.add("visually-hidden");
  span.textContent = "(current)";
  return span;
}

/**
 * Add the active class to the element with the specified id and add a hidden span to the element
 * @param id - The id of the element to be activated.
 */
function putActiveClass(id) {
  let elem = document.getElementById(id);
  elem.classList.add("active");
  elem.appendChild(createHiddenSpan());
}

if (statut != null && statut != "") { putActiveClass(statut); }
else if (window.location.href.toLowerCase().includes("admin")) { putActiveClass("admin"); }
else { putActiveClass("accueil"); }

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
  

  /**
   * The function `makeRequest` creates an XMLHttpRequest object, call the function `toastContents()` when the request is complete.
   * @param data - The data to be sent to the server.
   * @returns The response from the server.
   */
  function makeRequest(data) {
    ajax = new XMLHttpRequest();
    if (! ajax) { alert("Impossible de créer un objet XMLHTTP."); return false; }

    ajax.onreadystatechange = toastContents;
    ajax.open('POST', 'actionVote.php');
    ajax.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    ajax.send(data);
  };

  /**
   * When the AJAX request is complete, the function checks the status of the request. 
   * * If the request was successful, the function removes the image of the previous vote and adds a
   * green checkmark image. 
   * * If the request was not successful, the function removes the image of the previous vote and adds
   * a red cross image. 
   * * The function then shows a toast message
   */
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

// Menu déroulant des profs, page Admin
document.getElementById("select-prof").addEventListener("change", function() {
  var ajax = new XMLHttpRequest();
  var formData = new FormData();

  formData.append("ajax", "graph");
  formData.append("idProf", this.value);

  ajax.open("POST", "reqAjax.php");
  ajax.onload = function() {
    res = JSON.parse(ajax.response);

    // Les graph partagent la même conf, d'où les lignes suivantes
    // Maj graph donut
    myChartDonut.data.datasets[0].data = res.data;
    myChartDonut.config.options.scales.x.display = false;
    myChartDonut.config.type = "doughnut";
    myChartDonut.update();

    // Maj graph bar
    myChartHist.data.datasets[0].data = res.data;
    myChartHist.config.options.scales.x.display = true;
    myChartHist.config.type = "bar";
    myChartHist.update();
  };

  ajax.send(formData);
});

window.addEventListener("close", function(e) { e.preventDefault(); window.location.href = "logout.php"; this.close(); });