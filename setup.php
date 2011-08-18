<?php
// Prepare our various Tools
include 'Tools.class.php';
include 'Search.class.php';

// Load Models
include 'Model.class.php';
include 'Comment.class.php';
include 'Post.class.php';

checkInstall();  				// Check install
new Tools();       			// Load database
Search::indexAll();			// Index all Posts
echo "Setup complete."
	
  // Set up the .htaccess rules
function checkInstall($redo=false) {
    if(!file_exists("successful_install.txt") or $redo) {
        // Prepare .htaccess rules
        $rules = "RewriteEngine on\n";
        $rules.= "RewriteCond $1 !^(index\.php|favicon\.ico|robots\.txt|images|javascript|css|phpinfo) [NC]\n";
        $rules.= "RewriteRule ^(.*)$ ".str_replace('setup.php','',$_SERVER['REQUEST_URI'])."index.php?url=$1 [L]\n";

        // Write the .htaccess rules
        $ha = fopen(".htaccess", "w");
        fwrite($ha, $rules);
        fclose($ha);

        // Report the successful install
        $report = fopen("successful_install.txt", "w");
        fwrite($report, "Installed the RAS blogg to the relative PATH ".$_SERVER['REQUEST_URI']."\n");
        fclose($report);
        return true;
    } else return false;
}

?>
