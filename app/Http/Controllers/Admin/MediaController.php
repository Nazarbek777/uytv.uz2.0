<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $path = $request->input('path', 'public');
        $search = $request->input('search');

        $directories = Storage::directories($path);
        $files = Storage::files($path);

        // Filter files by search
        if ($search) {
            $files = array_filter($files, function ($file) use ($search) {
                return stripos(basename($file), $search) !== false;
            });
        }

        // Sort files by date (newest first)
        usort($files, function ($a, $b) use ($path) {
            return Storage::lastModified($b) - Storage::lastModified($a);
        });

        $fileList = [];
        foreach ($files as $file) {
            $fileList[] = [
                'name' => basename($file),
                'path' => $file,
                'url' => Storage::url($file),
                'size' => Storage::size($file),
                'last_modified' => Storage::lastModified($file),
                'type' => Storage::mimeType($file),
            ];
        }

        $dirList = [];
        foreach ($directories as $dir) {
            $dirList[] = [
                'name' => basename($dir),
                'path' => $dir,
            ];
        }

        $currentPath = $path;
        $breadcrumbs = $this->getBreadcrumbs($currentPath);

        return view('admin.media.index', compact('fileList', 'dirList', 'currentPath', 'breadcrumbs'));
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
            'path' => 'nullable|string',
        ]);

        $path = $request->input('path', 'public');
        $file = $request->file('file');
        $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();

        $file->storeAs($path, $filename);

        return redirect()->route('admin.media.index', ['path' => $path])
            ->with('success', 'Fayl muvaffaqiyatli yuklandi.');
    }

    public function delete(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
        ]);

        $filePath = $request->input('path');

        if (Storage::exists($filePath)) {
            Storage::delete($filePath);
            return redirect()->back()->with('success', 'Fayl muvaffaqiyatli o\'chirildi.');
        }

        return redirect()->back()->with('error', 'Fayl topilmadi.');
    }

    public function createFolder(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'path' => 'nullable|string',
        ]);

        $path = $request->input('path', 'public');
        $folderName = Str::slug($request->input('name'));
        $newPath = $path . '/' . $folderName;

        if (!Storage::exists($newPath)) {
            Storage::makeDirectory($newPath);
            return redirect()->route('admin.media.index', ['path' => $path])
                ->with('success', 'Papka muvaffaqiyatli yaratildi.');
        }

        return redirect()->back()->with('error', 'Papka allaqachon mavjud.');
    }

    private function getBreadcrumbs($path)
    {
        $parts = explode('/', $path);
        $breadcrumbs = [];
        $currentPath = '';

        foreach ($parts as $part) {
            $currentPath .= ($currentPath ? '/' : '') . $part;
            $breadcrumbs[] = [
                'name' => $part ?: 'Root',
                'path' => $currentPath,
            ];
        }

        return $breadcrumbs;
    }
}
