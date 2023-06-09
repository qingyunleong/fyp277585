function openForm() {
    document.getElementById("myForm").style.display = "block";
    document.getElementById("main").style.filter = "grayscale(1) blur(2px)" ;
}

function closeForm() {
    document.getElementById("myForm").style.display = "none";
    document.getElementById("main").style.filter = "grayscale(0) blur(0)" ;
}