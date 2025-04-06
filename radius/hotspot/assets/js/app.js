$(function () {
    "use strict";
    $(".mobile-toggle-menu").on("click", function () {
        $(".wrapper").addClass("toggled")
    }), $(".toggle-icon").click(function () {
        $(".wrapper").hasClass("toggled") ? ($(".wrapper").removeClass("toggled"), $(".sidebar-wrapper").unbind("hover")) : ($(".wrapper").addClass("toggled"), $(".sidebar-wrapper").hover(function () {
            $(".wrapper").addClass("sidebar-hovered")
        }, function () {
            $(".wrapper").removeClass("sidebar-hovered")
        }))
    }), $(document).ready(function () {
        $(window).on("scroll", function () {
            $(this).scrollTop() > 300 ? $(".back-to-top").fadeIn() : $(".back-to-top").fadeOut()
        }), $(".back-to-top").on("click", function () {
            return $("html, body").animate({
                scrollTop: 0
            }, 600), !1
        })
    }), $(function () {
        for (var e = window.location, o = $(".metismenu li a").filter(function () {
            return this.href == e
        }).addClass("").parent().addClass("mm-active"); o.is("li");) o = o.parent("").addClass("mm-show").parent("").addClass("mm-active")
    }), $(function () {
        $("#menu").metisMenu()
    }), $(".chat-toggle-btn").on("click", function () {
        $(".chat-wrapper").toggleClass("chat-toggled")
    }), $(".chat-toggle-btn-mobile").on("click", function () {
        $(".chat-wrapper").removeClass("chat-toggled")
    }), $(".email-toggle-btn").on("click", function () {
        $(".email-wrapper").toggleClass("email-toggled")
    }), $(".email-toggle-btn-mobile").on("click", function () {
        $(".email-wrapper").removeClass("email-toggled")
    }), $(".compose-mail-btn").on("click", function () {
        $(".compose-mail-popup").show()
    }), $(".compose-mail-close").on("click", function () {
        $(".compose-mail-popup").hide()
    }), $(".switcher-btn").on("click", function () {
        $(".switcher-wrapper").toggleClass("switcher-toggled")
    }), $(".close-switcher").on("click", function () {
        $(".switcher-wrapper").removeClass("switcher-toggled")
    }), $("#defaultmode").on("click", function () {
        $("html").attr("class", "");
        localStorage.setItem("sidebar", "");
        localStorage.setItem("header", "");
    }), $("#lightmode").on("click", function () {
        $("html").attr("class", "light-theme");
        localStorage.setItem("sidebar", "light-theme");
        localStorage.setItem("header", "");
    }), $("#darkmode").on("click", function () {
        $("html").attr("class", "dark-theme");
        localStorage.setItem("sidebar", "dark-theme");
        localStorage.setItem("header", "");
    }), $("#semidark").on("click", function () {
        $("html").attr("class", "semi-dark");
        localStorage.setItem("sidebar", "semi-dark");
        localStorage.setItem("header", "");
    }), $("#darkblue").on("click", function () {
        $("html").attr("class", "darkblue");
        localStorage.setItem("sidebar", "darkblue");
        localStorage.setItem("header", "");
    }), $("#headercolor1").on("click", function () {
        localStorage.setItem("header", "color-header headercolor1");
        $("html").addClass("color-header headercolor1"), $("html").removeClass("headercolor2 headercolor3 headercolor4 headercolor5 headercolor6 headercolor7 headercolor8 headercolor9 headercolor10 headercolor11 headercolor12")
    }), $("#headercolor2").on("click", function () {
        localStorage.setItem("header", "color-header headercolor2");
        $("html").addClass("color-header headercolor2"), $("html").removeClass("headercolor1 headercolor3 headercolor4 headercolor5 headercolor6 headercolor7 headercolor8 headercolor9 headercolor10 headercolor11 headercolor12")
    }), $("#headercolor3").on("click", function () {
        localStorage.setItem("header", "color-header headercolor3");
        $("html").addClass("color-header headercolor3"), $("html").removeClass("headercolor1 headercolor2 headercolor4 headercolor5 headercolor6 headercolor7 headercolor8 headercolor9 headercolor10 headercolor11 headercolor12")
    }), $("#headercolor4").on("click", function () {
        localStorage.setItem("header", "color-header headercolor4");
        $("html").addClass("color-header headercolor4"), $("html").removeClass("headercolor1 headercolor2 headercolor3 headercolor5 headercolor6 headercolor7 headercolor8 headercolor9 headercolor10 headercolor11 headercolor12")
    }), $("#headercolor5").on("click", function () {
        localStorage.setItem("header", "color-header headercolor5");
        $("html").addClass("color-header headercolor5"), $("html").removeClass("headercolor1 headercolor2 headercolor4 headercolor3 headercolor6 headercolor7 headercolor8 headercolor9 headercolor10 headercolor11 headercolor12")
    }), $("#headercolor6").on("click", function () {
        localStorage.setItem("header", "color-header headercolor6");
        $("html").addClass("color-header headercolor6"), $("html").removeClass("headercolor1 headercolor2 headercolor4 headercolor5 headercolor3 headercolor7 headercolor8 headercolor9 headercolor10 headercolor11 headercolor12")
    }), $("#headercolor7").on("click", function () {
        localStorage.setItem("header", "color-header headercolor7");
        $("html").addClass("color-header headercolor7"), $("html").removeClass("headercolor1 headercolor2 headercolor4 headercolor5 headercolor6 headercolor3 headercolor8 headercolor9 headercolor10 headercolor11 headercolor12")
    }), $("#headercolor8").on("click", function () {
        localStorage.setItem("header", "color-header headercolor8");
        $("html").addClass("color-header headercolor8"), $("html").removeClass("headercolor1 headercolor2 headercolor4 headercolor5 headercolor6 headercolor7 headercolor3 headercolor9 headercolor10 headercolor11 headercolor12")
    }), $("#headercolor9").on("click", function () {
        localStorage.setItem("header", "color-header headercolor9");
        $("html").addClass("color-header headercolor9"), $("html").removeClass("headercolor1 headercolor2 headercolor4 headercolor5 headercolor6 headercolor7 headercolor3 headercolor8 headercolor10 headercolor11 headercolor12")
    }), $("#headercolor10").on("click", function () {
        localStorage.setItem("header", "color-header headercolor10");
        $("html").addClass("color-header headercolor10"), $("html").removeClass("headercolor1 headercolor2 headercolor4 headercolor5 headercolor6 headercolor7 headercolor3 headercolor9 headercolor8 headercolor11 headercolor12")
    }), $("#headercolor11").on("click", function () {
        localStorage.setItem("header", "color-header headercolor11");
        $("html").addClass("color-header headercolor11"), $("html").removeClass("headercolor1 headercolor2 headercolor4 headercolor5 headercolor6 headercolor7 headercolor3 headercolor9 headercolor10 headercolor8 headercolor12")
    }), $("#headercolor12").on("click", function () {
        localStorage.setItem("header", "color-header headercolor12");
        $("html").addClass("color-header headercolor12"), $("html").removeClass("headercolor1 headercolor2 headercolor4 headercolor5 headercolor6 headercolor7 headercolor3 headercolor9 headercolor10 headercolor11 headercolor8")
    })


    // sidebar colors


    $('#sidebarcolor1').click(theme1);
    $('#sidebarcolor2').click(theme2);
    $('#sidebarcolor3').click(theme3);
    $('#sidebarcolor4').click(theme4);
    $('#sidebarcolor5').click(theme5);
    $('#sidebarcolor6').click(theme6);
    $('#sidebarcolor7').click(theme7);
    $('#sidebarcolor8').click(theme8);
    $('#sidebarcolor9').click(theme9);
    $('#sidebarcolor10').click(theme10);
    $('#sidebarcolor11').click(theme11);
    $('#sidebarcolor12').click(theme12);

    function theme1() {
        localStorage.setItem("sidebar", "color-sidebar sidebarcolor1");
        $('html').attr('class', 'color-sidebar sidebarcolor1');
    }

    function theme2() {
        localStorage.setItem("sidebar", "color-sidebar sidebarcolor2");
        $('html').attr('class', 'color-sidebar sidebarcolor2');

    }

    function theme3() {
        localStorage.setItem("sidebar", "color-sidebar sidebarcolor3");
        $('html').attr('class', 'color-sidebar sidebarcolor3');
    }

    function theme4() {
        localStorage.setItem("sidebar", "color-sidebar sidebarcolor4");
        $('html').attr('class', 'color-sidebar sidebarcolor4');
    }

    function theme5() {
        localStorage.setItem("sidebar", "color-sidebar sidebarcolor5");
        $('html').attr('class', 'color-sidebar sidebarcolor5');
    }

    function theme6() {
        localStorage.setItem("sidebar", "color-sidebar sidebarcolor6");
        $('html').attr('class', 'color-sidebar sidebarcolor6');
    }

    function theme7() {
        localStorage.setItem("sidebar", "color-sidebar sidebarcolor7");
        $('html').attr('class', 'color-sidebar sidebarcolor7');
    }

    function theme8() {
        localStorage.setItem("sidebar", "color-sidebar sidebarcolor8");
        $('html').attr('class', 'color-sidebar sidebarcolor8');
    }

    function theme9() {
        localStorage.setItem("sidebar", "color-sidebar sidebarcolor9");
        $('html').attr('class', 'color-sidebar sidebarcolor9');
    }

    function theme10() {
        localStorage.setItem("sidebar", "color-sidebar sidebarcolor10");
        $('html').attr('class', 'color-sidebar sidebarcolor10');
    }

    function theme11() {
        localStorage.setItem("sidebar", "color-sidebar sidebarcolor11");
        $('html').attr('class', 'color-sidebar sidebarcolor11');
    }

    function theme12() {
        localStorage.setItem("sidebar", "color-sidebar sidebarcolor12");
        $('html').attr('class', 'color-sidebar sidebarcolor12');
    }

    $(document).ready(function () {
        SetClass();
    });

    function SetClass() {
        $('html').attr('class', localStorage.getItem("sidebar"));
        $("html").addClass(localStorage.getItem("header"));
    }

});