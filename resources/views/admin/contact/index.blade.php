@extends('admin.layouts.app')

@section('title', 'Quản lý liên hệ')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Danh sách liên hệ</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Họ tên</th>
                <th>Email</th>
                <th>Số điện thoại</th>
                <th>Nội dung</th>
                <th>Thời gian</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($contacts as $contact)
                <tr>
                    <td>{{ $contact->id }}</td>
                    <td>{{ $contact->name }}</td>
                    <td>{{ $contact->email }}</td>
                    <td>{{ $contact->phone }}</td>
                    <td>{{ Str::limit($contact->message, 50) }}</td>
                    <td>{{ $contact->created_at ? $contact->created_at->format('H:i d/m/Y') : 'Không có' }}</td>
                    <td>
                        <!-- Nút Xem chi tiết -->
                        <a href="{{ route('admin.contact.show', $contact->id) }}" class="btn btn-sm btn-primary">Xem chi tiết</a>

                                                    @if(!$contact->is_contacted)
                            <form action="{{ route('admin.contact.markContacted', $contact->id) }}" method="POST" class="d-inline-block">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Xác nhận đã liên hệ với khách hàng này?')">liên hệ</button>
                            </form>
                        @else
                            <span class="badge bg-success">Đã liên hệ</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Không có dữ liệu liên hệ.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
