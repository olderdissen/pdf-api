# pdf-api

newer project to create pdf-file like pdf-lib-lite but without restrictions.
see **pdf-api-test.php** for a working example.

this newer project follows a different strategy of collecting and storing data than the older project described below.
resources are stored inside a single array for easier access.
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

## note ##

the precision of floating-numbers, for **sprintf** and such functions, is set to one digit.
this is enough for most drawings and saves a lot of space too.
a regular page got a size of 595 x 842 pixel which is 210 x 297 millimeter.
in this case 1 pixel is equal to (0.1 : 72 x 2.54) 0.35 millimeter.
there is no need for 6 digits precision under normal circumstances.
if more precission is needed **sprintf("%.1f", ...)** need to be replaced with **sprintf("%.f", ...)** inside script.

# pdf-lib-lite-clone

older project to create pdf-files like pdf-lib-lite but without restrictions.
only basic functionality is developed in this project.

some functions of this project will be replaced by newer ones in the future.
