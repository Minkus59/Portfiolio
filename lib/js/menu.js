$(window).scroll(function() {
    var scroll = $(window).scrollTop();
    if (scroll == 0) {
        $("nav").addClass("relativ");
        $("nav").removeClass("fixedTop");
        $("#Logo").addClass("relativ");
        $("#Logo").removeClass("Logo");
        $("#LogoNav").addClass("Logo");
        $("#LogoNav").removeClass("relativ");
        $("header").removeClass("fixed");
    }
    else {
        $("nav").removeClass("relativ");
        $("nav").addClass("fixedTop");
        $("#Logo").addClass("Logo");
        $("#LogoNav").addClass("relativ");
        $("#LogoNav").removeClass("Logo");
        $("header").addClass("fixed");
    }
});