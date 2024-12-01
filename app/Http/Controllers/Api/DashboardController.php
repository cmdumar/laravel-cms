<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\MediaFile;
use App\Models\User;
use Illuminate\Http\Response;

class DashboardController extends Controller
{
    public function stats()
    {
        try {
            $stats = [
                'total_pages' => Page::count(),
                'total_media' => MediaFile::count(),
                'total_users' => User::count(),
                'recent_pages' => Page::latest()->take(5)->get(),
                'recent_media' => MediaFile::latest()->take(5)->get(),
            ];

            return response()->json([
                'status' => 'success',
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
