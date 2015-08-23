<?php

require '../Epub.class.php';

$epub = new Epub('sample.epub');

$epub->setIdentifier('sample');
$epub->setTitle('Sample ePUB file');
$epub->setLang('fr');

$metadata = $epub->metadata();
$metadata->addTitle('Sample ePUB file');// repeat
$metadata->addTitle('Title N°2');
$metadata->addTitle('Title N°3');
$metadata->addCreator('Creator N°1');
$metadata->addCreator('Creator N°2', 'aut');
$metadata->addCreator('Creator N°3', 'oth', 'Creator 3');
$metadata->addSubject('Subject N°1');
$metadata->addSubject('Subject N°2');
$metadata->setDescription('Description');
$metadata->setPublisher('Publisher');
$metadata->addContributor('Contributor N°1');
$metadata->addContributor('Contributor N°2', 'aut');
$metadata->addContributor('Contributor N°3', 'oth', 'Contributor 3');
$metadata->addDate('2014');
$metadata->addDate('2014-12', 'modification');
$metadata->addDate('2014-12-25', 'publication');
$metadata->setType('type');
$metadata->setFormat('format');
$metadata->addIdentifier('sample');// repeat
$metadata->addIdentifier('uuid002');
$metadata->addIdentifier('uuid003', 'myid', 'scheme');
$metadata->setSource('source');
$metadata->addLanguage('en-US');
$metadata->addLanguage('fr-fr');
$metadata->addLanguage('de');
$metadata->setRelation('relation');
$metadata->setCoverage('coverage');
$metadata->setRights('rights');
$metadata->addMeta('name1', 'content1');
$metadata->addMeta('name2', 'content2');

$epub->guide()->append(new EpubGuideReference(array(
    'type'  => 'title-page',
    'title' => 'Title page',
    'href'  => 'texts/titlepage.xhtml'
)));
$epub->guide()->append(new EpubGuideReference(array(
    'type'  => 'toc',
    'title' => 'Table of Contents',
    'href'  => 'texts/contents.xhtml'
)));

$navPoint1 = new EpubNavPoint(array(
    'id'     => 'chap1',
    'label'  => 'Chapter 1',
    'source' => 'texts/chapter1.xhtml'
));
$navPoint2 = new EpubNavPoint(array(
    'id'     => 'chap2',
    'label'  => 'Chapter 2',
    'source' => 'texts/chapter2.xhtml'
));
$epub->toc()->append($navPoint1);
$epub->toc()->append($navPoint2);

$epub->addTextFromFile('texts/titlepage.xhtml', 'files/title_page.xhtml', 'titlepage');
$epub->addTextFromFile('texts/contents.xhtml', 'files/contents.xhtml', 'contents');
$epub->addTextFromFile('texts/chapter1.xhtml', 'files/chap1.xhtml', 'chap1');
$epub->addTextFromFile('texts/chapter2.xhtml', 'files/chap2.xhtml', 'chap2');
$epub->addStyleFromFile('styles/stylesheet.css', 'files/stylesheet.css', 'style');
$epub->addFileFromFile('images/logo.png', 'files/logo.png', 'logo', 'image/png');

try {
    $epub->valid();
} catch (Exception $e) {
    die($e->getMessage()."\n");
}

$epub->save();
