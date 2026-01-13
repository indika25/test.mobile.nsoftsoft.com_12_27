<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Grn_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        
    }

    public function loadsupplierjson($query) {
        $q = $this->db->select('SupCode AS id,SupName AS text')->from('supplier')->like('SupName', $query, 'after')->get()->result();
        return json_encode($q);
    }

    public function loadproductjson($query,$sup,$supCode,$pLevel) {


        if($sup!=0){
            $query1 =$this->db->select('product.ProductCode,product.Prd_Description,pricestock.Price,pricestock.Stock,department.Discount')
                    ->from('product')
                    //->join('productprice', 'productprice.ProductCode = product.ProductCode', 'INNER')
                    ->join(' pricestock', 'pricestock.PSCode = product.ProductCode', 'INNER')
                    ->join('department', 'department.DepCode = product.DepCode', 'INNER')
                    // ->join('productserialemistock','productserialemistock.ProductCode = product.ProductCode','LEFT')
                    // ->join('productserialstock','productserialstock.ProductCode = product.ProductCode','LEFT')
                    // ->join('productimeistock','productimeistock.ProductCode = product.ProductCode','LEFT')
                    ->where('product.Prd_Supplier', $supCode)
                    ->where('product.Prd_IsActive', 1)
                    ->where('pricestock.PSPriceLevel', $pLevel)
                    ->like("CONCAT(' ',product.ProductCode,product.Prd_Description,product.BarCode)", $query ,'left')
                    ->where('pricestock.Stock !=', 0)
                    ->limit(50)->get();
     
        }else{
          
            $query1 =$this->db->select('product.ProductCode,product.Prd_Description,pricestock.Price,pricestock.Stock,department.Discount')
                    ->from('product')
                    //->join('productprice', 'productprice.ProductCode = product.ProductCode', 'INNER')
                    ->join(' pricestock', 'pricestock.PSCode = product.ProductCode', 'INNER')
                    ->join('department', 'department.DepCode = product.DepCode', 'INNER')
                    //   ->join('productserialemistock','productserialemistock.ProductCode = product.ProductCode','LEFT')
                    // ->join('productserialstock','productserialstock.ProductCode = product.ProductCode','LEFT')
                    // ->join('productimeistock','productimeistock.ProductCode = product.ProductCode','LEFT')
                    ->where('pricestock.PSPriceLevel', $pLevel)
                     ->where('product.Prd_IsActive', 1)
                    ->like("CONCAT(' ',product.ProductCode,product.Prd_Description,product.BarCode)", $query ,'left')
                    ->where('pricestock.Stock !=', 0)
                    ->limit(50)->get();
                 
        }

        if ($query1->num_rows() > 0) {
            foreach ($query1->result_array() as $row) {
                $new_row['label'] = htmlentities(stripslashes($row['Prd_Description']." = Rs.".$row['Price']));
                $new_row['value'] = htmlentities(stripslashes($row['ProductCode']));
                $new_row['price'] = htmlentities(stripslashes($row['Price']));
                $new_row['discount'] = htmlentities(stripslashes($row['Discount']));
                $row_set[] = $new_row; //build an array
            }
            echo json_encode($row_set); //format the array into json data
        }
       
    }

    public function loadwholesalepriceproductjson($query,$sup,$supCode,$pLevel) {
        if($sup!=0){
            $query1 =$this->db->select('product.ProductCode,product.Prd_Description,pricestock.Price,pricestock.Stock,pricestock.WholesalesPrice')
                    ->from('product')
                    //->join('productprice', 'productprice.ProductCode = product.ProductCode', 'INNER')
                    ->join(' pricestock', 'pricestock.PSCode = product.ProductCode', 'INNER')
                    ->where('product.Prd_Supplier', $supCode)
                     ->where('product.Prd_IsActive', 1)
                      ->where('pricestock.WholesalesPrice IS NOT NULL', null, false)
                    ->like("CONCAT(' ',product.ProductCode,product.Prd_Description,product.BarCode)", $query ,'left')
                    ->where('pricestock.Stock !=', 0)
                    ->limit(50)->get();
     
        }else{
            $query1 =$this->db->select('product.ProductCode,product.Prd_Description,pricestock.Price,pricestock.Stock,pricestock.WholesalesPrice')
                    ->from('product')
                    //->join('productprice', 'productprice.ProductCode = product.ProductCode', 'INNER')
                    ->join(' pricestock', 'pricestock.PSCode = product.ProductCode', 'INNER')
                      ->where('pricestock.WholesalesPrice IS NOT NULL', null, false)
                    ->like("CONCAT(' ',product.ProductCode,product.Prd_Description,product.BarCode)", $query ,'left')
                    ->where('pricestock.Stock !=', 0)
                     ->where('product.Prd_IsActive', 1)
                    ->limit(50)->get();
        }

        if ($query1->num_rows() > 0) {
            foreach ($query1->result_array() as $row) {
                $new_row['label'] = htmlentities(stripslashes($row['Prd_Description']." = Rs.".$row['Price']." = WP.".$row['WholesalesPrice']));
                $new_row['value'] = htmlentities(stripslashes($row['ProductCode']));
                $new_row['price'] = htmlentities(stripslashes($row['Price']));
                $new_row['Wprice'] = htmlentities(stripslashes($row['WholesalesPrice']));
                $row_set[] = $new_row; //build an array
            }
            echo json_encode($row_set); //format the array into json data
        }
       
    }

     public function loadproductjsonGrn($query,$sup,$supCode) {
        if($sup!=0){
            $query1 =$this->db->select('product.ProductCode,product.Prd_Description,productprice.ProductPrice,productprice.PL_No,productcondition.DiscountLimit')
                    ->from('product')
                    ->join('productprice', 'productprice.ProductCode = product.ProductCode', 'INNER')
                    ->join('productcondition', 'productcondition.ProductCode = product.ProductCode', 'INNER')
                    ->where('product.Prd_Supplier', $supCode)
                    ->where('productprice.PL_No', 1)
                    ->where('product.Prd_IsActive',1)
                    ->like("CONCAT(' ',product.ProductCode,product.Prd_Description,product.BarCode)", $query ,'left')
                    ->get();
     
        }else{
            $query1 =$this->db->select('product.ProductCode,product.Prd_Description,productprice.ProductPrice,productprice.PL_No,productcondition.DiscountLimit')
                    ->from('product')
                    ->join('productprice', 'productprice.ProductCode = product.ProductCode', 'INNER')
                    ->join('productcondition', 'productcondition.ProductCode = product.ProductCode', 'INNER')
                    ->where('productprice.PL_No', 1)
                    ->where('product.Prd_IsActive',1)
                    ->like("CONCAT(' ',product.ProductCode,product.Prd_Description,product.BarCode)", $query ,'left')
                    ->get();
        }

        if ($query1->num_rows() > 0) {
            foreach ($query1->result_array() as $row) {
                $new_row['label'] = htmlentities(stripslashes($row['Prd_Description']." = Rs.".$row['ProductPrice']));
                $new_row['value'] = htmlentities(stripslashes($row['ProductCode']));
                $row_set[] = $new_row; //build an array
            }
            echo json_encode($row_set); //format the array into json data
        }
       
    }

      public function loadproductjsonjob($query,$sup,$supCode,$pLevel) {
        
           $query1 =$this->db->select('product.ProductCode,product.Prd_Description,pricestock.Price,pricestock.Stock')
                    ->from('product')
                    ->join('productprice', 'productprice.ProductCode = product.ProductCode', 'INNER')
                    ->join(' pricestock', 'pricestock.PSCode = product.ProductCode', 'INNER')
                    ->where('pricestock.PSPriceLevel', $pLevel)
                     ->where('product.Prd_IsActive', 1)
                    ->like("CONCAT(' ',product.ProductCode,product.Prd_Description,product.BarCode)", $query ,'left')
                    ->where('pricestock.Stock !=', 0)
                    ->limit(50)->get();
            // echo var_dump($query1);die;
        if ($query1->num_rows() > 0) {
            foreach ($query1->result_array() as $row) {
                $new_row['label'] = htmlentities(stripslashes($row['Prd_Description']." = Rs.".$row['Price']));
                $new_row['value'] = htmlentities(stripslashes($row['ProductCode']));
                $new_row['price'] = htmlentities(stripslashes($row['Price']));
                $row_set[] = $new_row; //build an array
            }
            echo json_encode($row_set); //format the array into json data
        }
       
    }


    public function loadpricelevel() {
        return $this->db->select()->from('pricelevel')->where('IsActive', 1)->get()->result();
    }
    public function loadlocations() {
        return $this->db->select()->from('location')->get()->result();
    }
    
    public function saveGrn($grnHed,$post,$grnNo,$totalDisPrecent,$grnCredit,$maxSerialQty) {   
        $product_codeArr = json_decode($post['product_code']);
        $unitArr = json_decode($post['unit_type']);
        $freeQtyArr = json_decode($post['freeQty']);
        $serial_noArr = json_decode($post['serial_no']);
        $qtyArr = json_decode($post['qty']);
        $sell_priceArr = json_decode($post['unit_price']);
        $cost_priceArr = json_decode($post['cost_price']);
        $pro_discountArr = json_decode($post['pro_discount']);
        $pro_discount_precentArr = json_decode($post['discount_precent']);
        $caseCostArr = json_decode($post['case_cost']);
        $upcArr = json_decode($post['upc']);
        $total_netArr = json_decode($post['total_net']);
        $price_levelArr = json_decode($post['price_level']);
        $totalAmountArr = json_decode($post['pro_total']);
        $isSerialArr = json_decode($post['isSerial']);
        $branchCostArr = json_decode($post['sendBranch_cost_price']);
        $sendwholesales_priceArr = json_decode($post['sendwholesales_price']);
        $sendisemiNo_Arr = json_decode($_POST['sendisemiNo']);
        $sendemiNo_Arr = json_decode($_POST['sendemiNo']);
        // var_dump($sendemiNo_Arr);die;
        $location = $post['location'];
        $isRawMat =0;

        $lastSerialNo = null;
        foreach (array_reverse($serial_noArr) as $val) {
            if (!empty($val)) {
                $lastSerialNo = $val;
                break;
            }
        }
       
        // echo var_dump($product_codeArr);die;
        //        $this->db->trans_begin();
        $this->db->trans_start();
        for ($i = 0; $i < count($product_codeArr); $i++) {
            $totalGrnQty = ($qtyArr[$i]+$freeQtyArr[$i]);
            $qtyPrice = $total_netArr[$i]/$qtyArr[$i];
            $grnDtl = array(
                'AppNo' => '1',
                'GRN_No' => $grnNo,
                'GRN_PONo' => '',
                'GRN_Date' => $post['grnDate'],
                'GRN_LineNo' => $i + 1,
                'GRN_Product' => $product_codeArr[$i],
                'GRN_UPC' => $upcArr[$i],
                'GRN_UPCType' => $unitArr[$i],
                'GRN_Qty' => $qtyArr[$i],
                'GRN_TotalQty' => $totalGrnQty,
                'GRN_FreeQty' => $freeQtyArr[$i],
                'GRN_OrderQty' => 0,
                'GRN_BalanceQty' => 0,
                'GRN_ReturnQty' => 0,
                'GRN_CaseCost' => $caseCostArr[$i],
                'GRN_UnitCost' => $cost_priceArr[$i],
                'GRN_QtyPrice' => $qtyPrice,
                'GRN_PriceLevel' => $price_levelArr[$i],
                'GRN_Selling' => $sell_priceArr[$i],
                'GRN_DisAmount' => $pro_discountArr[$i],
                'GRN_DisPersantage' => $pro_discount_precentArr[$i],
                'GRN_Amount' => $totalAmountArr[$i],
                'GRN_NetAmount' => $total_netArr[$i],
                'IsSerial' => isset($isSerialArr[$i]) && $isSerialArr[$i] != '' ? $isSerialArr[$i] : '0',
                'SerialNo' => isset($serial_noArr[$i]) && $serial_noArr[$i] != '' ? $serial_noArr[$i] : '0',
                'CostCode' => $sell_priceArr[$i],
                'BranchCostPrice' => $branchCostArr[$i],
                'WholesalesPrice' => isset($sendwholesales_priceArr[$i]) && $sendwholesales_priceArr[$i] != '' ? $sendwholesales_priceArr[$i] : '0',
                'IsEmiNo' => isset($sendisemiNo_Arr[$i]) ? $sendisemiNo_Arr[$i] : 0,
                'EmiNo' => isset($sendemiNo_Arr[$i]) ? $sendemiNo_Arr[$i] : 0,
            );
             
                // $this->db->insert('goodsreceivenotedtl', $grnDtl);

        $this->db->insert('goodsreceivenotedtl', $grnDtl);
        $err = $this->db->error();
        if (!empty($err['code'])) {
            $this->db->trans_rollback();
            log_message('error', 'GRN insert error at line ' . ($i+1) . ': ' . print_r($err, true));
            return ['status' => false, 'db_error' => $err, 'step' => 'insert_goodsreceivenotedtl', 'line' => $i+1];
        }
               
                //$isRawMat = $this->db->select('isRawMaterial')->from('productcondition')->where(array('ProductCode'=> $product_codeArr[$i]))->get()->row()->isRawMaterial;
                //echo var_dump($isRawMat);     
                //update serial stock
            //   if( $isRawMat==0){
                $ps = $this->db->select('ProductCode')->from('productserialstock')->where(array('ProductCode'=> $product_codeArr[$i],'SerialNo'=>$serial_noArr[$i],'Location'=>$location))->get();
                
                if($ps->num_rows()>0){
                    $isPro = $this->db->select('InvProductCode')->from('invoicedtl')->where(array('InvProductCode'=> $product_codeArr[$i],'InvSerialNo'=>$serial_noArr[$i],'InvLocation'=>$location))->get();
                   // echo var_dump('isPro' . $isPro);die;
                    if($isPro->num_rows()==0){
                         //echo var_dump('ture' . $isPro);die;
                        $this->db->update('productserialstock',array('Quantity'=>$qtyArr[$i]),array('ProductCode'=> $product_codeArr[$i],'SerialNo'=>$serial_noArr[$i],'Location'=> $location));
                        //$this->db->insert('productiemistock', array('ProductCode'=> $product_codeArr[$i],'Location'=> $location,'EmiNo'=>$sendemiNo_Arr[$i],'Quantity'=>$qtyArr[$i],'GrnNo'=>$grnNo));
                    $err = $this->db->error();
                if (!empty($err['code'])) {
                    $this->db->trans_rollback();
                    log_message('error', 'productserialstock update error: ' . print_r($err, true));
                    return ['status' => false, 'db_error' => $err, 'step' => 'update_productserialstock'];
                }
                    }
                }else{
                   
                    if($isSerialArr[$i]==1 && $sendisemiNo_Arr[$i] ==0){
                         // var_dump('isSerialArr' . $isSerialArr[$i]);die;
                        $this->db->insert('productserialstock', array('ProductCode'=> $product_codeArr[$i],'Location'=> $location,'SerialNo'=>$serial_noArr[$i],'Quantity'=>$qtyArr[$i],'GrnNo'=>$grnNo));
                    }
                    else if($isSerialArr[$i]==0 && $sendisemiNo_Arr[$i] ==1){
                    //echo var_dump('$sendisemiNo_Arr[$i]' . '-' . $sendisemiNo_Arr[$i] . '-'. $product_codeArr[$i] . '-' .$location . '-' . $sendemiNo_Arr[$i] . '-' . $qtyArr[$i] . '-' . $grnNo);die;
                    $this->db->insert('productimeistock', 
                    array('ProductCode'=> $product_codeArr[$i],
                    'Location'=> $location,
                    'EmiNo'=>$sendemiNo_Arr[$i],
                    'Quantity'=>$qtyArr[$i],
                    'GrnNo'=>$grnNo,
                    'Price'=>$sell_priceArr[$i],
                    'UnitCost'=>$cost_priceArr[$i]                   
                      ));
                    
                }else if ($isSerialArr[$i]==1 && $sendisemiNo_Arr[$i] ==1){
                         //echo var_dump('$sendaerialisemiNo_Arr[$i]' . $sendisemiNo_Arr[$i]);die;
                         $this->db->insert('productserialemistock', array('ProductCode'=> $product_codeArr[$i],'Location'=> $location,'SerialNo'=>$serial_noArr[$i],'EmiNo'=>$sendemiNo_Arr[$i],'Quantity'=>$qtyArr[$i],'GrnNo'=>$grnNo));
                    }

                     $err = $this->db->error();
            if (!empty($err['code'])) {
                $this->db->trans_rollback();
                log_message('error', 'product stock insert error: ' . print_r($err, true));
                return ['status' => false, 'db_error' => $err, 'step' => 'insert_productstock'];
            }
                }
            //   }
            
        
            //update product price
            $pp = $this->db->select('ProductCode')->from('productprice')->where(array('ProductCode'=> $product_codeArr[$i],'PL_No'=>$price_levelArr[$i]))->get();
            if($pp->num_rows()>0){
                $this->db->update('productprice',array('ProductPrice'=>$sell_priceArr[$i]),array('ProductCode'=> $product_codeArr[$i],'PL_No'=>$price_levelArr[$i]));
            }else{
                $this->db->insert('productprice', array('ProductCode'=> $product_codeArr[$i],'PL_No'=> $price_levelArr[$i],'ProductPrice'=>$sell_priceArr[$i]));
            }
        $err = $this->db->error();
        if (!empty($err['code'])) {
            $this->db->trans_rollback();
            log_message('error', 'productprice upsert error: ' . print_r($err, true));
            return ['status' => false, 'db_error' => $err, 'step' => 'productprice_upsert'];
        }
             //update wholesales price

            if($sendwholesales_priceArr[$i] !==0){
                 $this->db->update('productprice',array('ProductPrice'=>$sendwholesales_priceArr[$i]),array('ProductCode'=> $product_codeArr[$i],'PL_No'=>2));
                   $this->db->update('pricestock',array('WholesalesPrice'=>$sendwholesales_priceArr[$i]),array('PSCode'=> $product_codeArr[$i],'PSPriceLevel'=>$price_levelArr[$i],'Price'=> $sell_priceArr[$i],));
                $err = $this->db->error();
            if (!empty($err['code'])) {
                $this->db->trans_rollback();
                log_message('error', 'wholesale price update error: ' . print_r($err, true));
                return ['status' => false, 'db_error' => $err, 'step' => 'wholesale_update'];
            }
            }
            
            //update product cost
            $isUpdate=0;
            $isCostUpdate = $this->db->select('Value')->from('systemoptions')->where('ID', 'M001')->get();
            foreach ($isCostUpdate->result_array() as $row) {
                $isUpdate = $row['Value'];
            } 
            
            if($isUpdate==1){
                if($totalDisPrecent>0){
                    $cost = $qtyPrice - ($qtyPrice*$totalDisPrecent/100);
                    $this->db->update('product',array('Prd_CostPrice'=>$cost, 'branchCost'=>$branchCostArr[$i]),array('ProductCode'=> $product_codeArr[$i]));
                }else{
                    $this->db->update('product',array('Prd_CostPrice'=>$qtyPrice, 'branchCost'=>$branchCostArr[$i]),array('ProductCode'=> $product_codeArr[$i]));
                }
                  $err = $this->db->error();
            if (!empty($err['code'])) {
                $this->db->trans_rollback();
                log_message('error', 'product cost update error: ' . print_r($err, true));
                return ['status' => false, 'db_error' => $err, 'step' => 'update_product_cost'];
            }
            }
            
            $this->db->query("CALL SPT_UPDATE_PRICE_STOCK('$product_codeArr[$i]','$totalGrnQty','$price_levelArr[$i]','$cost_priceArr[$i]','$sell_priceArr[$i]','$location')");
             $err = $this->db->error();
        if (!empty($err['code'])) {
            $this->db->trans_rollback();
            log_message('error', 'SPT_UPDATE_PRICE_STOCK error: ' . print_r($err, true));
            return ['status' => false, 'db_error' => $err, 'step' => 'SPT_UPDATE_PRICE_STOCK'];
        }

           
            $this->db->update('pricestock',array('WholesalesPrice'=>$sendwholesales_priceArr[$i]),array('PSCode'=> $product_codeArr[$i],'PSPriceLevel'=>$price_levelArr[$i],'Price'=> $sell_priceArr[$i]));
             $err = $this->db->error();
            if (!empty($err['code'])) {
            $this->db->trans_rollback();
            log_message('error', 'pricestock update error: ' . print_r($err, true));
            return ['status' => false, 'db_error' => $err, 'step' => 'pricestock_update'];
        }
             //update product stock
           $this->db->query("CALL SPT_UPDATE_PRO_STOCK('$product_codeArr[$i]','$totalGrnQty',0,'$location')");
          $err = $this->db->error();
        if (!empty($err['code'])) {
            $this->db->trans_rollback();
            log_message('error', 'SPT_UPDATE_PRO_STOCK error: ' . print_r($err, true));
            return ['status' => false, 'db_error' => $err, 'step' => 'SPT_UPDATE_PRO_STOCK'];
        }
        
        }
        //}
        
        $supCode = $post['supcode'];
        $totalNet = $post['total_net_amount'];
        //update supplier outstanding
        if($supCode!=''){
        
            $this->db->query("CALL SPT_UPDATE_SUPOUTSTAND('$supCode','$totalNet',0)");
      $err = $this->db->error();
        if (!empty($err['code'])) {
            $this->db->trans_rollback();
            log_message('error', 'SPT_UPDATE_SUPOUTSTAND error: ' . print_r($err, true));
            return ['status' => false, 'db_error' => $err, 'step' => 'SPT_UPDATE_SUPOUTSTAND'];
        }
         }
        $this->db->insert('creditgrndetails', $grnCredit);
          $err = $this->db->error();
    if (!empty($err['code'])) {
        $this->db->trans_rollback();
        log_message('error', 'creditgrndetails insert error: ' . print_r($err, true));
        return ['status' => false, 'db_error' => $err, 'step' => 'insert_creditgrndetails'];
    }
        $this->db->insert('goodsreceivenotehed', $grnHed);
        $err = $this->db->error();
    if (!empty($err['code'])) {
        $this->db->trans_rollback();
        log_message('error', 'goodsreceivenotehed insert error: ' . print_r($err, true));
        return ['status' => false, 'db_error' => $err, 'step' => 'insert_goodsreceivenotehed'];
    }
        $this->update_max_code('Goods Received Note');
        if (!empty($lastSerialNo)) {
        $this->db->update('codegenerate',array('AutoNumber'=>($lastSerialNo)),array('FormName'=>('AutoSerial')));
     $err = $this->db->error();
        if (!empty($err['code'])) {
            $this->db->trans_rollback();
            log_message('error', 'codegenerate update error: ' . print_r($err, true));
            return ['status' => false, 'db_error' => $err, 'step' => 'update_codegenerate'];
        }    
    }
        $this->db->trans_complete();



       return $this->db->trans_status();
    }
    
    public function get_max_code($form)
    {
        $query = $this->db->select('*')->where('FormName',$form)->get('codegenerate');
        foreach ($query->result_array() as $row) {
           $code= $row['CodeLimit'];
           $input = $row['AutoNumber'];
           $string= $row['FormCode'];
           $code_len = $row['FCLength'];
           $item_ref = $string . str_pad(($input+1), $code_len, $code, STR_PAD_LEFT);
        } 
        return $item_ref;
    }
    
    public function get_autoNum($form)
    {
        $query = $this->db->select('*')->where('FormName',$form)->get('codegenerate');
        foreach ($query->result_array() as $row) {
           $code= $row['CodeLimit'];
           $input = $row['AutoNumber'];
           $string= $row['FormCode'];
           $code_len = $row['FCLength'];
           $item_ref = $string . str_pad(($input+1), $code_len, $code, STR_PAD_LEFT);
        } 
        return $input;
    }
    
    public function update_max_code($form)
    {
        $query = $this->db->select('*')->where('FormName',$form)->get('codegenerate');
        foreach ($query->result_array() as $row) {
           $input = $row['AutoNumber'];
        } 
        $this->db->update('codegenerate',array('AutoNumber'=>($input+1)),array('FormName'=>($form)));
    }
    
    public function get_data_by_where($table, $data) {
        $query = $this->db->get_where($table, $data)->row_array();
        return $query;
    }
    
    public function getActiveGrns($table, $q,$location) {
        $this->db->select('GRN_No');
        $this->db->like('GRN_No', $q)->where('GRN_IsCancel', 0)->where('GRN_Location', $location)->order_by('GRN_No', 'DESC');
        
        $query = $this->db->get($table);
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $new_row['label'] = htmlentities(stripslashes($row['GRN_No']));
                $new_row['value'] = htmlentities(stripslashes($row['GRN_No']));
                $row_set[] = $new_row; //build an array
            }
            echo json_encode($row_set); //format the array into json data
        }
    }

    public function loadGrnById($inv) {
        return $this->db->select('goodsreceivenotedtl.*,product.Prd_Description,goodsreceivenotehed.GRN_Amount AS GRN_totalAmount,goodsreceivenotehed.GRN_NetAmount AS totalNet, goodsreceivenotehed.GRN_DisAmount As TotalDiscount,goodsreceivenotehed.GRN_SupCode')->from('goodsreceivenotedtl')
                        ->where('goodsreceivenotedtl.GRN_No', $inv)
                        ->join('goodsreceivenotehed', 'goodsreceivenotehed.GRN_No = goodsreceivenotedtl.GRN_No')
                        ->join('product', 'product.ProductCode = goodsreceivenotedtl.GRN_Product')
                        ->get()->result();
    }
    
    public function cancelGrn($cancelNo,$location,$canDate,$grnNo,$remark,$user,$supplier) {
        $this->db->trans_start();
        $this->db->query("CALL SPT_CANCEL_GRN('$canDate','$grnNo','$user','$remark','$supplier','$cancelNo','$location')");
        $isRawMat =0;
        $query = $this->db->get_where('goodsreceivenotedtl',array('GRN_No'=>$grnNo));
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $product_codeArr=$row['GRN_Product'];
                $qtyArr=$row['GRN_Qty'];
                $price_levelArr=$row['GRN_PriceLevel'];
                $cost_priceArr=$row['GRN_UnitCost'];
                $sell_priceArr=$row['GRN_Selling'];
                $serial_noArr=$row['SerialNo'];
                $emi_noArr=$row['EmiNo'];
                $freeQtyArr=$row['GRN_FreeQty'];
                $isSeralArr=$row['IsSerial'];
                $isEmiArr=$row['IsEmiNo'];
                $isRawMat = $this->db->select('isRawMaterial')->from('productcondition')->where(array('ProductCode'=> $product_codeArr))->get()->row()->isRawMaterial;
                $this->db->query("CALL SPP_UPDATE_PRICE_STOCK('$product_codeArr','$qtyArr','$price_levelArr','$cost_priceArr','$sell_priceArr','$location','$serial_noArr','$freeQtyArr','0','0')");
                $this->db->update('product',array('Prd_CostPrice'=>$cost_priceArr),array('ProductCode'=> $product_codeArr));
               
               
              if ($isSeralArr == 1 && $isEmiArr == 0) {
    $this->db->delete('productserialstock', array(
        'ProductCode' => $product_codeArr,
        'Location' => $location,
        'SerialNo' => $serial_noArr
    ));
}
if ($isSeralArr == 0 && $isEmiArr == 1) {
    $this->db->delete('productimeistock', array(
        'ProductCode' => $product_codeArr,
        'Location' => $location,
        'EmiNo' => $emi_noArr
    ));
}
if ($isSeralArr == 1 && $isEmiArr == 1) {
    $this->db->delete('productserialemistock', array(
        'ProductCode' => $product_codeArr,
        'Location' => $location,
        'SerialNo' => $serial_noArr
    ));
}

            }
        }
        
        $this->update_max_code('CancelGRN');
        $this->db->trans_complete();
       return $this->db->trans_status();
    }
    
    public function loadstockAdjustment($route, $isall, $product = NULL)
{
    $this->db->distinct();
    $this->db->select('product.ProductCode,
                       product.Prd_Description,
                       product.Prd_CostPrice,
                       product.SubDepCode,
                       product.Prd_ROL,
                       product.Prd_ROQ,
                       subdepartment.Description,
                       location.location,
                       productstock.Stock,
                       product.Prd_SetAPrice,
                       supplier.SupName');
    $this->db->from('product');
    $this->db->join('supplier', 'supplier.SupCode = product.Prd_Supplier', 'LEFT');
    $this->db->join('productstock', 'productstock.ProductCode = product.ProductCode', 'LEFT');
    $this->db->join('subdepartment', 'subdepartment.SubDepCode = product.SubDepCode', 'INNER');
    // $this->db->join('productprice', 'productprice.ProductCode = product.ProductCode', 'LEFT');
    $this->db->join('subcategory', 'subcategory.SubCategoryCode = product.SubCategoryCode', 'LEFT');
    $this->db->join('location', 'location.location_id = productstock.Location', 'INNER');

    $this->db->where('product.Prd_IsActive', 1);


    if (!empty($route)) {
        $this->db->where_in('productstock.Location', $route);
    }

    if (!empty($product) && $isall == 0) {
        $this->db->where('product.ProductCode', $product);
    }

    $this->db->order_by('product.DepCode', 'ASC');
    $this->db->order_by('product.SubDepCode', 'ASC');
    $this->db->order_by('product.CategoryCode', 'ASC');
    $this->db->order_by('subcategory.Description', 'ASC');

    $result = $this->db->get()->result();

    $productCodes = array_map(function ($row) {
        return $row->ProductCode;
    }, $result);

 
    $serialStock = [];
    if (!empty($productCodes)) {
        $query1 = $this->db->where_in('ProductCode', $productCodes)
                           ->get('productserialstock')
                           ->result();
  
        foreach ($query1 as $row) {
            $serialStock[$row->ProductCode]['serials'][] = $row->SerialNo;
        }
    }

   
  
    $emiStock = [];
    if (!empty($productCodes)) {
        $query2 = $this->db->where_in('ProductCode', $productCodes)
                        // ->where('Quantity >', 0)
                           ->get('productimeistock')
                           ->result();

                        
        foreach ($query2 as $row) {
            $emiStock[$row->ProductCode]['emei'][] = $row->EmiNo;
        }
    }

    // Merge into final output
    $list = [];
    foreach ($result as $row) {

        $pcode = $row->ProductCode;

        $row->serial_list = isset($serialStock[$pcode]['serials'])
                            ? $serialStock[$pcode]['serials']
                            : [];

        $row->emei_list   = isset($emiStock[$pcode]['emei'])
                            ? $emiStock[$pcode]['emei']
                            : [];

        $list[$row->Description][] = $row;
    }

    return $list;
}


public function getEmeiStocktoProduct($emei, $productCode)
{
    return $this->db->select('Quantity,Price,UnitCost')
                    ->from('productimeistock')
                    ->where('EmiNo', $emei)
                    ->where('ProductCode', $productCode)
                    ->get()
                    ->row();
}


}