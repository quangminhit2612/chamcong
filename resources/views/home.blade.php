@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">📅 Dashboard Chấm Công</h2>
        <p class="text-muted">Theo dõi và thực hiện các hành động chấm công tại đây</p>
    </div>

    {{-- Nút Chấm Công --}}
    <div class="d-flex justify-content-center mb-5">
        <button class="btn btn-primary rounded-circle shadow-lg d-flex flex-column align-items-center justify-content-center hover-grow"
                style="width: 300px; height: 300px; font-size: 1.2rem; transition: all 0.3s ease;">
            <i class="bi bi-fingerprint fs-1 mb-2"></i>
            <span class="fw-semibold">Chấm Công</span>
        </button>
    </div>


    {{-- Hàng nút chức năng --}}
    <div class="row g-4 text-center">
        <div class="col-6 col-md-4">
            <button class="btn btn-danger w-100 py-3 shadow" data-bs-toggle="modal" data-bs-target="#leaveModal">
                <i class="bi bi-calendar-x fs-3 d-block"></i> Xin Nghỉ
            </button>
        </div>
        <div class="col-6 col-md-4">
            <button class="btn btn-success w-100 py-3 shadow" data-bs-toggle="modal" data-bs-target="#otModal">
                <i class="bi bi-clock-history fs-3 d-block"></i> Xin OT
            </button>
        </div>
        <div class="col-6 col-md-4">
            <button class="btn btn-success w-100 py-3 shadow" data-bs-toggle="modal" data-bs-target="#leaveBalanceModal">
                <i class="bi bi-clipboard-check fs-3 d-block"></i> Ngày Phép Còn
            </button>
        </div>
        <div class="col-6 col-md-4">
            <button class="btn btn-info w-100 py-3 shadow" data-bs-toggle="modal" data-bs-target="#leaveHistoryModal">
                <i class="bi bi-journal-text fs-3 d-block"></i> Lịch Sử Nghỉ
            </button>
        </div>
        <div class="col-6 col-md-4">
            <button class="btn btn-info w-100 py-3 shadow" data-bs-toggle="modal" data-bs-target="#otHistoryModal">
                <i class="bi bi-clock fs-3 d-block"></i> Lịch Sử OT
            </button>
        </div>
        <div class="col-6 col-md-4">
            <button class="btn btn-secondary w-100 py-3 shadow" data-bs-toggle="modal" data-bs-target="#otHistoryModal">
                <i class="bi bi-clock fs-3 d-block"></i> Lịch Sử Ngày Công
            </button>
        </div>
    </div>

    {{-- Các Modal giữ nguyên như bạn đã viết ở trên (có thể thêm border-radius + padding nếu cần) --}}
</div>
@endsection
