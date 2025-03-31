document.addEventListener("DOMContentLoaded", function() {
    const mainButton = document.getElementsByClassName("main-menu")[0];
    mainButton.addEventListener("click", display_principal_submenus);
});

function display_principal_submenus() {
    const submenuContainer = document.getElementsByClassName("principal-submenus")[0];
    submenuContainer.style.display = submenuContainer.style.display == "none" ? "block" : "none";
}