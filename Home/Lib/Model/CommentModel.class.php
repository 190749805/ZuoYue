<?php 
class CommentModel extends RelationModel{
	  protected $_link = array(
        'Title'=>array(
		'mapping_type'    =>BELONGS_TO,
        'class_name'    =>'title',
		'foreign_key'=>'tid',
		'mapping_name' => 'title',
		//'as_fields'=>'tcontent,ttime',
         ),
		'Comment'=>array(
		'mapping_type'    =>HAS_MANY,
        'class_name'    =>'comment',
		'parent_key'=>'cid',
		'mapping_name' => 'reply',
		//'mapping_fields'=>'ccontent,ctime',
         ), 
	); 
	/*  protected $_link = array(
        'Comment'=>array(
		'mapping_type'    =>HAS_MANY,
        'class_name'    =>'comment',
		'parent_key'=>'cid',
		'mapping_name' => 'comment',
		//'mapping_fields'=>'ccontent,ctime',
         ), 
	); */

}