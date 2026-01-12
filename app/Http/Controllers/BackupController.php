<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class BackupController extends Controller
{
    private $backupPath;
    
    public function __construct()
    {
        $this->backupPath = storage_path('app/backups');
    }
    
    public function index()
    {
        if (!in_array(auth()->user()->role, ['admin'])) {
            abort(403);
        }

        if (!File::exists($this->backupPath)) {
            File::makeDirectory($this->backupPath, 0755, true);
        }

        $files = File::files($this->backupPath);
        
        $backups = collect($files)
            ->filter(fn($file) => in_array($file->getExtension(), ['sql', 'gz', 'zip']))
            ->map(function ($file) {
                $type = 'manual';
                if (str_contains($file->getFilename(), 'daily')) $type = 'daily';
                if (str_contains($file->getFilename(), 'weekly')) $type = 'weekly';
                if (str_contains($file->getFilename(), 'monthly')) $type = 'monthly';
                
                return [
                    'name' => $file->getFilename(),
                    'size' => $this->formatBytes($file->getSize()),
                    'date' => date('d/m/Y H:i:s', $file->getMTime()),
                    'timestamp' => $file->getMTime(),
                    'type' => $type
                ];
            })
            ->sortByDesc('timestamp')
            ->values();

        return view('backup.index', compact('backups'));
    }

    public function create(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin'])) {
            abort(403);
        }

        try {
            $type = $request->input('type', 'manual');
            $fileName = $this->createBackup($type);
            
            return redirect()->route('backup.index')
                ->with('success', 'Backup berhasil dibuat: ' . $fileName);
        } catch (\Exception $e) {
            return redirect()->route('backup.index')
                ->with('error', 'Backup gagal: ' . $e->getMessage());
        }
    }

    public function download($fileName)
    {
        if (!in_array(auth()->user()->role, ['admin'])) {
            abort(403);
        }

        $filePath = $this->backupPath . '/' . $fileName;
        
        if (File::exists($filePath)) {
            return response()->download($filePath);
        }

        return redirect()->route('backup.index')
            ->with('error', 'File tidak ditemukan');
    }

    public function delete($fileName)
    {
        if (!in_array(auth()->user()->role, ['admin'])) {
            abort(403);
        }

        $filePath = $this->backupPath . '/' . $fileName;
        
        if (File::exists($filePath)) {
            File::delete($filePath);
            return redirect()->route('backup.index')
                ->with('success', 'Backup berhasil dihapus');
        }

        return redirect()->route('backup.index')
            ->with('error', 'File tidak ditemukan');
    }
    
    public function restore(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin'])) {
            abort(403);
        }
        
        $request->validate([
            'backup_file' => 'required|string'
        ]);
        
        try {
            $filePath = $this->backupPath . '/' . $request->backup_file;
            
            if (!File::exists($filePath)) {
                return redirect()->route('backup.index')
                    ->with('error', 'File backup tidak ditemukan');
            }
            
            // Extract jika gz
            if (pathinfo($filePath, PATHINFO_EXTENSION) === 'gz') {
                $sqlFile = str_replace('.gz', '', $filePath);
                $this->extractGzip($filePath, $sqlFile);
                $filePath = $sqlFile;
            }
            
            // Restore database
            $sql = File::get($filePath);
            DB::unprepared($sql);
            
            // Hapus file temporary
            if (isset($sqlFile) && File::exists($sqlFile)) {
                File::delete($sqlFile);
            }
            
            return redirect()->route('backup.index')
                ->with('success', 'Database berhasil direstore!');
        } catch (\Exception $e) {
            return redirect()->route('backup.index')
                ->with('error', 'Restore gagal: ' . $e->getMessage());
        }
    }

    private function createBackup($type = 'manual')
    {
        if (!File::exists($this->backupPath)) {
            File::makeDirectory($this->backupPath, 0755, true);
        }
        
        $date = date('Y-m-d_His');
        $fileName = "backup_{$type}_{$date}.sql";
        $filePath = $this->backupPath . '/' . $fileName;
        
        $tables = DB::select('SHOW TABLES');
        $sql = "-- MySQL Backup\n";
        $sql .= "-- Date: " . date('Y-m-d H:i:s') . "\n";
        $sql .= "-- Type: {$type}\n\n";
        
        foreach ($tables as $table) {
            $tableName = array_values((array)$table)[0];
            
            $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
            
            $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
            $sql .= $createTable[0]->{'Create Table'} . ";\n\n";
            
            $rows = DB::table($tableName)->get();
            if ($rows->count() > 0) {
                foreach ($rows as $row) {
                    $values = array_map(function($value) {
                        return is_null($value) ? 'NULL' : "'" . addslashes($value) . "'";
                    }, (array)$row);
                    
                    $sql .= "INSERT INTO `{$tableName}` VALUES (" . implode(', ', $values) . ");\n";
                }
                $sql .= "\n";
            }
        }
        
        File::put($filePath, $sql);
        
        // Compress
        $this->compressGzip($filePath, $filePath . '.gz');
        File::delete($filePath);
        
        return $fileName . '.gz';
    }
    
    private function compressGzip($source, $destination)
    {
        $fp = gzopen($destination, 'w9');
        gzwrite($fp, File::get($source));
        gzclose($fp);
    }
    
    private function extractGzip($source, $destination)
    {
        $fp = gzopen($source, 'r');
        $content = '';
        while (!gzeof($fp)) {
            $content .= gzread($fp, 4096);
        }
        gzclose($fp);
        File::put($destination, $content);
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
