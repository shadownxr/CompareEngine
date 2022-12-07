<?php

class CompareActionModuleFrontController extends ModuleFrontController {
    public function initContent()
    {
        parent::initContent();
        
        $lang_id = (int) Configuration::get('PS_LANG_DEFAULT');
        $basePrice = 0;
        $valueDifference = [0 => 0];

        $cookieProduct = json_decode($this->context->cookie->__get('CompareProducts'));
        foreach($cookieProduct as $key => $value) {
            $products[] = new Product($value, false, $lang_id);
            if($key == 0){
                $basePrice = $products[$key]->price;
                $valueDifference[$key] = 0;
            } else {
                $valueDifference[$key] = 100 - (($basePrice / $products[$key]->price) * 100);
            }
        }

        $this->context->smarty->assign([
            'array_product' => $products,
            'value_difference' => $valueDifference,
            'url' => $this->getCurrentURL()
        ]);

        $this->setTemplate("module:compare/views/templates/front/list.tpl");
    }

    public function postProcess()
    {
        $product_id = Tools::getValue('product-id');
        $action = Tools::getValue('action');
        $key = Tools::getValue('key');

        if(!empty($_POST)){
            if($action == 'add'){
                if(method_exists($this, 'addProductToCompare')){
                    call_user_func([$this, 'addProductToCompare'], $product_id);
                } 
            } else if($action == 'delete'){
                if(method_exists($this, 'deleteProductFromCompare')){
                    call_user_func([$this, 'deleteProductFromCompare'], $key);
                } 
            }
        }
    }

    private function addProductToCompare($product_id){
        if(!$this->context->cookie->__isset('CompareProducts')){
            $product_array = [0 => $product_id];
            $this->context->cookie->__set('CompareProducts',json_encode($product_array));
        } else {
            $product_array = json_decode($this->context->cookie->__get('CompareProducts'));
            $product_array[] = $product_id;
            $this->context->cookie->__set('CompareProducts',json_encode($product_array));
        }
    }
    
    private function deleteProductFromCompare($key){
        if($this->context->cookie->__isset('CompareProducts')){
            $product_array = json_decode($this->context->cookie->__get('CompareProducts'));
            array_splice($product_array, $key, 1);
            if(count($product_array) == 0){
                $this->context->cookie->__unset('CompareProducts');
            } else {
                $this->context->cookie->__set('CompareProducts',json_encode($product_array));
            }
        }
    }
} 

?>