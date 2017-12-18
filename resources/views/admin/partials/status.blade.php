@if (count($errors))
    <div class="errors">
        @foreach ((is_array($errors) ? $errors : $errors->all()) as $error)
            <div class="row">{{ $error }}</div>
        @endforeach
    </div>
@endif

@if (Session::has('success'))
    <div class="success">
        <?php $success = Session::get('success'); ?>
        @if (is_array($success))
            @foreach ($success as $succ)
                <div class="row">{{ $succ }}</div>
            @endforeach
        @else
            <div class="row">{{ $success }}</div>
        @endif
    </div>
@endif