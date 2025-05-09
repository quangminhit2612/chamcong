<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Trang chủ') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("Bạn đã đăng nhập!") }}
                </div>
            </div>
        </div> 
    </div>

    <div class="text-center mb-5">
        <h2 class="font-bold text-2xl">📅 Dashboard Chấm Công</h2>
        <!-- <p class="text-gray-500">Theo dõi và thực hiện các hành động chấm công tại đây</p> -->
    </div>

    {{-- Nút Chấm Công --}}
    <div class="flex justify-center mb-5">
        <button
            id="check-in-btn" onclick="checkIn()"
            class="btn text-white rounded-full shadow-lg flex flex-col items-center justify-center hover:scale-105 transition-all duration-300 ease-in-out"
            style="width: 300px; height: 300px; font-size: 1.2rem;">
            <i class="bi bi-fingerprint text-4xl mb-2"></i>
            <span class="font-semibold">Chấm Công</span>
        </button>
    </div>

    {{-- Hàng nút chức năng --}}
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-center max-w-7xl mx-auto">
        <div>
            <button class="btn w-full py-3 shadow-md bg-red-500 text-white rounded-lg" data-bs-toggle="modal" data-bs-target="#leaveModal">
                <i class="bi bi-calendar-x text-3xl block"></i> Xin Nghỉ
            </button>
        </div>
        <div>
            <button class="btn w-full py-3 shadow-md bg-orange-500 text-white rounded-lg" data-bs-toggle="modal" data-bs-target="#otRequestModal">
                <i class="bi bi-clock-history text-3xl block"></i> Xin OT
            </button>
        </div>
        <div>
            <button class="btn w-full py-3 shadow-md bg-lime-500 text-white rounded-lg" data-bs-toggle="modal" data-bs-target="#leaveBalanceModal">
                <i class="bi bi-clipboard-check text-3xl block"></i> Ngày Phép Còn
            </button>
        </div>
        <div>
            <button class="btn w-full py-3 shadow-md bg-blue-500 text-white rounded-lg" data-bs-toggle="modal" data-bs-target="#leaveHistoryModal">
                <i class="bi bi-journal-text text-3xl block"></i> Lịch Sử Nghỉ
            </button>
        </div>
        <div>
            <button class="btn w-full py-3 shadow-md bg-blue-500 text-white rounded-lg" data-bs-toggle="modal" data-bs-target="#otHistoryModal">
                <i class="bi bi-clock text-3xl block"></i> Lịch Sử OT
            </button>
        </div>
        <div>
            <button class="btn w-full py-3 shadow-md bg-blue-500 text-white rounded-lg" data-bs-toggle="modal" data-bs-target="#attendanceHistoryModal">
                <i class="bi bi-clock text-3xl block"></i> Lịch Sử Chấm Công
            </button>
        </div>
    </div>

    {{-- Các Modal giữ nguyên như bạn đã viết ở trên (có thể thêm border-radius + padding nếu cần) --}}

    <!-- Modal for OT Request -->
    <div class="modal fade" id="otRequestModal" tabindex="-1" aria-labelledby="otRequestModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="otRequestModalLabel">Xin Làm Thêm Giờ (OT)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form for submitting OT request -->
                    <form id="otRequestForm">
                        <div class="mb-3">
                            <label for="otDate" class="form-label">Ngày</label>
                            <input type="date" class="form-control" id="otDate" required>
                        </div>
                        <div class="mb-3">
                            <label for="otHours" class="form-label">Số Giờ OT</label>
                            <input type="number" class="form-control" id="otHours" required min="1">
                        </div>
                        <div class="mb-3">
                            <label for="otReason" class="form-label">Lý Do</label>
                            <textarea class="form-control" id="otReason" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="otStatus" class="form-label">Trạng Thái</label>
                            <select class="form-select" id="otStatus" required>
                                <option value="pending">Đang Chờ</option>
                                <option value="approved">Đã Phê Duyệt</option>
                                <option value="rejected">Từ Chối</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Gửi Yêu Cầu</button>
                    </form>

                    <!-- Table to display OT requests history -->
                    <h4 class="mt-4">Lịch Sử Yêu Cầu OT</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Ngày</th>
                                <th>Số Giờ OT</th>
                                <th>Lý Do</th>
                                <th>Trạng Thái</th>
                            </tr>
                        </thead>
                        <tbody id="otHistoryBody">
                            <!-- Data will be injected here dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal for Attendance History -->
    <div class="modal fade" id="attendanceHistoryModal" tabindex="-1" aria-labelledby="attendanceHistoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="attendanceHistoryModalLabel">Lịch Sử Chấm Công</h5>
                    <a href="{{ route('attendance.export.excel') }}" class="btn btn-success btn-sm ms-3" target="_blank">
                        Tải về Excel
                    </a>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Table to display attendance history -->
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Ngày</th>
                                <th>Trạng Thái</th>
                                <th>Giờ Chấm Công</th>
                                <th>Giờ Rời Công Ty</th>
                            </tr>
                        </thead>
                        <tbody id="attendanceHistoryBody">
                            <!-- Data will be injected here dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Gửi yêu cầu GET để lấy trạng thái chấm công từ server
        fetch("{{ route('attendance.status') }}", {
            method: "GET",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            }
        })
        .then(async res => {
            const contentType = res.headers.get("content-type");
            if (!res.ok) {
                const errorText = await res.text();
                console.error("Lỗi từ server:", errorText);
                return;
            }

            if (contentType && contentType.includes("application/json")) {
                const data = await res.json();

                const checkInButton = document.getElementById('check-in-btn');
                // Reset các màu trước
                checkInButton.classList.remove('bg-green-500', 'bg-yellow-500', 'bg-gray-500');

                // Cập nhật màu theo trạng thái
                if (data.status === 'checked_in') {
                    checkInButton.textContent = 'Rời công ty';
                    checkInButton.classList.add('bg-yellow-500'); // Màu vàng cho trạng thái đã chấm công
                } else if (data.status === 'checked_out') {
                    checkInButton.textContent = 'Hẹn gặp lại';
                    checkInButton.classList.add('bg-gray-500'); // Màu xám cho trạng thái đã rời công ty
                } else {
                    checkInButton.textContent = 'Chấm công';
                    checkInButton.classList.add('bg-green-500'); // Màu xanh cho trạng thái chưa chấm công
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });

    function checkIn() {
        const checkInButton = document.getElementById('check-in-btn');

        // Nếu button đang là "Chấm công", thực hiện chấm công
        if (checkInButton.textContent === 'Chấm công') {
            fetch("{{ route('attendance.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({})  // Gửi dữ liệu chấm công
            })
            .then(async res => {
                const contentType = res.headers.get("content-type");
                if (!res.ok) {
                    const errorText = await res.text();
                    console.error("Lỗi từ server:", errorText);
                    alert("Có lỗi xảy ra trên server!");
                    return;
                }

                if (contentType && contentType.includes("application/json")) {
                    const data = await res.json();

                    // Nếu chấm công thành công
                    if (data.message === 'Chấm công thành công!') {
                        checkInButton.textContent = 'Rời công ty';  // Chuyển button thành "Rời công ty"
                        checkInButton.classList.remove('bg-green-500', 'bg-yellow-500', 'bg-gray-500');
                        checkInButton.classList.add('bg-yellow-500');  // Cập nhật lại màu
                        alert('Chấm công thành công!');  // Hiển thị thông báo
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra!');
            });
        } 

        // Nếu button đang là "Rời công ty", thực hiện rời công ty
        else if (checkInButton.textContent === 'Rời công ty') {
            fetch("{{ route('attendance.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ action: 'checkout' })  // Gửi thông tin action = checkout để rời công ty
            })
            .then(async res => {
                const contentType = res.headers.get("content-type");
                if (!res.ok) {
                    const errorText = await res.text();
                    console.error("Lỗi từ server:", errorText);
                    alert("Có lỗi xảy ra trên server!");
                    return;
                }

                if (contentType && contentType.includes("application/json")) {
                    const data = await res.json();

                    // Nếu rời công ty thành công
                    if (data.message === 'Rời công ty thành công!') {
                        checkInButton.textContent = 'Hẹn gặp lại';  // Chuyển button thành "Hẹn gặp lại"
                        checkInButton.classList.remove('bg-green-500', 'bg-yellow-500', 'bg-gray-500');
                        checkInButton.classList.add('bg-gray-500');  // Cập nhật lại màu
                        alert('Hẹn gặp lại!');  // Hiển thị thông báo
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra!');
            });
        } 

        // Nếu button đang là "Hẹn gặp lại", thì không làm gì cả
        else if (checkInButton.textContent === 'Hẹn gặp lại') {
            alert('Ngày làm việc hôm nay của bạn đã kết thúc!');
        }
    }
</script>


<!-- Phần này là Script của Modal !-->
<script>
    // Lấy lịch sử chấm công khi modal mở
    $('#attendanceHistoryModal').on('show.bs.modal', function () {
        // Gửi request AJAX tới API để lấy lịch sử chấm công
        $.ajax({
            url: '/attendance-history',
            method: 'GET',
            success: function(response) {
                let attendanceHistoryBody = $('#attendanceHistoryBody');
                attendanceHistoryBody.empty(); // Clear the table before populating

                // Nếu có dữ liệu lịch sử chấm công
                if (response.length > 0) {
                    response.forEach((attendance, index) => {
                        let status = attendance.status === 'checked_in' ? 'Chưa chấm công khi ra về' : 'Đã chấm công đủ';
                        let checkedOutAt = attendance.checked_out_at ? attendance.checked_out_at : 'N/A';

                        // Thêm dữ liệu vào bảng
                        attendanceHistoryBody.append(`
                            <tr>
                                <td>${index + 1}</td>
                                <td>${attendance.checked_in_at}</td>
                                <td>${status}</td>
                                <td>${attendance.checked_in_at}</td>
                                <td>${checkedOutAt}</td>
                            </tr>
                        `);
                    });
                } else {
                    attendanceHistoryBody.append(`
                        <tr>
                            <td colspan="6" class="text-center">Chưa có lịch sử chấm công.</td>
                        </tr>
                    `);
                }
            },
            error: function(error) {
                console.log('Lỗi khi tải lịch sử chấm công:', error);
            }
        });
    });
</script>
