@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        {{-- {{ __('You are logged in!') }} --}}
                        <h5>Unread Notifications</h5>
                        @foreach (auth()->user()->unreadNotifications as $notification)
                            <div class="alert alert-success" role="alert">
                                {{ $notification->data['name'] }} started following you!!
                                <a href="{{ route('markAsRead', [$notification->id]) }}">Mask as read</a>
                            </div>
                        @endforeach

                        <h5>Readed Notifications</h5>
                        @foreach (auth()->user()->readNotifications as $notification)
                            <div class="alert alert-success" role="alert">
                                {{ $notification->data['name'] }} started following you!!
                                <a href="{{ route('markAsRead', [$notification->id]) }}">Mask as read</a>
                            </div>
                        @endforeach
                    </div>



                    <div class="card-body">
                        <a class="btn btn-primary w-100" href="{{ route('showInfo') }}">User Infomation</a>
                    </div>

                    <div class="card-body">
                        <a class="btn btn-primary w-100" href="{{ route('comment.create') }}">Create Comment</a>
                    </div>

                    <div class="card-body">
                        <a class="btn btn-primary w-100" href="{{ route('comment.show') }}">Show Comments</a>
                    </div>

                    <div class="card-body">
                        <a class="btn btn-primary w-100" href="{{ route('mail') }}">Send Email</a>
                    </div>

                    <div class="card-body">
                        <a class="btn btn-primary w-100" href="{{ route('notification') }}">Send Notification</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
