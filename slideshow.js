// Espera a que el contenido HTML de la página se cargue completamente
document.addEventListener("DOMContentLoaded", function() {
    // Define el índice de la diapositiva actual
    var slideIndex = 0;

    // Llama a la función para mostrar la diapositiva actual
    showSlides();

    // Función para mostrar la diapositiva actual
    function showSlides() {
        var i;
        var slides = document.getElementsByClassName("mySlides");
        var dots = document.getElementsByClassName("dot");
        // Oculta todas las diapositivas
        for (i = 0; i < slides.length; i++) {
            slides[i].style.display = "none";
        }
        // Incrementa el índice de la diapositiva y reinicia si es necesario
        slideIndex++;
        if (slideIndex > slides.length) { slideIndex = 1 }
        // Muestra la diapositiva actual y actualiza los puntos
        slides[slideIndex - 1].style.display = "block";
        setTimeout(showSlides, 2000); // Cambia la imagen cada 2 segundos
    }
});

// Variable para controlar el índice de la diapositiva actual
var slideIndex = 1;

// Función para avanzar o retroceder en las diapositivas
function plusSlides(n) {
    showSlides(slideIndex += n);
}

// Función para mostrar una diapositiva específica
function currentSlide(n) {
    showSlides(slideIndex = n);
}

// Función principal para mostrar las diapositivas
function showSlides(n) {
    var i;
    var slides = document.getElementsByClassName("mySlides");
    var dots = document.getElementsByClassName("dot");
    if (n > slides.length) {slideIndex = 1}    
    if (n < 1) {slideIndex = slides.length}
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";  
    }
    slides[slideIndex-1].style.display = "block";  
}
