var cOffX = 0;
var cOffY = 0;
var fOffX = 0;
var fOffY = 0;
var mouseX = 0;
var mouseY = 0;
var conts = [];
var draggingOjb = null;
var dragStartIndex = 0;

var modal = document.getElementById("myModal");
var openModal = document.getElementById("openModal");
var span = document.getElementsByClassName("modal-close")[0];


window.onload = function () { addEventListeners(); }
window.addEventListener('click', function (event) {
    if (event.target == modal) modal.style.display = "none"; modal.classList.remove('modal-extra');
});
openModal.onclick = function () { modal.style.display = "block"; }
span.onclick = function () { modal.style.display = "none"; }


function dragStart(e) {
    e = e || window.event;
    e.preventDefault();

    draggingOjb = e.target.closest('.container');
    cOffX = e.clientX;
    cOffY = e.clientY;
    fOffX = draggingOjb.offsetLeft;
    fOffY = draggingOjb.offsetTop;
    conts = document.querySelectorAll(".card.container");

    document.addEventListener('mousemove', dragMove);
    document.addEventListener('mouseup', dragEnd);

    draggingOjb.classList.add("dragging");
    draggingOjb.style.cursor = 'move';

    dragStartIndex = draggingOjb.getAttribute('data-index');
}


function dragMove(e) {
    e = e || window.event;
    e.preventDefault();

    draggingOjb.style.top = (e.clientY - cOffY).toString() + 'px';
    draggingOjb.style.left = (e.clientX - cOffX).toString() + 'px';

    for (let cont of conts) {
        if (!cont.classList.contains("dragging")) {
            let offsetRight = cont.offsetLeft + cont.offsetWidth;
            let offsetBottom = cont.offsetTop + cont.offsetHeight;
            if (mouseX >= cont.offsetLeft && mouseX <= offsetRight &&
                mouseY >= cont.offsetTop && mouseY <= offsetBottom) {
                cont.classList.add("semi-transparent");
            } else {
                cont.classList.remove("semi-transparent");
            }
        }
    }

    mouseX = e.clientX - cOffX + fOffX + draggingOjb.offsetWidth;
    mouseY = e.clientY - cOffY + fOffY;
}


function dragEnd(e) {
    e = e || window.event;
    e.preventDefault();

    document.removeEventListener('mousemove', dragMove);
    document.removeEventListener('mouseup', dragEnd);

    var dragEndIndex = -1;
    for (let cont of conts) {
        if (!cont.classList.contains("dragging")) {
            let offsetRight = cont.offsetLeft + cont.offsetWidth;
            let offsetBottom = cont.offsetTop + cont.offsetHeight;
            if (mouseX >= cont.offsetLeft && mouseX <= offsetRight &&
                mouseY >= cont.offsetTop && mouseY <= offsetBottom) {
                cont.classList.remove("semi-transparent");
                dragEndIndex = cont.getAttribute('data-index');
                break;
            }
        }
    }

    draggingOjb.classList.remove("dragging");
    draggingOjb.style.top = '0px';
    draggingOjb.style.left = '0px';
    draggingOjb.style.cursor = null;

    if (dragEndIndex != -1) {
        swapItems(dragStartIndex, dragEndIndex);
    } else {
        resetVars();
    }
}


function resetVars() {
    cOffX = 0;
    cOffY = 0;
    fOffX = 0;
    fOffY = 0;
    mouseX = 0;
    mouseY = 0;
    conts = [];
    draggingOjb = null;
    dragStartIndex = 0;
}


function swapItems(fromIndex, toIndex) {
    var cloned = [];
    var toX, toY;

    for (let cont of conts) {
        if (cont.getAttribute('data-index') === toIndex) {
            toX = cont.offsetLeft;
            toY = cont.offsetTop
            cont.setAttribute('data-index', fromIndex);
            cont.querySelector(".h3").innerHTML = `Chapter 0${parseInt(fromIndex) + 1}`;
        } else if (cont.getAttribute('data-index') === fromIndex) {
            cont.setAttribute('data-index', toIndex);
            cont.querySelector(".h3").innerHTML = `Chapter 0${parseInt(toIndex) + 1}`;
        }
        cloned.push(cont.cloneNode(true));
    }

    for (let cont of conts) {
        if (cont.getAttribute('data-index') === toIndex) {
            cont.style.top = (toY - fOffY).toString() + 'px';
            cont.style.left = (toX - fOffX).toString() + 'px';
        } else if (cont.getAttribute('data-index') === fromIndex) {
            cont.style.top = (fOffY - cont.offsetTop).toString() + 'px';
            cont.style.left = (fOffX - cont.offsetLeft).toString() + 'px';
        }
    }

    fetch("/dashboard/swap", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf
        },
        body: JSON.stringify({
            'fromIndex': fromIndex,
            'toIndex': toIndex
        })
    }).then(res => {
        if (res.status == 201) {
            res.json().then(parsedJson => {
                toastr.success("Chapters were swaped successfuly!");

                var parent = document.getElementById('cards');
                parent.innerHTML = "";

                cloned.sort(sortByOrder);
                for (var i = 0, l = cloned.length; i < l; i++) {
                    parent.appendChild(cloned[i]);
                }

                parent.innerHTML +=
                    `<div class="add-chapter" id="openModal"><span class="material-symbols-sharp wilke">wilke</span>
                    <div><span class="material-symbols-sharp">add</span><h1>Add Chapter</h1></div></div>`;

                openModal = document.getElementById("openModal");
                openModal.onclick = function () { modal.style.display = "block"; }

                resetVars();
                addEventListeners();
            })
        } else {
            toastr.error("There was an error swaping the chapters!");
        }
    });
}


function addEventListeners() {
    const draggables = document.querySelectorAll('.draggable');

    draggables.forEach(draggable => {
        draggable.addEventListener('dragstart', dragStart);
    });
}


function sortByOrder(a, b) {
    if (parseInt(a.getAttribute('data-index')) < parseInt(b.getAttribute('data-index'))) {
        return -1;
    }

    if (parseInt(a.getAttribute('data-index')) > parseInt(b.getAttribute('data-index'))) {
        return 1;
    }

    return 0;
}

