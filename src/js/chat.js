// Script que manejará el chat
var interval1 = null;
$(document).ready(function() {
    interval1 = setInterval(function(){
        $.ajax({
            type: "POST",
            url: '../../js/getchat.php',
            success: function(data){
                $('#div_message').html(data);
            }
        });
    },2000);
});

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
    if (e.target !== document.querySelector("dialog#userMenu1") && e.target !== document.querySelector("a.usermenu-toggle *")) {
        $("dialog#userMenu1").slideUp();
        document.querySelector("a.usermenu-toggle").style.pointerEvents = "all";
    }
});