<x-app-layout>
    <x-slot name="header">Danh sách đơn xin phép</x-slot>

    @foreach ($requests as $req)
        <div class="border p-4 my-2 rounded">
            <p><strong>{{ $req->user->name }}</strong> - {{ ucfirst($req->type) }} ngày {{ $req->date }}</p>
            <p>Lý do: {{ $req->reason ?? 'Không có' }}</p>
            <p>Trạng thái: <strong class="text-{{ $req->status === 'approved' ? 'green' : ($req->status === 'rejected' ? 'red' : 'yellow') }}-500">{{ ucfirst($req->status) }}</strong></p>

            @if($req->status === 'pending' && auth()->user()->role !== 'employee' && $req->user->role === 'employee')
                <form action="{{ route('requests.approve', $req) }}" method="POST" class="inline">
                    @csrf
                    <button class="bg-green-500 text-white px-3 py-1 rounded">Duyệt</button>
                </form>
                <form action="{{ route('requests.reject', $req) }}" method="POST" class="inline">
                    @csrf
                    <button class="bg-red-500 text-white px-3 py-1 rounded">Từ chối</button>
                </form>
            @endif
        </div>
    @endforeach
</x-app-layout>
