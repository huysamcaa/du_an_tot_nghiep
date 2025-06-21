@extends('admin.layouts.app')

@section('content')
<div class="container">
    <h2>Danh sách tài khoản bị khóa</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Họ tên</th>
                <th>Email</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td><span class="badge badge-danger">Bị khóa</span></td>
                <td>
                    <form action="{{ route('admin.users.unlock', $user->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button class="btn btn-success btn-sm" onclick="return confirm('Mở khóa tài khoản này?')">Mở khóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
