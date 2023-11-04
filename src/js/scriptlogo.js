if (document.querySelector("#img-logo").src.endsWith("telejobsEMP_logoNAV.png")) {
    //SCRIPTS PARA EMPRESA
    $("ul.barra-nav li:first-child a").on('mouseenter', function() {
        $(this).html(
            '<img id="img-logo" class="img-nav" src="../../logo/telejobsEMP_logoNAV_hover.png" alt="logoEmp"/>'
        );
        // $("img.img-nav").removeClass("no-border");
        // $("img.img-nav").addClass("border-black");
    });
    $("ul.barra-nav li:first-child a").on('mouseleave', function() {
        $(this).html(
            '<img id="img-logo" class="img-nav" src="../../logo/telejobsEMP_logoNAV.png" alt="logoEmp"/>'
        );
        // $("img.img-nav").removeClass("border-black");
        // $("img.img-nav").addClass("no-border");
    });

    $("div#ajax-ofertas div div.slider").each(function() {
        $(this).on('click', function(index, element) {
            $(this).next().slideToggle("fast");
        });
    });
} else if (document.querySelector("#img-logo").src.endsWith("telejobsDEM_logoNAV.png")) {
    //SCRIPTS PARA DEMANDANTE
    $("ul.barra-nav li:first-child a").on('mouseenter', function() {
        $(this).html(
            '<img id="img-logo" class="img-nav" src="../../logo/telejobsDEM_logoNAV_hover.png" alt="logoEmp"/>'
        );
        // $("img.img-nav").removeClass("no-border");
        // $("img.img-nav").addClass("border-black");
    });
    $("ul.barra-nav li:first-child a").on('mouseleave', function() {
        $(this).html(
            '<img id="img-logo" class="img-nav" src="../../logo/telejobsDEM_logoNAV.png" alt="logoEmp"/>'
        );
        // $("img.img-nav").removeClass("border-black");
        // $("img.img-nav").addClass("no-border");
    });
}