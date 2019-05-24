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

# pdf-lib-lite-clone

older project to create pdf-files like pdf-lib-lite but without restrictions.
only basic functionality is developed in this project.

some functions of this project will be replaced by newer ones in the future.

