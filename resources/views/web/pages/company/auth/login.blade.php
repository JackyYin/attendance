@extends('layouts.master')

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
    <form action="{{ route('web.company.authenticate') }}" method="post">
        <div class="form-group">
            <label for="exampleInputEmail1">公司統編</label>
            <input type="number" name="tax_id_number" class="form-control">
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">密碼</label>
            <input type="password" name="password" class="form-control" placeholder="Password">
        </div>
        <button type="submit" class="btn btn-primary">登入</button>
    </form>

@endsection
