<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="resources/offline.css">
    <script src="resources/ckeditor.js"></script>
    <title>Manual</title>
</head>

<body style="{{ $style }}
--box-shadow: 0 1px 2px 0 var(--mc-box-border),0 1px 3px 1px var(--mc-box-border);
--header-shadow: 0 0.45rem 1rem var(--mc-header-shadow);
--header-light-shadow: 0 0.3rem 1rem var(--mc-header-shadow)">
    <div id="wrapper">
        <div class="sticky-header" id="header">
            <button id="menu-btn">
                <svg viewBox="0 0 100 70" width="2rem" height="1rem" style="fill: var(--mc-primary-foreground);">
                    <rect width="100" height="10" ></rect>
                    <rect y="30" width="100" height="10"></rect>
                    <rect y="60" width="100" height="10"></rect>
                </svg>
            </button>
            <h3>Tiger Products Manual</h3>
        </div>
        <aside>
            <div class="sidebar">
                <h2>{{ $title }}</h2>
                <hr class="menu-divider">
                <ul class="nav-links">
                    @foreach ($topics as $top)<li class="parent">
                        <a class="mitem {{ str_replace(' ', '', ucwords($top->title)) }}"
			    id="{{$top->id}}"
                            href="#{{ str_replace(' ', '', ucwords($top->title)) }}">
                            <svg height="0.4rem" width="0.4rem">
                                <circle cx="0.2rem" cy="0.2rem" r="0.2rem" />
                            </svg>
                            <h4>{{ $top->title }}</h4>
                        </a>
			<ul class="submenu" style= "margin-left:2rem;">
                        </ul>
                    </li>
                @endforeach</ul>
            </div>
        </aside>
        <main>
            @for ($i = 0; $i < count($Data); $i++)<div class="container">
                <a class="anchor" id="{{ str_replace(' ', '', ucwords($topics[$i]->title)) }}"></a>
                <h3 class="page-title">{{ $title }}<svg style="margin: 0 0.5rem;" width="0.8rem"
                        height="0.8rem" viewBox="0 0 15 36">
                        <path d="M3 4 L15 20 L3 36 L0 34 L10.5 20 L0 6"></path></svg>{{ $topics[$i]->title }}
                </h3>
                <div class="ck-restricted-editing_mode_standard ck ck-content  ck-editor__editable ck-rounded-corners ck-editor__editable_inline ck-blurred" id="content" role="presentation">
                    {!! $Data[$i] !!}
                </div>
            </div>
        @endfor</main>
    </div>
</body>

<script>
    const aside = document.querySelector("aside");
    const menuBtn = document.querySelector("#menu-btn");
    const contentHolder = document.querySelector("main");
    const stickyHeader = document.querySelector(".sticky-header");
    const navLinks = document.querySelector(".nav-links");
    const fontSize = parseFloat(getComputedStyle(document.documentElement).fontSize);
    const containers = document.querySelectorAll('.container');
    const items = document.querySelectorAll(".mitem");
    var topics = @json($topics);

    var hide = function(event) {
        aside.classList.remove('show-aside');
    };

    menuBtn.addEventListener('click', () => {
        if (aside.classList.contains('show-aside') && aside.classList.contains('animate-aside')) {
            aside.classList.remove('animate-aside');
            aside.addEventListener('transitionend', hide, {
                once: true
            });
            toggleScrolling(0)
        } else if (aside.classList.contains('show-aside') && !aside.classList.contains('animate-aside')) {
            aside.classList.add('animate-aside');
            aside.removeEventListener('transitionend', hide);
            toggleScrolling(1)
        } else {
            aside.classList.add('show-aside');
            setTimeout(function() {
                aside.classList.add('animate-aside');
            }, 20);
            toggleScrolling(1)
        }
    })


    window.onclick = function(event) {
        if (aside.classList.contains('show-aside')) {
            let menuParent = event.target.closest('aside');
            if (aside.classList.contains('animate-aside') &&
                (menuParent != aside || event.target.closest('.nav-links') == navLinks ) &&
                 event.target.closest('.sticky-header') != stickyHeader) {
                aside.classList.remove('animate-aside');
                aside.addEventListener('transitionend', hide, {
                    once: true
                });
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

    function ucwords (str) {
        return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
            return $1.toUpperCase();
        });
    }

    function setCurrentTopic() {
        var current = "";
        for (let i = 0; i < containers.length; i++) {
            section = containers[i];
            const sectionBottom = section.getBoundingClientRect().bottom;
            const sectionTop = section.getBoundingClientRect().top;
            if ((5.0 * fontSize) < sectionBottom &&
                (7.0 * fontSize) > sectionTop) {
                current = section.querySelector("a").getAttribute("id");
                break;
            }

        }
        for (var i = 0; i < items.length; i++) {
            if (items[i].classList.contains(current)) {
                items[i].classList.add("active");
				var json = JSON.parse(topics.find(o => o.id === parseInt(items[i].id)).html_body);
                var results = json.blocks.filter(t=>t.type ==='header');
				subStr = ''
                for(let i = 0; i < results.length; i++) {
                let hd = results[i];
                subStr = subStr + "<li style='list-style: circle'><a style='font-size: 0.75em; height: 1.5rem;' href='#"+ucwords(hd.data.text).replace(/ /g,'')+"'>"+hd.data.text+'</a></li>'
                }
                subpar = items[i].closest(".parent")
                subpar.querySelector('.submenu').innerHTML = subStr;
            } else {
				subpar = items[i].closest(".parent")
                subpar.querySelector('.submenu').innerHTML = ''
                items[i].classList.remove('active');
            }
        }
    }

    setCurrentTopic();
    window.onscroll = () => {
        setCurrentTopic()
    };
</script>

</html>
