export default () => {
    if (document.getElementById("ginrummy")) {
        setTimeout(function () {
            location.hash = "ginrummy";
        }, 100);
    }
}