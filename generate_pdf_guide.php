<?php
require_once 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Read the markdown content
$markdownContent = file_get_contents('PANDUAN_PENGGUNA_LENGKAP.md');

// Convert markdown to HTML (simple conversion)
$html = convertMarkdownToHtml($markdownContent);

// Create PDF
$options = new Options();
$options->set('defaultFont', 'Arial');
$options->set('isRemoteEnabled', true);
$options->set('isHtml5ParserEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Save PDF
$filename = 'PANDUAN_PENGGUNA_LENGKAP_SIKAP.pdf';
file_put_contents($filename, $dompdf->output());

echo "✅ PDF berhasil dibuat: " . $filename . "\n";
echo "📁 Lokasi: " . realpath($filename) . "\n";
echo "📄 Format: PDF\n";
echo "📊 Ukuran: " . round(filesize($filename) / 1024, 2) . " KB\n";

function convertMarkdownToHtml($markdown) {
    $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Panduan Pengguna Lengkap - SIKAP</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            line-height: 1.6; 
            margin: 20px;
            color: #333;
        }
        h1 { 
            color: #2E74B5; 
            border-bottom: 3px solid #2E74B5; 
            padding-bottom: 10px;
            font-size: 24px;
        }
        h2 { 
            color: #1F4E79; 
            border-bottom: 2px solid #1F4E79; 
            padding-bottom: 5px;
            font-size: 20px;
            margin-top: 30px;
        }
        h3 { 
            color: #365F91; 
            font-size: 16px;
            margin-top: 25px;
        }
        h4 { 
            color: #4472C4; 
            font-size: 14px;
        }
        table { 
            border-collapse: collapse; 
            width: 100%; 
            margin: 15px 0;
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 8px; 
            text-align: left;
        }
        th { 
            background-color: #f2f2f2; 
            font-weight: bold;
        }
        code { 
            background-color: #f4f4f4; 
            padding: 2px 4px; 
            border-radius: 3px;
            font-family: Courier New, monospace;
        }
        pre { 
            background-color: #f4f4f4; 
            padding: 10px; 
            border-radius: 5px;
            overflow-x: auto;
        }
        ul, ol { 
            margin: 10px 0; 
            padding-left: 30px;
        }
        li { 
            margin: 5px 0; 
        }
        .warning { 
            background-color: #fff3cd; 
            border: 1px solid #ffeaa7; 
            padding: 10px; 
            border-radius: 5px;
            margin: 10px 0;
        }
        .success { 
            background-color: #d4edda; 
            border: 1px solid #c3e6cb; 
            padding: 10px; 
            border-radius: 5px;
            margin: 10px 0;
        }
        .page-break { 
            page-break-before: always; 
        }
    </style>
</head>
<body>';

    // Convert markdown elements to HTML
    $markdown = preg_replace('/^# (.+)$/m', '<h1>$1</h1>', $markdown);
    $markdown = preg_replace('/^## (.+)$/m', '<h2>$1</h2>', $markdown);
    $markdown = preg_replace('/^### (.+)$/m', '<h3>$1</h3>', $markdown);
    $markdown = preg_replace('/^#### (.+)$/m', '<h4>$1</h4>', $markdown);
    
    // Convert bold text
    $markdown = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $markdown);
    
    // Convert italic text
    $markdown = preg_replace('/\*(.+?)\*/', '<em>$1</em>', $markdown);
    
    // Convert code blocks
    $markdown = preg_replace('/```(.+?)```/s', '<pre><code>$1</code></pre>', $markdown);
    
    // Convert inline code
    $markdown = preg_replace('/`(.+?)`/', '<code>$1</code>', $markdown);
    
    // Convert lists
    $markdown = preg_replace('/^- (.+)$/m', '<li>$1</li>', $markdown);
    $markdown = preg_replace('/^(\d+)\. (.+)$/m', '<li>$2</li>', $markdown);
    
    // Wrap consecutive list items in ul tags
    $markdown = preg_replace('/(<li>.*<\/li>)/s', '<ul>$1</ul>', $markdown);
    
    // Convert line breaks
    $markdown = preg_replace('/\n\n/', '</p><p>', $markdown);
    $markdown = '<p>' . $markdown . '</p>';
    
    // Convert tables (simple conversion)
    $lines = explode("\n", $markdown);
    $inTable = false;
    $tableHtml = '';
    $newMarkdown = '';
    
    foreach ($lines as $line) {
        if (strpos($line, '|') !== false && strpos($line, '---') === false) {
            if (!$inTable) {
                $tableHtml = '<table>';
                $inTable = true;
            }
            $cells = explode('|', trim($line, '|'));
            $tableHtml .= '<tr>';
            foreach ($cells as $cell) {
                $tableHtml .= '<td>' . trim($cell) . '</td>';
            }
            $tableHtml .= '</tr>';
        } else {
            if ($inTable) {
                $tableHtml .= '</table>';
                $newMarkdown .= $tableHtml;
                $tableHtml = '';
                $inTable = false;
            }
            if (strpos($line, '---') === false) {
                $newMarkdown .= $line . "\n";
            }
        }
    }
    
    if ($inTable) {
        $tableHtml .= '</table>';
        $newMarkdown .= $tableHtml;
    }
    
    $markdown = $newMarkdown;
    
    // Add page breaks for major sections
    $markdown = preg_replace('/(<h2>)/', '<div class="page-break"></div>$1', $markdown);
    
    // Clean up extra p tags
    $markdown = preg_replace('/<p><\/p>/', '', $markdown);
    $markdown = preg_replace('/<p>(<h[1-4]>)/', '$1', $markdown);
    $markdown = preg_replace('/(<\/h[1-4]>)<\/p>/', '$1', $markdown);
    $markdown = preg_replace('/<p>(<table>)/', '$1', $markdown);
    $markdown = preg_replace('/(<\/table>)<\/p>/', '$1', $markdown);
    $markdown = preg_replace('/<p>(<ul>)/', '$1', $markdown);
    $markdown = preg_replace('/(<\/ul>)<\/p>/', '$1', $markdown);
    
    $html .= $markdown;
    $html .= '</body></html>';
    
    return $html;
}
?>