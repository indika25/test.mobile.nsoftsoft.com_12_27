<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Stocktransfer extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->ion_auth->logged_in() OR ! $this->ion_auth->is_admin()) {
            redirect('auth/login', 'refresh');
        }
        $this->load->model('admin/Customer_model');
        $this->load->model('admin/StockTransfer_model');
        $this->load->model('admin/Transer_model');
        $this->load->model('admin/Stock_model');
        date_default_timezone_set("Asia/Colombo");
    }


    public function stockout() {
        $this->breadcrumbs->unshift(1, lang('menu_addcustomer'), 'admin/Stocktransfer');
        $this->page_title->push('Stock Tranfer Out');
        $this->data['pagetitle'] = $this->page_title->show();
        $this->data['breadcrumb'] = $this->breadcrumbs->show();
        $this->data['pricelevels'] = $this->db->select('*')->from('pricelevel')->get()->result();
        $this->data['locations'] = $this->db->select('*')->from('location')->get()->result();
        $this->template->admin_render('admin/stocktransfer/stocktransferout', $this->data);
    }

      public function stockin() {
        $this->breadcrumbs->unshift(1, lang('menu_addcustomer'), 'admin/Stocktransfer');
         $this->page_title->push('Stock Tranfer In');
        $this->data['pagetitle'] = $this->page_title->show();
        $this->data['breadcrumb'] = $this->breadcrumbs->show();
        $this->template->admin_render('admin/stocktranfer/stocktranferin', $this->data);
    }


     public function loadproductjson() {
        
        $query = $_GET['q'];
        $tranferDate = $_GET['tranferDate'];
        $fromloc = $_GET['fromloc'];
        $toloc = $_GET['toloc'];
        $prlevel = $_GET['prlevel'];
        echo $this->StockTransfer_model->loadproductjson($query,$tranferDate,$fromloc,$toloc,$prlevel);
        die;
    }


     public function getProductByIdforSTO() {
        $product = $_POST['proCode'];
        $fromloc = $_POST['fromloc'];
        $costPrice = $_POST['costPrice'];
        $pl = $_POST['prlevel'];
       
        $arr['product'] = $this->StockTransfer_model->loadproductbypcode($product);
        
        $arr['productstock']  = $this->StockTransfer_model->loadproductstockbyid($product,$fromloc);
         $arr['pricestock']  = $this->StockTransfer_model->loadproductstockbyprice($product,$fromloc,$costPrice, $pl);
    
         $arr['serial'] = $this->StockTransfer_model->loadproductbyserialArrayByCode($product, $pl=1,$fromloc);
        echo json_encode($arr);
        die;
    }

     public function getProductByBarCodeforSTO() {
        $product = $_POST['proCode'];
        $location = $_POST['location'];
        $costPrice = $_POST['costPrice'];
        $pl = $_POST['prlevel'];
  
        $arr['product'] = $this->StockTransfer_model->loadproductbyserial($product, $pl, $location);
        $arr['productstock']  = $this->StockTransfer_model->loadproductstockbyidForSerial($product,$location);
        $arr['serial'] = $this->StockTransfer_model->loadproductbyserialArrayByCode($product, $pl, $location);
        $arr['pricestock']  = $this->StockTransfer_model->loadpricestockbyid($product, $pl=1,$location);
        echo json_encode($arr);
        die;
    }

     public function loadproductSerial() {
        $q = $_GET['q'];
        $product= $_REQUEST['proCode'];
        $location= $_REQUEST['location'];
        echo $this->Transer_model->loadproductSerial($product, $q, $location);
        die;
    }



    public function saveNewstocktransfer() {
        $barcode = 1;
        
        $grnNo = $this->StockTransfer_model->get_max_code('Stock Transfer Out');
       
        $remark = $_POST['grnremark'];
        $invDate = $_POST['tranferDate'];
        $grnDattime = date("Y-m-d H:i:s");
        $invUser = $_POST['invUser'];
        $total_amount = $_POST['total_net'];
        // echo var_dump($invDate);die;
       
        $total_net_amount = $_POST['total_net_amount'];
        $total_cost = $_POST['total_cost'];
        $location = $_POST['location'];
        $location_to = $_POST['toloc'];
        $location_from = $_POST['fromloc'];
        $isComplete=0;

        
        $product_codeArr = json_decode($_POST['product_code']);
        
        $unitArr = json_decode($_POST['unit_type']);
        $freeQtyArr = json_decode($_POST['freeQty']);
        $serial_noArr = json_decode($_POST['serial_no']);
        $qtyArr = json_decode($_POST['qty']);
        $sell_priceArr = json_decode($_POST['unit_price']);
        $cost_priceArr = json_decode($_POST['cost_price']);
     
        $caseCostArr = json_decode($_POST['case_cost']);
        $upcArr = json_decode($_POST['upc']);
        $total_netArr = json_decode($_POST['total_net']);
        $price_levelArr = json_decode($_POST['price_level']);
        $totalAmountArr = json_decode($_POST['pro_total']);
        $pro_nameArr = json_decode($_POST['proName']);
        
        $grnHed = array(
            'AppNo' => '1','TrnsNo' => $grnNo,'FromLocation'=>$location_from,'ToLocation'=>$location_to,'TrnsDate' => $invDate,'TransDateORG' => $grnDattime,
            'TransIsInProcess' => 1,'Remark' => $remark,'TransInDate' => $grnDattime,
            'CostAmount' => $total_amount,'TransInUser' => '-','TransInRemark' => '-',
            'TransUser' => $invUser,'Flag' => 0,'IsCancel'=>0
        );
        
        $id3 = array('CompanyID' => $location);
        $this->data['company'] = $this->Transer_model->get_data_by_where('company',$id3);
        $company = $this->data['company']['CompanyName'];
        $res2= $this->StockTransfer_model->newsaveStockOut($grnHed,$post,$grnNo);
        $return = array(
            'stockTransferNo' => $grnNo,
            'TransferDate' => $invDate
        );
        
        $return['fb'] = $res2;
        echo json_encode($return);
        die;
    }


     public function allstockout() {
        $this->breadcrumbs->unshift(1, lang('menu_addcustomer'), 'admin/StockTranfer');
         $this->page_title->push('All Stock Tranfer Out Details');
        $this->data['pagetitle'] = $this->page_title->show();
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        $this->data['locations'] = $this->db->select('*')->from('location')->get()->result();
        $this->template->admin_render('admin/stocktransfer/allstocktransferout', $this->data);
    }


    // public function allstocktrasferdetails() {
    //     $location = $_SESSION['location'];
    //     $this->load->library('Datatables');
    //     $this->datatables->select('newstocktransferhed.*,location.location AS FROM');
    //     $this->datatables->from('newstocktransferhed');
    //     // $this->datatables->join('newstocktransferdtl','newstocktransferdtl.TrnsNo=newstocktransferhed.TrnsNo', 'left');
    //     $this->datatables->join('location','location.location_id=newstocktransferhed.FromLocation', 'left');
      
    //     echo $this->datatables->generate();
    //     die;
    // }


    public function allstocktrasferdetails() {
        $location = $_SESSION['location'];
        $this->load->library('Datatables');

        $this->datatables->select('
            newstocktransferhed.*,
            loc_from.location AS from_location_name,
            loc_to.location AS to_location_name
        ');
        $this->datatables->from('newstocktransferhed');

        
        $this->datatables->join('location AS loc_from', 'loc_from.location_id = newstocktransferhed.FromLocation', 'left');


        $this->datatables->join('location AS loc_to', 'loc_to.location_id = newstocktransferhed.ToLocation', 'left');

        echo $this->datatables->generate();
        die;
    }


     public function view_stockout($transNo=null) {
        $this->load->helper('form');
        $this->load->helper(array('form', 'url'));
        $transNo = base64_decode($transNo);
        
        $this->page_title->push(('View Stockout   - '.$transNo));
        $this->breadcrumbs->unshift(1, 'Stockout', 'admin/stocktransfer/view_stockout');
        $this->data['pagetitle'] = $this->page_title->show();
        $this->data['breadcrumb'] = $this->breadcrumbs->show();
        $this->data['stockhead'] = $this->StockTransfer_model->loadStockTransoutHead($transNo);
        $this->data['stockheadDtls'] = $this->StockTransfer_model->loadStockTransoutHeadDetls($transNo);
     
       
      
        //customer all estimates
        $this->load->model('admin/Pos_model');
        $id3 = array('CompanyID' => $location);
        $this->data['company'] = $this->Pos_model->get_data_by_where('company', $id3);
        $this->template->admin_render('admin/stocktransfer/view_stockout', $this->data);
    }


     public function newsaveStockIn() {

        $transNo = $_POST['trnsNo'];
        $invUser = $_POST['invUser'];
        $this->db->where('TrnsNo', $transNo);
        $data = $this->db->get('newstocktransferhed')->row();
        

        // $location_to = $_POST['location_to'];
        // $location_from = $_POST['location_from'];
        // $location=$_POST['location'];
        // $canDate=$_POST['payDate'];
        // $grnNo=$_POST['invNo'];
        // $remark=$_POST['remark'];
        // $user=$_POST['invUser'];
//        $supplier=$_POST['supCode'];


        if ($data) {
            $location_to = $data->ToLocation;
            $location_from = $data->FromLocation;
            $grnNo = $data->TrnsNo;
           
            $user = $invUser;
            $canDate = date('Y-m-d');
           
            $remark = $data->Remark;
            
            $res2 = $this->StockTransfer_model->newsaveStockIn($location_to,$location_from,$canDate,$grnNo,$remark,$user);
            $return = array('CancelNo' => '','InvNo' => $_POST['trnsNo']);
            $return['fb'] = $res2;
            echo json_encode($return);
            die;
        }
    }


     public function newStockCancel() {
       $cancelNo = $this->StockTransfer_model->get_max_code('Stock Transfer Cancel');
       
        $transNo = $_POST['trnsNo'];
        $invUser = $_POST['invUser'];
       $this->db->where('TrnsNo', $transNo);
        $data = $this->db->get('newstocktransferhed')->row();

      if ($data) {
            $location_to = $data->ToLocation;
            $location_from = $data->FromLocation;
            $grnNo = $data->TrnsNo;
           
            $user = $invUser;
            $canDate = date('Y-m-d');
           
            $remark = $data->Remark;

        
        
        $invCanel = array(
            'AppNo' => '1',
            'CancelNo' => $cancelNo,
           
            'CancelDate' => $canDate,
            'TRNNo' => $grnNo,
            
            'CancelUser' =>  $user);
        
        $res2 = $this->StockTransfer_model->newStockCancel($location_to,$location_from,$canDate,$grnNo,$user,$invCanel);
        $return = array('CancelNo' => $cancelNo,'InvNo' => $grnNo);
        $return['fb'] = $res2;
        echo json_encode($return);
        die;
      }
    }


}