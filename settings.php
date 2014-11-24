<?php

class BlogSettingsController
{

    public $module = 'shop';

    /**
     * Page title
     * @var string
     */
    public $pageTitle;

    /**
     * The left side bread crumbs
     * @var string
     */
    public $pageNav;

    /**
     * The right side bread crumbs
     * @var string
     */
    public $pageNavr;
    
    private $Model;


    public function __construct()
    {
        $this->currentUrl = $_SERVER['REQUEST_URI'];
        $Register = Register::getInstance();
        //$Register['Validate']->setRules($this->_getValidateRules());
        
        $this->Model = $Register['ModManager']->getModelInstance('Blog');
    }



    public function getUrl($url = false)
    {
        return get_url($url = '/admin/' . $this->module . '/' . $url);
    }
    
    
    public function blogs_list()
    {

        $html = '<div class="list">
		<div class="title">' . __('Users blogs list') . '</div>
		<div class="level1">
			<div class="head">
				<div class="title">' . __('Blog') . '</div>
				<div class="buttons">
				</div>
				<div class="clear"></div>
			</div>
			<div class="items">';

        
        $blogs = $this->Model->getBlogs();
        foreach ($blogs as $i => $blog) {
            if (!empty($_GET['ac']) && $_GET['ac'] === 'del') {
                if (!empty($_GET['id']) && $_GET['id'] == $blog->getAuthor()->getId()) {
                    $this->Model->removeUserBlog($blog->getAuthor()->getId());
                    continue;
                }
            }
        
            $html .= '<div class="level2">
                    <div class="number">' . ($i + 1) . '</div>
                    <div class="title">' . h($blog->getAuthor()->getName()) . '</div>
                    <div class="buttons">
						<a title="' . __('Show') . '" 
						    href="' . $this->getUrl('view_blog/' . $blog->getAuthor()->getId()) . '" 
						    class="view"></a>
						<a title="' . __('Delete') . '" 
						    href="?ac=del&id=' . $blog->getAuthor()->getId() . '" 
						    onClick="return _confirm(\'' . __('Are you sure?') . '\');" 
						    class="delete"></a>
                    </div>
                    <div class="posts">' . $blog->getPosts_cnt() . '</div>
                </div>';
        }


        $html .= '</div></div></div>';
        return $html;
    }
    
    
    public function view_blog($author_id)
    {
        $html = '<div class="list">
		<div class="title">' . __('Users posts') . '</div>
		<div class="level1">
			<div class="head">
				<div class="title">' . __('Post') . '</div>
				<div class="buttons">
				</div>
				<div class="clear"></div>
			</div>
			<div class="items">';
        
        
        $posts = $this->Model->getCollection(array('author_id' => $author_id));
        foreach ($posts as $i => $post) {
            if (!empty($_GET['ac']) && $_GET['ac'] === 'del') {
                if (!empty($_GET['id']) && $_GET['id'] == $post->getId()) {
                    $post->gelete();
                    continue;
                }
            }
        
            $html .= '<div class="level2">
                    <div class="number">' . ($i + 1) . '</div>
                    <div class="title">' . h($post->getTitle()) . '</div>
                    <div class="buttons">
						<a title="' . __('Edit') . '" 
						    href="' . $this->getUrl('edit_post/' . $post->getId()) . '"
						    class="edit"></a>
						<a title="' . __('Delete') . '" 
						    href="?ac=del&id=' . $post->getId() . '" 
						    onClick="return _confirm(\'' . __('Are you sure?') . '\');" 
						    class="delete"></a>
                    </div>
                </div>';
        }


        $html .= '</div></div></div>';
        return $html;
    }
    
    
	public function edit_post($id) {
		$this->pageTitle .= ' - ' . __('Edit');
	
		$output = '';
		
		
		$Register = Register::getInstance();
		$categoriesModel = $Register['ModManager']->getModelInstance('PostCategories');
        $categories = $categoriesModel->getCollection();
		
		$id = intval($id);
		$entity = $this->Model->getById($id);
		
		
		
		if (!empty($_POST)) {
			$entity->setTitle($_POST['title']);
			$entity->setMain($_POST['main']);
			$entity->setSourse_email($_POST['email']);
			
			if (!empty($_POST['category'])) {
			    if (intval($_POST['category']) != $entity->getCategory_id()) {
			        $categoryTest = $categories = $categoriesModel->getById($_POST['category']);
			        if ($categoryTest) {
			            $entity->setCategory_id($_POST['category']);
			        }
			    }
			}
			
			
			$entity->save();
			$_SESSION['message'] = __('Saved');
			
			redirect($this->getUrl('view_blog/' . $entity->getAuthor()->getId()));
		}
		
		
		$output .= '<form method="POST" action="" enctype="multipart/form-data">
            <div class="list">
                <div class="title"></div>
                <div class="level1">
                    <div class="head">
	                    <div class="title settings">' . __('Post editing') . '</div>
	                    <div class="title-r"></div>
	                    <div class="clear"></div>
                    </div>
                    <div class="items">
		                <div class="setting-item"><div class="left">
			                ' . __('Title') . '
		                </div><div class="right">
			                <input type="text" name="title" value="'.h($entity->getTitle()).'" />
		                </div><div class="clear"></div></div>
		                
		                <div class="setting-item"><div class="left">
			                ' . __('Text of material') . '
		                </div><div class="right">
			                <textarea style="height:200px;" name="main">'.h($entity->getMain()).'</textarea>
		                </div><div class="clear"></div></div>
		                
		                <div class="setting-item"><div class="left">
			                ' . __('Email') . '
		                </div><div class="right">
			                <input type="text" name="email" value="'.h($entity->getSourse_email()).'" />
		                </div><div class="clear"></div></div>
		                
		                <div class="setting-item"><div class="left">
			                ' . __('Category') . '
		                </div><div class="right">
			                <select name="category">';
			                
	    foreach ($categories as $category) {
	        $selected = ($category->getId() == $entity->getCategory_id())
	            ? ' selected="selected"' : '';
	        $output .= '<option' . $selected . ' value="' . $category->getId() . 
	            '">' . h($category->getTitle()) . '</option>';
	    } 
			                
	    $output .= '</select>
		                </div><div class="clear"></div></div>
		                <div class="setting-item">
			                <div class="left">
			                </div>
			                <div class="right">
				                <input class="save-button" type="submit" name="send" value="' . __('Save') . '" />
			                </div>
			                <div class="clear"></div>
		                </div>
		    		</div>
	            </div>
            </div>
            <div class="pagination"><?php echo $pages ?></div>
            </form>';
		
		
		return $output;
	}
}
