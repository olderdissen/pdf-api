# pdf-api

updated project to create **pdf-file** like **PDFlib** but without restrictions.
this project follows a different strategy of collecting and storing data.
all data is stored inside an array for easy access.
each element is accessible, changeable and removable at any time.

supported filters
* /ASCIIHexDecode (processed by **bin2hex** and **hex2bin**)
* /ASCII85Decode
* /FlateDecode (processed by **gzcompress** and **gzuncompress**)
* /LZWDecode

supported image formats
* GIF (processed by **imagecreatefromgif** and **imagepng** to PNG)
* JPG (processed by **file_get_contents**)
* PNG (processed by **fclose**, **feof**, **fopen**, **fread**)
* others (processed by **convert** to JPG)

supported fonts
* /Type1 (14 core fonts)
* /TrueType (/Type2 in theory)
* /Type3 (experimental, self defined)

## feature ##

the internal list of loaded resources is stored the same way like /Resources of a single page.
whenever a resource is loaded, it is applied to the internal list of loaded resources.
whenver a resource is used by a page, it is applied to /Resources of the page.
this makes it possible to let users have real-time access to all key, values and elements at any time.
this prevent multiple loadings of identical fonts.

## example ##

see **pdf-api-test.php** for a working example.
more examples can be found in manual-pages of **PDFlib**.
keep in mind that some functions may work different than in the original **PDFlib**.

## compatibility ##

this project can be used to replace **PDFlib** since basic functionality is implemented.

## recommendation ##

you should read **pdf-reference** carfully and learn how to write **pdf-files** by hand, to understand the concept of **pdf-format**.
understanding the concept of **pdf-format** makes it much easier to create **pdf-files** in general.
the concept of drawing lines in **pdf-files** is similar to drawing lines in **svg-files**.

## requirements ##

there are no special requirements to run this script.
php's builtin functions **gzcompress** and **gzuncompress** are used for compression.
it could be useful to have **convert** installed, which is part of image magik, for additional image support.
the following functions and operators and kewords are used inside this script:

* array, array_reverse, array_slice, as
* basename, break
* case, chr, continue, cos, count
* define, die, do
* else, elseif, explode
* fclose, feof, filesize, file_get_contents, file_put_contents, fopen, for, foreach, fread, function
* getimagesize, **gzcompress**, **gzuncompress**
* header
* iconv, iconv_set_encoding, if, imagecreatefromgif, imagedestroy, imageinterlace, imagepng, imagettfbbox, implode, in_array, isset
* list
* ord
* preg_replace, preg_match_all, print
* range
* sin, **sprintf**, sscanf, strlen, strpos, strtolower, str_replace, substr, system (**convert**)
* tan, tempnam
* ucfirst, unlink, unset
* while

## note ##

the precision of floating-numbers, for **sprintf** and such functions, is set to one digit.
this sems to be enough for most drawings and saves a lot of space too.
a regular page got a size of 595 x 842 pixel which is 210 x 297 millimeter.
in this case 1 pixel is equal to (0.1 : 72 x 2.54) 0.35 millimeter.
from this point of view, there is no need for 6 digits precision, under normal circumstances.
if more precission is needed **sprintf("%.1f", ...)** needs to be replaced with **sprintf("%.f", ...)** inside this script.

## references ##

* [convert] (https://wiki.ubuntuusers.de/ImageMagick/)
* [fpdf] (http://www.fpdf.org/)
* [pdf] (https://www.adobe.com/content/dam/acom/en/devnet/pdf/pdfs/PDF32000_2008.pdf)
* [pdflib] (https://www.php.net/manual/en/ref.pdf.php)
* [php] (https://www.php.net/)
* [png] (http://www.libpng.org/pub/png/spec/1.2/PNG-Contents.html)

