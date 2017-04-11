<?php

$content = '<body><h2>This is a title</h2>
<p>Lorem ipsum lalalala Lorem ipsum lalalala Lorem ipsum lalalala Lorem ipsum lalalala Lorem ipsum lalalala Lorem ipsum lalalala Lorem ipsum lalalala Lorem ipsum lalalala Lorem ipsum lalalala Lorem ipsum lalalala Lorem ipsum lalalala Lorem ipsum lalalala Lorem ipsum lalalala Lorem ipsum lalalala Lorem ipsum lalalala Lorem ipsum lalalala</p>
<p>Lorem ipsum lalalala Lorem ipsum lalalala Lorem ipsum lalalala Lorem ipsum lalalala Lorem ipsum lalalala Lorem ipsum lalalala Lorem ipsum lalalala Lorem ipsum lalalala Lorem ipsum lalalala Lorem ipsum lalalala Lorem ipsum lalalala Lorem ipsum lalalala</p>
<p>&nbsp;</p>
<h4><em>Subtitle 1 italic bold</em></h4>
<p>&nbsp;</p>
<p style="text-align: center;"><span style="text-decoration: underline;">Subtitle 2 underline center</span></p>
<p><em>&nbsp;</em></p></body>';


// Creating the new document...
$phpWord = new \PhpOffice\PhpWord\PhpWord();

/* Note: any element you append to a document must reside inside of a Section. */
// Adding an empty Section to the document...
$section = $phpWord->addSection();

$section->addTitle('Welcome to PhpWord', 1);
\PhpOffice\PhpWord\Shared\Html::addHtml($section, $content);

$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('helloWorld.docx');

