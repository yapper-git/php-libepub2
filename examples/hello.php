<?php

require '../Epub.class.php';

$epub = new Epub('hello.epub');

$epub->setIdentifier('helloworld');
$epub->setTitle('Hello world!');

$epub->metadata()->addIdentifier('helloworld');
$epub->metadata()->addTitle('Hello world!');
$epub->metadata()->addLanguage('en');

$epub->addTextFromFile('texts/hello.xhtml', 'files/hello.xhtml', 'hello');

$epub->toc()->append(new EpubNavPoint(array(
    'id'     => 'hello',
    'label'  => 'Hello',
    'source' => 'texts/hello.xhtml'
)));

$epub->valid();
$epub->save();
