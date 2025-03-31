document.addEventListener("DOMContentLoaded", function() {
    const buttons = document.getElementsByClassName("dropdown-button");
    for (let button of buttons) // in enumerates over keys, while of enumerates over values
        button.addEventListener("click", display_children);
});

function display_children(event) {
    const sender = event.target;
    const container = sender.nextElementSibling;
    const currentDisplay = window.getComputedStyle(container).display;  // because when set in css, the display is "", not "none"
    container.style.display = currentDisplay == "none" ? "block" : "none";
}
