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
    <form action="{{ route('web.company.store') }}" method="post">
        <div class="form-group">
            <label>公司統編</label>
            <input type="number" name="tax_id_number" class="form-control">
        </div>
        <div class="form-group">
            <label>公司名稱</label>
            <input type="text" name="name" class="form-control">
        </div>
        <div class="form-group">
            <label>聯絡人</label>
            <input type="text" name="contact_person" class="form-control">
        </div>
        <div class="form-group">
            <label>聯絡電話</label>
            <input type="text" name="contact_phone_number" class="form-control">
        </div>
        <div class="form-group">
            <label>聯絡信箱</label>
            <input type="email" name="contact_email" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">註冊</button>
    </form>

@endsection
