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
* Responsive, image are resized for smartphone, and tablet with smaller screens. 
* Don't get listed by search engine to keep project private ( anyone with link can access )
* Subfolder works, and has mini thumbnails

## Usage :

Just drop the index.php file into a FTP folder with the "lib" subfolder.
Your folder is now ready to list images and files. 

## To know :

#### List of picture format to be display :

jpg,gif,png,jpeg
 
#### List of downloadable files : 

zip,txt,psd,pdf,avi,mpeg,tar,7z,odt,doc,docx,ppt,ora

#### Privacy :

Possibility to make the page private, via a secret path name, and uncommenting line 49, to block access of search engine and bots. 

#### Listing order :

Files are listed depending alphabetical order, first pictures then files ( to download, etc ). you can use number prefix to sort them manually ( eg: 01-file.jpg, 02-file.jpg etc... or 20140815-wip.png, 20140920-wip.png ). Number are removed automatically to make a clean title to the picture. 

## Troubles/Issues :

Most of the issues can come from *Timthumb*, the library I use for generating the thumbnails.

I ship a modified version of timthumb ( configuration changed only ) to use the /tmp/ folder of the Linux server as a place to cache images. With this method, I avoid having to download a cache folder when I backup my server ; but If this method doesn't work on your server ; you probably will prefer to setup manually a cache folder (l.40 on lib/timthumb.php ). You can see how the default timthumb last SVN file is setup here : [https://code.google.com/p/timthumb/source/browse/trunk/timthumb.php](https://code.google.com/p/timthumb/source/browse/trunk/timthumb.php) ; using './cache' . If you decide to use a cache folder, be sure to apply a 777 permission to it ( or 755 should be suffisent too ). Other modifications I made on the version I ship : max imagesize, max imageweight and a better sharpening filter in my opinion.

Read more info about installation recommendation here : [http://www.binarymoon.co.uk/2010/11/timthumb-hints-tips/](http://www.binarymoon.co.uk/2010/11/timthumb-hints-tips/)

