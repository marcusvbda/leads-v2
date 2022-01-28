<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogViewerController extends Controller
{
    private $ignore_index = [".", "..", ".gitkeep", ".gitignore", ".git"];

    public function index()
    {
        if (!hasPermissionTo('log-viewer-access')) {
            abort(404);
        }
        $tree = $this->getRecursiveFolderTree(storage_path() . '/logs/');
        return view('admin.log-viewer.index', compact('tree'));
    }

    private function getRecursiveFolderTree($dir)
    {
        $files = scandir($dir);
        foreach ($files as $key => $value) {
            $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
            if (!in_array($value, $this->ignore_index)) {
                if (!is_dir($path)) {
                    $results[] =  ["type" => "file", "path" => $path, "label" => $value, "children" => []];
                } else if (!in_array($value, $this->ignore_index)) {
                    $results[] = ["type" => "dir", "path" => $path, "label" => $value, "children" => $this->getRecursiveFolderTree($path, true)];
                }
            }
        }

        return $results;
    }

    public function getContent(Request $request)
    {
        $logFile = file($request->path);
        $logCollection = [];
        foreach ($logFile as $line_num => $line) {
            if (trim(preg_replace('/\s\s+/', ' ', $line))) {
                $logCollection[] = array('line' => $line_num, 'content' => htmlspecialchars($line));
            }
        }
        return $logCollection;
    }
}
