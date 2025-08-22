@extends('client.layouts.app')

@section('title','Liên hệ')

@section('content')
<style>
    .contact-info p {
        margin-bottom: 0.5rem;
    }
    .contact-section {
        background-color: #f8f9fa;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
    .map-container {
        overflow: hidden;
        border-radius: 15px;
        margin-top: 20px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .form-section {
        background-color: #ffffff;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 0 15px rgba(0,0,0,0.05);
    }
</style>

<div class="container my-5">
    <div class="row g-4">
        <div class="col-lg-5">
            <div class="contact-section">
                <h4 class="mb-3">Thông tin liên hệ</h4>
                <hr>
                <div class="contact-info">
                    <p><i class="bi bi-geo-alt-fill text-danger me-2"></i><strong>Địa chỉ:</strong> Số 8 đường Trịnh Văn Bô</p>
                    <p><i class="bi bi-envelope-fill text-primary me-2"></i><strong>Email:</strong> ulina@gmail.com</p>
                    <p><i class="bi bi-telephone-fill text-success me-2"></i><strong>Hotline:</strong> 0862 123 123</p>
                </div>
            </div>

           <div class="mt-4">
       <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.8801673889443!2d105.74580677503171!3d21.037480280614!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x313454925fe4eb45%3A0x5c64e5fa4eb4317!2zxJAuIFBoxrDGoW5nIENhbmgvOCBQLiBUcuG7i25oIFbEg24gQsO0LCBYdcOibiBQaMawxqFuZywgTmFtIFThu6sgTGnDqm0sIEjDoCBO4buZaSwgVmnhu4d0IE5hbQ!5e0!3m2!1svi!2s!4v1753807358985!5m2!1svi!2s" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
        </div>

        <div class="col-lg-7">
            <div class="form-section">
                <h3 class="text-center mb-4">Liên hệ với chúng tôi</h3>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form id="contactForm" action="{{ route('client.contact.submit') }}" method="POST" class="row g-3">
                    @csrf

                    <div class="col-md-6">
                        <label class="form-label">Họ và tên *</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                        @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Số điện thoại *</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                        @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label">Nội dung *</label>
                        <textarea name="message" class="form-control" rows="5" required>{{ old('message') }}</textarea>
                        @error('message') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary px-5 py-2">Gửi liên hệ</button>
                    </div>

                    <div id="formMessage" class="mt-3"></div>
                </form>
            </div>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#contactForm').on('submit', function(e) {
        e.preventDefault();

        let formData = $(this).serialize();

        $.ajax({
            type: 'POST',
            url: "{{ route('client.contact.submit') }}",
            data: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#formMessage').html(`<div class="alert alert-success">Cảm ơn bạn đã liên hệ!</div>`);
                $('#contactForm')[0].reset();
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let errorHtml = `<div class="alert alert-danger"><ul>`;
                    $.each(errors, function(key, value) {
                        errorHtml += `<li>${value[0]}</li>`;
                    });
                    errorHtml += `</ul></div>`;
                    $('#formMessage').html(errorHtml);
                } else {
                    $('#formMessage').html(`<div class="alert alert-danger">Đã xảy ra lỗi. Vui lòng thử lại sau.</div>`);
                }
            }
        });
    });
});
</script>
@endpush
