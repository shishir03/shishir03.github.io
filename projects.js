window.addEventListener("load", async function() {
    const home = document.getElementById("home-but");
    const about = document.getElementById("about-but");
    const resume = document.getElementById("resume-but");
    const projects = document.getElementById("projects-but");
    const socials = document.getElementById("socials-but");
    
    home.addEventListener('click', function() {
        window.location.href = "index.html";
    });
    
    about.addEventListener('click', function() {
        window.location.href = "inndex.html#about";
    });
    
    resume.addEventListener('click', function() {
        window.location.href = "index.html#resume";
    });

    projects.addEventListener('click', function() {
        window.location.href = "#";
    });
    
    socials.addEventListener('click', function() {
        window.location.href = "index.html#socials";
    });

    const response = await fetch("https://api.github.com/users/shishir03/repos");
    let repos = await response.json();
    repos = repos.sort((a, b) => new Date(b.pushed_at) - new Date(a.pushed_at));
    console.log(repos);
    
    for(let i = 0; i < repos.length; i++) {
        let r = repos[i];
        console.log(r);
        const projectDiv = document.getElementById("projects");
        projectDiv.innerHTML += `
        <h2 class="repo-name"><a href="${r.svn_url}">${r.name}</a></h2>
        `;
    }
});