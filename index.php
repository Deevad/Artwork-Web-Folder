<?php
/**
 * Artwork-Web-Folder by David Revoy 
 * UNSTABLE VERSION -- use at your own risk
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
 $subfoldername = "";

// we check if a subfolder variable is sent
    if( $_GET ) {
        // we got one. Let do something with it : ( test with index.php?subfolder=sketches )
        if( $_GET["subfolder"] ) {
        
        // Security : prohibition of special character to avoid malicious code in the string :
        $subfolder = $_GET["subfolder"];
        $invalid_characters = array("$", "%", "#", "<", ">", "|", ".");
        $subfolder = str_replace($invalid_characters, "", $subfolder);
        
        // Debug on the Ctrl+U view of HTML 
        echo "<!-- Debug Subfolder : ".$subfolder. "-->"."\n";
        
        // New sanified variable
        $pathtoscan = $subfolder."/";
        $subfoldername = ", ".$subfolder;
      }
    }


// Get and recognize content
	$texts = glob($pathtoscan . '*.{md}',GLOB_BRACE);
	$images = glob($pathtoscan . '*.{jpg,gif,png,jpeg}',GLOB_BRACE);
	$documents = glob($pathtoscan . '*.{zip,txt,psd,pdf,avi,mpeg,tar,7z,odt,doc,docx,ppt,ora}',GLOB_BRACE);
	$allcontents = array_merge($texts, $images, $documents); 
	$subdirectories = array_filter(glob('*'), 'is_dir');

// Count : return how many number of each stuff we grabbed previously
	$numberoftexts  = count($texts);
	$numberofimages  = count($images);
	$numberofdocuments  = count($documents);
	$numberofsubdirectories  = count($subdirectories);
	$numberofall = $numberoftexts + $numberofimages + $numberofdocuments + $numberofsubdirectories;
	
// Sort all contents alphabetical
    sort($allcontents);

// Debug infos ( hidden for "view source" Ctrl+U only ):
	echo '<!-- Debug Infos '."\n";
	echo ''.$numberoftexts.' text(s) available'."\n";
	echo ''.$numberofimages.' image(s) available'."\n";
	echo ''.$numberofdocuments.' file(s) available'."\n";
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
    $removethisstring = array ( '/\.png/i' ,'/\.gif/i' ,'/\.jpg/i' , '[yourstringtoremovehere]' , '%^(/*)[^/]+%' , '/[^%a-zA-Z]/'  );
    $replacewiththis = array ('' , '' , '' , '' , '' , ' ' );
    $titre_image = preg_replace( $removethisstring , $replacewiththis , $imagefilename);

    // Get images infos
    list($width, $height) = getimagesize($imagefilename);
    $imagefileweight = (filesize($imagefilename) / 1024) / 1024;
    $lastmodification=date ("d-M-Y", filemtime($imagefilename));	

    // Image container
    echo '<div class="imagecontainer"><a name="'.$imagefilename.'" href="#'.$imagefilename.'" ><h2>'.$titre_image.'</h2></a><div>'."\n";
    
    
    // If picture is small , it will affect presentation, so we propose a mini compact bottom bar in this case
    if ( $width < 500 ) {
        echo '<a name="'.$imagefilename.'" href="'.$imagefilename.'" ><img src="'.$imagefilename.'" title="'.$titre_image.'" alt="'.$imagefilename.'" ></a>';
        
        // Mini compact bottom bar :
        echo '<span class="titre"><a href="#'.$imagefilename.'" ><span class="imageanchor"></span></a><span class="date"> ' . date ("j/m/y", filemtime($imagefilename)) . '</span></span> '."\n";
        echo '<a href="'.$CurrentFolderURL.''.$imagefilename.'" title="'.$imagefilename.'" alt=" '.$imagefilename.'" ><span class="download">'.$width.'x'.$height.'px , '.round($imagefileweight, 2).'MB</span></a>'."\n";
        echo '</div></div>'."\n"."\n";
        
    } else {
    
        // If picture is smaller than required , display it directly ( no resize necessary = optimisation )
        if ( $width < $imagewidth && $height < $imageheight) {
            echo '<a name="'.$imagefilename.'" href="'.$imagefilename.'" ><img src="'.$imagefilename.'" title="'.$titre_image.'" alt="'.$imagefilename.'" ></a>';
            
        } else {
            
        // If picture is horyzontal, or a square :
            if ($width > $height || $width == $height) 
            {				
                echo '<a name="'.$imagefilename.'" href="'.$imagefilename.'" ><img src="'.$PathToLib.'timthumb.php?src='.$CurrentFolderURL.''.$imagefilename.'&w='.$imagewidth.'" title="'.$titre_image.'" alt="'.$imagefilename.'" ></a>';
	            
                } else {
                    
	            // It means, picture is vertical
	            echo '<a name="'.$imagefilename.'" href="'.$imagefilename.'" ><img src="'.$PathToLib.'timthumb.php?src='.$CurrentFolderURL.''.$imagefilename.'&h='.$imageheight.'" title="'.$titre_image.'" alt="'.$imagefilename.'" ></a>';
            }
        }

        // Large label of image
        echo '<span class="titre"><a href="#'.$imagefilename.'" ><span class="imageanchor"></span> link</a>&nbsp;&nbsp;&nbsp;&nbsp;<span class="date"> ' . date ("d M Y", filemtime($imagefilename)) . '</span></span> '."\n";
        echo '<a href="'.$CurrentFolderURL.''.$imagefilename.'" title="'.$imagefilename.'" alt=" '.$imagefilename.'" ><span class="download">'.$width.'x'.$height.'px , '.round($imagefileweight, 2).'MB  &nbsp;&nbsp;&nbsp;&nbsp;Download </span></a>'."\n";
        echo '</div></div>'."\n"."\n";
    
        }
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


// Display Markdown
// ----------------
function display_markdown($markdownfilename){
    
    global $PathToLib;
    
    // Get clean file name
    $removethisstring = array ( '/\.md/i' , '/[^%a-zA-Z]/'  );
    $replacewiththis = array ('' , '' );
    $author_markdown = preg_replace( $removethisstring , $replacewiththis , $markdownfilename);
    
    // Debug for Ctrl+U in htlm code again
    echo "<!-- Markdown lib path debug : ".$PathToLib."markdown.php -->";
    
    // Get markdown.php lib the dirty way : TODO; make it global as on the header, in progress
    $markdownlibpath = "lib/markdown.php";
	include_once $markdownlibpath;
	
	// Convert markdown to HTML
	$markdownContent = file_get_contents($markdownfilename);
	$markdownHTML = Markdown($markdownContent);

    // [TO-DO] when edit is ok : different style depending author
	switch($author_markdown)
            {               
                // No frame text
                case "manifesto": case "readme": case "info": case "header":
                echo '<div class="markdowncontainermanifesto" name="'.$markdownfilename.'" >'."\n";
                echo ''.$markdownHTML.' </div>'."\n"."\n";
                break;
                
                // Default
                default:
                echo '<div class="markdowncontainer" name="'.$markdownfilename.'" >'."\n";
                echo ''.$markdownHTML.''."\n"."\n";
                echo '<div class="markdowndate">' . date ("d M Y", filemtime($markdownfilename)) . '<a href="'.$markdownfilename.'" title="'.$markdownfilename.'" alt=" '.$markdownfilename.'" ></a></div></div>';
                break;
            }

}

// ==== ///
// SCAN ///
// ==== ///

// Generate an Auto title
// ----------------------
$removethisstring = array ( '/[^%a-zA-Z]/' );
$replacewiththis = array (' ');
$cleanProjectName = preg_replace( $removethisstring , $replacewiththis , $ProjectName);
echo '<h1>'.$cleanProjectName.''.$subfoldername.'</h1>';

// Subdirectories
// --------------
    
	if ($numberofsubdirectories !== 0 ) {
        
        // Root folders , appears just in case of subfolder
        if( $_GET ) {
        echo '<div class="folderup" name="main folder" ><a href="'.$CurrentFolderURL.'index.php" title="main folder" alt="main folder" >'."\n";
				echo '<b>Back to main folder</b></a></div>'."\n"."\n";
        } else {
        
            foreach($subdirectories as $subdirectories) {
            
                // exclude directories named lib
                if ($subdirectories !== "lib" ) {
                
				echo '<div class="folder" name="'.$subdirectories.'" ><a href="'.$CurrentFolderURL.'index.php?subfolder='.$subdirectories.'" title="'.$subdirectories.'" alt=" '.$subdirectories.'" >'."\n";
                echo '<b>'.$subdirectories.'</b></a>'."\n";
                
                // display miniature images inside folder, to preview content
                $pathtoscan = $subdirectories."/";
                $images = glob($pathtoscan . '*.{jpg,gif,png,jpeg}',GLOB_BRACE);
                $max_minifolderthumb=15;
                $count = 0;
                    foreach($images as $imagelink) {
                        echo '<div class="minifolderthumb"><a href="'.$CurrentFolderURL.'index.php?subfolder='.$subdirectories.'#'.$imagelink.'" ><img src="'.$PathToLib.'timthumb.php?src='.$CurrentFolderURL.''.$imagelink.'&h=40&w=40" title="thumbnail: click to enlarge" alt="'.$imagelink.'" ></a></div>';
                        $count++;
                        if($count==$max_minifolderthumb) break; 
                    }
                
                echo '</div>'."\n"."\n";
                }
            }
        }   
    }



// Then scan files
// ---------------

	foreach($allcontents as $content) {
	        $file_extension = pathinfo($content);


            switch($file_extension['extension'])
            {
            
                // Markdown
                // ------           
                case "md": case "txt":
                display_markdown($content);
                break;
                
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
