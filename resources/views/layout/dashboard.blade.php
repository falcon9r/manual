@extends('base.app')

@section('title')
    <title>Dashboard</title>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/dash.css') }}">
@endsection

@section('content')
    <h2 class="title">Chapters</h2>
    <div id="cards" class="cards">
        @if ($chapters->count())
            @foreach ($chapters as $chapter)
                <div data-index="{{ $chapter->order }}" class="container card">
                    <div class="action">
                        <span class="material-symbols-sharp draggable" draggable="true">drag_indicator</span>
                        <span onclick="window.location.href='{{ route ('topics', $chapter->id)}}'" class="material-symbols-sharp edit-item">edit</span>
                    </div>
                    <span class="material-symbols-sharp wilke">wilke</span>

                    <div class="middle">
                        <div>
                            <h3 class="h3">Chapter 0{{ $chapter->order + 1 }}</h3>
                            <h1>{{ $chapter->title }}</h1>
                        </div>
                    </div>
                    <div class="info">
                        <small class="text-muted">with {{ $chapter->topics->count() }} topics</small>
                        <small class="text-muted">updated {{ $chapter->updated_at->diffForHumans() }}</small>
                    </div>
                </div>
            @endforeach
        @endif

        <div class="add-chapter" id="openModal">
            <span class="material-symbols-sharp wilke">wilke</span>
            <div>
                <span class="material-symbols-sharp">add</span>
                <h1>Add Chapter</h1>
            </div>
        </div>
    </div>
@endsection

@section('modal')
    <div id="myModal" class="modal @error('name') modal-extra @enderror @error('title') modal-extra @enderror">
        <div class="modal-content @error('name') modal-content-extra @enderror @error('title') modal-content-extra @enderror">

            <div class="modal-header">
                <span class="modal-close">&times;</span>
                <h2>Add Chapter</h2>
            </div>

            <div class="modal-body">
                <form action="{{ route('addChapter') }}" method="post">
                    @csrf
                    <div>
                        <input type="text" name="title" id="title"
                            @error('title') placeholder="{{ $message }}" @enderror placeholder="Chapter Title"
                            autocomplete="off" class="modal-input @error('title') border-red @enderror">
                    </div>
                    @error('body')
                        <div class="form-warning">{{ $message }}
                        </div>
                    @enderror

                    <div>
                        <button id="myCb" type="submit">Add</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection

@section('script')
<script src="{{ asset('js/dash.js') }}"></script>
@endsection
