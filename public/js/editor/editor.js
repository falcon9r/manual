var blockData;

try {
    var blockData = JSON.parse(initialData.blockData);
} catch (e) {
    console.log("topic is empty!"); // error in the above string (in this case, yes)!
}

const editor = new EditorJS({
    holder: 'editorjs',
    tools: {
        header: {
            class: Header,
            inlineToolbar: ['marker', 'link'],
            config: {
                placeholder: 'Header'
            },
            shortcut: 'CMD+SHIFT+H'
        },
        image: SimpleImage,

        list: {
            class: List,
            inlineToolbar: true,
            shortcut: 'CMD+SHIFT+L'
        },

        checklist: {
            class: Checklist,
            inlineToolbar: true,
        },

        quote: {
            class: Quote,
            inlineToolbar: true,
            config: {
                quotePlaceholder: 'Enter a quote',
                captionPlaceholder: 'Quote\'s author',
            },
            shortcut: 'CMD+SHIFT+O'
        },

        warning: Warning,

        tip: Tip,

        marker: {
            class: Marker,
            shortcut: 'CMD+SHIFT+M'
        },

        code: {
            class: CodeTool,
            inlineToolbar: ['marker', 'inlineCode', 'bold', 'italic'],
            shortcut: 'CMD+SHIFT+C'
        },
        delimiter: Delimiter,

        inlineCode: {
            class: InlineCode,
            shortcut: 'CMD+SHIFT+C'
        },

        embed: Embed,

        table: {
            class: Table,
            inlineToolbar: true,
            shortcut: 'CMD+ALT+T'
        },

    },
    data: blockData
})


var saveButton = document.getElementById("save-btn");
var removeButton = document.getElementById("remove-btn");
var discardButton = document.getElementById("discard-btn");
var publishButton = document.getElementById("publish-btn");

publishButton.addEventListener('click', function () {
    newState = initialData.isPublished == 1 ? 0 : 1;
    console.log(newState);

    if (initialData.isTemp) {
        if (confirm(newState == 1 ? "Do you want to make this template available for new topics?" : "Do you want to make this template unavailable for new topics?")) {

            fetch("/template/publish/" + initialData.tempId, {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': initialData.csrf
                },
                body: JSON.stringify({ 'newState': newState })
            }).then(res => {
                if (res.status == 201) {
                    initialData.isPublished = newState;
                    publishButton.innerText = newState == 1 ? "Make it unavailable" : "Make it available";
                    toastr.info(newState == 1 ? "This template is now available to use!" : "This template will be unavailable for new topics!");
                } else {
                    toastr.error(newState == 1 ? "There was an error publishing the template!" : "There was an error unpublishing the template!");
                }
            });
        }
    } else {
        if (confirm(newState == 1 ? "Do you want to publish this topic to the internet?" : "Do you want to archieve this topic?\nthe topic will be unpublished.")) {

            fetch("/topic/publish/" + initialData.topicId, {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': initialData.csrf
                },
                body: JSON.stringify({ 'newState': newState })
            }).then(res => {
                if (res.status == 201) {
                    initialData.isPublished = newState;
                    publishButton.innerText = newState == 1 ? "Archive" : "Publish";
                    toastr.info(newState == 1 ? "Topic has been published successfully!" : "Topic has been archived successfully!");
                } else {
                    toastr.error(newState == 1 ? "There was an error publishing the topic!" : "There was an error unpublishing the topic!");
                }
            });
        }
    }

});



discardButton.addEventListener('click', function () {
    if (initialData.isTemp) {
        window.location.replace("/templates");
    } else {
        if (initialData.tempData == "0") {
            toastr.warning("This topic has no default template!");
        } else {
            if (confirm("This action will override the data in the editor, but you will still need to save the data manually.\nDo you want to continue?")) {

                try {
                    parsedData = JSON.parse(initialData.tempData);
                    editor.render(parsedData);
                } catch (e) {
                    toastr.warning("The associated template is empty!");
                }
            }
        }
    }
});


removeButton.addEventListener('click', function () {
    if (confirm("Are you sure you want to delete this \"" + initialData.name + "\"?")) {
        if (initialData.isTemp) {
            fetch("/template/delete/" + initialData.tempId, {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': initialData.csrf
                }
            }).then(res => {
                if (res.status == 201) {
                    toastr.options.fadeIn = 0
                    toastr.options.positionClass = "toast-top-center"
                    toastr.info("Template was removed successfully!");
                    setTimeout(() => { window.location.replace("/templates"); }, 500);
                } else {
                    toastr.error("There was an error removing the template!");
                }
            });
        } else {
            fetch("/topic/delete/" + initialData.topicId, {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': initialData.csrf
                }
            }).then(res => {
                if (res.status == 201) {
                    toastr.options.fadeIn = 0
                    toastr.options.positionClass = "toast-top-center"
                    toastr.info("Topic was removed successfully!");
                    setTimeout(() => { window.location.replace("/chapter/" + initialData.chapterId); }, 500);
                } else {
                    toastr.error("There was an error removing the topic!");
                }
            });
        }
    }
});



saveButton.addEventListener('click', function () {
    editor.save()
        .then((savedData) => {
            if (initialData.isTemp) {
                fetch("/template/editor/" + initialData.tempId, {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': initialData.csrf
                    },
                    body: JSON.stringify({ 'savedData': savedData })
                }).then(res => {
                    if (res.status == 201) {
                        toastr.info("The content was saved successfully!");
                    } else {
                        toastr.error("There was an error saving the content!");
                    }
                });
            } else {
                fetch("/topic/editor/" + initialData.topicId, {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': initialData.csrf
                    },
                    body: JSON.stringify({ 'savedData': savedData })
                }).then(res => {
                    if (res.status == 201) {
                        toastr.info("The content was saved successfully!");
                    } else {
                        toastr.error("There was an error saving the content!");
                    }
                });
            }
        })
        .catch((error) => {
            console.error('Saving error', error);
            toastr.error("There was an error saving the content!");
        });
});
