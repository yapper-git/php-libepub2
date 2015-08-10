EPUB_FILES   = $(patsubst %.php,%.epub,$(wildcard *.php))
EPUB_FOLDERS = $(patsubst %.php,%.epub-FILES,$(wildcard *.php))

all: $(EPUB_FILES) $(EPUB_FOLDERS)

%.epub: %.php
	php $<
	-epubcheck $@

%.epub-FILES: %.epub
	rm -rf $@
	unzip -o $< -d $@

clean:
	rm -rf *.epub-FILES

mrproper: clean
	rm -f *.epub

.PHONY: clean mrproper
