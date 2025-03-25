<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function show($filename)
    {
        // Retrieve the file from the storage directory
        $file = Storage::disk('public')->get($filename);

        // Determine the MIME type of the file
        $mime = Storage::disk('public')->mimeType($filename);

        // Return a response with the file contents and appropriate MIME type
        return response($file, 200)->header('Content-Type', $mime);
    }
}