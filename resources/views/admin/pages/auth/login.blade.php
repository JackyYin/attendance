@extends('admin.layouts.master')

@section('content')
    @if ( $session->has('danger'))
        <div class="alert alert-danger">
            @foreach ($session->get('danger') as $key => $value)
            {!! $value[0] !!}
            <br>
            @endforeach
        </div>
    @elseif ( $session->has('info'))
        <div class="alert alert-info">
            @foreach ($session->get('info') as $key => $value)
            {!! $value[0] !!}
            <br>
            @endforeach
        </div>
    @endif
    <form action="{{ route('admin.authenticate') }}" method="post">
        <div class="form-group">
            <label for="exampleInputEmail1">Email address</label>
            <input type="email" name="email" class="form-control" aria-describedby="emailHelp" placeholder="Enter email">
            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Password">
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

@endsection
