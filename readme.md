# pdf-api

updated project to create pdf-file like **pdflib** but without restrictions.
this project follows a different strategy of collecting and storing data.
all data is stored inside an array for easy access.
each element is accessible, changeable and removable at any time.

supported filters
* /ASCIIHexDecode
* /ASCII85Decode
* /FlateDecode
* /LZWDecode

supported image formats
* GIF to PNG
* JPG
* PNG
* others to JPG (depends on **convert**)

supported fonts
* /Type1 (14 core fonts)
* /Type2 (TTF)
* /Type3 (experimental)

## example ##

see **pdf-api-test.php** for a working example.
more examples can be found in manual-pages of **pdflib**.
keep in mind that some functions may work different than in the original **pdflib**.

## requirements ##

there are no special requirements to run this script except php.
it could be useful to have **convert** installed for more image support.

## note ##

the precision of floating-numbers, for **sprintf** and such functions, is set to one digit.
this is enough for most drawings and saves a lot of space too.
a regular page got a size of 595 x 842 pixel which is 210 x 297 millimeter.
in this case 1 pixel is equal to (0.1 : 72 x 2.54) 0.35 millimeter.
there is no need for 6 digits precision under normal circumstances.
if more precission is needed **sprintf("%.1f", ...)** need to be replaced with **sprintf("%.f", ...)** inside script.

this project can be used to replace **pdf-lib-lite** since most function-names are equal.

## references ##

* [PDF32000:2008-1](https://www.adobe.com/content/dam/acom/en/devnet/pdf/pdfs/PDF32000_2008.pdf)
* [pdflib](https://www.php.net/manual/en/ref.pdf.php)
* [fpdf](http://www.fpdf.org/)
