<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Html;

class Export extends Model
{
    private static function addStylesToWordDocument($phpWord){
        $phpWord->addTitleStyle(1, array('size' => 20, 'color' => '333333', 'bold' => true));
        $phpWord->addTitleStyle(2, array('size' => 16, 'color' => '666666'));
        $phpWord->addTitleStyle(3, array('size' => 14, 'italic' => true));
        $phpWord->addTitleStyle(4, array('size' => 12));

        return $phpWord;
    }

    public static function htmlToWordDocument ($html, $documentName = null)
    {
        $phpWord = self::addStylesToWordDocument(new PhpWord());

        $section = $phpWord->addSection();

        Html::addHtml($section, $html);

        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save(($documentName == null ? 'document_' . time() : $documentName) . '.docx');
    }
}
