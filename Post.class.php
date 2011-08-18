<?php
// A Post is a Blog post or a Blog article. It takes advantage of the general Model
// class for interaction with the database, but uses it's own SQL logic where that
// makes more sense.
// Posts can be found one by one or several at a time by giving the Post::find()
// method either an integer or an array of such.
// There are special methods to find all posts or the newest ones.
class Post extends Model 
{
    public $user_id;
    public $posted_at;
    public $title;
    public $summary;
    public $body;
    public $tags;
    private $comments;
  
  // Create a Post instance, and load all Comments
  public function __construct() {
      $this->loadComments();    
  }

  // Create a new Post from a POST request form
  public function create($postRequest) {
  		$new = new Post;
  		$new->user_id = $_SESSION['id'];
  		$new->posted_at = date("Y-m-d H:i:s");
  		$new->tags = $postRequest['tags'];
      $new->title = $postRequest['title'];
      $new->body = $postRequest['body'];
      $new->summary = $postRequest['summary'];
      return $new->save();
  }

  // Save the instance to the database, after verification
  public function save() {
  	$this->verifyBeforeSave();
    return Model::save($this);
  }
  
  // Return the user that wrote this Post
  public function user() {
		return User::find($this->user_id);
  }
  
  // Return the comments
  public function comments() {
		return $this->comments;
  }
  
  // Find an instance of Post by given id
  public function find($id){
      if (func_num_args() != 1) exit("Wrong number of arguments in Post::find()");
      if(is_numeric($id)) return Model::find("Post", $id);
      if(is_array($id)) {
      	$ids = join(',', $id);
				return Model::find("Post", "WHERE id IN ($ids)");
      }
      return false;
  }
  
  // Update the view count
  public function counter(){
		global $database;
		$query2 = "UPDATE Post SET counter=counter+1 WHERE id=" . $this->id;
		$stmt = $database->prepare($query2);
		$stmt->execute();
		Search::index($this);
  }

  // Retrive all Posts from given year, eventually given month
  public function getOld($year, $month=null) {
      if(is_null($month)) {
        $conditions = "WHERE posted_at BETWEEN '$year-01-01' AND '$year-12-31'";
      } else {
      	if(!is_numeric($month)) $month = Tools::months($month);
        $month = Tools::dateM('m', mktime(0, 0, 0, $month, 1, $year));
        $day = date('d', mktime(0, 0, 0, $month+1, 0, $year));
        $conditions = "WHERE posted_at BETWEEN '$year-$month-01' AND '$year-$month-$day'";
      }
      return Model::getAll("Post", $conditions);
  }
  
  // Get a list of all years with posts in them
  public function getYears() {
  	global $database;
		$query = "SELECT DISTINCT DATE_FORMAT(posted_at, '%Y') FROM Post";
		$stmt = $database->query($query);
    $result = $stmt->fetchAll(PDO::FETCH_COLUMN);
    return $result;
  }
  
  // Get an array of the 5 latest Post objects, or a specified number
  public function getNewest($count=5) {
  	if (func_num_args() > 1) exit("Wrong number of arguments in Post::getAll()");
		return Model::getAll("Post", "ORDER BY posted_at DESC LIMIT $count");
  }
  
  // Load all Post objects from the database
  public function getAll($ids=null) {
      if (func_num_args() > 1) exit("Wrong number of arguments in Post::getAll()");
      if(!is_null($ids)) {
				return Model::getAll('Post', 'WHERE id IN ('. join(',',$ids) .')');
      } 
      return Model::getAll("Post");
  }
  
  // Load all Comments for this Post to $this->comments
  public function loadComments() {
      $conditions = "WHERE post_id = '" . $this->id . "'";
      $this->comments = Comment::getAll($conditions);
  }

  // Verify all attributes
  private function verifyBeforeSave() {
  		// Verify not empty variables
			Model::verifyNotEmpty($this, array('id', 'tags','comments'));
			// Verify default text removal
			if($this->summary=="<p>Skriv sammendrag her (bytt ut denne teksten)</p>") throw new Exception("Du m&aring; skrive sammendraget.");
			if($this->body=="<p>Skriv innlegget her (bytt ut denne teksten)</p>") throw new Exception("Du m&aring; skrive innlegget.");
  }
  
}
?>
