// This is only temporary I promise
window.addEventListener("load", function() {
    const home = document.getElementById("home-but");
    const about = document.getElementById("about-but");
    const resume = document.getElementById("resume-but");
    const projects = document.getElementById("projects-but");
    const socials = document.getElementById("socials-but");
    
    home.addEventListener('click', function() {
        window.location.href = "#";
    });
    
    about.addEventListener('click', function() {
        window.location.href = "#about";
    });
    
    resume.addEventListener('click', function() {
        window.location.href = "#resume";
    });

    projects.addEventListener('click', function() {
        window.location.href = "projects.html";
    });
    
    socials.addEventListener('click', function() {
        window.location.href = "#socials";
    });
});
