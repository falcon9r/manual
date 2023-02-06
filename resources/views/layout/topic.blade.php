@extends('base.app')
@section('title')
    <title>Topics</title>
@endsection
@section('css')
    <link rel="stylesheet" href="{{ asset('css/topic.css') }}">
@endsection
@section('content')
    {{-- topics --}}
    <div class="topics">
        <div class="title">
		<div class="nav-address">
                <h2>{{ $chapter->title }} </h2>
                <span style="font-size:1.2rem; font-weight:700;" class="material-symbols-sharp">arrow_forward_ios</span>
                <h2>Topics</h2>
           </div>
           <div class="buttons">
                <button class="remove btn" id="remove-btn">Delete chapter</button>
                <button class="rename btn" id="rename-btn">Rename chapter</button>
            </div>
        </div>
        <table align="center">
            <thead>
                <tr>
                    <th align="center">Order`</th>
                    <th align="center">Topic name`</th>
                    <th>Creation date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @if ($topics->count())
                    @foreach ($topics as $topic)
                        <tr>
                            <td style="max-width: 20px; text-align: center;">
                                <input type="text" class="order-input" data-topicId="{{$topic->id}}" readonly value="{{ $topic->order }}" style="max-width: 70%; background: none">
                            </td>
                            <td style="text-align: center; max-width: 30px">
                                <input type="text" class="title-input"  data-topicId="{{ $topic->id }}" readonly value="{{ $topic->title }}" style="max-width: 100%; background: none">
                            </td>
                            <td>{{ $topic->created_at->toDateString() }}</td>
                            <td class="warning">
                                @if ($topic->status == 1)
                                    Published
                                @else
                                    Archived
                                @endif
                            </td>
                            <td class="primary"><a href="{{ route('editor', $topic->id) }}">edit</a></td>
                        </tr>
                    @endforeach
                @endif
                <tr>
                    <td colspan="5">
                        <div class="add-topic" id="addTopic">
                            <div>
                                <span class="material-symbols-sharp">add</span>
                                <h2>Add Topic</h2>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

    </div>
@endsection
@section('warning')
    @if (!$topics->count())
        <div class="error-box">
            <div>
                <strong class="font-bold">Error!</strong>
                <span>It seems like this Chapter does not have any topics in it, wanna create the first one?</span>
            </div>
            <div id="addTopicWarning">
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
                <h2>Add Topic</h2>
            </div>

            <div class="modal-body">
                <form action="{{ route('addTopic', $chapter->id) }}" method="post">
                    @csrf
                    <div>
                        <input type="text" name="title" id="title"
                            @error('title') placeholder="{{ $message }}" @enderror placeholder="Topic Name"
                            autocomplete="off" class="modal-input @error('title') border-red @enderror">
                    </div>
                    <div>
                        <select class="modal-input" name="temp" id="temp">
                            <option value="-1">Without Template</option>
                            @foreach ($templates as $template)
                                <option value="{{ $template->id }}">{{ $template->title }}</option>
                            @endforeach
                        </select>
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


    <div id="reModal" class="modal @error('newName') modal-extra @enderror">
        <div class="modal-content @error('newName') modal-content-extra @enderror">
            <div class="modal-header">
                <span class="modal-close">&times;</span>
                <h2>Rename Chapter</h2>
            </div>

            <div class="modal-body">
                <form action="{{ route('renameChapter', $chapter->id) }}" method="post">
                    @csrf
                    <div>
                        <input type="text" name="newName" id="newName"
                            @error('newName') placeholder="{{ $message }}" @enderror placeholder="New Name"
                            autocomplete="off" class="modal-input @error('newName') border-red @enderror">
                    </div>
                    <div>
                        <button id="new-name-submit" type="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        var removeButton = document.getElementById("remove-btn");
        var renameButton = document.getElementById("rename-btn");
        var renameModal = document.getElementById("reModal");
        var closeReModal = renameModal.getElementsByClassName("modal-close")[0];

        closeReModal.onclick = function() {
            renameModal.style.display = "none";
        }

        var modal = document.getElementById("myModal");
        var addTopicBtnAlt = document.getElementById("addTopicWarning");
        var addTopicBtn = document.getElementById("addTopic");
        var closeModalBtn = modal.getElementsByClassName("modal-close")[0];

        addTopicBtn.onclick = function() {
            modal.style.display = "block";
        }
        closeModalBtn.onclick = function() {
            modal.style.display = "none";
        }


        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
                modal.classList.remove('modal-extra');
            }
        }


        removeButton.addEventListener('click', function() {
            if (confirm("Are you sure you want to delete this chapter, \"{{ $chapter->title }}\"?")) {
                fetch("/chapter/delete/{{ $chapter->id }}", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    }
                }).then(res => {
                    if (res.status == 201) {
                        toastr.options.fadeIn = 0
                        toastr.options.positionClass = "toast-top-center"
                        toastr.info("Chapter was deleted successfully!");
                        setTimeout(() => {
                            window.location.replace("/dashboard");
                        }, 500);
                    } else {
                        toastr.error("There was an error removing the chapter!");
                    }
                });
            }
        });



        renameButton.addEventListener('click', function() {
            renameModal.style.display = "block";
        });

        $('.order-input').click(function (){
                $(this).attr('readonly' , false);
        })
        $('.order-input').focusout(function (){
                $(this).attr('readonly' , true);
                fetch('/topic/order-change/' + $(this).attr('data-topicId'), {
                    method : 'POST',
                    headers : {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body : JSON.stringify({
                        order : $(this).val()
                    })
                }).then(res => {
                    if (res.status == 201) {
                        toastr.info("The order was saved successfully!");
                        toastr.info("Refresh Page for result!");
                    } else {
                        toastr.error("There was an error saving the content!");
                    }
                });
        });
        $('.title-input').click(function (){
            $(this).attr('readonly' , false);
        })
        $('.title-input').focusout(function (){
            console.log($(this).val());
           fetch('/topic/renaming/' + $(this).attr('data-topicId'), {
               method: 'PATCH',
               headers: {
                   'Content-Type': 'application/json',
                   'X-CSRF-TOKEN': '{{ csrf_token() }}'
               },
               body: JSON.stringify({
                   name: $(this).val()
               })
           }).then(res => {
               if (res.status == 201) {
                   toastr.info("The order was saved successfully!");
                   toastr.info("Refresh Page for result!");
               } else {
                   toastr.error("There was an error saving the content!");
               }
           });;
        });
    </script>
@endsection
