@extends('admin.layouts.app')

@section('title', 'Chi tiết liên hệ')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Chi tiết liên hệ</h2>

    <div class="card">
        <div class="card-body">
            <p><strong>ID:</strong> {{ $contact->id }}</p>
            <p><strong>Họ tên:</strong> {{ $contact->name }}</p>
            <p><strong>Email:</strong> {{ $contact->email }}</p>
            <p><strong>Số điện thoại:</strong> {{ $contact->phone }}</p>
            <p><strong>Nội dung:</strong></p>
            <div class="border p-3 bg-light">
                {{ $contact->message }}
            </div>
            <p class="mt-3"><strong>Thời gian gửi:</strong> {{ $contact->created_at->format('H:i d/m/Y') }}</p>
        </div>
    </div>

    <a href="{{ route('admin.contact.index') }}" class="btn btn-secondary mt-3">← Quay lại danh sách</a>
</div>
@endsection
