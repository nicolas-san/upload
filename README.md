* Simple function to manage uploaded file
* Simple form for testing

This function verify:
 * multiple extension (logo.php.png)
 * extension allowed (jpg, png or gif)
 * good mime type with php finfo_file method http://php.net/manual/en/intro.fileinfo.php
 * if image is valid and remove EXIF with one of the imagecreateFORMAT function from GD http://php.net/manual/en/function.imagecreatefrompng.php
 * if target directory is writable
 * if move_uploaded_file pass without error

Finally save the new created image (from GD) to the directory path, with a new random name, and return it.

Comments, critics and improvements are very welcome :)
Feel free to fork and PR !



The MIT License (MIT)

Copyright (c) <year> <copyright holders>

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.