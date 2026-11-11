
@extends('admin.layouts.main')

@section('content')
<div class="container-fluid py-4">

    <div class="mb-4">
        <h4 class="fw-bold">Thông tin nhà trọ</h4>
        <p class="text-muted mb-0">
            Quản lý thông tin chung của nhà trọ.
        </p>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">

            <form action="{{ route('house-settings.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Tên nhà trọ <span class="text-danger">*</span></label>
                    <input
                        type="text"
                        name="house_name"
                        class="form-control @error('house_name') is-invalid @enderror"
                        value="{{ old('house_name', $houseSetting?->house_name) }}"
                        placeholder="Nhập tên nhà trọ"
                        required
                    >
                    @error('house_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Địa chỉ <span class="text-danger">*</span></label>
                    <input
                        type="text"
                        name="address"
                        class="form-control @error('address') is-invalid @enderror"
                        value="{{ old('address', $houseSetting?->address) }}"
                        placeholder="Nhập địa chỉ nhà trọ"
                        required
                    >
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Số điện thoại liên hệ</label>
                    <input
                        type="text"
                        name="phone"
                        class="form-control @error('phone') is-invalid @enderror"
                        value="{{ old('phone', $houseSetting?->phone) }}"
                        placeholder="Nhập số điện thoại"
                    >
                    @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="form-label">Mô tả / Thông tin bổ sung</label>
                    <textarea
                        name="description"
                        class="form-control @error('description') is-invalid @enderror"
                        rows="4"
                        placeholder="Nhập thông tin bổ sung..."
                    >{{ old('description', $houseSetting?->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-success px-4">
                        <i class="fa-solid fa-floppy-disk me-2"></i>
                        Lưu thông tin
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection
