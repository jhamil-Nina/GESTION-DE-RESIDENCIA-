// Personaliza aquí el nombre y la duración de la celebración.
const CONFIG = {
    name: "Majerly",
    birthdayDuration: 12500
};

const reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
const timeScale = reducedMotion ? 0.18 : 1;
const wait = (milliseconds) => new Promise((resolve) => {
    window.setTimeout(resolve, milliseconds * timeScale);
});

const scenes = Array.from(document.querySelectorAll(".escena"));
const rainLayer = document.getElementById("lluvia-palabras");
const confettiLayer = document.getElementById("confeti");
const countdown = document.getElementById("numero");
const messageWord = document.getElementById("palabra");
const cardFrame = document.getElementById("marco-carta");
const cardCover = document.getElementById("portada-carta");
const cardContent = document.getElementById("interior-carta");
const album = document.getElementById("album");
const albumPages = Array.from(document.querySelectorAll(".pagina-album"));
const previousPageButton = document.getElementById("pagina-anterior");
const nextPageButton = document.getElementById("pagina-siguiente");
const pageCounter = document.getElementById("contador-paginas");
const pageMessage = document.getElementById("mensaje-recuerdo");
const albumMessages = [
    "Hoy celebro tu risa, tu luz y todas las formas en que haces más bonito el mundo. Que cada sueño encuentre su camino hasta ti. Gracias por ser, sencillamente, tú.",
    "Hoy el mundo celebra un año más de tu existencia, y yo celebro la dicha de conocerte. Eres de esas personas que tienen una luz propia, de esas que iluminan los lugares con su sola presencia. Deseo de todo corazón que este nuevo año te traiga mil motivos para sonreír y que nunca te falte nada. ¡Feliz cumpleaños!"
];

let activeScene = "lluvia";
let sceneTransitioning = false;
let cardTransitioning = false;
let pageTransitioning = false;
let currentPage = 0;
let celebrationTimer = null;
let pointerStartX = null;
let pointerStartY = null;

function personalize() {
    document.title = `Una sorpresa para ${CONFIG.name}`;
    document.querySelectorAll("#nombre-fiesta, #nombre-portada, #nombre-carta").forEach((element) => {
        element.textContent = CONFIG.name;
    });
}

function createWordRain() {
    const characters = "HAPPYBIRTHDAY";
    const colors = ["#ff65b5", "#f83e9e", "#d93691", "#ff91ca"];
    const columnCount = Math.ceil(window.innerWidth / 18);

    for (let index = 0; index < columnCount; index++) {
        const column = document.createElement("span");
        column.className = "palabra-lluvia";
        const startingCharacter = Math.floor(Math.random() * characters.length);
        column.textContent = Array.from({ length: 39 }, (_, characterIndex) => (
            characters[(startingCharacter + characterIndex) % characters.length]
        )).join("\n");
        column.style.setProperty("--posicion-x", `${index * (window.innerWidth / columnCount)}px`);
        column.style.setProperty("--inicio-y", `${-Math.random() * 100}vh`);
        column.style.setProperty("--tamano", `${0.6 + Math.random() * 0.2}rem`);
        column.style.setProperty("--opacidad", `${0.32 + Math.random() * 0.45}`);
        column.style.setProperty("--duracion", `${7 + Math.random() * 7}s`);
        column.style.setProperty("--retraso", `${-Math.random() * 12}s`);
        column.style.setProperty("--color-palabra", colors[Math.floor(Math.random() * colors.length)]);
        rainLayer.appendChild(column);
    }
}

function createConfetti() {
    const colors = ["#efb889", "#e89a9d", "#d9c4ed", "#f5d8a2", "#fff1df"];

    for (let index = 0; index < 34; index++) {
        const piece = document.createElement("span");
        piece.className = "confeti-pieza";
        piece.style.setProperty("--x", `${Math.random() * 100}%`);
        piece.style.setProperty("--ancho", `${3 + Math.random() * 5}px`);
        piece.style.setProperty("--alto", `${6 + Math.random() * 10}px`);
        piece.style.setProperty("--color", colors[Math.floor(Math.random() * colors.length)]);
        piece.style.setProperty("--tiempo", `${5 + Math.random() * 7}s`);
        piece.style.setProperty("--demora", `${-Math.random() * 12}s`);
        piece.style.setProperty("--deriva", `${-50 + Math.random() * 100}px`);
        confettiLayer.appendChild(piece);
    }
}

async function transitionTo(sceneId) {
    if (sceneTransitioning || activeScene === sceneId) return false;

    const outgoing = document.getElementById(activeScene);
    const incoming = document.getElementById(sceneId);
    if (!outgoing || !incoming) return false;

    sceneTransitioning = true;
    outgoing.classList.remove("activa");
    outgoing.classList.add("saliendo");
    await wait(480);
    outgoing.classList.remove("saliendo");
    outgoing.setAttribute("aria-hidden", "true");
    incoming.removeAttribute("aria-hidden");
    incoming.classList.add("activa");
    activeScene = sceneId;
    sceneTransitioning = false;
    return true;
}

function showCountdownNumber(value) {
    countdown.textContent = String(value);
    countdown.classList.remove("cambio");
    void countdown.offsetWidth;
    countdown.classList.add("cambio");
}

function showMessageWord(value) {
    messageWord.textContent = value;
    messageWord.classList.remove("entrando");
    void messageWord.offsetWidth;
    messageWord.classList.add("entrando");
}

async function startExperience() {
    await wait(6500);
    await transitionTo("computadora");
    await wait(220);

    for (let value = 3; value >= 0; value--) {
        showCountdownNumber(value);
        await wait(value === 0 ? 760 : 820);
    }

    await wait(280);
    await transitionTo("mensaje");
    const birthdayWords = ["FELIZ", "CUMPLEAÑOS", CONFIG.name.toUpperCase()];
    for (const word of birthdayWords) {
        showMessageWord(word);
        await wait(word === birthdayWords[birthdayWords.length - 1] ? 1100 : 850);
    }

    await transitionTo("celebracion");
    celebrationTimer = window.setTimeout(openLetterScene, CONFIG.birthdayDuration * timeScale);
}

async function openLetterScene() {
    if (activeScene !== "celebracion") return;
    if (celebrationTimer !== null) {
        window.clearTimeout(celebrationTimer);
        celebrationTimer = null;
    }
    await transitionTo("carta");
}

function updatePageControls() {
    previousPageButton.disabled = currentPage === 0;
    nextPageButton.disabled = currentPage === albumPages.length - 1;
    pageCounter.textContent = `${String(currentPage + 1).padStart(2, "0")} / ${String(albumPages.length).padStart(2, "0")}`;
    pageMessage.textContent = albumMessages[currentPage];
}

async function turnToPage(nextIndex) {
    if (pageTransitioning || nextIndex < 0 || nextIndex >= albumPages.length || nextIndex === currentPage) return;

    pageTransitioning = true;
    const outgoing = albumPages[currentPage];
    const incoming = albumPages[nextIndex];
    outgoing.classList.add("saliendo");
    await wait(240);
    outgoing.classList.remove("activa", "saliendo");
    outgoing.hidden = true;
    incoming.hidden = false;
    incoming.classList.add("activa");
    currentPage = nextIndex;
    updatePageControls();
    await wait(460);
    pageTransitioning = false;
}

async function openCard() {
    if (cardTransitioning || cardFrame.classList.contains("abierta")) return;
    cardTransitioning = true;
    cardFrame.classList.add("abierta");
    cardContent.hidden = false;
    cardContent.setAttribute("aria-hidden", "false");
    cardCover.disabled = true;
    await wait(820);
    cardCover.hidden = true;
    cardTransitioning = false;
}

async function closeCard() {
    if (cardTransitioning || !cardFrame.classList.contains("abierta")) return;
    cardTransitioning = true;
    cardContent.setAttribute("aria-hidden", "true");
    cardContent.hidden = true;
    cardCover.hidden = false;
    cardFrame.classList.remove("abierta");
    albumPages.forEach((page, index) => {
        page.classList.toggle("activa", index === 0);
        page.classList.remove("saliendo");
        page.hidden = index !== 0;
    });
    currentPage = 0;
    updatePageControls();
    await wait(820);
    cardCover.disabled = false;
    cardTransitioning = false;
    cardCover.focus();
}

function handleAlbumSwipe(event) {
    if (pointerStartX === null || pageTransitioning) return;
    const distance = event.clientX - pointerStartX;
    pointerStartX = null;
    pointerStartY = null;
    if (Math.abs(distance) < 48) return;
    turnToPage(distance < 0 ? currentPage + 1 : currentPage - 1);
}

personalize();
createWordRain();
createConfetti();
updatePageControls();

scenes.forEach((scene) => {
    if (!scene.classList.contains("activa")) scene.setAttribute("aria-hidden", "true");
});

document.getElementById("abrir-carta").addEventListener("click", openLetterScene);
cardCover.addEventListener("click", openCard);
cardCover.addEventListener("pointerdown", (event) => {
    pointerStartX = event.clientX;
    pointerStartY = event.clientY;
});
cardCover.addEventListener("pointerup", (event) => {
    if (pointerStartX === null) return;
    const distance = event.clientX - pointerStartX;
    const verticalDistance = event.clientY - pointerStartY;
    pointerStartX = null;
    pointerStartY = null;
    if (Math.abs(distance) > 30 || Math.abs(verticalDistance) > 45) openCard();
});
cardCover.addEventListener("pointercancel", () => {
    pointerStartX = null;
    pointerStartY = null;
});
previousPageButton.addEventListener("click", () => turnToPage(currentPage - 1));
nextPageButton.addEventListener("click", () => turnToPage(currentPage + 1));
document.getElementById("cerrar-carta").addEventListener("click", closeCard);

album.addEventListener("pointerdown", (event) => {
    if (event.target.closest("button")) return;
    pointerStartX = event.clientX;
    pointerStartY = event.clientY;
});
album.addEventListener("pointerup", handleAlbumSwipe);
album.addEventListener("pointercancel", () => {
    pointerStartX = null;
    pointerStartY = null;
});

document.addEventListener("keydown", (event) => {
    if (!cardFrame.classList.contains("abierta")) return;
    if (event.key === "Escape") closeCard();
    if (event.key === "ArrowRight") turnToPage(currentPage + 1);
    if (event.key === "ArrowLeft") turnToPage(currentPage - 1);
});

startExperience();
