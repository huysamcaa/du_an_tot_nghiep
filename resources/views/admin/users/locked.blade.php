@extends('admin.layouts.app')

@section('title', 'Tài khoản bị khóa')

@section('content')


<div class="content">

    <div class="animated fadeIn">
         <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="mb-0">Tài khoản bị khóa</h4>
                <small class="text-muted">Danh sách tài khoản bị khóa</small>
            </div>
            <div class="mb-3 d-flex" style="gap: 10px;">
                <a href="{{ route('admin.brands.create') }}"
                    style="background-color: #ffa200; color: #fff; border: none; padding: 8px 15px; border-radius: 6px; display: inline-flex; align-items: center; gap: 6px; text-decoration: none;"
                    onmouseover="this.style.backgroundColor='#e68a00'" onmouseout="this.style.backgroundColor='#ffa200'">
                    <i class="fa fa-plus"></i> Thêm tài khoản
                </a>

            </div>


        </div>
        <div class="row">
            <div class="col-md-12">

                {{-- Flash messages --}}
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">Danh sách tài khoản bị khóa</strong>
                    </div>
                    <div class="card-body">
                        <table id="bootstrap-data-table" class="table table-striped table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Họ tên</th>
                                    <th>Email</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $index => $user)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td><span class="badge badge-danger">Bị khóa</span></td>
                                    <td>
                                        <form action="{{ route('admin.users.unlock', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn mở khóa tài khoản này?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Mở khóa">
                                                <i class="fa fa-unlock"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Không có tài khoản bị khóa.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
