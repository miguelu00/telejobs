var evento, toggle = false, firstClick = false;

//Al hacer clic en la imagen de perfil, abrir un Dialog con las opciones
$("a.usermenu-toggle").on("click", function(e) {
    var userMenu = $("dialog#userMenu1");
    // toggle ? toggle=false : toggle=true;
    //if (toggle) { //Mostramos y colocamos el dialog
        userMenu.css("position", "absolute");
        userMenu.css("top", e.clientY);
        userMenu.css("left", e.clientX-500);
        userMenu.slideToggle();
        userMenu.focus();
        document.querySelector("a.usermenu-toggle").style.pointerEvents = "none";
    //}
});
document.querySelector("body").addEventListener("click", function(e) {
    if (e.target !== document.querySelector("dialog") && e.target !== document.querySelector("a.usermenu-toggle *") && e.target !== document.querySelector("dialog#userMenu1 *")) {
        $("dialog#userMenu1").slideUp();
        e.stopPropagation();
        document.querySelector("a.usermenu-toggle").style.pointerEvents = "all";
    }
});
if (document.title.endsWith("Empresas")) {
    $("dialog#userMenu1").css("background-color", "lightblue");
    $("dialog#userMenu1 div.abs-usermenu").css("background-color", "white");
} else if (document.title.endsWith("Empleo")) {
    $("dialog#userMenu1").css("background-color", "lightsalmon");
    $("dialog#userMenu1 div.abs-usermenu").css("background-color", "white");
}
if (document.title.endsWith("Empresas")) {
    document.querySelector("ul.barra-nav li details summary a").addEventListener("click", function(e) {
        e.target.parentElement.parentElement.toggleAttribute("open");
    });
    document.querySelector("ul.barra-nav li details summary a i").addEventListener("click", function(e) {
        e.target.parentElement.parentElement.parentElement.toggleAttribute("open");
    });
}
