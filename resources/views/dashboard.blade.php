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
            <button class="btn w-full py-3 shadow-md bg-red-500 text-white rounded-lg" data-bs-toggle="modal" data-bs-target="#requestModal">
                <i class="bi bi-calendar-x text-3xl block"></i> Xin Nghỉ/ Đi muộn
            </button>
        </div>
        <div>
            <button class="btn w-full py-3 shadow-md bg-amber-500 text-white rounded-lg" data-bs-toggle="modal" data-bs-target="#otRequestModal">
                <i class="bi bi-clock-history text-3xl block"></i> Xin OT
            </button>
        </div>
        <!-- <div>
            <button class="btn w-full py-3 shadow-md bg-amber-500 text-white rounded-lg" data-bs-toggle="modal" data-bs-target="#leaveBalanceModal">
                <i class="bi bi-clipboard-check text-3xl block"></i> Lịch sử công làm việc
            </button>
        </div> -->
        <!-- <div>
            <button class="btn w-full py-3 shadow-md bg-amber-500 text-white rounded-lg" data-bs-toggle="modal" data-bs-target="#requestListModal">
                <i class="bi bi-journal-text text-3xl block"></i> Lịch Sử Nghỉ
            </button>
        </div>
        <div>
            <button class="btn w-full py-3 shadow-md bg-amber-500 text-white rounded-lg" data-bs-toggle="modal" data-bs-target="#otHistoryModal">
                <i class="bi bi-clock text-3xl block"></i> Lịch Sử OT
            </button>
        </div>
        <div> -->
            <button class="btn w-full py-3 shadow-md bg-amber-500 text-white rounded-lg" data-bs-toggle="modal" data-bs-target="#attendanceHistoryModal">
                <i class="bi bi-clock text-3xl block"></i> Lịch sử làm việc
            </button>
        </div>
    </div>

    {{-- Các Modal giữ nguyên như bạn đã viết ở trên (có thể thêm border-radius + padding nếu cần) --}}

    <!-- Modal Tạo Đơn Xin Nghỉ -->
    <div class="modal fade" id="requestModal" tabindex="-1" aria-labelledby="requestModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="requestModalLabel">Tạo Đơn Xin nghỉ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('requests.store') }}" method="POST" id="requestForm">
                        @csrf

                        <div class="mb-3">
                            <label for="type" class="form-label">Loại Đơn</label>
                            <select name="type" id="type" class="form-select" required>
                                <option value="">-- Chọn loại đơn --</option>
                                <option value="leave">Nghỉ phép</option>
                                <option value="late">Đi muộn</option>
                                <option value="remote">Làm việc từ xa</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="date" class="form-label">Ngày</label>
                            <input type="date" name="date" id="date" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="reason" class="form-label">Lý Do</label>
                            <textarea name="reason" id="reason" class="form-control" rows="3" placeholder="Nhập lý do" required></textarea>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Gửi Đơn</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal chứa danh sách đơn -->
    <div class="modal fade" id="requestListModal" tabindex="-1" aria-labelledby="requestListModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="requestListModalLabel">Danh Sách Đơn Xin Phép</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    @foreach ($requests as $req)
                        <div class="border rounded p-3 mb-3">
                            <p><strong>{{ $req->user->name }}</strong> - {{ ucfirst($req->type) }} ngày {{ $req->date }}</p>
                            <p><strong>Lý do:</strong> {{ $req->reason ?? 'Không có' }}</p>
                            <p>
                                <strong>Trạng thái:</strong>
                                <span class="text-{{ $req->status === 'approved' ? 'success' : ($req->status === 'rejected' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($req->status) }}
                                </span>
                            </p>

                            @if($req->status === 'pending' && auth()->user()->role !== 'employee' && $req->user->role === 'employee')
                                <form action="{{ route('requests.approve', $req) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-success btn-sm">Duyệt</button>
                                </form>
                                <form action="{{ route('requests.reject', $req) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-danger btn-sm">Từ chối</button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

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
                            <div class="row">
                                <div class="col">
                                    <label for="otStartTime" class="form-label">Từ</label>
                                    <input type="time" class="form-control" id="otStartTime" required>
                                </div>
                                <div class="col">
                                    <label for="otEndTime" class="form-label">Đến</label>
                                    <input type="time" class="form-control" id="otEndTime" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="otHours" class="form-label">Tổng Số Giờ OT</label>
                            <input type="number" class="form-control" id="otHours" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="otReason" class="form-label">Lý Do</label>
                            <textarea class="form-control" id="otReason" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Gửi Yêu Cầu</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Attendance History -->
    <div class="modal fade" id="attendanceHistoryModal" tabindex="-1" aria-labelledby="attendanceHistoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="attendanceHistoryModalLabel">Lịch Sử</h5>

                    <select id="historyTypeSelector" class="form-select form-select-sm w-auto ms-3">
                        <option value="attendance">Chấm Công</option>
                        <option value="leave">Xin nghỉ</option>
                        <option value="ot">Làm Thêm Giờ</option>
                    </select>

                    <a id="downloadBtn" class="btn btn-success btn-sm ms-3" href="#" target="_blank">
                        Tải về Excel
                    </a>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <!-- Table to display attendance history -->
                    <table class="table table-bordered" id="attendanceHistoryTable">
                        <thead id="attendanceHistoryHeader">
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
                    if (res.status === 403) {
                        // Giải mã Unicode và hiển thị thông báo
                        alert("Yêu cầu kết nối vào Wifi công ty");  // Hiển thị thông báo sau khi giải mã
                    } else {
                        alert("Có lỗi xảy ra trên server!");
                    }
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
                    if (res.status === 403) {
                        // Giải mã Unicode và hiển thị thông báo
                        alert("Yêu cầu kết nối vào Wifi công ty");  // Hiển thị thông báo sau khi giải mã
                    } else {
                        alert("Có lỗi xảy ra trên server!");
                    }
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
    const USER_ROLE = "{{ auth()->user()->role }}"; // ví dụ: 'admin', 'gd', 'nhanvien'
    // Khi mở modal, tự động gọi theo mặc định (chấm công)
    $('#attendanceHistoryModal').on('show.bs.modal', function () {
        loadHistory('attendance');
    });

    // Khi thay đổi loại lịch sử (Chấm công / OT / Nghỉ phép)
    $('#historyTypeSelector').on('change', function () {
        const type = $(this).val();
        loadHistory(type);
    });

    function loadHistory(type) {
        $.ajax({
            url: '/history-data',
            method: 'GET',
            data: { type: type },
            success: function(response) {
                const thead = $('#attendanceHistoryHeader');
                const tbody = $('#attendanceHistoryBody');

                // Hủy DataTable nếu đã được khởi tạo trước đó
                if ($.fn.DataTable.isDataTable('#attendanceHistoryTable')) {
                    $('#attendanceHistoryTable').DataTable().destroy();
                }

                // Xóa nội dung cũ
                thead.empty();
                tbody.empty();

                // Xử lý từng loại
                if (type === 'attendance') {
                    thead.html(`
                        <tr>
                            <th>#</th>
                            <th>Ngày</th>
                            <th>Trạng Thái</th>
                            <th>Giờ Chấm Công</th>
                            <th>Giờ Rời Công Ty</th>
                        </tr>
                    `);

                    if (!response || response.length === 0) {
                        tbody.append(`<tr><td colspan="5" class="text-center">Không có dữ liệu chấm công.</td></tr>`);
                    } else {
                        response.forEach((item, index) => {
                            let status = item.status === 'checked_in' ? 'Chưa chấm công khi ra về' : 'Đã chấm công đủ';
                            let checkedOutAt = item.checked_out_at || 'N/A';
                            tbody.append(`
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${item.checked_in_at}</td>
                                    <td>${status}</td>
                                    <td>${item.checked_in_at}</td>
                                    <td>${checkedOutAt}</td>
                                </tr>
                            `);
                        });
                    }

                } else if (type === 'ot') {
                    thead.html(`
                        <tr>
                            <th>#</th>
                            <th>Ngày</th>
                            <th>Giờ Bắt Đầu</th>
                            <th>Giờ Kết Thúc</th>
                            <th>Số Giờ</th>
                            <th>Lý Do</th>
                        </tr>
                    `);

                    if (!response || response.length === 0) {
                        tbody.append(`<tr><td colspan="6" class="text-center">Không có dữ liệu làm thêm.</td></tr>`);
                    } else {
                        response.forEach((item, index) => {
                            tbody.append(`
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${item.ot_date}</td>
                                    <td>${item.ot_start_time}</td>
                                    <td>${item.ot_end_time}</td>
                                    <td>${item.ot_hours}</td>
                                    <td>${item.ot_reason || ''}</td>
                                </tr>
                            `);
                        });
                    }

                } else if (type === 'leave') {
                    thead.html(`
                        <tr>
                            <th>#</th>
                            <th>Ngày</th>
                            <th>Lý Do Nghỉ</th>
                        </tr>
                    `);

                    if (!response || response.length === 0) {
                        tbody.append(`<tr><td colspan="3" class="text-center">Không có dữ liệu nghỉ phép.</td></tr>`);
                    } else {
                        response.forEach((item, index) => {
                            tbody.append(`
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${item.date}</td>
                                    <td colspan="2">${item.reason}</td>
                                </tr>
                            `);
                        });
                    }
                }

                // Khởi tạo lại DataTable sau khi dữ liệu đã sẵn sàng
                $('#attendanceHistoryTable').DataTable({
                    pageLength: 10,
                    destroy: true,
                    language: {
                        search: "Tìm kiếm:",
                        lengthMenu: "Hiển thị _MENU_ dòng",
                        info: "Hiển thị _START_ đến _END_ của _TOTAL_ dòng",
                        paginate: {
                            first: "Đầu",
                            last: "Cuối",
                            next: "→",
                            previous: "←"
                        },
                        zeroRecords: "Không tìm thấy dữ liệu phù hợp",
                    }
                });
            },
            error: function(error) {
                console.error('Lỗi khi tải lịch sử:', error);
            }
        });
    }

    function getLabel(type) {
        switch (type) {
            case 'ot': return 'làm thêm giờ (OT)';
            case 'leave': return 'nghỉ phép';
            default: return 'chấm công';
        }
    }
</script>


<!-- Tự tính số giờ OT !-->
<script>
    const startTimeInput = document.getElementById('otStartTime');
    const endTimeInput = document.getElementById('otEndTime');
    const otHoursInput = document.getElementById('otHours');

    function calculateOTHours() {
        const startTime = startTimeInput.value;
        const endTime = endTimeInput.value;

        if (startTime && endTime) {
            const [startHour, startMinute] = startTime.split(':').map(Number);
            const [endHour, endMinute] = endTime.split(':').map(Number);

            let start = new Date();
            let end = new Date();
            start.setHours(startHour, startMinute, 0);
            end.setHours(endHour, endMinute, 0);

            let diffMs = end - start;

            if (diffMs > 0) {
                const diffHours = diffMs / (1000 * 60 * 60);
                otHoursInput.value = diffHours.toFixed(2); // giữ 2 số thập phân
            } else {
                otHoursInput.value = '';
            }
        }
    }

    startTimeInput.addEventListener('input', calculateOTHours);
    endTimeInput.addEventListener('input', calculateOTHours);
</script>


<!-- Modal Xin OT!--> 
<script>
document.addEventListener('DOMContentLoaded', function () {
    const startTimeInput = document.getElementById('otStartTime');
    const endTimeInput = document.getElementById('otEndTime');
    const hoursInput = document.getElementById('otHours');

    function calculateHours() {
        const start = startTimeInput.value;
        const end = endTimeInput.value;

        if (start && end) {
            const startDate = new Date(`1970-01-01T${start}`);
            const endDate = new Date(`1970-01-01T${end}`);
            let diff = (endDate - startDate) / (1000 * 60 * 60);

            if (diff < 0) {
                diff += 24; // support overnight OT
            }

            hoursInput.value = diff.toFixed(2);
        }
    }

    startTimeInput.addEventListener('change', calculateHours);
    endTimeInput.addEventListener('change', calculateHours);

    // Handle form submission
    document.getElementById('otRequestForm').addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = {
            ot_date: document.getElementById('otDate').value,
            ot_start_time: startTimeInput.value,
            ot_end_time: endTimeInput.value,
            ot_hours: hoursInput.value,
            ot_reason: document.getElementById('otReason').value,
        };

        fetch("{{ route('ot-request.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json', // <- BẮT BUỘC THÊM DÒNG NÀY
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(formData)
        })

        .then(res => res.json())
        .then(data => {
            alert(data.message);
            document.getElementById('otRequestForm').reset();
            hoursInput.value = '';
            const modal = bootstrap.Modal.getInstance(document.getElementById('otRequestModal'));
            modal.hide();
        })
        .catch(err => {
            alert("Đã xảy ra lỗi khi gửi yêu cầu OT.");
            console.error(err);
        });
    });
});
</script>


<!-- Tải excel !-->
<script>
    // Lắng nghe sự kiện khi chọn loại lịch sử
    document.getElementById('historyTypeSelector').addEventListener('change', function() {
        // Lấy giá trị của select
        var historyType = this.value;

        // Lấy nút tải về
        var downloadBtn = document.getElementById('downloadBtn');

        // Cập nhật href của nút tải về với giá trị lịch sử được chọn
        downloadBtn.href = '{{ route('attendance.export.excel') }}?history_type=' + historyType;
    });

    // Mặc định gán giá trị của select khi trang tải
    document.addEventListener('DOMContentLoaded', function() {
        var historyType = document.getElementById('historyTypeSelector').value;
        document.getElementById('downloadBtn').href = '{{ route('attendance.export.excel') }}?history_type=' + historyType;
    });
</script>

<script>
    $('#attendanceHistoryTable').DataTable({
        destroy: true, // Cho phép khởi tạo lại nếu bảng đã được DataTables hóa
        pageLength: 10,
        language: {
            search: "Tìm kiếm:",
            lengthMenu: "Hiển thị _MENU_ dòng",
            info: "Hiển thị _START_ đến _END_ trong _TOTAL_ dòng",
            paginate: {
                first: "Đầu",
                last: "Cuối",
                next: "→",
                previous: "←"
            },
            zeroRecords: "Không tìm thấy dữ liệu",
        }
    });
</script>