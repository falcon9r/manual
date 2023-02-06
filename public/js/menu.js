const aside = document.querySelector("aside");
const menuBtn = document.querySelector("#menu-btn");
const contentHolder = document.querySelector("main .content");
const stickyHeader = document.querySelector(".sticky-header");
var hide = function (event) {
    aside.classList.remove('show-aside');
};

menuBtn.addEventListener('click', () => {
    if (aside.classList.contains('show-aside') && aside.classList.contains('animate-aside')) {
        aside.classList.remove('animate-aside');
        aside.addEventListener('transitionend', hide, { once: true });
        toggleScrolling(0)
    } else if (aside.classList.contains('show-aside') && !aside.classList.contains('animate-aside')) {
        aside.classList.add('animate-aside');
        aside.removeEventListener('transitionend', hide);
        toggleScrolling(1)
    } else {
        aside.classList.add('show-aside');
        setTimeout(function () {
            aside.classList.add('animate-aside');
        }, 20);
        toggleScrolling(1)
    }
})


window.onclick = function (event) {
    if (aside.classList.contains('show-aside')) {
        let menuParent = event.target.closest('aside');
        if (aside.classList.contains('animate-aside') &&
            menuParent != aside && event.target.closest('.sticky-header') != stickyHeader) {
            aside.classList.remove('animate-aside');
            aside.addEventListener('transitionend', hide, { once: true });
            toggleScrolling(0)
        }
    }
}





function toggleScrolling(state) {
    if (contentHolder) {
        if (state === 1) {
            contentHolder.classList.add("fixed-position");
        } else {
            contentHolder.classList.remove("fixed-position");
        }
    } else {
        if (state === 1) {
            document.body.classList.add("fixed-position");
        } else {
            document.body.classList.remove("fixed-position");
        }
    }
}

