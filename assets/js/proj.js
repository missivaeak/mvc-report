export default () => {
    const lines = document.getElementsByClassName('event-line');
    // console.log(lines.length);
    for (let i = 0; i < lines.length; i++) {
        const delay = 600 * i;
        setTimeout(()=>{
            lines[i].style = "animation: jump-text .4s ease 2 alternate;";
        }, delay);
    }
}