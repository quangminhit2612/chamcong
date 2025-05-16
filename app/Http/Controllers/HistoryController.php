<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as HttpRequest;
use App\Models\Attendance;
use App\Models\OtRequest;
use App\Models\LeaveRequest; // model nghỉ phép

class HistoryController extends Controller
{
    public function get(HttpRequest $request)
    {
        $type = $request->query('type', 'attendance');
        $user = auth()->user();
        $isAdminOrGD = in_array(strtolower($user->role), ['admin', 'gd']);

        switch ($type) {
            case 'ot':
                $query = OtRequest::query();
                if (!$isAdminOrGD) {
                    $query->where('user_id', $user->id);
                }
                return $query->latest()->get();

            case 'leave':
                $query = LeaveRequest::query();
                if (!$isAdminOrGD) {
                    $query->where('user_id', $user->id);
                }
                return $query->latest()->get();

            case 'attendance':
            default:
                $query = Attendance::query();
                if (!$isAdminOrGD) {
                    $query->where('user_id', $user->id);
                }
                return $query->latest()->get();
        }
    }

}
