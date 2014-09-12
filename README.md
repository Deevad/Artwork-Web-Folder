Artwork-Web-Folder
==================

A php file listing and serving picture, zip, pdf inside a folder. Require a php server, and a FTP client to upload files to the folder.

**Demo** : [http://www.davidrevoy.com/data/documents/Artwork-Web-Folder-demo/](http://www.davidrevoy.com/data/documents/Artwork-Web-Folder-demo/)

## Features :

* Auto resize picture to jpg optimised and sharp, and cache thumbnail in tmp folder of your server ( thx Timthumb )
* Keep high-res on disk to download, so you can list a lot of hi-res PNG easily
* link (auto scroll in the page ) to the picture available.
* Display date, file weight, image size
* Generate page title and title for the files automatically.

## Usage :

Just drop the index.php file into a FTP folder with the "lib" subfolder.
Your folder is now ready to list images and files. 

## To know :

list of picture format to be display : jpg,gif,png,jpeg
list of downloadable files : zip,txt,psd,pdf,avi,mpeg,tar,7z,odt,doc,docx,ppt,ora

Possibility to make the page private, via a secret path name, and uncommenting line 49, to block access of search engine and bots. 

Files are listed depending alphabetical order, first pictures then files ( to download, etc ). you can use number prefix to sort them manually ( eg: 01-file.jpg, 02-file.jpg etc... or 20140815-wip.png, 20140920-wip.png ). Number are removed automatically to make a clean title to the picture. 
