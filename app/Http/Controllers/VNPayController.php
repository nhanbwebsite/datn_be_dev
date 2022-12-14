<?php

namespace App\Http\Controllers;

use App\Http\Validators\VNPay\VNPayCreateValidator;
use App\Http\Validators\VNPay\VNPayOrderCreateValidator;
use App\Models\Order;
use App\Models\VNPayOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class VNPayController extends Controller
{
    /**
     * Create payment with VNPAY
     * @return string or null
     */
    public function create(Request $request, VNPayCreateValidator $validator){
        $input = $request->all();
        $validator->validate($input);
        try{
            $order_code = 'DH'.date('YmdHis', time());
            $params = [
                'vnp_Version' => env('VNPAY_VERSION'),
                'vnp_Command' => VNPAY_COMMAND_PAY,
                'vnp_TmnCode' => env('VNPAY_TMNCODE'),
                'vnp_Amount' => $input['amount'] * 100,
                'vnp_CreateDate' => date('YmdHis', time()),
                'vnp_CurrCode' => VNPAY_CURRENCY,
                'vnp_IpAddr' => $request->ip(),
                'vnp_Locale' => VNPAY_LOCALE,
                'vnp_OrderInfo' => 'Thanh toan don hang ['.$order_code.']',
                'vnp_ReturnUrl' => $input['returnUrl'],
                'vnp_ExpireDate' => date('YmdHis', time() + 15*60),
                'vnp_TxnRef' => $order_code,
            ];
            ksort($params);
            $vnpay_query_params = '';
            $i = 0;
            foreach($params as $key => $value){
                if($i == 1){
                    $vnpay_query_params .= '&'.urlencode($key).'='.urlencode($value);
                }
                else{
                    $vnpay_query_params .= urlencode($key).'='.urlencode($value);
                    $i = 1;
                }
            }

            $vnp_SecureHash	= hash_hmac('sha512', $vnpay_query_params, env('VNPAY_HASHSECRET'));

            $finalUrl = env('VNPAY_URL').'?'.$vnpay_query_params.'&'.'vnp_SecureHash='.$vnp_SecureHash;
        }
        catch(HttpException $e){
            return response()->json([
                'status' => 'error',
                'message' => [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ],
            ], $e->getStatusCode());
        }

        return response()->json([
            'status' => 'success',
            'url' => $finalUrl ?? null,
        ]);
    }

    public function returnData(Request $request, VNPayOrderCreateValidator $validator){
        $input = $request->all();
        $user = $request->user();

        $secureHash = $input['vnp_SecureHash'];
        unset($input['vnp_SecureHash']);

        ksort($input);
        $i = 0;
        $query_params = '';
        foreach($input as $key => $value){
            if($i == 1){
                $query_params .= '&'.urlencode($key).'='.urlencode($value);
            }
            else{
                $query_params .= urlencode($key).'='.urlencode($value);
                $i = 1;
            }
        }

        $vnp_SecureHash = hash_hmac('sha512', $query_params, env('VNPAY_HASHSECRET'));

        try{
            DB::beginTransaction();

            if($vnp_SecureHash == $secureHash){
                if ($input['vnp_ResponseCode'] == '00' || $input['vnp_TransactionStatus'] == '00') {
                    $input['created_by'] = !empty($user) ? $user->id : null;
                    $input['updated_by'] = !empty($user) ? $user->id : null;
                    $input['vnp_Amount'] = $input['vnp_Amount'] / 100;
                    $validator->validate($input);
                    VNPayOrder::create($input);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Thanh to??n kh??ng th??nh c??ng ! Vui l??ng th??? l???i !',
                    ], 400);
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Sai ch??? k?? ! D??? li???u ???? b??? can thi???p trong qu?? tr??nh truy???n t???i ?????n VNPay !'
                ], 400);
            }

            DB::commit();
        }
        catch(HttpException $e){
            DB::rollback();
            return response()->json([
                'status' => 'error',
                'message' => [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ],
            ], $e->getStatusCode());
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'order_code' => $input['vnp_TxnRef'],
            ],
        ]);
    }
}
