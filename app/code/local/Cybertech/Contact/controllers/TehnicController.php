<?phpclass Cybertech_Contact_TehnicController extends Mage_Core_Controller_Front_Action{	const MAIL_ADDRESS_ORDERS = 'suport@printcard.ro';        //const MAIL_ADDRESS_ORDERS = 'vishal14.patel@yahoo.com';	const MAIL_NAME_ORDERS = 'Printcard Systems - departament suport tehnic';	const MAIL_SUBJECT_ORDERS = 'Formular catre Departamentul tehnic - printcard.ro';    public function indexAction()    {        //Get current layout state        $this->loadLayout();          $this->getLayout()->getBlock('root')->setTemplate('page/2columns-left.phtml');        $this->getLayout()->getBlock('head')->setTitle($this->__('Departament Tehnic'));                $breadcrumbs = $this -> getLayout() -> getBlock("breadcrumbs");        $breadcrumbs -> addCrumb("home", array(                "label" => $this -> __("Home"),                 "title" => $this -> __("Home"),                 "link" => Mage::getBaseUrl()            )        );                $breadcrumbs -> addCrumb("contact",             array(                "label" => $this -> __("Contact"),                 "title" => $this -> __("Contact"),                "link" => Mage::getBaseUrl() . 'contact/'            )        );                $breadcrumbs -> addCrumb("departament-tehnic",             array(                "label" => $this -> __("Departament Tehnic"),                 "title" => $this -> __("Departament Tehnic")            )        );                $block = $this->getLayout()->createBlock(            'Mage_Core_Block_Template',            'cybertech.contact',            array(                'template' => 'contact-custom/contact.phtml'            )        );         $block = $this->getLayout()->createBlock(            'Mage_Core_Block_Template',            'cybertech.contact_form_tehnic',            array(                'template' => 'contact-custom/contact-form-tehnic.phtml'            )        );        $this->getLayout()->getBlock('content')->append($block);        //$this->getLayout()->getBlock('right')->insert($block, 'catalog.compare.sidebar', true);        $this->_initLayoutMessages('core/session');        $this->renderLayout();    }     public function sendemailAction()    {        //Fetch submited params        $params = $this->getRequest()->getParams();         $messageContent = '        	<table>        		<tr>        			<td width="150">Nume</td>        			<td>'.$params['name'].'</td>        		</tr>        		<tr>        			<td>Firma</td>        			<td>'.$params['company'].'</td>        		</tr>        		<tr>        			<td>Telefon</td>        			<td>'.$params['phone'].'</td>        		</tr>        		<tr>        			<td>E-mail</td>        			<td>'.$params['email'].'</td>        		</tr>        		<tr>        			<td>&nbsp;</td>        			<td>&nbsp;</td>        		</tr>        		<tr>        			<td>Model imprimanta</td>        			<td>'.$params['model_imprimanta'].'</td>        		</tr>                <tr>        			<td>Serie</td>        			<td>'.$params['serie'].'</td>        		</tr>        		<tr>        			<td>&nbsp;</td>        			<td>&nbsp;</td>        		</tr>        		<tr>        			<td colspan="2">Descriere solicitare:</td>        		</tr>        		<tr>        			<td colspan="2">'.$params['message'].'</td>        		</tr>        	</table>        ';                $mail = new Zend_Mail();        $mail->setBodyHtml($messageContent);                /*mail->setFrom('info@printcard.ro', 'Support Desk');                $customerSupportName = Mage::getStoreConfig('trans_email/ident_support/name');        $customerSupportEmail = Mage::getStoreConfig('trans_email/ident_support/email');        $customerSupportEmail = 'vishal14.patel@yahoo.com';        //echo $customerSupportEmail;die;        $mail->addTo($customerSupportEmail, $customerSupportName);        $mail->setSubject(self::MAIL_SUBJECT_ORDERS);*/                        $customerSupportName = Mage::getStoreConfig('trans_email/ident_support/name');        $customerSupportEmail = Mage::getStoreConfig('trans_email/ident_support/email');                $mail = Mage::getModel('core/email');        $mail->setToName($customerSupportName);        $mail->setToEmail($customerSupportEmail);        $mail->setBody($messageContent);        $mail->setSubject(self::MAIL_SUBJECT_ORDERS);        $mail->setFromEmail('info@printcard.ro');        $mail->setFromName("printcard.ro support");        $mail->setType('html');// YOu can use Html or text as Mail format        try {            $mail->send();            Mage::getSingleton('core/session')->addSuccess('Solicitarea Dvs. a fost trimisa. Va vom contacta in cel mai scurt timp posibil. Va multumim!');        }        catch(Exception $ex) {                        Mage::getSingleton('core/session')->addError('E-mailul nu a putut fi trimis. Va rugam trimiteti un mail pe adresa '.MAIL_ADDRESS_ORDERS.'. Va multumim pentru intelegere!');         }        //Redirect back to index action of (this) inchoo-simplecontact controller        $this->_redirect('contact/tehnic/');    }} 