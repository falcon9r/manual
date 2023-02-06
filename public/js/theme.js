const themeToggle = document.querySelector(".theme-toggle");


themeToggle.addEventListener('click', () => {
   toggle();
})


function toggle() {
    document.body.classList.toggle('dark-theme-variables');
    themeToggle.querySelector('span:nth-child(1)').classList.toggle('active');
    themeToggle.querySelector('span:nth-child(2)').classList.toggle('active');

    fetch("/dashboard/toggle-theme", {
        method: "POST",
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf
        },
        body: JSON.stringify({
            'key': 'settings-dark-theme',
            'value': document.body.classList.contains("dark-theme-variables")
        })
    });
}
