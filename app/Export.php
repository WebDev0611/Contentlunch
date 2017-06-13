<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\SimpleType\Jc;

class Export extends Model {

    public static function exportPath ()
    {
        return public_path() . '/tmp/';
    }

    private static function addStylesToWordDocument ($phpWord)
    {
        $phpWord->addFontStyle('mainTitleStyle', array('alignment' => Jc::CENTER, 'size' => 16, 'bold' => true));
        $phpWord->addParagraphStyle('pStyle', array('spaceAfter' => 100));
        $phpWord->addTitleStyle(1, ['size' => 20, 'color' => '333333', 'bold' => true]);
        $phpWord->addTitleStyle(2, ['size' => 16, 'color' => '666666']);
        $phpWord->addTitleStyle(3, ['size' => 14, 'italic' => true]);
        $phpWord->addTitleStyle(4, ['size' => 12]);
        $phpWord->addTableStyle('tableStyle', ['borderSize' => 6, 'borderColor' => '999999', 'cellMargin' => 30]);

        return $phpWord;
    }

    public static function contentToWordDocument ($content, $documentName = null)
    {
        $phpWord = self::addStylesToWordDocument(new PhpWord());
        $section = $phpWord->addSection();

        // Content title
        $section->addText($content->title, 'mainTitleStyle', ['alignment' => Jc::CENTER]);
        $section->addTextBreak(2);

        // Type and due date
        $textrun = $section->addTextRun();
        $textrun->addText('Content Type: ');
        $textrun->addText($content->contentType->name, ['bold' => true]);
        $textrun->addTextBreak(1);
        $textrun->addText('Due Date: ');
        $textrun->addText(date('m-d-Y', strtotime($content->due_date)), ['bold' => true]);
        $textrun->addTextBreak(1);

        // Body (html)
        Html::addHtml($section, html_entity_decode($content->body));
        $section->addTextBreak(1);

        // Table
        $table = $section->addTable('tableStyle');
        $table->addRow();
        $table->addCell(2000)->addText('Tags');
        $table->addCell(6000)->addText(implode(", ", $content->tags->pluck('tag')->toArray()), ['bold' => true]);
        $table->addRow();
        $table->addCell(2000)->addText('Related Content');
        $table->addCell(6000)->addText(implode(", ", $content->related->pluck('title')->toArray()), ['bold' => true]);
        $table->addRow();
        $table->addCell(2000)->addText('Buying Stage');
        $table->addCell(6000)->addText(isset($content->buying_stage) ? $content->buying_stage->name : '', ['bold' => true]);
        $table->addRow();
        $table->addCell(2000)->addText('Persona');
        $table->addCell(6000)->addText(isset($content->persona->name) ? $content->persona->name : '', ['bold' => true]);
        $table->addRow();
        $table->addCell(2000)->addText('Meta Title');
        $table->addCell(6000)->addText($content->meta_title, ['bold' => true]);
        $table->addRow();
        $table->addCell(2000)->addText('Meta Description');
        $table->addCell(6000)->addText($content->meta_description, ['bold' => true]);
        $table->addRow();
        $table->addCell(2000)->addText('Keywords');
        $table->addCell(6000)->addText($content->meta_keywords, ['bold' => true]);

        // Footer
        $section->addFooter()->addText('Generated on ' . date('m-d-Y') . ' at ' . date('h:i A') . ' with ContentLaunch', null, ['alignment' => Jc::RIGHT]);

        // Save it
        $pathToFile = self::exportPath() . ($documentName == null ? 'document_' . time() . '_' . str_random(16) : $documentName) . '.docx';
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($pathToFile);

        return $pathToFile;
    }

    public static function contentToPDFDocument ($content, $documentName = null)
    {
        Settings::setPdfRendererPath('../vendor/mpdf/mpdf');
        Settings::setPdfRendererName('MPDF');

        // Let's create docx document first
        $docx = self::contentToWordDocument($content);
        $temp = IOFactory::load($docx);

        $pathToFile = self::exportPath() . ($documentName == null ? 'document_' . time() . '_' . str_random(16) : $documentName) . '.pdf';

        // Save PDF file
        $xmlWriter = IOFactory::createWriter($temp , 'PDF');
        $xmlWriter->save($pathToFile, TRUE);

        // Delete temporary docx file
        File::delete($docx);

        return $pathToFile;
    }

    public static function orderToWordDocument ($order, $documentName = null)
    {
        $phpWord = self::addStylesToWordDocument(new PhpWord());
        $section = $phpWord->addSection();

        // Content title
        $section->addText($order->preview_title, 'mainTitleStyle', ['alignment' => Jc::CENTER]);
        $section->addTextBreak(2);

        // Body (html)
        Html::addHtml($section, html_entity_decode($order->preview_text));
        $section->addTextBreak(1);

        // Footer
        $section->addFooter()->addText('Generated on ' . date('m-d-Y') . ' at ' . date('h:i A') . ' with ContentLaunch', null, ['alignment' => Jc::RIGHT]);

        // Save it
        $pathToFile = self::exportPath() . ($documentName == null ? 'document_' . time() . '_' . str_random(16) : $documentName) . '.docx';
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($pathToFile);

        return $pathToFile;
    }

}
