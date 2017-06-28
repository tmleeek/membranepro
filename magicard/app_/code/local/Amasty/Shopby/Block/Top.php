<?php
/**
* @author Amasty Team
* @copyright Copyright (c) 2008-2012 Amasty (http://www.amasty.com)
* @package Amasty_Shopby
*/
class Amasty_Shopby_Block_Top extends Mage_Core_Block_Template
{
    private $options = array();
    
    private function trim($str)
    {
        $str = strip_tags($str);
        $str = str_replace('"', '', $str);
        return trim($str, " -");
    }
    
    public function getBlockId()
    {
        return 'amshopby-filters-wrapper';
    }        


    protected function _isPageHandled()
    {
        $page = Mage::getModel('amshopby/page');
        foreach ($page->getCollection() as $p){
            if ($p->match() && $p->getNum() > $page->getNum()){
                $page = $p;
            }
        }

        if (!$page->getNum()){
            return false;
        }
            
        $head = $this->getLayout()->getBlock('head');

        //canonical
        if (!Mage::helper('amshopby')->isVersionLessThan(1, 4)){
            $url = Mage::getSingleton('catalog/layer')->getCurrentCategory()->getUrl();
            $head->removeItem('link_rel', $url);
            $head->addLinkRel('canonical', $page->getUrl());
        }

        // metas
        $title = $head->getTitle();
        // trim prefix if any
        $prefix = Mage::getStoreConfig('design/head/title_prefix');
        $prefix = htmlspecialchars(html_entity_decode(trim($prefix), ENT_QUOTES, 'UTF-8'));
        if ($prefix){
            $title = substr($title, strlen($prefix));
        }
        $suffix = Mage::getStoreConfig('design/head/title_suffix');
        $suffix = htmlspecialchars(html_entity_decode(trim($suffix), ENT_QUOTES, 'UTF-8'));
        if ($suffix){
            $title = substr($title, 0, -1-strlen($suffix));
        }
        $descr = $head->getDescription();
        $kw = $head->getKeywords();

        $titleSeparator = Mage::getStoreConfig('amshopby/general/title_separator');
        $descrSeparator = Mage::getStoreConfig('amshopby/general/descr_separator');
        $kwSeparator = ',';

        if ($page->getUseCat()){
            $title = $title . $titleSeparator . $page->getMetaTitle();
            $descr = $descr . $descrSeparator . $page->getMetaDescr();
            $kw = $page->getMetaKw() . $kwSeparator . $kw;
        }
        else {
            $title = $page->getMetaTitle();
            $descr = $page->getMetaDescr();
            $kw = $page->getMetaKw();
        }

        $head->setTitle($this->trim($title));
        $head->setDescription($this->trim($descr));
        $head->setKeywords($this->trim($kw));

        // in-page description
        $page->setShowOnList(true);
        $this->options = array($page);

        return true;

    }

    protected function _prepareLayout()
    {
        if ($this->_isPageHandled()){
        	$this->handleExtraAttributes();
			return parent::_prepareLayout();
        }

        $hasCanonical = !Mage::helper('amshopby')->isVersionLessThan(1, 4);
        if ($hasCanonical){
            $url = Mage::getSingleton('catalog/layer')->getCurrentCategory()->getUrl();

            $head = $this->getLayout()->getBlock('head');
            //remove canonical URL for the categories starting from CE 1.4.x
            $head->removeItem('link_rel', $url);

            $isShopby = in_array(Mage::app()->getRequest()->getModuleName(), array(Mage::getStoreConfig('amshopby/seo/key'), 'amshopby'));
            if ($isShopby){
                $url = '';
            }
            $head->addLinkRel('canonical', Mage::helper('amshopby/url')->getCanonicalUrl($url));
        }

        $robotsIndex  = 'index';
        $robotsFollow = 'follow';
        
       
        $filters = Mage::getResourceModel('amshopby/filter_collection')
                ->addTitles()
                ->setOrder('position');
        $hash = array();
        
        foreach ($filters as $f){
            $code = $f->getAttributeCode();
            $vals = Mage::helper('amshopby')->getRequestValues($code);
            if ($vals){
                foreach($vals as $v){
                    $hash[$v] = $f->getShowOnList();
                }
                if ($f->getSeoNofollow()){
                    $robotsFollow = 'nofollow';
                }
                if ($f->getSeoNoindex()){
                    $robotsIndex = 'noindex';
                }
            }
        }

        $priceVals = Mage::app()->getRequest()->getParam('price');
        if ($priceVals) {
            if (Mage::helper('amshopby')->getSeoPriceNofollow()){
                $robotsFollow = 'nofollow';
            }
            if (Mage::helper('amshopby')->getSeoPriceNoindex()){
                $robotsIndex = 'noindex';
            }
        }
        
        /*
         * Check Category Settings
         */
        $catNoIndex = Mage::getStoreConfig('amshopby/seo/cat_noindex_nofollow');
        if ($catNoIndex != '') {
        	$categoriesIds = array_flip(explode(",", $catNoIndex));
        	if (isset($categoriesIds[Mage::getSingleton('catalog/layer')->getCurrentCategory()->getId()])) {
        		$robotsIndex  = 'noindex';
        		$robotsFollow = 'nofollow';
        	}	 
        }
        
        $this->handleExtraAttributes();        

        $head = $this->getLayout()->getBlock('head');
        if ($head){
            if ('noindex' == $robotsIndex || 'nofollow' == $robotsFollow){
                $head->setRobots($robotsIndex .', '. $robotsFollow);
            }
        }

        if (!$hash){
            return parent::_prepareLayout();
        }

        $options = Mage::getResourceModel('amshopby/value_collection')
            ->addFieldToFilter('option_id', array('in' => array_keys($hash)))
            ->load();

        $cnt = $options->count();
        if (!$cnt){
            return parent::_prepareLayout();
        }

        //some of the options value have wrong value;
        if ($cnt && $cnt < count($hash)){
            return parent::_prepareLayout();
            // or make 404 ?
        }

        // sort options by attribute ids and add "show_on_list" property
        foreach ($options as $opt){
            $id = $opt->getOptionId();
            
            $opt->setShowOnList($hash[$id]);
            $hash[$id] = clone $opt;
        }

        // unset "fake"  options (not object)
        foreach ($hash as $id => $opt){
            if (!is_object($opt)){
                unset($hash[$id]);
            }
        }
        if (!$hash){
            return parent::_prepareLayout();
        }

        if ($head){
            $title = $head->getTitle();
            // trim prefix if any
            $prefix = Mage::getStoreConfig('design/head/title_prefix');
            $prefix = htmlspecialchars(html_entity_decode(trim($prefix), ENT_QUOTES, 'UTF-8'));
            if ($prefix){
                $title = substr($title, strlen($prefix));
            }
            $suffix = Mage::getStoreConfig('design/head/title_suffix');
            $suffix = htmlspecialchars(html_entity_decode(trim($suffix), ENT_QUOTES, 'UTF-8'));
            if ($suffix){
                $title = substr($title, 0, -1-strlen($suffix));
            }

            $descr = $head->getDescription();
          
            $titleSeparator = Mage::getStoreConfig('amshopby/general/title_separator');
            $descrSeparator = Mage::getStoreConfig('amshopby/general/descr_separator');

            $kwSeparator = ',';
            $kw = '';
            $brandBread = '';

            $query = Mage::app()->getRequest()->getQuery();
            foreach ($hash as $opt){
            	if (isset($query[Mage::getStoreConfig('amshopby/brands/attr')])) {
        			if ($opt->getOptionId() == $query[Mage::getStoreConfig('amshopby/brands/attr')]) {
        				//$breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
						//$breadcrumbs->addCrumb('amshopby-brand', array('label' => $opt->getTitle(), 'title' => $opt->getTitle()));
        			} 
		        }
            	
                if ($opt->getMetaTitle())
                    $title .= $titleSeparator . $opt->getMetaTitle();

                if ($opt->getMetaDescr())
                    $descr .= $descrSeparator . $opt->getMetaDescr();

                if ($opt->getMetaKw())
                    $kw .= $opt->getMetaKw() . $kwSeparator;
            }

            $kw = $kw . $head->getKeywords();

            $head->setTitle($this->trim($title));
            $head->setDescription($this->trim($descr));
            $head->setKeywords($this->trim($kw));
        }
        $this->options = $hash;

        return parent::_prepareLayout();
    }

    public function getOptions()
    {
        $res = array();
        foreach ($this->options as $opt){
            if (!$opt->getShowOnList()){
                continue;
            }

            $item = array();
            $item['title'] = $this->htmlEscape($opt->getTitle());
            $item['descr'] = $opt->getDescr();
            $item['cms_block'] = '';

            $blockName = $opt->getCmsBlock();
            if ($blockName) {
                $item['cms_block'] = $this->getLayout()
                    ->createBlock('cms/block')
                    ->setBlockId($blockName)
                    ->toHtml();
            }

            $item['image'] = '';
            if ($opt->getImgBig()){
                $item['image'] = Mage::getBaseUrl('media') . '/amshopby/' . $opt->getImgBig();
            }
            $res[] = $item;
        }
        return $res;
    }
    
/**
     * Handle price in urls.
     * If it noindex or nofollow tag is enabled - modify head tag
     */
    public function handleExtraAttributes()
    {
    	$head = $this->getLayout()->getBlock('head');
    	
        if ($head){
        	
        	$index = 'index';
        	$follow = 'follow';
        	
        	/*
        	 * Prev Next
        	 */
        	
        	if (Mage::getStoreConfig('amshopby/seo/prev_next')) {
        		
        		$tool = $this->getLayout()->getBlock('product_list_toolbar_pager');
        		if ($tool) {
	        		$tool->setCollection(Mage::getSingleton('catalog/layer')->getProductCollection());
	        		
				    /*
				     * Get Current Url without page
				     */
				    $currenUrl = Mage::helper('amshopby/url')->getFullUrl();
			    
			        if ($tool->getLastPageNum() > 1) {
			            if (!$tool->isFirstPage()) {
			                $url = $tool->getPreviousPageUrl();
							preg_match('/[?,&,;]p=(\d+)/', $url, $matches);
			                    
			                if ($tool->getCurrentPage() == 2) {
			                    $prevUrl = preg_replace('/&p=(\d+)/', '', $currenUrl);
			                    $prevUrl = preg_replace('/\?p=(\d+)/', '', $prevUrl);
			                }
			                else {
			                	if (isset($matches[1])) {
									$prevUrl = preg_replace('/p=(\d+)/', 'p=' . $matches[1], $currenUrl);
			                    }
			                }
			                $head->addLinkRel('prev', $prevUrl);
			            }
			            
			            if (!$tool->isLastPage()) {
			            	$url = $tool->getNextPageUrl();
							preg_match('/[?,&,;]p=(\d+)/', $url, $matches);								
			            	if (isset($matches[1])) {
                                preg_match('/p=(\d+)/', $currenUrl, $matches2);								
                                if (count($matches2) > 0) {
                                    $nextUrl = preg_replace('/p=(\d+)/', 'p=' . $matches[1], $currenUrl);
			                   	} else {
                                    if (strpos($currenUrl, '?') === false) {
                                        $nextUrl = $currenUrl . '?p=' . $matches[1];
                                    } else {
                                        $nextUrl = $currenUrl . '&p=' . $matches[1];
                                    }
                                    
			                   	}
			                }
			            	$head->addLinkRel('next', $nextUrl);
			            }
			        }
        		}
        	}
        	
        	
        	/*
        	 * Set only if price is in request
        	 */
        	if (Mage::app()->getRequest()->getParam('price')) {
	        	$robotsIndex = Mage::getStoreConfig('amshopby/general/price_tag_noindex');
	        	$robotsFollow = Mage::getStoreConfig('amshopby/general/price_tag_nofollow');
	        	
	        	if ($robotsIndex) {
	        		$index = 'noindex';
	        	}
	        	
	            if ($robotsFollow) {
	        		$follow = 'nofollow';
	        	}
	        	
	            $head->setRobots($index .', '. $follow);
        	}
        }
    }

}