<?php
/*---------------------------------------------\
|											   |
| @Author:       Andrey Brykin (Drunya)        |
| @Version:      1.1                           |
| @Project:      CMS                           |
| @Package       AtomX CMS                     |
| @subpackege    BlogSections Entity           |
| @copyright     ©Andrey Brykin 2010-2014      |
| @last mod      2014/05/07                    |
|----------------------------------------------|
|											   |
| any partial or not partial extension         |
| CMS Fapos,without the consent of the         |
| author, is illegal                           |
|----------------------------------------------|
| Любое распространение                        |
| CMS Fapos или ее частей,                     |
| без согласия автора, является не законным    |
\---------------------------------------------*/



/**
 *
 */
class BlogSectionsEntity extends FpsEntity
{
	
	protected $id;
	protected $parent_id;
	protected $path;
	protected $announce;
	protected $title;
	protected $view_on_home;
	protected $no_access;
	

	public function save()
	{
		$params = array(
			'parent_id' => intval($this->parent_id),
			'path' => (string)$this->path,
			'announce' => (string)$this->announce,
			'title' => (string)$this->title,
			'view_on_home' => (string)$this->view_on_home,
			'no_access' => (string)$this->no_access,
		);
		
		
		if ($this->id) $params['id'] = intval($this->id);
		$Register = Register::getInstance();
		return $Register['DB']->save('blog_sections', $params);
	}
	
	
	
	public function delete()
	{ 
		$Register = Register::getInstance();
		$Register['DB']->delete('blog_sections', array('id' => $this->id));
	}
}