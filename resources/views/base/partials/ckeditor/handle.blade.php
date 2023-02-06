<script !src="">

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
</script>
