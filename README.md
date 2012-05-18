Easy Gallery HG
===============

Simple gallery script using an index.php file and a 'photos' folder of images.

Example:
http://halgatewood.com/free/gallery/gatewood/

Requirements:
* PHP 5.2+ (json functions)
* GD Library
* File and Folder write permissions

Installation
------------
* Create a folder where you want the gallery to be: /gatewood/
* Add index.php to that folder
* Create a photos folder inside of that old folder: /gatewood/photos
* Add your images to the photos folder
* In your browser visit the new page and click 'Build Images'

Adding More Photos
------------
After each build of the images the script will lock that page. 
This will keep people from building your images all the time.

In your main directory edit the file photos.json and set the variable 'locked' to 'false'

Adding a Zip of All Files
------------
Include a file named Archive.zip to your main directory and a 'Download all Files' button will appear in the header.


