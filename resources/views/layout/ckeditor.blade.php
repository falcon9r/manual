@extends('base.app')

@section('title')
<title>ckeditor</title>
@endsection


@section('css')
    <link rel="stylesheet" href="{{ asset('css/editor.css') }}">
@endsection
@section('script')

    @include('base.partials.ckeditorDependencies')
    @include('base.partials.ckeditor.tools')
    @if ($tempActive)
        <script>
            var initialData = {
                isTemp: true,
                isPublished: "{{ $template->status }}",
                name: "{{ $template->title }}",
                tempId: "{{ $template->id }}",
                csrf: "{{ csrf_token() }}",
                blockData: @json($template->html_body)
            };
        </script>

    @else
        <script>
            var initialData = {
                isTemp: false,
                isPublished: "{{ $topic->status }}",
                name: "{{ $topic->title }}",
                topicId: "{{ $topic->id }}",
                chapterId: "{{ $chapter->id }}",
                csrf: "{{ csrf_token() }}",
                tempData: @json($templateBody),
                blockData: @json($topic->html_body)
            };
        </script>
    @endif
    @include('base.partials.ckeditor.handle')
    <script>
        var editor;
        const saveButton = document.getElementById('save-btn');

        CKEDITOR.ClassicEditor.create(document.getElementById("ckeditor"), CKEditorConfig).then(newEditor => {
            editor = newEditor
            newEditor.setData(initialData.blockData);
        });

        saveButton.addEventListener('click', function (){
        if (initialData.isTemp) {
            fetch("/template/editor/" + initialData.tempId, {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': initialData.csrf
                },
                body: JSON.stringify({ 'savedData': editor.getData() })
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
                body: JSON.stringify({ 'savedData': editor.getData() })
            }).then(res => {
                if (res.status == 201) {
                    toastr.info("The content was saved successfully!");
                } else {
                    toastr.error("There was an error saving the content!");
                }
            });
        }
    })
    </script>
@endsection

@section('content')
    <div class="editor">
        <div class="tp-cont">
            <div class="title">
                @if ($tempActive)
                    <h2>{{ $template->title }}</h2>
                @else
                    <h2>{{ $topic->title }}</h2>
                    <h3>from "{{ $chapter->title }}"</h3>
                @endif
            </div>

            <div>
                <div class="btn-cont">
                    <button class="discard btn" id="remove-btn">Delete @if ($tempActive)
                            template
                        @else
                            topic
                        @endif
                    </button>

                    <button class="save btn" id="save-btn">Save</button>
                </div>
                <div class="btn-cont">
                    <button class="discard btn" id="discard-btn">
                        @if ($tempActive)
                            Discard Changes
                        @else
                            Reset to Template
                        @endif
                    </button>
                    <button class="publish btn" id="publish-btn">
                        @if ($tempActive)
                            @if ($template->status == 1)
                                Make it unavailable
                            @else
                                Make it available
                            @endif
                        @else
                            @if ($topic->status == 1)
                                Archive
                            @else
                                Publish
                            @endif
                        @endif
                    </button>
                </div>
            </div>
        </div>
        <textarea  id="ckeditor" name="content"></textarea>
    </div>
@endsection




