function _classCallCheck(e, t) {
    if (!(e instanceof t)) throw new TypeError("Cannot call a class as a function")
}

function _defineProperties(e, t) {
    for (var r = 0; r < t.length; r++) {
        var a = t[r];
        a.enumerable = a.enumerable || !1, a.configurable = !0, "value" in a && (a.writable = !0), Object.defineProperty(e, a.key, a)
    }
}

function _createClass(e, t, r) {
    return t && _defineProperties(e.prototype, t), r && _defineProperties(e, r), e
}

function _defineProperty(e, t, r) {
    return t in e ? Object.defineProperty(e, t, {
        value: r,
        enumerable: !0,
        configurable: !0,
        writable: !0
    }) : e[t] = r, e
}

function _typeof(e) {
    return (_typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function(e) {
        return typeof e
    } : function(e) {
        return e && "function" == typeof Symbol && e.constructor === Symbol && e !== Symbol.prototype ? "symbol" : typeof e
    })(e)
}
var edjsParser = function() {
    "use strict";
    var e = function(e) {
            return e && "object" === _typeof(e) && !Array.isArray(e)
        },
        t = function t(r, a) {
            var n = Object.assign({}, r);
            return e(r) && e(a) && Object.keys(a).forEach((function(c) {
                e(a[c]) && c in r ? n[c] = t(r[c], a[c]) : Object.assign(n, _defineProperty({}, c, a[c]))
            })), n
        },
        r = {
            youtube: '<div class="embed"><iframe class="embed-youtube" frameborder="0" src="<%data.embed%>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen <%data.length%>></iframe></div>',
            twitter: '<blockquote class="twitter-tweet" class="embed-twitter" <%data.length%>><a href="<%data.source%>"></a></blockquote> <script async src="//platform.twitter.com/widgets.js" charset="utf-8"><\/script>',
            instagram: '<blockquote class="instagram-media" <%data.length%>><a href="<%data.embed%>/captioned"></a></blockquote><script async defer src="//www.instagram.com/embed.js"><\/script>',
            codepen: '<div class="embed"><iframe <%data.length%> scrolling="no" src="<%data.embed%>" frameborder="no" loading="lazy" allowtransparency="true" allowfullscreen="true"></iframe></div>',
            defaultMarkup: '<div class="embed"><iframe src="<%data.embed%>" <%data.length%> class="embed-unknown" allowfullscreen="true" frameborder="0" ></iframe></div>'
        },
        a = {
            paragraph: function(e, t) {
                return '<p class="'.concat(t.paragraph.paragraphBlockClass, '">', e.text, "</p>")
            },
            header: function(e, t) {
                return '<a class="anchor" id="'.concat(e.text.toLowerCase().replace(/ /g,''),'"></a><h', e.level, ' class="', t.header.headerBlockClass ,'">', e.text, "</h", e.level, ">")
            },
            list: function(e, t) {
                var o = "ordered" === e.style ? "ol" : "ul",
                    r = e.items.reduce((function(e, o) {
                        return e + '<li class="'.concat(t.list.listItemClass , '">', o, "</li>")
                    }), "");
                return "<".concat(o, ' class="', t.list.listBlockClass, '">', r, "</", o, ">")
            },
            checklist: function(e, t) {
                r = e.items.reduce((function(e, o) {
                    var checked = true === o.checked ? t.checklist.checkedItemClass : "";
                    return e + '<div class="'.concat(t.checklist.itemClass , " ", checked, '"><span class="', t.checklist.itemCheckBoxClass, '"></span><div class="',t.checklist.itemTextClass,'">', o.text ,'</div></div>');
                }), "");
                return r;
            },
            quote: function(e, t) {
                var r = "";
                return t.quote.applyAlignment && (r = 'style="text-align: '.concat(e.alignment, ';"')), "<blockquote ".concat(r, "><p>").concat(e.text, "</p><cite>").concat(e.caption, "</cite></blockquote>")
            },
            table: function(e, t) {
                var h = e.withHeadings == true;
                var o = e.content.map((function(e) {
		    var tt = h ? "th" : "td";
                    h = false;
                    return "<tr>".concat(e.reduce((function(e, t) {
                        var z = null == t ? "" : '<div style="padding: 8% 12%;">'.concat(t, "</div>");
                        return e + "<"+ tt +">".concat(z, "</" +tt+ ">");
                    }), ""), "</tr>")
                }));
                var z = '<table class="' +t.table.tableBlockClass+ '"><tbody>'.concat(o.join(""), "</tbody></table>");
                console.log(z);
                return z;
            },
            warning: function(e, t) {
                return '<div class="'.concat(t.warning.warningBlockClass,'">',`<div>
                <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>`,'<h3>', e.title,'</h3></div><p>', e.message,'</p></div>');
            },
	    tip: function(e, t) {
                return '<div class="'.concat(t.tip.tipBlockClass,'">',`<div>
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 1000 1000" enable-background="new 0 0 1000 1000" xml:space="preserve">
      		<g><path stroke="black" stroke-width="40" d="M500,210.2c-74.5,0-144.6,28.7-197.3,80.9C250,343.3,221,412.7,221,486.5c0,73.8,29,143.2,81.7,195.3c52.7,52.2,122.8,80.9,197.3,80.9s144.6-28.7,197.3-80.9C750,629.7,779,560.3,779,486.5c0-73.8-29-143.2-81.7-195.4C644.7,238.9,574.5,210.2,500,210.2z M500,704.5c-121.4,0-220.3-97.7-220.3-218c0-120.2,98.8-218,220.3-218c121.4,0,220.3,97.8,220.3,218C720.3,606.7,621.5,704.5,500,704.5z M503.2,138.8c16.2,0,29.4-13,29.4-29.2V39.2c0-16.1-13.2-29.2-29.4-29.2c-16.2,0-29.5,13-29.5,29.2v70.5C473.8,125.8,487,138.8,503.2,138.8z M256,237.6c7.6,0,15.1-2.9,20.8-8.6c11.5-11.4,11.5-29.8,0-41.2l-50.3-49.9c-11.5-11.4-30.1-11.4-41.6,0c-11.5,11.4-11.5,29.8,0,41.2l50.3,49.8C240.9,234.7,248.4,237.6,256,237.6z M151.6,422.2H80.4c-16.2,0-29.5,13-29.5,29.2c0,16.1,13.2,29.2,29.5,29.2h71.2c16.2,0,29.5-13,29.5-29.2C181.1,435.3,167.9,422.2,151.6,422.2z M919.6,428.8h-71.2c-16.2,0-29.5,13-29.5,29.2c0,16.1,13.2,29.2,29.5,29.2h71.2c16.2,0,29.5-13,29.5-29.2C949.1,441.8,935.9,428.8,919.6,428.8z M748.8,242.2c7.6,0,15.1-2.9,20.8-8.5l50.3-49.9c11.5-11.4,11.5-29.8,0-41.2s-30.1-11.4-41.7,0l-50.4,49.9c-11.5,11.4-11.5,29.8,0,41.2C733.7,239.3,741.1,242.2,748.8,242.2z M608.7,820.9H391.4c-16.2,0-29.5,13.1-29.5,29.2s13.2,29.2,29.5,29.2h217.3c16.2,0,29.5-13,29.5-29.2C638.1,834,624.9,820.9,608.7,820.9z M569.4,931.6H430.7c-16.2,0-29.5,13-29.5,29.2c0,16.1,13.2,29.2,29.5,29.2h138.6c16.2,0,29.5-13.1,29.5-29.2C598.8,944.7,585.6,931.6,569.4,931.6z"/></g>
      		</svg>`,'<h3>', e.title,'</h3></div><p>', e.message,'</p></div>');
            },
            image: function(e, t) {
                var r, a = "".concat(e.stretched ? "img-fullwidth" : "", " ").concat(e.withBorder ? "img-border" : "", " ").concat(e.withBackground ? "img-bg" : ""),
                    n = t.image.imgClass || "";
                if (r = e.url ? e.url : "absolute" === t.image.path ? e.file.url : t.image.path.replace(/<(.+)>/, (function(t, r) {
                        return e.file[r]
                    })), "img" === t.image.use) return '<img class="'.concat(a, " ").concat(n, '" src="').concat(r, '" alt="').concat(e.caption, '">');
                if ("figure" === t.image.use) {
                    var c = t.image.figureClass || "",
                        o = t.image.figCapClass || "";
                    return '<figure class="'.concat(c, " ", a, '"><img class="').concat(n, " ").concat(a, '" src="').concat(r, '" alt="').concat(e.caption, '"><figcaption style="padding: 0.5rem" class="').concat(o, '">').concat((e.caption == null) ? "" : e.caption, "</figcaption></figure>")
                }
            },
            code: function(e, t) {
                return '<div style="display= inline-block" class="'.concat(t.pre.preBlockClass,'">',e.code, "</div>")
            },
            raw: function(e) {
                return e.html
            },
            delimiter: function(e, t) {
                return '<hr class="' + t.delimiter.delimiterBlockClass + '"/>'
            },
            embed: function(e, t) {
                t.embed.useProvidedLength ? e.length = 'width="'.concat(e.width, '" height="').concat(e.height, '"') : e.length = "";
                var r = new RegExp(/<%data\.(.+?)%>/, "gm");
                return t.embedMarkups[e.service] ? t.embedMarkups[e.service].replace(r, (function(t, r) {
                    return e[r]
                })) : t.embedMarkups.defaultMarkup.replace(r, (function(t, r) {
                    return e[r]
                }))
            }
        },
        n = {
            header: {
                headerBlockClass: "header"
            },
            image: {
                use: "figure",
                imgClass: "img",
                figureClass: "fig-img",
                figCapClass: "fig-cap",
                path: "absolute"
            },
            paragraph: {
                paragraphBlockClass: "paragraph block"
            },
            pre: {
                preBlockClass: "pre block"
            },
            code: {
                codeBlockClass: "code block"
            },
            list: {
                listBlockClass: "list block",
                listItemClass: "list-item"
            },
            checklist : {
                checkedItemClass: "checked",
                itemClass: "checklist-item block",
                itemCheckBoxClass: "checklist-box",
                itemTextClass: "checklist-text"
            },
            table: {
                tableBlockClass: "table block"
            },
            delimiter: {
                delimiterBlockClass: "delimiter"
            },
            warning: {
                warningBlockClass: "warning block"
            },
            tip: {
                tipBlockClass: "tip block"
            },
            embed: {
                useProvidedLength: !1
            },
            quote: {
                applyAlignment: 1
            }
        };
    return function() {
        function e() {
            var c = arguments.length > 0 && void 0 !== arguments[0] ? arguments[0] : {},
                o = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {},
                i = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : {};
            _classCallCheck(this, e), this.config = t(n, c), this.config.embedMarkups = Object.assign(r, i), this.parsers = Object.assign(a, o)
        }
        return _createClass(e, [{
            key: "parse",
            value: function(e) {
                var t = this;
                return e.blocks.map((function(e) {
                    var r = t.parseBlock(e);
                    return r instanceof Error ? "" : r
                })).join("")
            }
        }, {
            key: "parseBlock",
            value: function(e) {
                if (!this.parsers[e.type]) return new Error("".concat(e.type, " is not supported! Define your own custom function."));
                try {
                    return this.parsers[e.type](e.data, this.config)
                } catch (e) {
                    return e
                }
            }
        }]), e
    }()
}();
