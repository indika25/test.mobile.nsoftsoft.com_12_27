<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_count_record($table) {
        $query = $this->db->count_all($table);

        return $query;
    }

    public function get_data($table) {
        $query = $this->db->get($table);
        return $query;
    }

    public function get_data_by_where($table, $data) {
        $query = $this->db->get_where($table, $data)->result();
        return $query;
    }

    public function insert_data($table, $data) {
        $query = $this->db->insert($table, $data);
        return $query;
    }

    public function insert_batchdata($table, $data) {
        return $this->db->insert_batch($table, $data);
    }

    public function update_data($table, $data, $id) {
        $query = $this->db->update($table, $data, $id);
        return $query;
    }

    public function loadproductstockbyid($product,$location)
    {
        return $this->db->select('Stock')
            ->from('productstock')->where('ProductCode', $product)->where('Location', $location)
            ->get()->row();
    }


//         public function loadproductstockbyid($product, $location)
// {
    
//     $serialCheck = $this->db->select('productstock.Stock')
//         ->from('productserialstock')
//         ->join('productstock','productstock.ProductCode = productserialstock.ProductCode','INNER')
//         ->where('productserialstock.SerialNo', $product) 
//         ->where('productserialstock.Location', $location)
//         ->get()
//         ->row();

//     $serialEmiCheck = $this->db->select('productstock.Stock')
//         ->from('productserialemistock')
//         ->join('productstock','productstock.ProductCode = productserialemistock.ProductCode','LEFT')
//         // ->where('productserialemistock.SerialNo', $product) 
//         // ->where('productserialemistock.Location', $location)
//         ->get()
//         ->row();

//     if ($serialCheck) {
//         return $serialCheck;
//     }else if($serialEmiCheck){
//          return $serialEmiCheck;
//     } else {
       
//         return $this->db->select('Stock')
//             ->from('productstock')->where('ProductCode', $product)->where('Location', $location)
//             ->get()
//             ->row();
//     }
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

    public function loadPriceStockPrice($product){
        return $this->db->select('Price')
                    ->from('pricestock')
                    ->where('PSCode', $product)
                    ->get()->result();
    }

    public function get_max_code($form) {
        $query = $this->db->select('*')->where('FormName', $form)->get('codegenerate');
        foreach ($query->result_array() as $row) {
            $code = $row['CodeLimit'];
            $input = $row['AutoNumber'];
            $string = $row['FormCode'];
            $code_len = $row['FCLength'];
            $item_ref = $string . str_pad(($input + 1), $code_len, $code, STR_PAD_LEFT);
        }
        return $item_ref;
    }

    public function update_max_code($form) {
        $query = $this->db->select('*')->where('FormName', $form)->get('codegenerate');
        foreach ($query->result_array() as $row) {
            $input = $row['AutoNumber'];
        }
        $this->db->update('codegenerate', array('AutoNumber' => ($input + 1)), array('FormName' => ($form)));
    }

    public function get_products($table, $q) {
        $query = $this->db->select('product.Prd_Description,product.ProductCode,productprice.ProductPrice')->from('product')
                ->like("CONCAT(' ',product.ProductCode,product.Prd_Description)", $q ,'left')
                ->join('productprice', 'productprice.ProductCode = product.ProductCode')
                ->where('product.Prd_IsActive', 1)
                ->limit(50)
                ->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $new_row['label'] = htmlentities(stripslashes($row['Prd_Description']));
                $new_row['value'] = htmlentities(stripslashes($row['ProductCode']));
                $new_row['price'] = htmlentities(stripslashes($row['ProductPrice']));
                $row_set[] = $new_row; //build an array
            }
            echo json_encode($row_set); //format the array into json data
        }
    }

    public function loadproductbyid($product) {
        return $this->db->select('product.*,productcondition.*')->from('product')
                        ->where('product.ProductCode', $product)
                        // ->where('product.Prd_IsActive', 1)
                        ->join('productcondition', 'productcondition.ProductCode = product.ProductCode')
                        ->get()->row();
    }

    public function loadpricelistbyid($product) {
        return $this->db->select('productprice.ProductPrice,productprice.PL_No,pricelevel.PriceLevel')
                ->from('productprice')->where('ProductCode', $product)
                ->join('pricelevel','pricelevel.PL_No = productprice.PL_No')
                ->get()->result();
    }

    public function loadproductlocationbyid($product) {
        return $this->db->select('productlocation.*,rack.rack_no,store_location.bin_no,location.location,')
                ->from('productlocation')->where('ProductCode', $product)
                ->join('rack','rack.rack_id = productlocation.ProRack')
                ->join('location','location.location_id = rack.rack_loc')
                ->join('store_location','store_location.store_id = productlocation.ProBin')
                ->get()->result();
    }

    public function loadproductbypcode($product, $pl) {
        
        return $this->db->select('product.*,productcondition.*,productprice.ProductPrice,productstock.stock')->from('product')
                        ->where('product.ProductCode', $product)
                        ->where('productprice.PL_No', $pl)
                        ->join('productcondition', 'productcondition.ProductCode = product.ProductCode')
                        ->join('productprice', 'productprice.ProductCode = product.ProductCode')
                        ->join('productstock', 'productstock.ProductCode = product.ProductCode')
                        ->get()->row();
    }

   

    public function loadproductbypcodegrn($product, $pl) {
        return $this->db->select('product.*,productcondition.*,
                        productprice.ProductPrice')
                        ->from('product')
                        ->where('product.ProductCode', $product)
                        // ->where('productprice.PL_No', $pl)
                        ->join('productcondition', 'productcondition.ProductCode = product.ProductCode')
                        ->join('productprice', 'productprice.ProductCode = product.ProductCode')
                       
                        ->get()->row();
    }

    public function loadproductbybarcode($product, $pl) {
        return $this->db->select('product.*,productcondition.*,productprice.ProductPrice')->from('product')
                        ->where('product.BarCode', $product)
                        ->where('productprice.PL_No', $pl)
                        ->join('productcondition', 'productcondition.ProductCode = product.ProductCode')
                        ->join('productprice', 'productprice.ProductCode = product.ProductCode')
                        ->get()->row();
    }

    public function loadproductbypcodeandbarcode($product, $pl) {
        $query1= $this->db->select('product.*,productcondition.*,productprice.ProductPrice')->from('product')
                        ->where('product.ProductCode', $product)
                        ->where('productprice.PL_No', $pl)
                        ->join('productcondition', 'productcondition.ProductCode = product.ProductCode')
                        ->join('productprice', 'productprice.ProductCode = product.ProductCode')
                        ->get()->row();
        $query2= $this->db->select('product.*,productcondition.*,productprice.ProductPrice')->from('product')
                        ->where('product.BarCode', $product)
                        ->where('productprice.PL_No', $pl)
                        ->join('productcondition', 'productcondition.ProductCode = product.ProductCode')
                        ->join('productprice', 'productprice.ProductCode = product.ProductCode')
                        ->get()->row();
       if(($query1->num_rows())>0){
            return $query1->row();
        }else if(($query2->num_rows())>0){
            return $query2->row();
        }                 
    }

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
    
    public function loadproductbyserialArray($product, $pl, $location) {
        $query2 = $this->db->select('productserialstock.SerialNo,department.Discount')->from('product')
        ->join('productserialstock', 'productserialstock.ProductCode = product.ProductCode')
        ->join('productprice', 'productprice.ProductCode = product.ProductCode')
        ->join('department','department.DepCode = product.DepCode','left')
        ->where('productserialstock.Location', $location)
        ->where('productprice.PL_No', $pl)
        ->where('productserialstock.Quantity', 1)
        ->get();
        $query1 = $this->db->select('productcondition.IsSerial,department.Discount')->from('product')
        ->join('productcondition', 'productcondition.ProductCode = product.ProductCode')
        ->join('department','department.DepCode = product.DepCode','left')
        ->where('product.ProductCode', $product)
        ->where('productcondition.IsSerial', 1)
        ->get();

        if (($query1->num_rows()) > 0) {
            if (($query2->num_rows()) > 0) {
                foreach ($query2->result_array() as $row) {
                    $row_set[] = htmlentities(stripslashes($row['SerialNo']));
                }
                return ($row_set);
            } else {
                return NULL;
            }
        } else {
            return NULL;
        }
    }
    
    // public function loadproductbyserialArrayByCode($product, $pl, $location) {
       
   
    //     $query2 = $this->db->select('productserialstock.SerialNo')->from('product')
    //             ->where('product.ProductCode', $product)
    //             ->where('productserialstock.Location', $location)
    //             ->where('productprice.PL_No', $pl)
    //             ->where('productserialstock.Quantity', 1)
    //             ->join('productserialstock', 'productserialstock.ProductCode = product.ProductCode')
    //             ->join('productprice', 'productprice.ProductCode = product.ProductCode')
    //             ->get();
    //     $query4 = $this->db->select('productserialstock.SerialNo')->from('product')
    //             //->where('productserialstock.SerialNo', $product)
    //             ->where('productserialstock.Location', $location)
    //             ->where('productprice.PL_No', $pl)
    //             ->where('productserialstock.Quantity', 1)
    //             ->join('productserialstock', 'productserialstock.ProductCode = product.ProductCode')
    //             ->join('productprice', 'productprice.ProductCode = product.ProductCode')
    //             ->get();
    //             $query1 = $this->db->select('productcondition.IsSerial')->from('product')
    //             ->where('product.ProductCode', $product)
    //             ->where('productcondition.IsSerial', 1)
    //             ->join('productcondition', 'productcondition.ProductCode = product.ProductCode')
    //             ->get();
    //             $query3 = $this->db->select('productcondition.IsSerial')->from('product')
    //             ->where('productserialstock.SerialNo', $product)
    //             ->where('productcondition.IsSerial', 1)
    //             ->join('productcondition', 'productcondition.ProductCode = product.ProductCode')
    //             ->join('productserialstock', 'productserialstock.ProductCode = product.ProductCode')
    //             ->get();
                
    //             $query5 = $this->db->select('product.ProductCode')->from('product')
    //             ->where('productserialstock.SerialNo', $product)
    //             ->join('productserialstock', 'productserialstock.ProductCode = product.ProductCode')
    //             ->group_by('product.ProductCode')
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

 public function loadproductbyEMEIArrayByCode($product, $pl, $location)
{

   
    // 1️⃣ Check in productimeistock (Main EMI Table)
    $query1 = $this->db->select('productimeistock.EmiNo')
        ->from('productimeistock')
        ->join('productprice', 'productprice.ProductCode = productimeistock.ProductCode', 'inner')
        ->where('productimeistock.EmiNo', $product)
        ->where('productimeistock.Location', $location)
        ->where('productprice.PL_No', $pl)
        ->where('productimeistock.Quantity', 1)
        ->get();

    if ($query1->num_rows() > 0) {
        return $query1->result_array();
    }


    // 2️⃣ Check in productserialemistock (Serial + EMI table) — BUT return ONLY EMI
    $query2 = $this->db->select('productserialemistock.EmiNo')
        ->from('productserialemistock')
        ->join('productprice', 'productprice.ProductCode = productserialemistock.ProductCode', 'inner')
        ->group_start()
            ->where('productserialemistock.SerialNo', $product)
            ->or_where('productserialemistock.EmiNo', $product)
        ->group_end()
        ->where('productserialemistock.Location', $location)
        ->where('productprice.PL_No', $pl)
        ->where('productserialemistock.Quantity', 1)
        ->get();

    if ($query2->num_rows() > 0) {
        return $query2->result_array();
    }


    // 3️⃣ Get EMI from productserialemistock (ONLY EMI)
    $emi_serials = $this->db->select('EmiNo')
        ->from('productserialemistock')
        ->where('ProductCode', $product)
        ->where('Location', $location)
        ->where('Quantity', 1)
        ->get();

    if ($emi_serials->num_rows() > 0) {
        return $emi_serials->result_array();
    }


    // 4️⃣ Get EMI from productimeistock (Backup)
    $emis = $this->db->select('EmiNo')
        ->from('productimeistock')
        ->where('ProductCode', $product)
        ->where('Location', $location)
        ->where('Quantity', 1)
        ->get();

    if ($emis->num_rows() > 0) {
        return $emis->result_array();
    }

    
    return null;
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
    
    public function loaddepartment() {
        return $this->db->select()->from('department')->get()->result();
    }

    public function loaddepDis($dep) {
        return $this->db->select('Discount')->from('department')->where('DepCode',$dep)->get()->row();
    }

    public function loadsubdepartment($dep) {
        return $this->db->select()->from('subdepartment')->where('DepCode', $dep)->get()->result();
    }

    public function loadcategory($subdep,$dep) {
        return $this->db->select()->from('category')->where('SubDepCode', $subdep)->where('DepCode', $dep)->get()->result();
    }

    public function loadsubcategory($cat,$subdep,$dep) {
        return $this->db->select()->from('subcategory')->where('DepCode', $dep)->where('SubDepCode', $subdep)->where('CategoryCode', $cat)->get()->result();
    }

    public function loadbin($rack) {
        return $this->db->select()->from('store_location')->where('store_rack', $rack)->get()->result();
    }

    public function loadracks($location) {
        return $this->db->select()->from('rack')->where('rack_loc', $location)->get()->result();
    }

    public function loadsuppliers() {
        return $this->db->select('SupCode,SupName')->from('supplier')->get()->result();
    }

    public function loadpricelevel() {
        return $this->db->select()->from('pricelevel')->where('IsActive', 1)->get()->result();
    }

    public function loadmeasuretype() {
        return $this->db->select()->from('measure')->where('IsActive', 1)->get()->result();
    }

    public function save_product() {
        return $this->db->insert();
    }
    public function saveProduct($data,$productcondition,$pldata){

        $this->db->trans_start();
        $this->insert_data('product', $data);
        $this->insert_data('productcondition', $productcondition);
        $this->insert_batchdata('productprice', $pldata);
        $loc_array  = json_decode($_POST['loc_array']);
        $rack_array = json_decode($_POST['rack_array']);
        $bin_array  = json_decode($_POST['bin_array']);

        //product location
        for ($i=0; $i <count($loc_array) ; $i++) { 
           $proLoc['ProductCode'] = $data['ProductCode'];
           $proLoc['ProLocation'] = $loc_array[$i];
           $proLoc['ProRack']     = $rack_array[$i]; 
           $proLoc['ProBin']      = $bin_array[$i];
           $this->insert_data('productlocation', $proLoc);
        }
        $this->update_max_code('Product');
         $this->db->trans_complete();
       return $this->db->trans_status();
    }

     public function loadSystemOptionById($id){
       return $this->db->select('Value')->from('systemoptions')->where('ID', $id)->get()->row()->Value;
    }


    //   public function loadpricestockbyid($product,$location,$price,$pl)
    // {
    //         return $this->db->select('Stock,Price,UnitCost')
    //         ->from('pricestock')
    //         ->where('PSCode', $product)
    //         ->where('PSLocation', $location)
    //         ->where('Price', $price)
    //         ->where('PSPriceLevel', $pl)
    //         ->get()->row();
        
        
    // }

    public function loadpricestockbyid($product, $location, $price, $pl)
        {
            
            if($pl ==1){
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
                    
                    ->get()
                    ->row();
            }
            }else if($pl==2){
                
                 $serialCheck = $this->db->select('pricestock.Stock AS Stock, pricestock.WholesalesPrice AS Price, pricestock.UnitCost AS UnitCost')
                ->from('productserialstock')
                ->join('pricestock','pricestock.PSCode = productserialstock.ProductCode','INNER')
                ->where('SerialNo', $product) 
                ->where('Location', $location)
                ->get()
                ->row();

            $serialEmiCheck = $this->db->select('pricestock.Stock AS Stock, pricestock.WholesalesPrice AS Price, pricestock.UnitCost AS UnitCost')
                ->from('productserialemistock')
                ->join('pricestock','pricestock.PSCode = productserialemistock.ProductCode','INNER')
                ->group_start() // Start OR condition group
                    ->where('productserialemistock.SerialNo', $product)
                    ->or_where('productserialemistock.EmiNo', $product)
                ->group_end()
                ->where('Location', $location)
                ->get()
                ->row();

            $EmiCheck = $this->db->select('pricestock.Stock AS Stock, pricestock.WholesalesPrice AS Price, pricestock.UnitCost AS UnitCost')
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
            
                return $this->db->select('Stock,WholesalesPrice AS Price, UnitCost')
                    ->from('pricestock')
                    ->where('PSCode', $product)
                    ->where('PSLocation', $location)
                    ->where('Price', $price)
                    ->get()
                    ->row();
            }
            }
           
        }


     public function loadproductbypcodegrnWhole($product, $pl) {
        return $this->db->select('productprice.ProductPrice')
                        ->from('product')
                        ->where('product.ProductCode', $product)
                         ->where('productprice.PL_No', 2)
                        ->join('productcondition', 'productcondition.ProductCode = product.ProductCode')
                        ->join('productprice', 'productprice.ProductCode = product.ProductCode')
                        ->get()->row();
    }

   
}
