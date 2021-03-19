@extends('nova::auth.layout')

@section('content')


<div class="bg-white shadow rounded-lg p-8 max-w-login mx-auto">
    @if ($errors->any())
        @foreach($errors->all() as $message)
            <p class="text-center font-semibold text-danger my-3">
                {{ $message }}
            </p>
        @endforeach
    @endif
    <a class="w-full btn btn-default btn-primary hover:bg-primary-dark text-center" href="{{ route('auth.user') }}">
        {{ __('Login with Twitch') }}
    </a>
</div>
@endsection
