var blockData;

try {
    var blockData = JSON.parse(data);
} catch (e) {
    console.log("topic is empty!");
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

        tip: Tip,

        warning: Warning,

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

saveButton.addEventListener('click', function () {
    editor.save()
        .then((savedData) => {
            fetch("/welcome/editor/save", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf
                },
                body: JSON.stringify({ 'savedData': savedData })
            }).then(res => {
                if (res.status == 201) {
                    toastr.info("The content was saved successfully!");
                } else {
                    toastr.error("There was an error saving the content!");
                }
            });
        })
        .catch((error) => {
            console.error('Saving error', error);
            toastr.error("There was an error saving the content!");
        });
});
