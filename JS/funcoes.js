document.addEventListener("DOMContentLoaded", () => {

    function scrollToNext() {
        document.getElementById("steps-section").scrollIntoView({
            behavior: "smooth"
        });
    }

    window.scrollToNext = scrollToNext;

    //Animação ao rolar
    const elements = document.querySelectorAll('.fade-scroll');

    function revealOnScroll() {
        const triggerBottom = window.innerHeight * 0.85;

        elements.forEach(el => {
            const boxTop = el.getBoundingClientRect().top;

            if (boxTop < triggerBottom) {
                el.classList.add('active');
            }
        });
    }

    window.addEventListener('scroll', revealOnScroll);
    window.addEventListener('load', revealOnScroll);
    revealOnScroll(); // já ativa ao carregar
});

// FUNÇÃO SCROLL SUAVE
function voltarAoTopo() {
    window.scrollTo({
        top: 0,
        behavior: "smooth"
    });
}

// MOSTRAR/ESCONDER BOTÃO
const btnTopo = document.getElementById("btn-topo");

window.addEventListener("scroll", () => {
    if (window.scrollY > 300) {
        btnTopo.classList.add("show");
    } else {
        btnTopo.classList.remove("show");
    }
});