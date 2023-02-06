<script !src="">

    var removeButton = document.getElementById("remove-btn");
    var discardButton = document.getElementById("discard-btn");
    var publishButton = document.getElementById("publish-btn");

    discardButton.addEventListener('click', function () {
        if (initialData.isTemp) {
            window.location.replace("/templates");
        } else {
            if (initialData.tempData == "0") {
                toastr.warning("This topic has no default template!");
            } else {
                if (confirm("This action will override the data in the editor, but you will still need to save the data manually.\nDo you want to continue?")) {
                    try {
                        editor.setData(initialData.tempData);
                    } catch (e) {
                        toastr.warning("The associated template is empty!");
                    }
                }
            }
        }
    });

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

</script>
