@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
        </div>
    </div>
</div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12">
            <h3>My Dance Style</h3>
        </div>
    </div>
    <form method="POST" action="{{ route('my-dance-style-store') }}">
    @csrf
    @foreach ($dancestyles as $dancestyle)
        <div class="row justify-content-center mb-3">
            <div class="col-sm-6">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="dance_styles[]"
                        value="{{ $dancestyle->dancestyleId }}" id="dancestyle_{{ $dancestyle->dancestyleId }}"
                        {{ isset($mydancestyles[$dancestyle->dancestyleId]) ? 'checked' : '' }}>
                    <label class="form-check-label" for="dancestyle_{{ $dancestyle->dancestyleId }}">
                        {{ $dancestyle->dancestyleName }}
                    </label>
                </div>
            </div>
            <div class="col-sm-6">
                @foreach ($dancelevels as $dancelevel)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox"
                            name="dance_selection[{{ $dancestyle->dancestyleId }}][]"
                            value="{{ $dancelevel->dancelevelId }}"
                            id="dancelevel_{{ $dancestyle->dancestyleId }}_{{ $dancelevel->dancelevelId }}"
                            {{ isset($mydancestyles[$dancestyle->dancestyleId]) && in_array($dancelevel->dancelevelId, $mydancestyles[$dancestyle->dancestyleId]['danceLevel']) ? 'checked' : '' }}>
                        <label class="form-check-label"
                            for="dancelevel_{{ $dancestyle->dancestyleId }}_{{ $dancelevel->dancelevelId }}">
                            {{ $dancelevel->dancelevelName }}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
    <div class="row justify-content-center">
        <div class="col-sm-12">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </div>
</form>

</div>
@endsection