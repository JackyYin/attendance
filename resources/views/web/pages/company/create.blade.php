@extends('web.layouts.master')

@section('content')
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
        <div class="form-group">
            <label>密碼</label>
            <input type="password" name="password" class="form-control">
        </div>
        <div class="form-group">
            <label>確認密碼</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">註冊</button>
    </form>

@endsection
