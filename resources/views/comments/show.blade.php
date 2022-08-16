@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Show Comments') }}</div>

                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Id</th>
                                <th scope="col">Content</th>
                                <th scope="col">Name Author</th>
                                <th scope="col">Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($comments as $comment)
                                <tr>
                                    <th scope="row">{{ $comment->id }}</th>
                                    <td>{{ $comment->content }}</td>
                                    <td>{{ $comment->user->name }}</td>
                                    <td>
                                        @can('update', $comment)
                                            <a class="btn btn-primary"
                                                href="{{ route('commment.edit', ['comment_id' => $comment->id]) }}">Edit</a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
