<?php
 
class Cybertech_Contact_VanzariController extends Mage_Core_Controller_Front_Action
{
	const MAIL_ADDRESS_SALES = 'vanzari@printcard.ro';
	const MAIL_NAME_SALES = 'Vanzari si Consultanta PrintCard';
	const MAIL_SUBJECT_SALES = 'Formular Vanzari si Consultanta - printcard.ro';
	
    public function indexAction()
    {
        //Fetch submited params
    	$params = $this->getRequest()->getParams();

        
        
        if($params['sku']) {
            
            $product = Mage::getModel('catalog/product')->loadByAttribute('sku',$params['sku']);
            
            
            if($product) {
                $productName = $product->getName();
                $productUrl = $product->getProductUrl();
            }
        }
        
        //Get current layout state
        $this->loadLayout();  
 
        $block = $this->getLayout()->createBlock(
            'Mage_Core_Block_Template',
            'cybertech.contact_form_vanzari',
            array(
                'template' => 'contact-custom/contact-form-vanzari.phtml'
            )
        );
 
        // pass data to view
        if($params['sku'])
            $block->setData('sku',$params['sku']);
        if($productName)
            $block->setData('productName',$productName);
        if($productUrl)
            $block->setData('productUrl',$productUrl);
        
        $this->getLayout()->getBlock('content')->append($block);
        //$this->getLayout()->getBlock('right')->insert($block, 'catalog.compare.sidebar', true);
 
        $this->_initLayoutMessages('core/session');
 
        $this->renderLayout();
    }
 
    public function sendemailAction()
    {
        //Fetch submited params
        $params = $this->getRequest()->getParams();
        
        if($params['sku']) {
            $product = Mage::getModel('catalog/product')->loadByAttribute('sku',$params['sku']);
            if(!$product) {
                throw new Exception("Product not found...");
            }
            else {
                $productUrl = $product->getProductUrl();
                $productName = $product->getName();
            }
            
        }
        
        $messageContent = '
        	<table>
                <tr>
        			<td width="150">SKU</td>
        			<td>'.$params['sku'].'</td>
        		</tr>
        		<tr>
        			<td>Product Name</td>
        			<td>'.$productName.'</td>
        		</tr>
        		<tr>
        			<td>Product URL</td>
        			<td>'.$productUrl.'</a></td>
        		</tr>
        		<tr>
        			<td width="150">Nume</td>
        			<td>'.$params['name'].'</td>
        		</tr>
        		<tr>
        			<td>Firma</td>
        			<td>'.$params['company'].'</td>
        		</tr>
        		<tr>
        			<td>Telefon</td>
        			<td>'.$params['phone'].'</td>
        		</tr>
        		<tr>
        			<td>E-mail</td>
        			<td>'.$params['email'].'</td>
        		</tr>
        		<tr>
        			<td>&nbsp;</td>
        			<td>&nbsp;</td>
        		</tr>
        		<tr>
        			<td>Tip solicitare</td>
        			<td>"'.$params['request'].'"</td>
        		</tr>
        		<tr>
        			<td>&nbsp;</td>
        			<td>&nbsp;</td>
        		</tr>
        		<tr>
        			<td colspan="2">Descriere solicitare:</td>
        		</tr>
        		<tr>
        			<td colspan="2">'.$params['message'].'</td>
        		</tr>
        	</table>
        ';
        
 
        $mail = new Zend_Mail();
        $mail->setBodyHtml($messageContent);
        $mail->setFrom($params['email'], $params['name']);
        $mail->addTo(self::MAIL_ADDRESS_SALES, self::MAIL_NAME_SALES);
        //$mail->addTo('lucian.nunu@cybertech.ro', self::MAIL_NAME_SALES);
        $mail->setSubject(self::MAIL_SUBJECT_SALES);
        try {
            $mail->send();
            Mage::getSingleton('core/session')->addSuccess('Solicitarea Dvs. a fost trimisa. Va vom contacta in cel mai scurt timp posibil. Va multumim!');
        }
        catch(Exception $ex) {
            Mage::getSingleton('core/session')->addError('E-mailul nu a putut fi trimis. Va rugam trimiteti un mail pe adresa '.self::MAIL_ADDRESS_SALES.'. Va multumim pentru intelegere!');
 
        }
 
        //Redirect to thank you page with newsletter subscribe proposal
        $this->_redirect('contact/thank-you/');
        //$this->_redirect('contact/vanzari/');
    }
}
 
