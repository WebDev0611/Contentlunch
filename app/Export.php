<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Html;

class Export extends Model {

    public static function exportPath ()
    {
        return public_path() . '/tmp/';
    }

    private static function addStylesToWordDocument ($phpWord)
    {
        $phpWord->addTitleStyle(1, ['size' => 20, 'color' => '333333', 'bold' => true]);
        $phpWord->addTitleStyle(2, ['size' => 16, 'color' => '666666']);
        $phpWord->addTitleStyle(3, ['size' => 14, 'italic' => true]);
        $phpWord->addTitleStyle(4, ['size' => 12]);

        return $phpWord;
    }

    public static function htmlToWordDocument ($html, $documentName = null)
    {
        $phpWord = self::addStylesToWordDocument(new PhpWord());

        $section = $phpWord->addSection();

        Html::addHtml($section, $html);
        $pathToFile = self::exportPath() . ($documentName == null ? 'document_' . time() : $documentName) . '.docx';

        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($pathToFile);

        return $pathToFile;
    }
}
