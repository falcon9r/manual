@extends('base.app')

@section('title')
    <title>Templates</title>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/template.css') }}">
@endsection

@section('content')
    {{-- templates --}}
    <div class="temps">
        <h2>Templates</h2>
        <table>
            <thead>
                <tr>
                    <th>Temp name</th>
                    <th>Creation date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($templates->count())
                    @foreach ($templates as $temp)
                        <tr>
                            <td>{{ $temp->title }}</td>
                            <td>{{ $temp->created_at->toDateString() }}</td>
                            <td class="warning">
                                @if ($temp->status == 1)
                                    Published
                                @else
                                    Archived
                                @endif
                            </td>
                            <td class="primary"><a href="{{ route('template/editor', $temp->id) }}">edit</a></td>
                        </tr>
                    @endforeach
                @endif
                <tr>
                    <td colspan="4">
                        <div class="add-temp" id="addTemp">
                            <div>
                                <span class="material-symbols-sharp">add</span>
                                <h2>Add Template</h2>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
@endsection

@section('warning')
    @if (!$templates->count())
        <div class="error-box">
            <div>
                <strong class="font-bold">Error!</strong>
                <span>It seems like there you have no templates, wanna create the first one?</span>
            </div>
            <div id="addTempWarning">
                <a>Create</a>
            </div>
        </div>
    @endif
@endsection

@section('modal')
    <div id="myModal" class="modal @error('title') modal-extra @enderror">
        <div class="modal-content @error('title') modal-content-extra @enderror">
            <div class="modal-header">
                <span class="modal-close">&times;</span>
                <h2>Add Template</h2>
            </div>

            <div class="modal-body">
                <form action="{{ route('addTemp') }}" method="post">
                    @csrf
                    <div>
                        <input type="text" name="title" id="title"
                            @error('title') placeholder="{{ $message }}" @enderror placeholder="Template Name"
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
    <script>
        window.onload = function() {
            var modal = document.getElementById("myModal");
            var btn = document.getElementById("addTempWarning");
            var secondBtn = document.getElementById("addTemp");

            var span = document.getElementsByClassName("modal-close")[0];
            if (btn !== null) {
                btn.onclick = function() {
                    modal.style.display = "block";
                }
            }

            secondBtn.onclick = function() {
                modal.style.display = "block";
            }
            span.onclick = function() {
                modal.style.display = "none";
            }
        };

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
                modal.classList.remove('modal-extra');
            }
        }
    </script>
@endsection
