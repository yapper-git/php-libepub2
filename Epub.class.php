<?php

require_once __DIR__.'/EpubMetadata.class.php';
require_once __DIR__.'/EpubManifest.class.php';
require_once __DIR__.'/EpubSpine.class.php';
require_once __DIR__.'/EpubGuide.class.php';
require_once __DIR__.'/EpubToc.class.php';

class Epub
{
    const XML_ENCODING  = 'utf-8';

    protected $tab;
    protected $endLine;
    protected $zip;
    protected $metadata;
    protected $manifest;
    protected $spine;
    protected $guide;
    protected $toc;
    protected $identifier;
    protected $title;
    protected $lang;

    public function __construct($filename)
    {
        if (!isset($filename)) {
            throw new \RuntimeException('Epub: You need to specify a filename');
        }

        if (is_dir($filename)) {
            throw new \RuntimeException('\''.$filename.'\' is a directory');
        }

        if (is_file($filename) and !@unlink($filename)) {
            throw new \RuntimeException('Unable to remove the file \''.$filename.'\'');
        }

        // Initializes variables
        $this->tab      = "    ";
        $this->endLine  = "\n";
        $this->zip      = new \ZipArchive;
        $this->metadata = new EpubMetadata;
        $this->manifest = new EpubManifest;
        $this->spine    = new EpubSpine;
        $this->guide    = new EpubGuide;
        $this->toc      = new EpubToc;

        // Prepares the epub file
        $this->init($filename);
    }

    protected function init($filename)
    {
        // Creates a ZIP Archive containing a file mimetype at the right place
        file_put_contents($filename, base64_decode(
            'UEsDBAoAAAAAAOmRAT1vYassFAAAABQAAAAIAAAAbWltZXR5cGVhcHBsaWNhd'.
            'Glvbi9lcHViK3ppcFBLAQIUAAoAAAAAAOmRAT1vYassFAAAABQAAAAIAAAAAA'.
            'AAAAAAIAAAAAAAAABtaW1ldHlwZVBLBQYAAAAAAQABADYAAAA6AAAAAAA='
        ));

        // Opens the ZIP Archive
        if (!$this->zip->open($filename)) {
            throw new \Exception(
                'Error loading the archive containing the mimetype file'
            );
        }

        // Creates directory OEBPS
        /*if (!$this->zip->addEmptyDir('OEBPS')) {
            throw new \Exception(
                'Unable to create an empty directory called OEBPS'
            );
        }*/

        // Create directory META-INF
        /*if (!$this->zip->addEmptyDir('META-INF')) {
            throw new \Exception(
                'Unable to create an empty directory called META-INF'
            );
        }*/

        // Creates the file OEBPS/container.xml
        $this->zip->addFromString(
            'META-INF/container.xml',
            '<?xml version="1.0" encoding="'.self::XML_ENCODING.'"?>'.$this->endLine.
            '<container version="1.0" xmlns="urn:oasis:names:tc:opendocument:xmlns:container">'.$this->endLine.
            $this->tab.'<rootfiles>'.$this->endLine.
            $this->tab.$this->tab.'<rootfile full-path="OEBPS/content.opf" media-type="application/oebps-package+xml"/>'.$this->endLine.
            $this->tab.'</rootfiles>'.$this->endLine.
            '</container>'
        );

        // Adds manifestItem for the file toc.ncx
        $manifestItem = new EpubManifestItem;
        $manifestItem->setId('ncx');
        $manifestItem->setHref('toc.ncx');
        $manifestItem->setMediaType('application/x-dtbncx+xml');
        $this->manifest->append($manifestItem);

        $this->spine->setToc('toc.ncx');
    }

    public function metadata()
    {
        return $this->metadata;
    }

    public function manifest()
    {
        return $this->manifest;
    }

    public function spine()
    {
        return $this->spine;
    }

    public function guide()
    {
        return $this->guide;
    }

    public function toc()
    {
        return $this->toc;
    }

    public function setIdentifier($identifier)
    {
        if (!is_string($identifier) or empty($identifier)) {
            throw new \InvalidArgumentException(
                'Epub: The identifier attribute must be a valid string'
            );
        }

        $this->identifier = $identifier;
    }

    public function identifier()
    {
        return $this->identifier;
    }

    public function setTitle($title)
    {
        if (!is_string($title) or empty($title)) {
            throw new \InvalidArgumentException(
                'Epub: The title attribute must be a valid string'
            );
        }

        $this->title = $title;
    }

    public function title()
    {
        return $this->title;
    }

    public function setLang($lang)
    {
        if (!is_string($lang) or empty($lang)) {
            throw new \InvalidArgumentException(
                'Epub: The lang attribute must be a valid string'
            );
        }

        $this->lang = $lang;
    }

    public function lang()
    {
        return $this->lang();
    }

    public function setTab($tab)
    {
        $this->tab = (string) $tab;
    }

    public function tab()
    {
        return $this->tab;
    }

    public function setEndLine($endLine)
    {
        $this->endLine = (string) $endLine;
    }

    public function endLine()
    {
        return $this->endLine;
    }

    public function addFileFromFile($localname, $filename, $id, $mediaType)
    {
        // Verify that the file exists (else it will generated without
        // errors/warnings an empty archive
        if (!is_file($filename)) {
            throw new \Exception('Unable to find file called \''.$filename.'\'');
        }

        // Add the file to the archive
        if (!$this->zip->addFile($filename, 'OEBPS/'.$localname)) {
            throw new \Exception(
                'Unable to add \''.$localname.'\' to the archive, from the file \''.$filename.'\''
            );
        }

        // Add the file in the manifest
        $manifestItem = new EpubManifestItem;
        $manifestItem->setId($id);
        $manifestItem->setHref($localname);
        $manifestItem->setMediaType($mediaType);
        $this->manifest->append($manifestItem);
    }

    public function addFileFromString($localname, $content, $id, $mediaType)
    {
        // Add a file with such contents to the archive
        if (!$this->zip->addFromString('OEBPS/'.$localname, $content)) {
            throw new \Exception(
                'Unable to add \''.$localname.'\' to the archive, from string'
            );
        }

        // Add the file in the manifest
        $manifestItem = new EpubManifestItem;
        $manifestItem->setId($id);
        $manifestItem->setHref($localname);
        $manifestItem->setMediaType($mediaType);
        $this->manifest->append($manifestItem);
    }

    public function addStyleFromFile($localname, $filename, $id)
    {
        $this->addFileFromFile($localname, $filename, $id, 'text/css');
    }

    public function addStyleFromString($localname, $content, $id)
    {
        $this->addFileFromString($localname, $content, $id, 'text/css');
    }

    public function addTextFromFile($localname, $filename, $id)
    {
        $this->addFileFromFile($localname, $filename, $id, 'application/xhtml+xml');
        $this->spine->append($id);
    }

    public function addTextFromString($localname, $content, $id)
    {
        $this->addFileFromString($localname, $content, $id, 'application/xhtml+xml');
        $this->spine->append($id);
    }

    public function valid()
    {
        if (!isset($this->title)) {
            throw new \Exception('Epub: You must set a title');
        }

        if (!isset($this->identifier)) {
            throw new \Exception('Epub: You must set an identifier');
        }

        $this->metadata->valid();

        $this->manifest->valid();

        $this->spine->valid();

        $this->guide->valid();

        $this->toc->valid();
    }

    public function save()
    {
        $this->valid();

        // Generates the toc.ncx content and add the file to the archive
        if (!$this->zip->addFromString('OEBPS/toc.ncx', $this->renderTocFile())) {
            throw new \Exception('Unable to add OEBPS/toc.ncx to the archive');
        }

        // Generates the content.opf content and add the file to the archive
        if (!$this->zip->addFromString('OEBPS/content.opf', $this->renderOpenPackagingFormatFile())) {
            throw new \Exception('Unable to add OEBPS/content.opf to the archive');
        }

        // Save
        $this->zip->close();
    }

    protected function renderOpenPackagingFormatFile()
    {
        // XML declaration
        $buffer = '<?xml version="1.0" encoding="'.self::XML_ENCODING.'"?>'.$this->endLine;

        // <package>
        $buffer .= '<package xmlns="http://www.idpf.org/2007/opf" version="2.0" unique-identifier="BookId">'.$this->endLine;

        // <metadata>...</metadata>
        $buffer .= $this->renderMetadataTag(1);

        // <manifest>...</manifest>
        $buffer .= $this->renderManifestTag(1);

        // <spine>...</spine>
        $buffer .= $this->renderSpineTag(1);

        // <guide>...</guide>
        $buffer .= $this->renderGuideTag(1);

        // </package>
        $buffer .= '</package>';

        return $buffer;
    }

    protected function renderMetadataTag($level)
    {
        $start = str_repeat($this->tab, $level+1);
        $end   = $this->endLine;;

        // <metadata>
        $buffer = str_repeat($this->tab, $level).'<metadata xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:opf="http://www.idpf.org/2007/opf">'.$this->endLine;

        // titles (at least one)
        $titles = $this->metadata->titles();
        foreach ($titles as $title) {
            $buffer .= $start.'<dc:title>'.$title.'</dc:title>'.$end;
        }

        // creators
        $creators = $this->metadata->creators();
        if (!empty($creators)) {
            foreach ($creators as $creator) {
                $buffer .= $start.'<dc:creator';

                if (!empty($creator['role'])) {
                    $buffer .= ' opf:role="'.$creator['role'].'"';
                }

                if (!empty($creator['file-as'])) {
                    $buffer .= ' opf:file-as="'.$creator['file-as'].'"';
                }

                $buffer .= '>'.$creator['name'].'</dc:creator>'.$end;
            }
        }

        // subjects
        $subjects = $this->metadata->subjects();
        if (!empty($subjects)) {
            foreach ($subjects as $subject) {
                $buffer .= $start.'<dc:subject>'.$subject.'</dc:subject>'.$end;
            }
        }

        // description
        $description = $this->metadata->description();
        if (!empty($description)) {
            $buffer .= $start.'<dc:description>'.$description.'</dc:description>'.$end;
        }

        // publisher
        $publisher = $this->metadata->publisher();
        if (!empty($publisher)) {
            $buffer .= $start.'<dc:publisher>'.$publisher.'</dc:publisher>'.$end;
        }

        // contributors
        $contributors = $this->metadata->contributors();
        if (!empty($contributors)) {
            foreach ($contributors as $contributor) {
                $buffer .= $start.'<dc:contributor';

                if (!empty($contributor['role'])) {
                    $buffer .= ' opf:role="'.$contributor['role'].'"';
                }

                if (!empty($contributor['file-as'])) {
                    $buffer .= ' opf:file-as="'.$contributor['file-as'].'"';
                }

                $buffer .= '>'.$contributor['name'].'</dc:contributor>'.$end;
            }
        }

        // dates
        $dates = $this->metadata->dates();
        if (!empty($dates)) {
            foreach ($dates as $date) {
                $buffer .= $start.'<dc:date';

                if (!empty($date['event'])) {
                    $buffer .= ' opf:event="'.$date['event'].'"';
                }

                $buffer .= '>'.$date['date'].'</dc:date>'.$end;
            }
        }

        // type
        $type = $this->metadata->type();
        if (!empty($type)) {
            $buffer .= $start.'<dc:type>'.$type.'</dc:type>'.$end;
        }

        // format
        $format = $this->metadata->format();
        if (!empty($format)) {
            $buffer .= $start.'<dc:format>'.$format.'</dc:format>'.$end;
        }

        // identifiers (at least one)
        $identifiers = $this->metadata->identifiers();
        foreach ($identifiers as $identifier) {
            $buffer .= $start.'<dc:identifier id="'.$identifier['id'].'"';

            if (!empty($identifier['scheme'])) {
                $buffer .= ' opf:scheme="'.$identifier['scheme'].'"';
            }

            $buffer .= '>'.$identifier['content'].'</dc:identifier>'.$end;
        }

        // source
        $source = $this->metadata->source();
        if (!empty($source)) {
            $buffer .= $start.'<dc:source>'.$source.'</dc:source>'.$end;
        }

        // languages (at least one)
        $languages = $this->metadata->languages();
        foreach ($languages as $lang) {
            $buffer .= $start.'<dc:language>'.$lang.'</dc:language>'.$end;
        }

        // relation
        $relation = $this->metadata->relation();
        if (!empty($relation)) {
            $buffer .= $start.'<dc:relation>'.$relation.'</dc:relation>'.$end;
        }

        // coverage
        $coverage = $this->metadata->coverage();
        if (!empty($coverage)) {
            $buffer .= $start.'<dc:coverage>'.$coverage.'</dc:coverage>'.$end;
        }

        // rights
        $rights = $this->metadata->rights();
        if (!empty($rights)) {
            $buffer .= $start.'<dc:rights>'.$rights.'</dc:rights>'.$end;
        }

        // metas
        $metas = $this->metadata->metas();
        if (!empty($metas)) {
            foreach ($metas as $meta) {
                $buffer .= $start.'<meta name="'.$meta['name'].'" content="'.$meta['content'].'"/>'.$end;
            }
        }

        // </metadata>
        $buffer .= str_repeat($this->tab, $level).'</metadata>'.$this->endLine;

        return $buffer;
    }

    protected function renderManifestTag($level)
    {
        // <manifest>
        $buffer = str_repeat($this->tab, $level).'<manifest>'.$this->endLine;

        // <item ... />
        $items = $this->manifest->items();
        foreach ($items as $item) {
            $buffer .= str_repeat($this->tab, $level+1).'<item'
                    .' id="'.$item->id().'"'
                    .' href="'.$item->href().'"'
                    .' media-type="'.$item->mediaType().'"'
                    .'/>'.$this->endLine;
        }

        // </manifest>
        $buffer .= str_repeat($this->tab, $level).'</manifest>'.$this->endLine;

        return $buffer;
    }

    protected function renderSpineTag($level)
    {
        // <spine>
        $buffer = str_repeat($this->tab, $level).'<spine toc="ncx">'.$this->endLine;

        // <itemref ... />
        $itemRefs = $this->spine->itemRefs();
        foreach ($itemRefs as $idRef) {
            $buffer .= str_repeat($this->tab, $level+1).'<itemref idref="'.$idRef.'"/>'.$this->endLine;
        }

        // </spine>
        $buffer .= str_repeat($this->tab, $level).'</spine>'.$this->endLine;

        return $buffer;
    }

    protected function renderGuideTag($level)
    {
        $references = $this->guide->references();

        if (empty($references)) {
            return '';
        }

        // <guide>
        $buffer = str_repeat($this->tab, $level).'<guide>'.$this->endLine;

        // <reference ... />
        foreach ($references as $reference) {
            $buffer .= str_repeat($this->tab, $level+1).'<reference'
                    .' type="'.$reference->type().'"'
                    .' title="'.$reference->title().'"'
                    .' href="'.$reference->href().'"'
                    .'/>'.$this->endLine;
        }

        // </guide>
        $buffer .= $this->tab.'</guide>'.$this->endLine;

        return $buffer;
    }

    /**
     * The values for the docTitle, docAuthor, and meta name="dtb:uid" elements
     * should match their analogs in the OPF file. (Source: Wikipédia)
     * But docAuthor is not metadata. "The metatdata components of ncx are just
     * vestigial elements of a previous standard that's been superseded and are
     * only allowed for reasons of backwards-compatibility. If you put any
     * metadata there it needs to be exactly the same as that specified in the
     * opf, so it's redundant." (Source: forum from mobileread.com)
     */
    protected function renderTocFile()
    {
        // XML declaration
        $buffer = '<?xml version="1.0" encoding="'.self::XML_ENCODING.'"?>'.$this->endLine;

        // DOCTYPE declaration
        $buffer .= '<!DOCTYPE ncx PUBLIC "-//NISO//DTD ncx 2005-1//EN" "http://www.daisy.org/z3986/2005/ncx-2005-1.dtd">'.$this->endLine;

        // <ncx>
        $buffer .= '<ncx version="2005-1"';
        if (isset($this->lang)) {
            $buffer .= ' xml:lang="'.$this->lang.'"';
        }
        $buffer .= ' xmlns="http://www.daisy.org/z3986/2005/ncx/">'.$this->endLine;

        // <head>...</head>
        $buffer .= $this->tab.'<head>'.$this->endLine;
        $buffer .= $this->tab.$this->tab.'<meta name="dtb:uid" content="'.$this->identifier.'"/>'.$this->endLine;// same as in .opf
        $buffer .= $this->tab.$this->tab.'<meta name="dtb:depth" content="'.$this->toc->depth().'"/>'.$this->endLine;// 1 or higher
        $buffer .= $this->tab.$this->tab.'<meta name="dtb:totalPageCount" content="0"/>'.$this->endLine;// must be 0
        $buffer .= $this->tab.$this->tab.'<meta name="dtb:maxPageNumber" content="0"/>'.$this->endLine;// must be 0
        $buffer .= $this->tab.'</head>'.$this->endLine;

        // <docTitle>...</docTitle> (required according to epubcheck)
        $buffer .= $this->tab.'<docTitle><text>'.$this->title.'</text></docTitle>'.$this->endLine;

        // <navMap>...</navMap>
        $buffer .= $this->tab.'<navMap>'.$this->endLine;
        $playOrder = 0;
        $navPoints = $this->toc->navPoints();
        foreach ($navPoints as $navPoint) {
            $playOrder++;
            $buffer .= $this->renderNavPoint($navPoint, $playOrder, 2);
        }
        $buffer .= $this->tab.'</navMap>'.$this->endLine;

        // </ncx>
        $buffer .= '</ncx>';

        return $buffer;
    }

    protected function renderNavPoint(EpubNavPoint $navPoint, &$playOrder, $level)
    {
        $buffer = '';

        // <navPoint>
        $buffer .= str_repeat($this->tab, $level).'<navPoint id="'.$navPoint->id().'" playOrder="'.$playOrder.'">'.$this->endLine;

        // <navLabel><text></text></navLabel>
        $buffer .= str_repeat($this->tab, $level+1).'<navLabel><text>'.$navPoint->label().'</text></navLabel>'.$this->endLine;

        // <content/>
        $buffer .= str_repeat($this->tab, $level+1).'<content src="'.$navPoint->source().'"/>'.$this->endLine;

        // <navPoint>...</navPoint> if children
        foreach ($navPoint->navPoints() as $subNavPoint) {
            $playOrder++;
            $buffer .= $this->renderNavPoint($subNavPoint, $playOrder, $level+1);
        }

        // </navPoint>
        $buffer .= str_repeat($this->tab, $level).'</navPoint>'.$this->endLine;

        return $buffer;
    }
}
