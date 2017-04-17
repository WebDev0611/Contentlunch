<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\JcTable;

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

        $section->addText($content->title, 'mainTitleStyle', ['alignment' => Jc::CENTER]);
        $section->addTextBreak(2);
        $textrun = $section->addTextRun();
        $textrun->addText('Content Type: ');
        $textrun->addText($content->contentType->name, ['bold' => true]);
        $textrun->addTextBreak(1);
        $textrun->addText('Due Date: ');
        $textrun->addText($content->due_date, ['bold' => true]);
        $textrun->addTextBreak(1);
        Html::addHtml($section, html_entity_decode($content->body));
        $section->addTextBreak(1);

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

        $section->addFooter()->addText('Generated on ' . date('m-d-Y') . ' at ' . date('h:i A') . ' with ContentLaunch', null, ['alignment' => Jc::RIGHT]);

        $pathToFile = self::exportPath() . ($documentName == null ? 'document_' . time() : $documentName) . '.docx';

        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($pathToFile);

        return $pathToFile;
    }
}
