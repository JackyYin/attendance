@extends('web.layouts.master')

@section('content')
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
