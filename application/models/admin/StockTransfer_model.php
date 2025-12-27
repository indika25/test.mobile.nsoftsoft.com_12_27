<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class StockTransfer_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }


     public function loadproductjson($query,$tranferDate,$fromloc,$toloc,$prlevel) {

         
         $query1 =$this->db->select('product.ProductCode,product.Prd_Description,pricestock.Price,pricestock.PSLocation,pricestock.PSPriceLevel')
         ->from('product')
      
         ->join(' pricestock', ' pricestock.PSCode = product.ProductCode', 'INNER')
         
         ->where('pricestock.PSLocation', $fromloc)
         ->where('pricestock.PSPriceLevel',$prlevel)

         ->like("CONCAT(' ',product.ProductCode,product.Prd_Description,product.BarCode)", $query ,'left')
         ->limit(50)->get();
        // echo var_dump($query1);die;
        

        if ($query1->num_rows() > 0) {
            foreach ($query1->result_array() as $row) {
                $new_row['label'] = htmlentities(stripslashes($row['Prd_Description']." = Rs.".$row['Price']));
                $new_row['value'] = htmlentities(stripslashes($row['ProductCode']));
                $new_row['costPrice'] = htmlentities(stripslashes($row['Price']));
                $row_set[] = $new_row; 
            }
            echo json_encode($row_set); 
        }
       
    }


     public function loadproductbypcode($product) {
        return $this->db->select('product.*,productcondition.*,productprice.ProductPrice')->from('product')
                        ->where('product.ProductCode', $product)
                        // ->where('productprice.PL_No', $pl)
                        ->join('productcondition', 'productcondition.ProductCode = product.ProductCode')
                        ->join('productprice', 'productprice.ProductCode = product.ProductCode')
                        ->get()->row();
    }


     public function loadproductstockbyid($product,$fromloc)
    {
        return $this->db->select('Stock')
            ->from('productstock')->where('ProductCode', $product)->where('Location', $fromloc)
            ->get()->row();
    }

    public function loadproductstockbyprice($product,$fromloc,$costPrice,$pl)
    {
       
        if($pl ==1){

             return $this->db->select('Stock,Price,UnitCost')
            ->from('pricestock')->where('PSCode', $product)->where('PSLocation', $fromloc)->where('Price',$costPrice)
            ->get()->row();
        }else{
             return $this->db->select('Stock,WholesalesPrice AS Price,UnitCost')
            ->from('pricestock')->where('PSCode', $product)->where('PSLocation', $fromloc)->where('Price',$costPrice)
            ->get()->row();
        }
       
    }

    public function loadpricestockbyid($product, $location, $price, $pl)
        {
            
            $serialCheck = $this->db->select('pricestock.Stock AS Stock, pricestock.Price AS Price, pricestock.UnitCost AS UnitCost')
                ->from('productserialstock')
                ->join('pricestock','pricestock.PSCode = productserialstock.ProductCode','INNER')
                ->where('SerialNo', $product) 
                ->where('Location', $location)
                ->get()
                ->row();

            $serialEmiCheck = $this->db->select('pricestock.Stock AS Stock, pricestock.Price AS Price, pricestock.UnitCost AS UnitCost')
                ->from('productserialemistock')
                ->join('pricestock','pricestock.PSCode = productserialemistock.ProductCode','INNER')
                ->group_start() // Start OR condition group
                    ->where('productserialemistock.SerialNo', $product)
                    ->or_where('productserialemistock.EmiNo', $product)
                ->group_end()
                ->where('Location', $location)
                ->get()
                ->row();

            $EmiCheck = $this->db->select('pricestock.Stock AS Stock, pricestock.Price AS Price, pricestock.UnitCost AS UnitCost')
                ->from('productimeistock')
                ->join('pricestock','pricestock.PSCode = productimeistock.ProductCode','INNER')
                ->where('EmiNo', $product) 
                ->where('Location', $location)
                ->get()
                ->row();

            

            if ($serialCheck) {
            
                return $serialCheck;
            }else if($serialEmiCheck){
                return $serialEmiCheck;
            }else if($EmiCheck){
                return $EmiCheck;
            } else {
            
                return $this->db->select('Stock, Price, UnitCost')
                    ->from('pricestock')
                    ->where('PSCode', $product)
                    ->where('PSLocation', $location)
                    ->where('Price', $price)
                    ->where('PSPriceLevel', $pl)
                    ->get()
                    ->row();
            }
        }
    


    //   public function loadproductbyserialArrayByCode($product, $pl, $location) {
   
    //     $query2 = $this->db->select('productserialstock.SerialNo')->from('product')
    //             ->where('product.ProductCode', $product)
    //             ->where('productserialstock.Location', $location)
    //             ->where('productprice.PL_No', $pl)
    //     ->where('productserialstock.Quantity', 1)
    //             ->join('productserialstock', 'productserialstock.ProductCode = product.ProductCode')
    //             ->join('productprice', 'productprice.ProductCode = product.ProductCode')
    //             ->get();
    //     $query4 = $this->db->select('productserialstock.SerialNo')->from('product')
    //             //->where('productserialstock.SerialNo', $product)
    //             ->where('productserialstock.Location', $location)
    //             ->where('productprice.PL_No', $pl)
    //     ->where('productserialstock.Quantity', 1)
    //             ->join('productserialstock', 'productserialstock.ProductCode = product.ProductCode')
    //             ->join('productprice', 'productprice.ProductCode = product.ProductCode')
    //             ->get();
    //     $query1 = $this->db->select('productcondition.IsSerial')->from('product')
    //             ->where('product.ProductCode', $product)
    //             ->where('productcondition.IsSerial', 1)
    //             ->join('productcondition', 'productcondition.ProductCode = product.ProductCode')
    //             ->get();
    //     $query3 = $this->db->select('productcondition.IsSerial')->from('product')
    //             ->where('productserialstock.SerialNo', $product)
    //             ->where('productcondition.IsSerial', 1)
    //             ->join('productcondition', 'productcondition.ProductCode = product.ProductCode')
    //             ->join('productserialstock', 'productserialstock.ProductCode = product.ProductCode')
    //             ->get();
        
    //     $query5 = $this->db->select('product.ProductCode')->from('product')
    //             ->join('productserialstock', 'productserialstock.ProductCode = product.ProductCode')
    //             ->where('productserialstock.SerialNo', $product)
    //             // ->group_by('product.ProductCode')
    //             ->get();
     
    //     if (($query1->num_rows()) > 0) {
    //         if (($query2->num_rows()) > 0) {
    //             foreach ($query2->result_array() as $row) {
    //                 $row_set[] = htmlentities(stripslashes($row['SerialNo']));
    //             }
    //             return ($row_set);
    //         }
    //     }else if (($query3->num_rows()) > 0) {
    //         if (($query5->num_rows()) > 0) {
    //             //get product code by serial
    //             foreach ($query5->result_array() as $row) {
    //                 $pro = htmlentities(stripslashes($row['ProductCode']));
    //             }
                
    //              $query4 = $this->db->select('productserialstock.SerialNo')->from('product')
    //             ->where('product.ProductCode', $pro)
    //             ->where('productserialstock.Location', $location)
    //             ->where('productprice.PL_No', $pl)
    //             ->where('productserialstock.Quantity', 1)
    //             ->join('productserialstock', 'productserialstock.ProductCode = product.ProductCode')
    //             ->join('productprice', 'productprice.ProductCode = product.ProductCode')
    //             ->get();
                
    //             foreach ($query4->result_array() as $row) {
    //                 $row_set[] = htmlentities(stripslashes($row['SerialNo']));
    //             }
    //             return ($row_set);
    //         } 
    //     } else  {
    //         return NULL;
    //     }
    // }

    public function loadproductbyserialArrayByCode($product, $pl, $location)
        {
            // Check in productserialstock first
            $query1 = $this->db->select('productserialstock.SerialNo')
                ->from('productserialstock')
                ->join('productprice', 'productprice.ProductCode = productserialstock.ProductCode', 'inner')
                ->where('productserialstock.SerialNo', $product)
                ->where('productserialstock.Location', $location)
                ->where('productprice.PL_No', $pl)
                ->where('productserialstock.Quantity', 1)
                ->get();

            if ($query1->num_rows() > 0) {
                $result = $query1->row_array(); 
            return $result;
            }

            //If not found, check in productemiserialstock
            $query2 = $this->db->select('productserialemistock.SerialNo,.productserialemistock.EmiNo')
                ->from('productserialemistock')
                ->join('productprice', 'productprice.ProductCode = productserialemistock.ProductCode', 'inner')
                ->group_start() // Start OR condition group
                    ->where('productserialemistock.SerialNo', $product)
                    ->or_where('productserialemistock.EmiNo', $product)
                ->group_end()
                ->where('productserialemistock.Location', $location)
                ->where('productprice.PL_No', $pl)
                ->where('productserialemistock.Quantity', 1)
                ->get();

            if ($query2->num_rows() > 0) {
                $result = $query2->row_array(); 
            return $result;
            }
            $query3 = $this->db->select('productimeistock.EmiNo')
                ->from('productimeistock')
                ->join('productprice', 'productprice.ProductCode = productimeistock.ProductCode', 'inner')
                ->where('productimeistock.EmiNo', $product)
                ->where('productimeistock.Location', $location)
                ->where('productprice.PL_No', $pl)
                ->where('productimeistock.Quantity', 1)
                ->get();

            if ($query3->num_rows() > 0) {
                $result = $query3->row_array(); 
            return $result;
            }

            $query4 = $this->db->select('productimeistock.EmiNo')
                ->from('productimeistock')
                ->join('productprice', 'productprice.ProductCode = productimeistock.ProductCode', 'inner')
                ->where('productimeistock.EmiNo', $product)
                ->where('productimeistock.Location', $location)
                ->where('productprice.PL_No', $pl)
                ->where('productimeistock.Quantity', 1)
                ->get();

            if ($query3->num_rows() > 0) {
                $result = $query3->row_array(); 
            return $result;
            }
        
            $serials = $this->db->select('SerialNo')
                ->from('productserialstock')
                ->where('ProductCode', $product)
                ->where('Location', $location)
                ->where('Quantity', 1)
                ->get()
                ->result_array();
        if($serials){
            $serial_numbers = array_column($serials, 'SerialNo');

            $result = ['SerialNo' => $serial_numbers];

            return $result;
            }

            $emi_serials = $this->db->select('SerialNo, EmiNo')
                ->from('productserialemistock')
                ->where('ProductCode', $product)
                ->where('Location', $location)
                ->where('Quantity', 1)
                ->get();

                if ($emi_serials->num_rows() > 0) {
                    $result = $emi_serials->row_array(); 
                return $result;
                }
                

            $emis = $this->db->select('EmiNo')
                ->from('productimeistock')
                ->where('ProductCode', $product)
                ->where('Location', $location)
                ->where('Quantity', 1)
                ->get();

                
            if ($emis->num_rows() > 0) {
                $result = $emis->row_array(); 
            return $result;
            }
            


            // Not found in either table
            return null;
        }

     public function newsaveStockOut($grnHed,$post,$grnNo) { 
      
        $product_codeArr = json_decode($_POST['product_code']);
   
        $unitArr = json_decode($_POST['unit_type']);

        $serial_noArr = json_decode($_POST['serial_no']);
        $qtyArr = json_decode($_POST['qty']);
        $sell_priceArr = json_decode($_POST['unit_price']);
        $cost_priceArr = json_decode($_POST['cost_price']);
        $upcArr = json_decode($_POST['upc']);
        $price_levelArr = json_decode($_POST['price_level']);
        $tofindSellPrice = json_decode($_POST['price_level'], true);
        // $price_levelArr = 1;
          //echo var_dump($tofindSellPrice );die;
        $totalAmountArr = json_decode($_POST['total_net']);
        $isSerialArr = json_decode($_POST['isSerial']);

        $emi_noArr = json_decode($_POST['emi_no']);
        $isEmiArr = json_decode($_POST['isEmi']);
       
         $location_to = $_POST['toloc'];
        $location_from = $_POST['fromloc'];
        $grnDattime = date("Y-m-d H:i:s");
        
        
        
        
       
            $this->db->trans_start();
             for ($i = 0; $i < count($product_codeArr); $i++) {
                
               

                $grnDtl = array(
                    'TrnsNo' => $grnNo,
                    'Location' => 1,
                    'TrnsDate' => $grnDattime,
                    'FromLocation' => $location_from,
                    'ToLocation' => $location_to,
                    'ProductCode' => $product_codeArr[$i],
                    'UnitPerCase' => $upcArr[$i],
                    'CaseOrUnit' => $unitArr[$i],
                    'TransQty' => $qtyArr[$i],
                    'DismissQty' => 0,
                    'CostPrice' => $cost_priceArr[$i],
                    'PriceLevel' => $price_levelArr[$i],
                    'SellingPrice' => $sell_priceArr[$i],
                    'TransAmount' => $totalAmountArr[$i],
                    'IsSerial' => $isSerialArr[$i],
                    'Serial' => $serial_noArr[$i],
                    'EmiNo'=>$emi_noArr[$i],
                    'IsEmi'=>$isEmiArr[$i],
                    
                );
                $this->db->insert('newstocktransferdtl', $grnDtl);
                if (!empty($tofindSellPrice) && $tofindSellPrice[0] == "2") {
                    
                    $this->Stock_model->updateStock($product_codeArr[$i], $location_from, -$qtyArr[$i], $sell_priceArr[$i]);
                   
                }else{
                     $this->db->query("CALL SPP_UPDATE_PRICE_STOCK('$product_codeArr[$i]','$qtyArr[$i]','1','$cost_priceArr[$i]','$sell_priceArr[$i]','$location_from','$serial_noArr[$i]',0,0,0)");
                    // $this->db->query("CALL SPT_UPDATE_PRO_STOCK('$product_codeArr','$qtyArr',0,'$location_from')");
                }
                //update price and product stock
           
             //update serial stock
            //  $this->db->query("UPDATE productserialstock AS S
            //                     INNER JOIN  newstocktransferdtl AS D ON S.ProductCode=D.ProductCode
            //                     SET S.Quantity=0
            //                     WHERE S.SerialNo = D.Serial AND D.IsSerial = 1 AND D.TrnsNo = '$grnNo' AND D.Location = '$location_from'");

             if($isSerialArr[$i]== 1 && $isEmiArr[$i] == 0){
                $this->db->update('productserialstock',array('Quantity'=>0),array('ProductCode'=> $product_codeArr[$i],'Location'=> $location_from,'SerialNo'=> $serial_noArr[$i]));
            }

            if($isSerialArr[$i]== 0 && $isEmiArr[$i] == 1){
                $this->db->update('	productimeistock',array('Quantity'=>0),array('ProductCode'=> $product_codeArr[$i],'Location'=> $location_from,'EmiNo'=> $emi_noArr[$i]));
            }
            if($isSerialArr[$i]== 1 && $isEmiArr[$i] == 1){
                $this->db->update('productserialemistock',array('Quantity'=>0),array('ProductCode'=> $product_codeArr[$i],'Location'=> $location_from,'SerialNo'=> $serial_noArr[$i]));
            }
            }
        
        $this->db->insert('newstocktransferhed', $grnHed);
        $this->update_max_code('Stock Transfer Out');
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

    public function update_max_code($form)
    {
        $query = $this->db->select('*')->where('FormName',$form)->get('codegenerate');
        foreach ($query->result_array() as $row) {
           $input = $row['AutoNumber'];
        } 
        $this->db->update('codegenerate',array('AutoNumber'=>($input+1)),array('FormName'=>($form)));
    }

    public function loadStockTransoutHead($transNo){
          return $this->db->select(' newstocktransferhed.*,
            loc_from.location AS from_location_name,
            loc_to.location AS to_location_name')
            ->from('newstocktransferhed')
            ->join('location AS loc_from', 'loc_from.location_id = newstocktransferhed.FromLocation', 'left')
            ->join('location AS loc_to', 'loc_to.location_id = newstocktransferhed.ToLocation', 'left')
            ->like('newstocktransferhed.TrnsNo', $transNo)
            ->get()->row();
    }

    public function loadStockTransoutHeadDetls($transNo){
            return $this->db->select('newstocktransferhed.TrnsNo,newstocktransferdtl.*,product.Prd_Description')
            ->from('newstocktransferhed')
            ->join('newstocktransferdtl','newstocktransferdtl.TrnsNo =newstocktransferhed.TrnsNo','left')
            ->join('product','product.ProductCode =newstocktransferdtl.ProductCode')
             ->like('newstocktransferdtl.TrnsNo', $transNo)
            ->get()->result();
    }


    public function newsaveStockIn($location_to,$location_from,$canDate,$grnNo,$remark,$user) {
    
    {
       
            // Start transaction
            $this->db->trans_start();

            
            $this->db->update('newstocktransferhed', ['TransIsInProcess' => 0,'TransInDate'=> $dattime,'TransInUser'=> $user,'TransInRemark'=> $remark], ['TrnsNo'=> $grnNo,'FromLocation'=> $location_from,'ToLocation'   => $location_to]);

            // Step 2: Get joined header + detail rows
            $this->db->select('h.TrnsNo, h.FromLocation, h.ToLocation,d.ProductCode, d.TransQty, d.PriceLevel, d.CostPrice, d.SellingPrice, d.Serial, d.EmiNo, d.IsSerial, d.IsEmi');
            $this->db->from('newstocktransferhed h');
            $this->db->join('newstocktransferdtl d', 'h.TrnsNo = d.TrnsNo', 'inner');
            $this->db->where('h.TrnsNo', $grnNo);
            $this->db->where('h.TransIsInProcess', 0);
            $query = $this->db->get();

            // Step 3: Process each product
            if ($query->num_rows() > 0) {
                foreach ($query->result_array() as $row) {
                     
                    $product_codeArr = $row['ProductCode'];
                    $qtyArr          = $row['TransQty'];
                    $price_levelArr  = $row['PriceLevel'];
                    $cost_priceArr   = $row['CostPrice'];
                    $sell_priceArr   = $row['SellingPrice'];
                    $serial_noArr    = $row['Serial'];
                    $emi_noArr       = $row['EmiNo'];
                    $isSerialArr     = $row['IsSerial'];
                    $isEmiArr        = $row['IsEmi'];
                    $location_to     = $row['ToLocation'];

                    // Update detail table dismiss qty
                    $this->db->update('newstocktransferdtl', ['DismissQty' => $qtyArr], ['ProductCode' => $product_codeArr,'TrnsNo'=> $grnNo,'FromLocation'=> $location_from,'ToLocation'=> $location_to]);

                    // Update stock tables
                    if ($isSerialArr == 1 && $isEmiArr == 0) {
                        // Serial only
                        $this->db->update('productserialstock', ['Quantity' => 1], ['ProductCode' => $product_codeArr,'Location'=> $location_to,'SerialNo'=> $serial_noArr]);

                    } elseif ($isSerialArr == 0 && $isEmiArr == 1) {
                        // EMI only
                        $this->db->update('productimeistock', ['Quantity' => 1], ['ProductCode' => $product_codeArr,'Location'=> $location_to,'EmiNo'=> $emi_noArr]);

                    } elseif ($isSerialArr == 1 && $isEmiArr == 1) {
                        // Serial + EMI
                        $this->db->update('productserialemistock', ['Quantity' => 1], ['ProductCode' => $product_codeArr,'Location'=> $location_to,'SerialNo'=> $serial_noArr]);

                    }

                    if($price_levelArr==1){
                        // $this->db->query("CALL SPT_UPDATE_PRO_STOCK('$product_codeArr','$qtyArr',0,'$location_to')");
                        $this->db->query("CALL SPT_UPDATE_PRICE_STOCK('$product_codeArr','$qtyArr','1','$cost_priceArr','$sell_priceArr','$location_to')");
                         $this->db->query("CALL SPT_UPDATE_PRO_STOCK('$product_codeArr','$qtyArr',0,'$location_to')"); 
                    }else{
                        $this->Stock_model->updateStock($product_codeArr, $location_to, $qtyArr, $sell_priceArr);
                    }
                        
                   
                }
            }

            // Step 4: Commit transaction
            $this->db->trans_complete();
            return $this->db->trans_status();
        }
     }



      public function newStockCancel($location_to,$location_from,$canDate,$grnNo,$user,$invCanel) {
        $this->db->trans_start();
        $dattime = date("Y-m-d H:i:s");
       $this->db->update('newstocktransferhed',array('TransIsInProcess'=>0,'TransInDate'=>$dattime,'TransInUser'=>$user,'TransInRemark'=>$remark,'IsCancel'=>1),array('TrnsNo'=>$grnNo,'FromLocation'=>$location_from,'ToLocation'=>$location_to));
        $query = $this->db->get_where('newstocktransferdtl',array('TrnsNo'=>$grnNo));
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $product_codeArr=$row['ProductCode'];
                $qtyArr=$row['TransQty'];
                $price_levelArr=$row['PriceLevel'];
                $cost_priceArr=$row['CostPrice'];
                $sell_priceArr=$row['SellingPrice'];
                $serial_noArr=$row['Serial'];
                $freeQtyArr=0;
                $isSerialArr=$row['IsSerial'];
                $emi_noArr=$row['EmiNo'];
                $isEmiArr=$row['IsEmi'];
                //update stock trans dtl
                 $this->db->update('newstocktransferdtl',array('DismissQty'=>0),array('ProductCode'=> $product_codeArr,'TrnsNo'=>$grnNo,'FromLocation'=> $location_from,'ToLocation'=>$location_to));
                 
                //update to location serial stock 
                // $ps = $this->db->select('ProductCode')->from('productserialstock')->where(array('ProductCode'=> $product_codeArr,'SerialNo'=>$serial_noArr,'Location'=>$location_from))->get();
                // if($ps->num_rows()>0){
                //     $isPro = $this->db->select('InvProductCode')->from('invoicedtl')->where(array('InvProductCode'=> $product_codeArr,'InvSerialNo'=>$serial_noArr,'InvLocation'=>$location_from))->get();
                    // if($isPro->num_rows()==0){
                    //     $this->db->update('productserialstock',array('Quantity'=>1),array('ProductCode'=> $product_codeArr,'SerialNo'=>$serial_noArr,'Location'=> $location_from));
                    // }
                // }else{
                    // if($isSerialArr==1){
                    //     $this->db->insert('productserialstock', array('ProductCode'=> $product_codeArr,'Location'=> $location_from,'SerialNo'=>$serial_noArr,'Quantity'=>1,'GrnNo'=>$grnNo));
                    // }

                    if($isSerialArr==1 &&  $isEmiArr == 0 ){
                        $this->db->insert('productserialstock', array('ProductCode'=> $product_codeArr,'Location'=> $location_from,'SerialNo'=>$serial_noArr,'Quantity'=>1));
                    }else if($isSerialArr==0 &&  $isEmiArr == 1 ){
                        $this->db->insert('productemistock', array('ProductCode'=> $product_codeArr,'Location'=> $location_from,'EmiNo'=>$isEmiArr,'Quantity'=>1));
                    }else if($isSerialArr==1 &&  $isEmiArr == 1 ){
                        $this->db->insert('productserialemistock', array('ProductCode'=> $product_codeArr,'Location'=> $location_from,'SerialNo'=>$serial_noArr,'Quantity'=>1));
                    }
                //}
                

                //update price stock
                if($price_levelArr==1){
                    $this->db->query("CALL SPT_UPDATE_PRICE_STOCK('$product_codeArr','$qtyArr','1','$cost_priceArr','$sell_priceArr','$location_from')");
                }else{
                     $this->Stock_model->updateStock($product_codeArr, $location_from, $qtyArr, $sell_priceArr);
                }
               

            //update product stock
            //$this->db->query("CALL SPT_UPDATE_PRO_STOCK('$product_codeArr','$qtyArr',0,'$location_from')");
            }
        }
        $this->db->insert('newstockcanceltranser', $invCanel);
       $this->update_max_code('Stock Transfer Cancel');
        $this->db->trans_complete();
       return $this->db->trans_status();
    }


    //  public function loadproductbyserial($product, $pl, $location) {
    //     $query2 = $this->db->select('product.*,productcondition.*,productprice.ProductPrice,productserialstock.SerialNo,goodsreceivenotedtl.GRN_UnitCost AS Prd_CostPrice')->from('product')
    //                     ->where('productserialstock.SerialNo', $product)
    //                     ->where('productserialstock.Location', $location)
    //                     ->where('productprice.PL_No', $pl)
    //                     ->where('productserialstock.Quantity', 1)
    //                     ->join('productcondition', 'productcondition.ProductCode = product.ProductCode')
    //                     ->join('productserialstock', 'productserialstock.ProductCode = product.ProductCode')
    //                     ->join('goodsreceivenotedtl', 'goodsreceivenotedtl.SerialNo = productserialstock.SerialNo')
    //                     ->join('productprice', 'productprice.ProductCode = product.ProductCode')
    //                     ->get();
    //     $query1 =$this->db->select('product.*,productcondition.*,productprice.ProductPrice')->from('product')
    //                     ->where('product.BarCode', $product)
    //                     ->where('productprice.PL_No', $pl)
    //                     ->join('productcondition', 'productcondition.ProductCode = product.ProductCode')
    //                     ->join('productprice', 'productprice.ProductCode = product.ProductCode')
    //                     ->get();
    //     if(($query1->num_rows())>0){
    //         return $query1->row();
    //     }else if(($query2->num_rows())>0){
    //         return $query2->row();
    //     }
    // }

    public function loadproductbyserial($product, $pl, $location) {
        $query2 = $this->db->select('product.*,productcondition.*,productprice.ProductPrice,productserialstock.SerialNo,goodsreceivenotedtl.GRN_UnitCost AS Prd_CostPrice,department.Discount')
                        ->from('product')
                        ->join('productcondition', 'productcondition.ProductCode = product.ProductCode')
                        ->join('productserialstock', 'productserialstock.ProductCode = product.ProductCode')
                        ->join('goodsreceivenotedtl', 'goodsreceivenotedtl.SerialNo = productserialstock.SerialNo')
                        ->join('productprice', 'productprice.ProductCode = product.ProductCode')
                        ->join('department','department.DepCode = product.DepCode','left')
                        ->where('productserialstock.SerialNo', $product)
                        ->where('productserialstock.Location', $location)
                        ->where('productprice.PL_No', $pl)
                        ->where('productserialstock.Quantity', 1)
                        ->get();
        $query3 =  $this->db->select('product.*,productcondition.*,productprice.ProductPrice, productserialemistock.SerialNo,goodsreceivenotedtl.GRN_UnitCost AS Prd_CostPrice,department.Discount')
                        ->from('product')
                        ->join('productcondition', 'productcondition.ProductCode = product.ProductCode')
                        ->join('productserialemistock', 'productserialemistock.ProductCode = product.ProductCode')
                        ->join('goodsreceivenotedtl', 'goodsreceivenotedtl.SerialNo = productserialemistock.SerialNo')
                        ->join('productprice', 'productprice.ProductCode = product.ProductCode')
                        ->join('department','department.DepCode = product.DepCode','left')
                        ->group_start() // Start OR condition group
                            ->where('productserialemistock.SerialNo', $product)
                            ->or_where('productserialemistock.EmiNo', $product)
                        ->group_end()
                        ->where('productserialemistock.Location', $location)
                        ->where('productprice.PL_No', $pl)
                        ->where('productserialemistock.Quantity', 1)
                        ->get();
        $query1 =$this->db->select('product.*,productcondition.*,productprice.ProductPrice,department.Discount')
                        ->from('product')
                        ->where('product.BarCode', $product)
                        ->where('productprice.PL_No', $pl)
                        ->join('productcondition', 'productcondition.ProductCode = product.ProductCode')
                        ->join('productprice', 'productprice.ProductCode = product.ProductCode')
                        ->join('department','department.DepCode = product.DepCode','left')
                        ->get();
        $query4 = $this->db->select('product.*,productcondition.*,productprice.ProductPrice, productimeistock.EmiNo,goodsreceivenotedtl.GRN_UnitCost AS Prd_CostPrice,department.Discount')
                        ->from('product')
                        ->join('productcondition', 'productcondition.ProductCode = product.ProductCode')
                        ->join('productimeistock', 'productimeistock.ProductCode = product.ProductCode')
                        ->join('goodsreceivenotedtl', 'goodsreceivenotedtl.EmiNo = productimeistock.EmiNo')
                        ->join('productprice', 'productprice.ProductCode = product.ProductCode')
                        ->join('department','department.DepCode = product.DepCode','left')
                        ->where('productimeistock.EmiNo', $product)
                        ->where('productimeistock.Location', $location)
                        ->where('productprice.PL_No', $pl)
                        ->where('productimeistock.Quantity', 1)
                        ->get();
        if(($query1->num_rows())>0){
            return $query1->row();
        }else if(($query2->num_rows())>0){
            return $query2->row();
        }else if(($query3->num_rows())>0){
            return $query3->row();
        }else if(($query4->num_rows())>0){ 
             return $query4->row();
        }
    }

    //  public function loadproductstockbyidForSerial($product,$location)
    // {
    //     return $this->db->select('productserialstock.Quantity AS Stock')
    //         ->from('productserialstock')->where('SerialNo', $product)->where('Location', $location)
    //         ->get()->row();
    // }

    public function loadproductstockbyidForSerial($product,$location)
    {
        // return $this->db->select('productserialstock.Quantity AS Stock')
        //     ->from('productserialstock')->where('SerialNo', $product)->where('Location', $location)
        //     ->get()->row();

        $serialCheck = $this->db->select('productstock.Stock')
        ->from('productserialstock')
        ->join('productstock','productstock.ProductCode = productserialstock.ProductCode','INNER')
        ->where('productserialstock.SerialNo', $product) 
        ->where('productserialstock.Location', $location)
        ->get()
        ->row();

        $serialEmiCheck = $this->db->select('productstock.Stock')
            ->from('productserialemistock')
            ->join('productstock','productstock.ProductCode = productserialemistock.ProductCode','LEFT')
            ->group_start() // Start OR condition group
                ->where('productserialemistock.SerialNo', $product)
                ->or_where('productserialemistock.EmiNo', $product)
            ->group_end()
            ->where('productserialemistock.Location', $location)
            ->get()
            ->row();
        
        $EmiCheck = $this->db->select('productstock.Stock')
            ->from('productimeistock')
            ->join('productstock','productstock.ProductCode = productimeistock.ProductCode','LEFT')
            ->where('productimeistock.EmiNo', $product) 
            ->where('productimeistock.Location', $location)
            ->get()
            ->row();

        if ($serialCheck) {
            return $serialCheck;
        }else if($serialEmiCheck){
            return $serialEmiCheck;
        }else if ($EmiCheck){
            return $EmiCheck;
        }
         else {
        
            return $this->db->select('Stock')
                ->from('productstock')->where('ProductCode', $product)->where('Location', $location)
                ->get()
                ->row();
        }
    }


    public function loadproductbyserialArrayBySerial($product, $pl, $location) {
   
        $query2 = $this->db->select('productserialstock.SerialNo')->from('product')
                ->where('product.ProductCode', $product)
                ->where('productserialstock.Location', $location)
                ->where('productprice.PL_No', $pl)
                ->where('productserialstock.Quantity', 1)
                ->join('productserialstock', 'productserialstock.ProductCode = product.ProductCode')
                ->join('productprice', 'productprice.ProductCode = product.ProductCode')
                ->get();
        $query4 = $this->db->select('productserialstock.SerialNo')->from('product')
                ->where('productserialstock.SerialNo', $product)
                ->where('productserialstock.Location', $location)
                ->where('productprice.PL_No', $pl)
                ->where('productserialstock.Quantity', 1)
                ->join('productserialstock', 'productserialstock.ProductCode = product.ProductCode')
                ->join('productprice', 'productprice.ProductCode = product.ProductCode')
                ->get();
        $query1 = $this->db->select('productcondition.IsSerial')->from('product')
                ->where('product.ProductCode', $product)
                ->where('productcondition.IsSerial', 1)
                ->join('productcondition', 'productcondition.ProductCode = product.ProductCode')
                ->get();
        $query3 = $this->db->select('productcondition.IsSerial')->from('product')
                ->where('productserialstock.SerialNo', $product)
                ->where('productcondition.IsSerial', 1)
                ->join('productcondition', 'productcondition.ProductCode = product.ProductCode')
                ->join('productserialstock', 'productserialstock.ProductCode = product.ProductCode')
                ->get();
        
        if (($query1->num_rows()) > 0) {
            if (($query2->num_rows()) > 0) {
                foreach ($query2->result_array() as $row) {
                    $row_set[] = htmlentities(stripslashes($row['SerialNo']));
                }
                return ($row_set);
            }
        }else if (($query3->num_rows()) > 0) {
            if (($query4->num_rows()) > 0) {
                foreach ($query4->result_array() as $row) {
                    $row_set[] = htmlentities(stripslashes($row['SerialNo']));
                }
                return ($row_set);
            } 
        } else  {
            return NULL;
        }
    }
}