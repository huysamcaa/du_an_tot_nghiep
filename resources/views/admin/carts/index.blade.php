@extends('admin.layouts.app')

@section('content')

<div class="content">
    <h1 class="mb-4">Quản lý Giỏ hàng</h1>
    @forelse($cartItems as $userId => $items)
    <h5 class="mb-3"><strong class="ms-4 ">User ID: {{ $userId }} ({{ $items[0]->user->email ?? '' }})</strong></h5>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">Danh sách sản phẩm trong giỏ</strong>
                    </div>
                    <div class="card-body">
                        <table id="cart-table-{{ $userId }}" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Ảnh</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Biến thể</th>
                                    <th>Giá</th>
                                    <th>Số lượng</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                <tr>
                                    <td>
                                        <img src="{{ asset('storage/' . $item->thumbnail) }}"
                                            width="50" alt="ảnh">
                                    </td>
                                    <td>{{ $item->product->name ?? '[Đã xoá]' }}</td>
                                    <td>
                                        @if($item->variant)
                                            @if($item->variant->variant_name)
                                                <p>{{$item->variant->variant_name}}</p>
                                            @else
                                            <p>Chưa cấu hình thuộc tính</p>
                                            @endif
                                        @else
                                            <p>Default</p>
                                        @endif
                                    </td>
                                    
                                    <td class="item-total" data-id="{{$item->id}}">
                                        {{ number_format(($item->variant->price ?? $item->product->price) * $item->quantity) }}đ
                                    </td>
                                    
                                    {{-- Form cập nhật số lượng --}}
                                    <td>
                                        <div class="d-flex align-items-center">
                                        <form action="{{ route('admin.carts.update', $item->id) }}" method="POST" class="d-flex">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" name="action" data-action="decrease" data-id="{{$item->id}}" class="btn btn-sm btn-outline-secondary change-qty">-</button>
                                            <span class="px-3 mt-1 quantity-text" data-id="{{$item->id}}">{{$item->quantity}}</span>
                                            <button type="submit" name="action" data-action="increase" data-id="{{$item->id}}" class="btn btn-sm btn-outline-secondary change-qty">+</button>
                                        </form>
                                        </div>
                                    </td>

                                    {{-- Xoá sản phẩm --}}
                                    <td>
                                        <form action="{{ route('admin.carts.destroy', $item->id) }}" method="POST" 
                                            onsubmit="return confirm('Xoá sản phẩm này khỏi giỏ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Xoá</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <h4 class="mt-3">Tổng tiền: 
                            <strong id="cart-total-{{$userId}}">
                                {{ number_format($items->sum(fn($i) => ($i->variant->price ?? $i->product->price) * $i->quantity)) }}đ
                            </strong>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="alert alert-warning">Không có giỏ hàng nào.</div>
@endforelse
{{ $userIds->links() }}
</div><!-- .content -->


<div class="clearfix"></div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function () {
    $('table[id^="cart-table-"]').each(function () {
        $(this).DataTable({
            order: [[0, 'desc']]
        });
    });
});

$(document).ready(function(){
    $('.change-qty').click(function(e){
        e.preventDefault();

        let button = $(this);
        let action = button.data('action');
        let itemId = button.data('id');

        $.ajax({
            url: '/admin/carts/' + itemId,
            method: 'PATCH',
            data: {
                _token: '{{ csrf_token() }}',
                action: action
            },
            success: function(res){
                if(res.success){
                    // cập nhật số lượng
                    $(`.quantity-text[data-id="${itemId}"]`).text(res.new_quantity);

                    // cập nhật tiền sản phẩm
                    $(`.item-total[data-id="${itemId}"]`).text(res.item_total + 'đ');

                    // cập nhật tổng tiền giỏ
                    $(`#cart-total-${res.user_id}`).text(res.cart_total + 'đ');
                }
            },
            error: function(err){
                alert('Đã xảy ra lỗi khi cập nhật số lượng');
                console.log(err);
            }
        });
    });
})

</script>
@endsection
