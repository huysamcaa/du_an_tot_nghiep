<div class="row">
    @foreach ($products as $product)
        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
            <div class="productItem01">
                <div class="pi01Thumb" style="height: 300px; overflow: hidden;">
                    <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}"
                         style="width: 100%; height: auto; object-fit: cover;" />

                    <div class="pi01Actions" data-product-id="{{ $product->id }}">
                        <a href="javascript:void(0);" class="pi01QuickView"><i class="fa-solid fa-arrows-up-down-left-right"></i></a>
                        <a href="{{ route('product.detail', $product->id) }}"><i class="fa-solid fa-eye"></i></a>
                    </div>

                    @if ($product->sale_price && $product->price > $product->sale_price)
                        <div class="productLabels clearfix">
                            <span class="plDis">
                                -{{ number_format($product->price - $product->sale_price, 0, ',', '.') }}đ
                            </span>
                            <span class="plSale">SALE</span>
                        </div>
                    @endif
                </div>

                <div class="pi01Details">
                    <h3 style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $product->name }}">
                        <a href="{{ route('product.detail', $product->id) }}" style="color: inherit; text-decoration: none;">
                            {{ $product->name }}
                        </a>
                    </h3>

                    <div class="pi01Price">
                        <ins>{{ number_format($product->sale_price ?? $product->price, 0, ',', '.') }}đ</ins>
                        @if ($product->sale_price && $product->price > $product->sale_price)
                            <del>{{ number_format($product->price, 0, ',', '.') }}đ</del>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $products->links() }}
</div>
<style>
    
.pagination {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}


.pagination .page-link {
    color: #7b9494; 
    border: 1px solid #c5d0d0; 
    background-color: transparent;
    border-radius: 50% !important; 
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 500;
    transition: all 0.3s ease;
    padding: 0;
    font-size: 22px;
    
}

.pagination .page-link:hover {
    background-color: #7b9494;
    color: white;
    border-color: #7b9494;
}


.pagination .active .page-link {
    background-color: #7b9494;
    border-color: #7b9494;
    color: white;
}


.pagination .page-item {
    margin: 0 4px;
}


.pagination .disabled .page-link {
    opacity: 0.5;
    pointer-events: none;
}

</style>
