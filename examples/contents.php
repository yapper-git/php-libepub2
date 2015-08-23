<?php

require '../Epub.class.php';

$epub = new Epub('contents.epub');

$epub->setIdentifier('contents');
$epub->setTitle('Deep Table of Contents');

$epub->metadata()->addIdentifier('contents');
$epub->metadata()->addTitle('Deep Table of Contents');
$epub->metadata()->addLanguage('en');

$navPoint1 = new EpubNavPoint(array(
    'id'     => 'toc.1',
    'label'  => '1. Chapter 1',
    'source' => 'text.html#toc.1'
));
$navPoint2 = new EpubNavPoint(array(
    'id'     => 'toc.2',
    'label'  => '2. Chapter 2',
    'source' => 'text.html#toc.2'
));
$navPoint3 = new EpubNavPoint(array(
    'id'     => 'toc.3',
    'label'  => '3. Chapter 3',
    'source' => 'text.html#toc.3'
));
$epub->toc()->append($navPoint1);
$epub->toc()->append($navPoint2);
$epub->toc()->append($navPoint3);

$navPoint1_1 = new EpubNavPoint(array(
    'id'     => 'toc.1.1',
    'label'  => '1.1. Section 1',
    'source' => 'text.html#toc.1.1'
));
$navPoint1_2 = new EpubNavPoint(array(
    'id'     => 'toc.1.2',
    'label'  => '1.2. Section 2',
    'source' => 'text.html#toc.1.2'
));
$navPoint1->append($navPoint1_1);
$navPoint1->append($navPoint1_2);

$navPoint2_1 = new EpubNavPoint([
    'id'        => 'toc.2.1',
    'label'     => '2.1. Section 1',
    'source'    => 'text.html#toc.2.1',
    'navPoints' => [
        new EpubNavPoint([
            'id'        => 'toc.2.1.1',
            'label'     => '2.1.1. Subsection 1',
            'source'    => 'text.html#toc.2.1.1'
        ]),
        new EpubNavPoint([
            'id'        => 'toc.2.1.2',
            'label'     => '2.1.2. Subsection 2',
            'source'    => 'text.html#toc.2.1.2'
        ]),
        new EpubNavPoint([
            'id'        => 'toc.2.1.3',
            'label'     => '2.1.3. Subsection 3',
            'source'    => 'text.html#toc.2.1.3'
        ])
    ]
]);
$navPoint2_2 = new EpubNavPoint([
    'id'     => 'toc.2.2',
    'label'  => '2.2. Section 2',
    'source' => 'text.html#toc.2.2'
]);
$navPoint2->append($navPoint2_1);
$navPoint2->append($navPoint2_2);

$text = <<<EOF
<?xml version="1.0" encoding="UTF-8" ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
  <head>
    <meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8"/>
    <title>Deep Table of Contents</title>
  </head>
  <body>
    <h1 id="toc.1">1. Chapter 1</h1>
    <h2 id="toc.1.1">1.1. Section 1</h2>
    <h2 id="toc.1.2">1.2. Section 2</h2>
    <h1 id="toc.2">2. Chapter 2</h1>
    <h2 id="toc.2.1">2.1. Section 1</h2>
    <h3 id="toc.2.1.1">2.1.1. Subsection 1</h3>
    <h3 id="toc.2.1.2">2.1.2. Subsection 2</h3>
    <h3 id="toc.2.1.3">2.1.3. Subsection 3</h3>
    <h2 id="toc.2.2">2.2. Section 2</h2>
    <h1 id="toc.3">3. Chapter 3</h1>
  </body>
</html>
EOF;

$epub->addTextFromString('text.html', $text, 'text');

$epub->valid();
$epub->save();
