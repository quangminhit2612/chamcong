<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bảng Quản Trị') }}
        </h2>
    </x-slot>

    <div class="py-6 px-4 max-w-7xl mx-auto space-y-8">

        <!-- BẢNG NGHỈ PHÉP & OT -->
        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-lg font-bold mb-4">1. Danh sách xin nghỉ & làm thêm (OT)</h3>

            <div class="grid md:grid-cols-2 gap-6">
                <!-- Nghỉ phép -->
                <div>
                    <h4 class="font-semibold mb-2">Xin nghỉ</h4>
                    @if($leaves->count() > 0)
                    <table id="leaveTable" class="w-full text-sm border">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-3 py-2 border">#</th>
                                <th class="px-3 py-2 border">Tên</th>
                                <th class="px-3 py-2 border">Ngày</th>
                                <th class="px-3 py-2 border">Lý do</th>
                                <th class="px-3 py-2 border">Trạng thái</th>
                                <th class="px-3 py-2 border">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leaves as $index => $leave)
                                <tr>
                                    <td class="px-3 py-2 border">{{ $index + 1 }}</td>
                                    <td class="px-3 py-2 border">{{ $leave->user->name }}</td>
                                    <td class="px-3 py-2 border">{{ $leave->date->format('d-m-Y') }}</td>
                                    <td class="px-3 py-2 border">{{ $leave->reason }}</td>
                                    <td class="px-3 py-2 border">
                                        @if ($leave->status === 'approved')
                                            <span class="text-green-600 font-semibold">Đã duyệt</span>
                                        @elseif ($leave->status === 'rejected')
                                            <span class="text-red-600 font-semibold">Từ chối</span>
                                        @else
                                            <span class="text-yellow-600 font-semibold">Chờ duyệt</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-2 border">
                                        @if ($leave->status === 'pending')
                                            <form method="POST" action="{{ route('requests.approve', $leave->id) }}">
                                                @csrf
                                                <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">
                                                    Duyệt
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-400">--</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-3">Không có đơn nghỉ phép nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    @endif
                </div>

                <!-- OT -->
                <div>
                    <h4 class="font-semibold mb-2">Làm thêm (OT)</h4>
                    @if($ots->count() > 0)
                    <table id="otTable" class="w-full text-sm border">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-3 py-2 border">#</th>
                                <th class="px-3 py-2 border">Tên</th>
                                <th class="px-3 py-2 border">Ngày</th>
                                <th class="px-3 py-2 border">Giờ bắt đầu</th>
                                <th class="px-3 py-2 border">Giờ kết thúc</th>
                                <th class="px-3 py-2 border">Trạng thái</th>
                                <th class="px-3 py-2 border">Thao tác</th> {{-- Thêm cột hành động --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ots as $index => $item)
                                <tr>
                                    <td class="px-3 py-2 border">{{ $index + 1 }}</td>
                                    <td class="px-3 py-2 border">{{ $item->user->name }}</td>
                                    <td class="px-3 py-2 border">{{ $item->ot_date ? \Carbon\Carbon::parse($item->ot_date)->format('d-m-Y') : 'N/A' }}</td>
                                    <td class="px-3 py-2 border">{{ \Carbon\Carbon::parse($item->ot_start_time)->format('H:i') }}</td>
                                    <td class="px-3 py-2 border">{{ \Carbon\Carbon::parse($item->ot_end_time)->format('H:i') }}</td>
                                    <td class="px-3 py-2 border">
                                        @if($item->status === 'pending')
                                            <span class="text-yellow-600 font-semibold">Chờ duyệt</span>
                                        @elseif($item->status === 'approved')
                                            <span class="text-green-600 font-semibold">Đã duyệt</span>
                                        @else
                                            {{ ucfirst($item->status) }}
                                        @endif
                                    </td>
                                    <td class="px-3 py-2 border text-center">
                                        @if($item->status === 'pending')
                                            <form action="{{ route('ot.approve', $item->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 text-sm rounded">
                                                    Duyệt
                                                </button>
                                            </form>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-3 py-2 border text-center">Không có dữ liệu phù hợp.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>
        <!-- BẢNG CHẤM CÔNG NHÂN VIÊN -->
        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-lg font-bold mb-4">2. Danh sách chấm công nhân viên</h3>

            <form method="GET" class="mb-4 flex gap-4">
                <!-- Chọn từ ngày -->
                <label for="start_date" class="flex items-center">Từ</label>
                <input type="date" name="start_date" class="border rounded px-2 py-1" value="{{ request('start_date') }}">

                <!-- Chọn đến ngày -->
                <label for="end_date" class="flex items-center">Đến</label>
                <input type="date" name="end_date" class="border rounded px-2 py-1" value="{{ request('end_date') }}">

                <!-- Dropdown chọn nhân viên -->
                <select name="user_id" class="border rounded px-2 py-1">
                    <option value="">Chọn nhân viên</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>

                <!-- Nút lọc -->
                <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">Tìm kiếm</button>
            </form>


            <div class="overflow-x-auto" id="ot-results">
                @if($attendances->count() > 0)
                    <table id="attendanceTable" class="min-w-full text-sm border">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-3 py-2 border">#</th>
                                <th class="px-3 py-2 border">Tên</th>
                                <th class="px-3 py-2 border">Ngày</th>
                                <th class="px-3 py-2 border">Giờ vào</th>
                                <th class="px-3 py-2 border">Giờ ra</th>
                                <th class="px-3 py-2 border">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendances as $index => $item)
                                <tr>
                                    <td class="px-3 py-2 border">{{ $index + 1 }}</td>
                                    <td class="px-3 py-2 border">{{ $item->user->name }}</td>
                                    <td class="px-3 py-2 border">{{ $item->checked_in_at->format('d-m-Y') }}</td>
                                    <td class="px-3 py-2 border">{{ $item->checked_in_at->format('H:i') }}</td>
                                    <td class="px-3 py-2 border">{{ $item->checked_out_at ? $item->checked_out_at->format('H:i') : 'N/A' }}</td>
                                    <td class="px-3 py-2 border">{{ $item->status === 'checked_in' ? 'Chưa chấm công ra' : 'Hoàn tất' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center py-4 text-gray-500 text-sm">Không có dữ liệu phù hợp.</div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>

<!-- Khởi tạo DataTable sau khi DOM load hoàn toàn -->
<script>
    $(document).ready(function () {
        $('#attendanceTable').DataTable({
            language: {
                emptyTable: "Không có dữ liệu phù hợp",
                search: "Tìm kiếm:",
                lengthMenu: "Hiển thị _MENU_ dòng",
                info: "Hiển thị _START_ đến _END_ của _TOTAL_ dòng",
                paginate: {
                    first: "Đầu",
                    last: "Cuối",
                    next: "→",
                    previous: "←"
                }
            }
        });

        $('#leaveTable').DataTable({
            language: {
                emptyTable: "Không có dữ liệu phù hợp",
                search: "Tìm kiếm:",
                lengthMenu: "Hiển thị _MENU_ dòng",
                info: "Hiển thị _START_ đến _END_ của _TOTAL_ dòng",
                paginate: {
                    first: "Đầu",
                    last: "Cuối",
                    next: "→",
                    previous: "←"
                }
            }
        });
        $('#otTable').DataTable({
            language: {
                emptyTable: "Không có dữ liệu phù hợp",
                search: "Tìm kiếm:",
                lengthMenu: "Hiển thị _MENU_ dòng",
                info: "Hiển thị _START_ đến _END_ của _TOTAL_ dòng",
                paginate: {
                    first: "Đầu",
                    last: "Cuối",
                    next: "→",
                    previous: "←"
                }
            }
        });
    });
</script>

<!-- Trỏ con lăn chuột đúng vị trí !-->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('start_date') || urlParams.has('end_date') || urlParams.has('user_id')) {
            const resultSection = document.getElementById('ot-results');
            if (resultSection) {
                resultSection.scrollIntoView({ behavior: 'smooth' });
            }
        }
    });
</script>
