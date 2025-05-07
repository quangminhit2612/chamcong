<x-app-layout>
    <x-slot name="header">Tạo đơn xin phép</x-slot>

    <form action="{{ route('requests.store') }}" method="POST" class="max-w-md mx-auto mt-6 space-y-4">
        @csrf

        <select name="type" required class="w-full border rounded p-2">
            <option value="">-- Chọn loại đơn --</option>
            <option value="leave">Nghỉ phép</option>
            <option value="late">Đi muộn</option>
            <option value="remote">Làm việc từ xa</option>
            <option value="ot">Tăng ca</option>
        </select>

        <input type="date" name="date" required class="w-full border rounded p-2" />
        <textarea name="reason" placeholder="Lý do" class="w-full border rounded p-2"></textarea>

        <button class="bg-blue-500 text-white px-4 py-2 rounded">Gửi đơn</button>
    </form>
</x-app-layout>
