<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Variable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\UploadedFile;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;

class AdminBaseController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function __construct()
    {

        $this->middleware(Variable::ROLE_ADMIN);
    }

    /**
     * Upload an image to storage
     *
     * @param UploadedFile $file
     * @param string $directory
     * @return string Path to uploaded file
     */
    protected function uploadImage(UploadedFile $file, string $directory = 'images'): string
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs($directory, $filename, 'public');
        
        return $path;
    }

    /**
     * Delete an image from storage
     *
     * @param string|null $path
     * @return bool
     */
    protected function deleteImage(?string $path): bool
    {
        if (!$path) {
            return false;
        }

        try {
            if (Storage::disk('public')->exists($path)) {
                return Storage::disk('public')->delete($path);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to delete image', [
                'path' => $path,
                'error' => $e->getMessage(),
            ]);
        }

        return false;
    }

    /**
     * Handle database operations with error handling
     *
     * @param callable $operation
     * @param string $successMessage
     * @param string $errorMessage
     * @param string|null $redirectRoute
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function handleDatabaseOperation(
        callable $operation,
        string $successMessage,
        string $errorMessage = 'An error occurred while processing your request.',
        ?string $redirectRoute = null
    ) {
        try {
            $operation();

            return redirect()
                ->to($redirectRoute ?? url()->previous())
                ->with('success', $successMessage);
        } catch (\Illuminate\Database\QueryException $e) {
            \Log::error('Database operation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', $errorMessage . ' Please try again.');
        } catch (\Exception $e) {
            \Log::error('Operation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', $errorMessage . ' Please try again.');
        }
    }

    /**
     * Handle file upload with error handling
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $directory
     * @return string|null
     */
    protected function handleFileUpload(\Illuminate\Http\UploadedFile $file, string $directory = 'images'): ?string
    {
        try {
            return $this->uploadImage($file, $directory);
        } catch (\Exception $e) {
            \Log::error('File upload failed', [
                'directory' => $directory,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }
}
