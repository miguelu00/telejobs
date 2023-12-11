//Este fichero se usará para settear una cookie.
// Esta cookie reflejará si el usuario logeado se ha registrado parcialmente.
//  en caso de que el usuario haya registrado todos sus datos, la cookie no se registrará.

//Para saber esto, usamos ajax y un select en SQL
$.ajaxSetup({
    url: "../Repositories/API.php",
    global: false,
    type: "POST",
    statusCode: {
        404: function() {
            alert("FALLO AL SACAR EL DATO!");
        },
        200: function() {
            alert("HECHO!");
        },
        400: function() {
            alert("BAD REQUEST! Contacte con el admin.");
        }
    }
});
$.ajax({
    data: {

    }
});

if (getCookie("regParcial") !== "") {

}

function setCookie(cookieName, cookieValue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    let expires = "expires=" + d.toUTCString();

    document.cookie = cookieName + "=" + cookieValue + ";" + expires + ";path=/";
}

function getCookie(cookieName) {
    let name = cookieName + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for(let i = 0; i <ca.length; i++) {
      let c = ca[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }
    return "";
  }