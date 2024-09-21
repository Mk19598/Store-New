function toggleNav() {
    const sidebar = document.getElementById("mySidebar");
    const main = document.getElementById("main");
    sidebar.classList.toggle("closed");
    if (window.innerWidth <= 768) {
        sidebar.classList.toggle("open");
    }
}

$(document).ready(function () {
    $(".header-dropdown-button").click(function () {
        $(".dropdown-menu").toggle();
    });

    $(document).click(function (event) {
        if (!$(event.target).closest('.header-dropdown-dropdown').length) {
            $(".dropdown-menu").hide();
        }
    });

});