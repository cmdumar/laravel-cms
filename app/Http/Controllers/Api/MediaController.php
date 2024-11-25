<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MediaService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class MediaController extends Controller
{
    protected $mediaService;

    public function __construct(MediaService $mediaService)
    {
        $this->mediaService = $mediaService;
    }

    public function index()
    {
        try {
            $files = $this->mediaService->getAllFiles();
            return response()->json([
                'status' => 'success',
                'data' => $files
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'file' => 'required|file|max:10240', // 10MB max
                'slug' => 'nullable|string|max:255|unique:media_slugs,slug'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()
                ], Response::HTTP_BAD_REQUEST);
            }

            $file = $this->mediaService->uploadFile(
                $request->file('file'),
                $request->input('slug')
            );

            return response()->json([
                'status' => 'success',
                'data' => $file
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        try {
            $file = $this->mediaService->getFileById($id);
            return response()->json([
                'status' => 'success',
                'data' => $file
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function getBySlug($slug)
    {
        try {
            $file = $this->mediaService->getFileBySlug($slug);
            return response()->json([
                'status' => 'success',
                'data' => $file
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }

    public function addSlug(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'slug' => 'required|string|max:255|unique:media_slugs,slug'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()
                ], Response::HTTP_BAD_REQUEST);
            }

            $slug = $this->mediaService->addSlugToFile($id, $request->input('slug'));
            return response()->json([
                'status' => 'success',
                'data' => $slug
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function destroy($id)
    {
        try {
            $this->mediaService->deleteFile($id);
            return response()->json([
                'status' => 'success',
                'message' => 'File deleted successfully'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
