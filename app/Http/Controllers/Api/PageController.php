<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PageService;
use App\Repositories\Interfaces\PageRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    protected $pageService;

    public function __construct(PageService $pageService)
    {
        $this->pageService = $pageService;
    }

    public function index()
    {
        try {
            $result = $this->pageService->getAllPages();
            return response()->json(['status' => 'success', 'data' => $result], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(Request $request)
    {
        try {
            $page = $this->pageService->createPage($request->all());
            return response()->json(['status' => 'success', 'data' => $page], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function show($id)
    {
        try {
            $page = $this->pageService->getPageById($id);
            return response()->json([
                'status' => 'success',
                'data' => $page
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Page not found'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $page = $this->pageService->updatePage($id, $request->all());

            // Trigger revalidation in Next.js
            try {
                $nextJsUrl = env('NEXTJS_URL', 'http://host.docker.internal:3000');

                $response = Http::post($nextJsUrl . '/api/revalidate', [
                    'path' => "/{$id}"  // The path that needs to be revalidated
                ]);

                if (!$response->successful()) {
                    \Log::warning('Failed to revalidate Next.js cache', [
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                }
            } catch (\Exception $e) {
                // Log the error but don't fail the update
                \Log::error('Error revalidating Next.js cache: ' . $e->getMessage());
            }

            return response()->json(['status' => 'success', 'data' => $page], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function destroy($id)
    {
        try {
            $this->pageService->deletePage($id);
            return response()->json(['status' => 'success', 'message' => 'Page deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function attachMedia(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'media_id' => 'required|exists:media_files,id'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'error', 'message' => $validator->errors()], Response::HTTP_BAD_REQUEST);
            }

            $page = $this->pageService->attachMedia($id, $request->media_id);
            return response()->json(['status' => 'success', 'data' => $page], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function detachMedia(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'media_id' => 'required|exists:media_files,id'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'error', 'message' => $validator->errors()], Response::HTTP_BAD_REQUEST);
            }

            $page = $this->pageService->detachMedia($id, $request->media_id);
            return response()->json(['status' => 'success', 'data' => $page], Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
