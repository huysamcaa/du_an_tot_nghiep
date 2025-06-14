// resources/js/product-variants.js
document.addEventListener('DOMContentLoaded', function() {
    const variantSelects = document.querySelectorAll('.variant-attribute');
    
    if (variantSelects.length > 0) {
        variantSelects.forEach(select => {
            select.addEventListener('change', updateSelectedVariant);
        });
    }
    
    function updateSelectedVariant() {
        const productId = document.querySelector('meta[name="product-id"]').content;
        const selectedValues = Array.from(variantSelects).map(select => select.value);
        
        fetch(`/api/products/${productId}/variant`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ attributes: selectedValues })
        })
        .then(response => response.json())
        .then(data => {
            if (data.variant) {
                updateVariantInfo(data.variant);
            } else {
                showVariantNotAvailable();
            }
        });
    }
    
    function updateVariantInfo(variant) {
        document.querySelector('.variant-price').textContent = variant.price_formatted;
        document.querySelector('.variant-stock').textContent = variant.stock > 0 ? 'Còn hàng' : 'Hết hàng';
        document.querySelector('input[name="variant_id"]').value = variant.id;
        
        // Enable add to cart button
        document.querySelector('.add-to-cart-btn').disabled = variant.stock <= 0;
    }
    
    function showVariantNotAvailable() {
        document.querySelector('.variant-price').textContent = '--';
        document.querySelector('.variant-stock').textContent = 'Không có sẵn';
        document.querySelector('input[name="variant_id"]').value = '';
        document.querySelector('.add-to-cart-btn').disabled = true;
    }
});