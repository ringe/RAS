<?php
// The Search class takes responsibility for indexing and searching of Post objects.
// Search makes use of plain text indexing in a separate index folder. It takes the
// raw data from the Post object and its Comments and dumps it to a file with the name
// of the id of the Post. It is not possible to index a Post for Search unless it has
// an id, so the Post must be stored in the database.
class Search {

  // Create searchable index of all Posts
  public function indexAll() {
    $posts = Post::getAll();
    foreach($posts as $post) {
      self::index($post);
    }
  }

  // Create searchable index of given Post object
  public function index($post) {
    if(isset($post->id)) {
      if(!is_dir('index')) mkdir('index');
      $index = opendir('index');

      // Create the search object
      foreach ($post as $name=>$val) {
        $content = strip_tags($val);
        $content = strtolower($content);
        if(isset($searchobject))
            $searchobject = array_merge($searchobject, preg_split("/[\s,]+/", $content));
        else
            $searchobject = preg_split("/[\s,]+/", $content);
      }

      // Indexing the comments...
      foreach ($post->comments() as $comment) {
        $content = strip_tags($comment->comment);
        $content = strtolower($content);
        $searchobject = array_merge($searchobject, preg_split("/[\s,]+/", $content));
        $content = strip_tags($comment->title);
        $content = strtolower($content);
        $searchobject = array_merge($searchobject, preg_split("/[\s,]+/", $content));
      }

      $pif = fopen("index/$post->id", 'w'); //post-index-file
      fwrite($pif, join(" ", $searchobject));
      fclose($pif);

      closedir($index);
      return true;
    }
    throw new Exception("Can't index a Post without an id.");
  }

  // Find a post by given keywords
  public function find($string="") {
      if(!is_dir('index')) throw new Exception("No search index.");
      $index = scandir('index');
      $hits = array();
      foreach ($index as $pif) {
        if($pif != '.' and $pif != '..') {
          $content = file_get_contents("index/".$pif);
          $string = strtolower($string);
          if(preg_match("/$string/", $content)) $hits[] = $pif;
        }
      }
      if(empty($hits)) return false;
      $hits = Post::getAll($hits);
      return $hits;
  }
}
?>
