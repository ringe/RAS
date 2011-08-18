<?php
// The Comment class is another class building upon the general Model class for most
// of it's database handling. The exception is deleting a comment.
class Comment extends Model {
  public $user_id;
  public $post_id;
  public $posted_at;
  public $title;
  public $comment;

  // Constructor
  public function __construct() {}
  
  // Create a comment object from a comment form POST request
  public function create($commentRequest) {
      $new = new Comment;
      $new->user_id = $_SESSION['id'];
      $new->posted_at = date("Y-m-d H:i:s");
    	$new->post_id = $commentRequest['post_id'];
      $new->title = $commentRequest['title'];
      $new->comment = $commentRequest['comment'];
      $new->save();
  }
  
  // Save a Comment instance to the database
  public function save(){
			Model::save($this);
  }
  
  // Return the Post object this Comment belongs to
  public function post() {
		return Post::find($this->post_id);
  }
  
  // Return the user that wrote this Comment
  public function user() {
		return User::find($this->user_id);
  }
  
  // Return a Comment object by given id
  public function find($id) {
  	if (func_num_args() != 1) exit ("Wrong number of arguments in Comment::find()");
  	return Model::find("Comment", $id);      
  }
  
  // Return all Comments by given conditions
  public function getALL($conditions=""){
		if (func_num_args() > 1) exit ("Wrong number of arguments in Comment::getAll()");
  	return Model::getAll("Comment", $conditions);
  }
  
  // Delete a comment from the database by a given id
  public function delete($commentId){
		global $database;
		try{
		$query = "DELETE FROM Comment WHERE id=?";
		$stmt = $database->prepare($query);
		$stmt->execute(array($commentId));
		}catch (Exception $e) {
			echo 'Error: '.$e;
			return false;	
		}
		
  }
  
}
?>
