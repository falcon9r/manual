@extends('base.app')
@section('title')
    <title>Styling</title>
@endsection
@section('css')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
@endsection
@section('content')
    <div class="styles">
        <div class="title">
            <h2>Style Editor</h2>
            <button class="save btn" id="save-btn">Save changes</button>
        </div>

        <div class="container">

            <div class="notification">
                <p>note that these settings will only apply on the public page and the exported htmls, the editor is
                    excluded.
                </p>
                <p>attributes below are the general attributes and are mostly for the non-content parts of the manual. </p>
            </div>
            <div class="body-style">
                <div class="item">
                    <h4>Css key name</h4>
                    <h4>Value ( css format )</h4>
                </div>
                @foreach ($settings->slice(0, 10) as $item)
                    <div class="item">
                        <h4>{{ implode(' ', explode('-', substr($item->key, 5))) }} color</h4>

                        <input type="text" name="{{ $item->key }}" id="{{ $item->key }}" value="{{ $item->value }}"
                            placeholder="fill in this parameter" autocomplete="off" class="input">

                    </div>
                @endforeach
            </div>
            <div class="notification">
                <p>attributes below are general size and colors and are only for the content part of the page.</p>
            </div>
            <div class="body-style">
                <div class="item">
                    <h4>Css key name</h4>
                    <h4>Value ( css format )</h4>
                </div>
                @foreach ($settings->slice(10, 1) as $item)
                    <div class="item">
                        <h4>{{ implode(' ', explode('-', substr($item->key, 5))) }}</h4>

                        <input type="text" name="{{ $item->key }}" id="{{ $item->key }}" value="{{ $item->value }}"
                            placeholder="fill in this parameter" autocomplete="off" class="input">

                    </div>
                @endforeach
                @foreach ($settings->slice(11, 6) as $item)
                    <div class="item">
                        <h4>{{ implode(' ', explode('-', substr($item->key, 5))) }} color</h4>

                        <input type="text" name="{{ $item->key }}" id="{{ $item->key }}"
                            value="{{ $item->value }}" placeholder="fill in this parameter" autocomplete="off"
                            class="input">

                    </div>
                @endforeach
            </div>

            <div class="notification">
                <p>attrubutes below are specificly for the checklist element.</p>
            </div>
            <div class="body-style">
                <div class="item">
                    <h4>Css key name</h4>
                    <h4>Value ( css format )</h4>
                </div>
                @foreach ($settings->slice(17, 5) as $item)
                    <div class="item">
                        <h4>{{ implode(' ', explode('-', substr($item->key, 5))) }} color</h4>

                        <input type="text" name="{{ $item->key }}" id="{{ $item->key }}"
                            value="{{ $item->value }}" placeholder="fill in this parameter" autocomplete="off"
                            class="input">

                    </div>
                @endforeach
            </div>

            <div class="notification">
                <p>attributes below are specificly for the code block element.</p>
            </div>
            <div class="body-style">
                <div class="item">
                    <h4>Css key name</h4>
                    <h4>Value ( css format )</h4>
                </div>
                @foreach ($settings->slice(22, 3) as $item)
                    <div class="item">
                        <h4>{{ implode(' ', explode('-', substr($item->key, 5))) }} color</h4>

                        <input type="text" name="{{ $item->key }}" id="{{ $item->key }}"
                            value="{{ $item->value }}" placeholder="fill in this parameter" autocomplete="off"
                            class="input">

                    </div>
                @endforeach
            </div>

            <div class="notification">
                <p>attributes below are specificly for the warning block element.</p>
            </div>
            <div class="body-style">
                <div class="item">
                    <h4>Css key name</h4>
                    <h4>Value ( css format )</h4>
                </div>
                @foreach ($settings->slice(25, 3) as $item)
                    <div class="item">
                        <h4>{{ implode(' ', explode('-', substr($item->key, 5))) }} color</h4>

                        <input type="text" name="{{ $item->key }}" id="{{ $item->key }}"
                            value="{{ $item->value }}" placeholder="fill in this parameter" autocomplete="off"
                            class="input">

                    </div>
                @endforeach
            </div>

	    <div class="notification">
                <p>attributes below are specificly for the tip block element.</p>
            </div>
            <div class="body-style">
                <div class="item">
                    <h4>Css key name</h4>
                    <h4>Value ( css format )</h4>
                </div>
                @foreach ($settings->slice(28, 3) as $item)
                    <div class="item">
                        <h4>{{ implode(' ', explode('-', substr($item->key, 5))) }} color</h4>

                        <input type="text" name="{{ $item->key }}" id="{{ $item->key }}"
                            value="{{ $item->value }}" placeholder="fill in this parameter" autocomplete="off"
                            class="input">

                    </div>
                @endforeach
            </div>


            <div class="notification">
                <p>attributes below are specificly for the table block element.</p>
            </div>
            <div class="body-style">
                <div class="item">
                    <h4>Css key name</h4>
                    <h4>Value ( css format )</h4>
                </div>
                @foreach ($settings->slice(31, 3) as $item)
                    <div class="item">
                        <h4>{{ implode(' ', explode('-', substr($item->key, 5))) }} color</h4>

                        <input type="text" name="{{ $item->key }}" id="{{ $item->key }}"
                            value="{{ $item->value }}" placeholder="fill in this parameter" autocomplete="off"
                            class="input">

                    </div>
                @endforeach

                @foreach ($settings->slice(34, 1) as $item)
                    <div class="item">
                        <h4>{{ implode(' ', explode('-', substr($item->key, 5))) }}</h4>

                        <input type="text" name="{{ $item->key }}" id="{{ $item->key }}"
                            value="{{ $item->value }}" placeholder="fill in this parameter" autocomplete="off"
                            class="input">

                    </div>
                @endforeach

                @foreach ($settings->slice(35, 4) as $item)
                    <div class="item">
                        <h4>{{ implode(' ', explode('-', substr($item->key, 5))) }} color</h4>

                        <input type="text" name="{{ $item->key }}" id="{{ $item->key }}"
                            value="{{ $item->value }}" placeholder="fill in this parameter" autocomplete="off"
                            class="input">

                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script>
        var saveButton = document.getElementById("save-btn");
        saveButton.addEventListener('click', function() {
            const inputs = document.querySelectorAll('input');
            obj = {};
            inputs.forEach(input => {
                if (input.type != 'hidden')
                    obj[input.id] = input.value;
            });
	    console.log(obj);
            fetch("/styling/save", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf
                    },
                    body: JSON.stringify({ 'data': obj })
                }).then(res => {
                    console.log(res);
                    if (res.status == 201) {
                        toastr.info("The values were saved successfully!");
                    } else {
                        toastr.error("There was an error saving the values!");
                    }
                });
        });
    </script>
@endsection
