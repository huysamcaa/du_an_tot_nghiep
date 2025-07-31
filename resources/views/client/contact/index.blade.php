@extends('client.layouts.app')

@section('title','liên hệ')

@section('content')
<br><br><br><br>
<div class="mt-5">
        <h4>Thông tin liên hệ</h4><hr>
        <p><strong>Địa chỉ:</strong>số 8 đường Trịnh Văn Bô</p>
        <p><strong>Email:</strong> ulina@gmail.com</p>
        <p><strong>Hotline:</strong> 0862 123 123</p>
    </div>

    <div class="mt-4">
       <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.8801673889443!2d105.74580677503171!3d21.037480280614!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x313454925fe4eb45%3A0x5c64e5fa4eb4317!2zxJAuIFBoxrDGoW5nIENhbmgvOCBQLiBUcuG7i25oIFbEg24gQsO0LCBYdcOibiBQaMawxqFuZywgTmFtIFThu6sgTGnDqm0sIEjDoCBO4buZaSwgVmnhu4d0IE5hbQ!5e0!3m2!1svi!2s!4v1753807358985!5m2!1svi!2s" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
</div>

<div class="container py-5">
    <h2 class="text-center mb-4">Liên hệ với chúng tôi</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

  <form id="contactForm" action="{{ route('client.contact.submit') }}" method="POST" class="row g-3">
        @csrf

        <div class="col-md-6">
            <label for="name" class="form-label">Họ và tên *</label>
            <input type="name" name="name" class="form-control" value="{{ old('name') }}" required>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="col-md-6">
            <label for="email" class="form-label">Email *</label>
            <input type="text" name="email" class="form-control" value="{{ old('email') }}" required>
            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="col-md-6">
            <label for="phone" class="form-label">Số điện thoại *</label>
            <input type="number" name="phone" class="form-control" value="{{ old('phone') }}" required>
            @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="col-md-12">
            <label for="message" class="form-label">Nội dung *</label>
            <textarea name="message" class="form-control" rows="5">{{ old('message') }}</textarea>
            @error('message') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-primary">Gửi liên hệ</button>
        </div>
        <div id="formMessage" class="mt-3"></div>
    </form>

@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#contactForm').on('submit', function(e) {
        e.preventDefault(); // Ngăn reload trang

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
                $('#contactForm')[0].reset(); // Xoá form sau khi gửi
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
