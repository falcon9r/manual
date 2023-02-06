@extends('base.app')
@section('title')
    <title>Editor</title>
@endsection
@section('css')
    <link rel="stylesheet" href="{{ asset('css/editor.css') }}">
@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/header@latest"></script><!-- Header -->
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/simple-image@latest"></script><!-- Image -->
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/delimiter@latest"></script><!-- Delimiter -->
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/list@latest"></script><!-- List -->
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/checklist@latest"></script><!-- Checklist -->
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/quote@latest"></script><!-- Quote -->
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/embed@latest"></script><!-- Embed -->
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/table@latest"></script><!-- Table -->
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/link@latest"></script><!-- Link -->
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/raw@latest"></script><!-- Raw -->
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/warning@latest"></script><!-- Warning -->
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/marker@latest"></script><!-- Marker -->
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/inline-code@latest"></script><!-- Inline Code -->
    <script src="https://cdn.jsdelivr.net/npm/editorjs-text-alignment-blocktune@latest"></script><!-- object alignment -->

    <script src="{{asset('js/editor/code.js')}}"></script><!-- Code -->
    <script src="{{asset('js/editor/tip.js')}}"></script><!-- Tip -->

    <!-- Load Editor.js's Core -->
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest"></script>
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
    <script src="{{ asset('js/editor/editor.js') }}"></script>
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

        <div class="editorjs" id="editorjs"></div>
    </div>
@endsection
