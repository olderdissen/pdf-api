# pdf-api

newer project to create pdf-file like pdf-lib but without restrictions.
see *pdf-api-test.php* for a working example.

this project follows a different strategy of collecting and storing data.
resources are stored inside an array.
each element is accessible and changeable at any time.

supported filters
* /ASCIIHexDecode
* /ASCII85Decode
* /FlateDecode
* /LZWDecode

supported image format
* GIF
* JPG
* PNG

supported fonts
* /Type1 (14 core fonts)
* /Type2 (TTF)
* /Type3 (experimental)

# pdf-lib-lite-clone

older project to create pdf-files like pdf-lib but without restrictions.
