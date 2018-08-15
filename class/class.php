<?PHP
class Curl {
    private $ch;
    private $cookie_path;
    private $agent;

    public function __construct($userId) {
        $this->cookie_path = dirname(realpath(basename($_SERVER['PHP_SELF']))).'/cookies/' . $userId . '.txt';
        $this->agent = "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.4) Gecko/20030624 Netscape/7.1 (ax)";
    }

    private function init() {
        $this->ch = curl_init();
    }

    private function close() {
        curl_close ($this->ch);
    }

    private function setOptions($submit_url) {
        $headers[] = "Accept: */*";
        $headers[] = "Connection: Keep-Alive";
        curl_setopt($this->ch, CURLOPT_URL, $submit_url);
        curl_setopt($this->ch, CURLOPT_USERAGENT, $this->agent); 
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);  
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER,  $headers);
        curl_setopt($this->ch, CURLOPT_COOKIEFILE, $this->cookie_path);         
        curl_setopt($this->ch, CURLOPT_COOKIEJAR, $this->cookie_path);
    }

    public function curl_cookie_set($submit_url) {
        $this->init();
        $this->setOptions($submit_url);
        curl_exec ($this->ch);
        echo curl_error($this->ch);
    }  

    public function get_form_fields($submit_url) {
        curl_setopt($this->ch, CURLOPT_URL, $submit_url);
        $result = curl_exec ($this->ch);
        echo curl_error($this->ch);
        return $this->getFormFields($result);
    }

    public function curl_post_request($referer, $submit_url, $data) {
        $post = http_build_query($data);
        curl_setopt($this->ch, CURLOPT_URL, $submit_url);
        curl_setopt($this->ch, CURLOPT_POST, 1);  
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $post);  
        curl_setopt($this->ch, CURLOPT_REFERER, $referer);
        $result =  curl_exec ($this->ch);
		// uncomment here to not load ebay page
        //$this->close();
        return $result;
    }    

     public function show_page($submit_url) {
        curl_setopt($this->ch, CURLOPT_URL, $submit_url);
        $result =  curl_exec ($this->ch);
        echo curl_error($this->ch);
        return $result;
    }
	
    private function getFormFields($data) {
        if (preg_match('/(<form name="SignInForm".*?<\/form>)/is', $data, $matches)) {
            $inputs = $this->getInputs($matches[1]);
            return $inputs;
        } else {
            die('Form not found.');
        }
    }

    private function getInputs($form) {
        $inputs = array();
        $elements = preg_match_all('/(<input[^>]+>)/is', $form, $matches);
        if ($elements > 0) {
            for($i = 0; $i < $elements; $i++) {
                $el = preg_replace('/\s{2,}/', ' ', $matches[1][$i]);
                if (preg_match('/name=(?:["\'])?([^"\'\s]*)/i', $el, $name)) {
                    $name  = $name[1];
                    $value = '';

                    if (preg_match('/value=(?:["\'])?([^"\'\s]*)/i', $el, $value)) {
                        $value = $value[1];
                    }
                    $inputs[$name] = $value;
                }
            }
        }
        return $inputs;
    }

    public function curl_clean() {
        if (file_exists($this->cookie_path)) { 
            unlink($this->cookie_path); 
        }
        if ($this->ch != '') { 
            curl_close ($this->ch);
        }
    }    
}
