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
            $mediaUrl = $file->getFirstMediaUrl('files');

            return response()->json([
                'status' => 'success',
                'data' => [
                    'id' => $file->id,
                    'original_name' => $file->original_name,
                    'mime_type' => $file->mime_type,
                    'file_size' => $file->file_size,
                    'url' => $mediaUrl,
                    'slugs' => $file->slugs
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 404);
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
                ], 400);
            }

            $slug = $this->mediaService->addSlugToFile($id, $request->slug);

            return response()->json([
                'status' => 'success',
                'data' => $slug
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Add method to get file by slug
    public function getBySlug($slug)
    {
        try {
            $mediaFile = $this->mediaService->getFileBySlug($slug);

            return response()->json([
                'status' => 'success',
                'data' => $mediaFile
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'File not found'
            ], 404);
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
