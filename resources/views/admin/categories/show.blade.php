@extends('admin.layouts.app')

@section('title', 'Chi tiết danh mục')

@section('content')
<div class="content">
    <div class="page-header">
        <div class="page-title">
            <h4>Chi tiết danh mục</h4>
            <h6>Thông tin chi tiết về danh mục sản phẩm</h6>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <h5 class="mb-3">Thông tin cơ bản</h5>
                </div>
                
                <div class="col-lg-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label>ID</label>
                        <div class="form-control-static">{{ $category->id }}</div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Tên danh mục</label>
                        <div class="form-control-static">{{ $category->name }}</div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Slug</label>
                        <div class="form-control-static">{{ $category->slug }}</div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Danh mục cha</label>
                        <div class="form-control-static">{{ $category->parent ? $category->parent->name : '-' }}</div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Icon</label>
                        <div class="form-control-static">{!! $category->icon !!}</div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Thứ tự hiển thị</label>
                        <div class="form-control-static">{{ $category->ordinal }}</div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Trạng thái</label>
                        <div class="form-control-static">
                            @if($category->is_active)
                                <span class="badge badge-success">Hiển thị</span>
                            @else
                                <span class="badge badge-secondary">Ẩn</span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Ngày tạo</label>
                        <div class="form-control-static">{{ $category->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-sm-6 col-12">
                    <div class="form-group">
                        <label>Ngày cập nhật</label>
                        <div class="form-control-static">{{ $category->updated_at->format('d/m/Y H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <h5 class="mb-3">Sản phẩm liên quan ({{ $products->count() }})</h5>
                </div>
                
                <div class="col-lg-12">
                    @if($products->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Ảnh</th>
                                        <th>Tên</th>
                                        <th>Thương hiệu</th>
                                        <th>Giá</th>
                                        <th>Tồn kho</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                    <tr>
                                        <td>
                                            @if($product->thumbnail)
                                                <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="thumb" style="width:60px; height:60px; object-fit:cover;">
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $product->name }}
                                            <div class="small">
                                                @if($product->is_featured)
                                                    <span class="badge badge-primary">Nổi bật</span>
                                                @endif
                                                @if($product->is_trending)
                                                    <span class="badge badge-warning">Xu hướng</span>
                                                @endif
                                                @if($product->is_sale)
                                                    <span class="badge badge-danger">Sale</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ optional($product->brand)->name ?? '—' }}</td>
                                        <td>
                                            <div>{{ number_format($product->price,0,',','.') }} đ</div>
                                            @if($product->sale_price)
                                                <div class="text-danger">{{ number_format($product->sale_price,0,',','.') }} đ</div>
                                            @endif
                                        </td>
                                        <td>{{ $product->stock }}</td>
                                        <td>
                                            @if($product->is_active)
                                                <span class="badge badge-success">Hiển thị</span>
                                            @else
                                                <span class="badge badge-secondary">Ẩn</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center text-muted">Không có sản phẩm nào trong danh mục này.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('admin.categories.index') }}" class="btn btn-cancel">
            <i class="fa fa-arrow-left"></i> Quay lại danh sách
        </a>
    </div>
</div>
@endsection