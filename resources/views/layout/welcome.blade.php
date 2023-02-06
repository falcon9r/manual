@extends('base.app')
@section('title')
    <title>Editor</title>
@endsection
@section('css')
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
@endsection
@section('content')
    <div class="editor">
        <div class="title">
            <h2>Welcome page</h2>
            <button class="save btn" id="save-btn">Save changes</button>
        </div>
        <textarea id="ckeditor"></textarea>
    </div>
@endsection


@section('script')
    @include('base.partials.ckeditorDependencies')
    @include('base.partials.ckeditor.tools')
    <script>
        var csrf = "{{ csrf_token() }}";
        var data = @json($content);
        var saveButton = document.getElementById('save-btn');
        var editor;        CKEDITOR.ClassicEditor.create(document.getElementById("ckeditor"), CKEditorConfig).then(newEditor => {
            editor = newEditor
            newEditor.setData(data);
            editor.setReadOnly( true);
        });
        saveButton.addEventListener('click', function (){
            fetch("/welcome/editor/save/",{
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf
                },
                body: JSON.stringify({ 'savedData': editor.getData() })
            }).then(res => {
                if (res.status == 201) {
                    toastr.info("The content was saved successfully!");
                } else {
                    toastr.error("There was an error saving the content!");
                }
            });
        })
        CKFinder.editor(editor);
    </script>
@endsection
