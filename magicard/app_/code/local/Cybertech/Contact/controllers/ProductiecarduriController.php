<?php
 
class Cybertech_Contact_ProductiecarduriController extends Mage_Core_Controller_Front_Action
{
	const MAIL_ADDRESS_CARD_PRODUCTION = 'vanzari@printcard.ro';
	const MAIL_NAME_CARD_PRODUCTION = 'Productie Carduri PrintCard';
	const MAIL_SUBJECT_CARD_PRODUCTION = 'Formular Productie Carduri - printcard.ro';
	
    public function indexAction()
    {
        //Get current layout state
        $this->loadLayout();  
 
        $block = $this->getLayout()->createBlock(
            'Mage_Core_Block_Template',
            'cybertech.contact_form_comenzi',
            array(
                'template' => 'contact-custom/contact-form-productie-carduri.phtml'
            )
        );
 
        $this->getLayout()->getBlock('content')->append($block);
        //$this->getLayout()->getBlock('right')->insert($block, 'catalog.compare.sidebar', true);
 
        $this->_initLayoutMessages('core/session');
 
        $this->renderLayout();
    }
 
    public function sendemailAction()
    {
        //Fetch submited params
        $params = $this->getRequest()->getParams();
 
        $messageContent = '
        	<table>
        		<tr>
        			<td width="150">Societate</td>
        			<td>'.$params['company'].'</td>
        		</tr>
        		<tr>
        			<td>Nume</td>
        			<td>'.$params['name'].'</td>
        		</tr>
        		<tr>
        			<td>Telefon contact</td>
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
        			<td>Tip card</td>
        			<td>'.$params['card_type'].'</td>
        		<tr>
        			<td>Cantitate</td>
        			<td>'.$params['qty'].'</td>
        		</tr>
        		<tr>
        			<td>Imprimare</td>
        			<td>'.$params['printing_type'].'</td>
        		</tr>
        ';

        $messageContent .= '
        		<tr>
        			<td>Cromatica</td>
        			<td>'.$params['printing_color'].'</td>
        		</tr>
        	';
        
        if($params['metal_covering'])
        	$messageContent .= '
        		<tr>
        			<td>'.$params['metal_covering'].'</td>
        			<td>Da</td>
        		</tr>
        	';
        if($params['uv_ink'])
        	$messageContent .= '
        		<tr>
        			<td>'.$params['uv_ink'].'</td>
        			<td>Da</td>
        		</tr>
        	';
        	
        if($params['magnetic_stripe'])
        	$messageContent .= '
        		<tr>
        			<td>Banda magnetica</td>
        			<td>'.$params['magnetic_stripe'].'</td>
        		</tr>
        	';
        	
        if($params['contactless_chip'])
        	$messageContent .= '
        		<tr>
        			<td>Chip proximitate</td>
        			<td>'.$params['contactless_chip'].'</td>
        		</tr>
        	';
        	
        if($params['contact_chip'])
        	$messageContent .= '
        		<tr>
        			<td>Contact Chip</td>
        			<td>'.$params['contact_chip'].'</td>
        		</tr>
        	';
        	
        if($params['signature_area'])
        	$messageContent .= '
        		<tr>
        			<td>'.$params['signature_area'].'</td>
        			<td>Da</td>
        		</tr>
        	';
        if($params['magnetic_encoding_type'])
        	$messageContent .= '
        		<tr>
        			<td>'.$params['magnetic_encoding_type'].'</td>
        			<td>Da</td>
        		</tr>
        	';
        if($params['rasure_area'])
        	$messageContent .= '
        		<tr>
        			<td>'.$params['rasure_area'].'</td>
        			<td>Da</td>
        		</tr>
        	';
        if($params['serialization'])
        	$messageContent .= '
        		<tr>
        			<td>'.$params['serialization'].'</td>
        			<td>Da</td>
        		</tr>
        	';
        if($params['bar_codes'])
        	$messageContent .= '
        		<tr>
        			<td>'.$params['bar_codes'].'</td>
        			<td>Da</td>
        		</tr>
        	';
        if($params['hologram'])
        	$messageContent .= '
        		<tr>
        			<td>'.$params['hologram'].'</td>
        			<td>Da</td>
        		</tr>
        	';
        if($params['embossing'])
        	$messageContent .= '
        		<tr>
        			<td>'.$params['embossing'].'</td>
        			<td>Da</td>
        		</tr>
        	';
        if($params['perforating'])
        	$messageContent .= '
	        	<tr>
		        	<td>'.$params['perforating'].'</td>
		        	<td>Da</td>
	        	</tr>
        	';
        if($params['presonalization'])
        	$messageContent .= '
	        	<tr>
		        	<td>'.$params['presonalization'].'</td>
		        	<td>Da</td>
	        	</tr>
        	';
        
        $messageContent .= '
        		<tr>
        			<td>Servicii design card</td>
        			<td>'.$params['design_services'].'</td>
        		</tr>
        		<tr>
        			<td colspan="2">Alte instructiuni:</td>
        		</tr>
        		<tr>
        			<td colspan="2">'.($params['message'] ? $params['message'] : '-').'</td>
        		</tr>
        	</table>
        ';

        
        $mail = new Zend_Mail();
        $mail->setBodyHtml($messageContent);
        $mail->setFrom($params['email'], $params['name']);
        // $mail->addTo(self::MAIL_ADDRESS_CARD_PRODUCTION, self::MAIL_NAME_CARD_PRODUCTION);
	$mail->addTo(self::MAIL_ADDRESS_CARD_PRODUCTION);
        $mail->setSubject(self::MAIL_SUBJECT_CARD_PRODUCTION);
        try {
            $mail->send();
            Mage::getSingleton('core/session')->addSuccess('Solicitarea Dvs. a fost trimisa. Va vom contacta in cel mai scurt timp posibil. Va multumim!');
        }
        catch(Exception $ex) {
            Mage::getSingleton('core/session')->addError('E-mailul nu a putut fi trimis. Va rugam trimiteti un mail pe adresa '.MAIL_ADDRESS_CARD_PRODUCTION.'. Va multumim pentru intelegere!');
 
        }
 
        //Redirect back to index action of (this) inchoo-simplecontact controller
        $this->_redirect('contact/productiecarduri/');
    }
}
 
