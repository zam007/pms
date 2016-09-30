<?php
namespace Home\Model;
use Think\Model;
class OrderModel extends Model {
//    protected $tablePrefix = '';
//    protected $patchValidate = true;
    
    public function getOrder($orderId, $filed = '*'){
        $order = M("Order"); // 实例化order对象
        $orderInfo = $order->field($filed)->where('order_id='.$orderId)->find();
        return $orderInfo;
    }
    
    public function getList($userId, $page, $filed = '*'){
        $order = M("Order"); // 实例化order对象
        if((int)$page <= 1){
            $page = 1;
        }
        $pageMin = ($page-1)*20;
        $pageMax = $page*20;
        $data['page_max'] = ceil($data['count']/20);
        $where = array(
            'order.user_id' => $userId,
            'order_type' => 1,
            'order.flag' => 1,
        );
        $data['count'] = $order->where($where)->count();
        $data['item'] = $order->field($filed)->join('left join answer_sheet on answer_sheet.order_id=order.order_id ')
                        ->where($where)->order('order.order_id desc')->limit($pageMin,$pageMax)->select();
        return $data;
    }

    /**
     * 修改订单
     * @param type $orderId 用户id
     * @param array $update 修改参数
     * @return type
     */
    public function modify( $orderId,$update){
        $order = M("Order"); // 实例化order对象
        #$update["update_time"] = date("Y-m-d H:i:s", time());
        return $order->where('order_id='.$orderId)->save($update);
    }

    public function addOrder($data){
        $order = M("Order");//实例化order对象
        if($data['order_type'] == 1){
            $orderNo = "WCGR18CT-";
        }
        switch($data['order_type']){
            case 2:
                $orderNo = "WCTT18CT-";
            break;
            default :
                $orderNo = "WCGR18CT-";
        }
        $time = "'".date('Y-m-d',time())."%'";
        $t = date('Ymd',time())."-";
        $num = 10000+$order->where('order_time like '.$time)->count();
        $num = substr($num,1,5);
        $data['order_no'] = $orderNo.$t.$num;
        return $order->add($data);
    }
    
}