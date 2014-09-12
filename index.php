<?php
/**
 * Artwork-Web-Folder by David Revoy
 * A php file listing and serving picture, zip, pdf inside a folder. 
 * Require a php server, and a FTP client to upload files to the folder.
 *
 * Library :
 * TimThumb lib by Ben Gillbanks, Mark Maunder, Tim McDaniels and Darren Hoyt
 * http://code.google.com/p/timthumb/
 * 
 * GNU General Public License, version 2
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * Examples and documentation available on the project Github homepage
 * 
 */


// Display without resizing picture smaller than this number of pixels :
$imagewidth= 1100;
$imageheight= 900;

// auto : Domain URL
$domain = $_SERVER['HTTP_HOST'];
	
// auto : Current path
$path = dirname($_SERVER['SCRIPT_NAME']) . '/';
	
// auto : Current Folder URL
$CurrentFolderURL = "http://" . $domain . $path;
	
// manual : path to lib folder ( css, timthumb )
$PathToLib = $CurrentFolderURL.'lib/';

// auto : Deduce project name from path
$ProjectName = basename($path);


// Document
// =========
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
        <!-- uncomment this line under to stop search engine robot to list your page -->
        <!-- <meta name="ROBOTS" CONTENT="NOINDEX, NOFOLLOW"> -->
        <title><?php echo $ProjectName; ?></title>
		<link rel="stylesheet" type="text/css" href="<?php echo $PathToLib; ?>/style.css?v=1.0" media="screen" />
    </head>
<body>

<div id="global">

<?php
// Path of the folder to scan for images or files ( empty = actual )
	$pathtoscan = "";

// Get and recognize content
	$texts = glob($pathtoscan . '*.{md}',GLOB_BRACE);
    
    // images allowed to display :
	$images = glob($pathtoscan . '*.{jpg,gif,png,jpeg}',GLOB_BRACE);
    
    // file allowed to be scan :
	$documents = glob($pathtoscan . '*.{zip,txt,psd,pdf,avi,mpeg,tar,7z,odt,doc,docx,ppt,ora}',GLOB_BRACE);
    
	$allcontents = array_merge($texts, $images, $documents); 

// Count : return how many number of each stuff we grabbed previously
	$numberoftexts  = count($texts);
	$numberofimages  = count($images);
	$numberofdocuments  = count($documents);
	$numberofall = $numberoftexts + $numberofimages + $numberofdocuments;
	
// Sort all contents alphabetical
    sort($allcontents);

// Debug infos ( hidden for "view source" Ctrl+U only ):
	echo '<!-- Debug Infos '."\n";
	echo ''.$numberofimages.' image(s) found'."\n";
	echo ''.$numberofdocuments.' documents found'."\n";
	echo ''.$numberofall.' total entrie(s) found'."\n";
	echo 'Current Folder URL : '.$CurrentFolderURL.''."\n";
	echo 'Path to lib folder : '.$PathToLib.''."\n";
	echo '-->'."\n\n";
	
// ========= //
// FUNCTIONS //
// ========= //


// Display Image
// -------------
function display_image($imagefilename, $PathToLib, $CurrentFolderURL, $imagewidth, $imageheight){		
    // Clean titles
    $removethisstring = array ( '/\.png/i' ,'/\.gif/i' ,'/\.jpg/i' , '[specifictexttoremoveonfilename]' , '/[^%a-zA-Z]/'  );
    $replacewiththis = array ('' , '' , '' , '' , ' ' );
    $titre_image = preg_replace( $removethisstring , $replacewiththis , $imagefilename);

    // Get images infos
    list($width, $height) = getimagesize($imagefilename);
    $imagefileweight = (filesize($imagefilename) / 1024) / 1024;
    $lastmodification=date ("d-M-Y", filemtime($imagefilename));	

    // Image container
    echo '<div class="imagecontainer"><a name="'.$imagefilename.'" href="#'.$imagefilename.'" ><h2>'.$titre_image.'</h2></a><div>'."\n";
        // Lower res than width or height variables
        if ( $width < $imagewidth ) 
        {
            echo '<a name="'.$imagefilename.'" href="'.$imagefilename.'" ><img src="'.$imagefilename.'" title="'.$titre_image.'" alt="'.$imagefilename.'" ></a>';
        } else {
        // Higher res than width or height variables
            if ($width > $height || $width == $height) 
            {					
                // Horyzontal resize rule
                echo '<a name="'.$imagefilename.'" href="'.$imagefilename.'" ><img src="'.$PathToLib.'timthumb.php?src='.$CurrentFolderURL.''.$imagefilename.'&w='.$imagewidth.'" title="'.$titre_image.'" alt="'.$imagefilename.'" ></a>';
	            } else {		
	            // Vertical resize rule
	            echo '<a name="'.$imagefilename.'" href="'.$imagefilename.'" ><img src="'.$PathToLib.'timthumb.php?src='.$CurrentFolderURL.''.$imagefilename.'&h='.$imageheight.'" title="'.$titre_image.'" alt="'.$imagefilename.'" ></a>';
            }
        }			
        // Label of image
        echo '<span class="titre"><a href="#'.$imagefilename.'" ><span class="imageanchor"></span> link</a>&nbsp;&nbsp;&nbsp;&nbsp;<span class="date"> ' . date ("d M Y", filemtime($imagefilename)) . '</span></span> '."\n";
        echo '<a href="'.$CurrentFolderURL.''.$imagefilename.'" title="'.$imagefilename.'" alt=" '.$imagefilename.'" ><span class="download">'.$width.'x'.$height.'px , '.round($imagefileweight, 2).'MB  &nbsp;&nbsp;&nbsp;&nbsp;Download </span></a>'."\n";
    echo '</div></div>'."\n"."\n";
}


// Display document
// ----------------
function display_document($documentfilename){		
	// Check filesize, calculus for MB
	$filesize = (filesize($documentfilename) / 1024) / 1024;
	$document_extension = pathinfo($documentfilename);
	
	switch($document_extension['extension'])
        {
            case "pdf":
            echo '<div class="filecontainerpdf" name="'.$documentfilename.'" ><a href="'.$documentfilename.'" title="'.$documentfilename.'" alt=" '.$documentfilename.'" >'."\n";
	        echo '<div class="date">' . date ("d M Y", filemtime($documentfilename)) . '</div>';
	        echo '<h2>Document</h2> '.$documentfilename.' ('.round($filesize, 2).'MB)</a></div>'."\n"."\n";
            break;
            
            case "zip":
            echo '<div class="filecontainerzip" name="'.$documentfilename.'" ><a href="'.$documentfilename.'" title="'.$documentfilename.'" alt=" '.$documentfilename.'" >'."\n";
	        echo '<div class="date">' . date ("d M Y", filemtime($documentfilename)) . '</div>';
	        echo '<h2>Download</h2> '.$documentfilename.' ( '.round($filesize, 2).'MB) </a></div>'."\n"."\n";
            break;
            
            default:
            // all other
            echo '<div class="filecontainer" name="'.$documentfilename.'" ><a href="'.$documentfilename.'" title="'.$documentfilename.'" alt=" '.$documentfilename.'" >'."\n";
	        echo '<div class="date">' . date ("d M Y", filemtime($documentfilename)) . '</div>';
	        echo '<h2>Download</h2> '.$documentfilename.' ( '.round($filesize, 2).'MB) </a></div>'."\n"."\n";
            break;
        }

}


// ==== ///
// SCAN ///
// ==== ///

// Generate an Auto main title
// ----------------------------
$removethisstring = array ( '/[^%a-zA-Z]/' );
$replacewiththis = array (' ');
$cleanProjectName = preg_replace( $removethisstring , $replacewiththis , $ProjectName);
echo '<h1>'.$cleanProjectName.'</h1>';

// Then scan files
// ---------------

	foreach($allcontents as $content) {
	        $file_extension = pathinfo($content);


            switch($file_extension['extension'])
            {
                // Images
                // ------
                case "jpg": case "png": case "jpeg": case "gif":
                display_image($content, $PathToLib, $CurrentFolderURL, $imagewidth, $imageheight);
                break;
               
                // Handle errors
                // -------------
                case "": case NULL:
                break;
                
                // Documents
                // -------------
                default:
                display_document($content);
                break;
            }

				
				
	}

?>

	<div id="footer">
		<a href="https://github.com/Deevad?tab=repositories">powered by Artwork-Web-Folder</a>
	</div><br/>

</div>
</body>
</html>
