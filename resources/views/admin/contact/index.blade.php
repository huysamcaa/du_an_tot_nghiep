@extends('admin.layouts.app')

@section('title', 'Quản lý liên hệ')

@section('content')
<div class="breadcrumbs">
    <div class="breadcrumbs-inner">
        <div class="row m-0">
            <div class="col-sm-4">
                <div class="page-header float-left">
                    <div class="page-title">
                        <h1>Admin</h1>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="page-header float-right">
                    <div class="page-title">
                        <ol class="breadcrumb text-right">
                            <li><a href="#">Trang chủ</a></li>
                            <li class="active">Quản lý liên hệ</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">Danh sách liên hệ</strong>
                    </div>
                    <div class="card-body">

                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <div class="table-responsive">
                            <table id="contacts-table" class="table table-striped table-bordered">
                                <thead class="thead-dark text-center">
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
                                            <td class="text-center">{{ $contact->id }}</td>
                                            <td>{{ $contact->name }}</td>
                                            <td>{{ $contact->email }}</td>
                                            <td>{{ $contact->phone }}</td>
                                            <td>{{ Str::limit($contact->message, 50) }}</td>
                                            <td class="text-center">{{ $contact->created_at ? $contact->created_at->format('H:i d/m/Y') : 'Không có' }}</td>
                                            <td class="text-center">
                                                <div class="d-flex gap-2 justify-content-center flex-wrap">
                                                    <a href="{{ route('admin.contact.show', $contact->id) }}" class="btn btn-sm btn-info">Xem</a>
                                                    @if(!$contact->is_contacted)
                                                        <form action="{{ route('admin.contact.markContacted', $contact->id) }}" method="POST" onsubmit="return confirm('Xác nhận đã liên hệ với khách hàng này?')">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm btn-success">Liên hệ</button>
                                                        </form>
                                                    @else
                                                        <span class="badge bg-success px-2 py-1">Đã liên hệ</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">Không có dữ liệu liên hệ.</td>
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
</div>

{{-- DataTables --}}
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
    $(document).ready(function () {
        $('#contacts-table').DataTable({
            order: [[0, 'desc']],
            pageLength: 10,
            language: {
                search: "Tìm kiếm:",
                lengthMenu: "Hiển thị _MENU_ mục",
                info: "Hiển thị _START_ đến _END_ của _TOTAL_ liên hệ",
                paginate: {
                    first: "Đầu",
                    last: "Cuối",
                    next: "›",
                    previous: "‹"
                },
                emptyTable: "Không có dữ liệu"
            }
        });
    });
</script>
@endsection
