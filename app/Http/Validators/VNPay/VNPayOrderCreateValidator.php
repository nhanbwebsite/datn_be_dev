<?php

namespace App\Http\Validators\VNPay;

use App\Http\Validators\ValidatorBase;

class VNPayOrderCreateValidator extends ValidatorBase
{
    protected function rules(){
        return [
            'vnp_TxnRef' => 'required|unique_deleted_at_null:vnpay_orders,vnp_TxnRef',
            'vnp_Amount' => 'required|numeric',
            'vnp_BankCode' => 'required|string',
            'vnp_BankTranNo' => 'required|string',
            'vnp_CardType' => 'required|string',
            'vnp_OrderInfo' => 'required|string',
            'vnp_PayDate' => 'required|string',
            'vnp_ResponseCode' => 'required|string',
            'vnp_TransactionNo' => 'required|string',
            'vnp_TransactionStatus' => 'required|string',
        ];
    }

    protected function messages(){
        return [
            'vnp_TxnRef.required' => ':attribute không được để trống !',
            'vnp_TxnRef.unique_deleted_at_null' => ':attribute đã tồn tại !',
            'vnp_Amount.required' => ':attribute không được để trống !',
            'vnp_Amount.numeric' => ':attribute phải là số !',
            'vnp_BankCode.required' => ':attribute không được để trống !',
            'vnp_BankCode.string' => ':attribute phải là chuỗi !',
            'vnp_BankTranNo.required' => ':attribute không được để trống !',
            'vnp_BankTranNo.string' => ':attribute phải là chuỗi !',
            'vnp_CardType.required' => ':attribute không được để trống !',
            'vnp_CardType.string' => ':attribute phải là chuỗi !',
            'vnp_OrderInfo.required' => ':attribute không được để trống !',
            'vnp_OrderInfo.string' => ':attribute phải là chuỗi !',
            'vnp_PayDate.required' => ':attribute không được để trống !',
            'vnp_PayDate.string' => ':attribute phải là chuỗi !',
            'vnp_ResponseCode.required' => ':attribute không được để trống !',
            'vnp_ResponseCode.string' => ':attribute phải là chuỗi !',
            'vnp_TransactionNo.required' => ':attribute không được để trống !',
            'vnp_TransactionNo.string' => ':attribute phải là chuỗi !',
            'vnp_TransactionStatus.required' => ':attribute không được để trống !',
            'vnp_TransactionStatus.string' => ':attribute phải là chuỗi !',
        ];
    }

    protected function attributes(){
        return [
            'vnp_TxnRef' => 'Mã tham chiếu của giao dịch',
            'vnp_Amount' => 'Số tiền thanh toán',
            'vnp_BankCode' => 'Mã Ngân hàng thanh toán',
            'vnp_BankTranNo' => 'Mã giao dịch tại Ngân hàng',
            'vnp_CardType' => 'Loại tài khoản/thẻ khách hàng sử dụng',
            'vnp_OrderInfo' => 'Thông tin mô tả nội dung thanh toán',
            'vnp_PayDate' => 'Thời gian thanh toán',
            'vnp_ResponseCode' => 'Mã phản hồi kết quả thanh toán',
            'vnp_TransactionNo' => 'Mã giao dịch ghi nhận tại hệ thống VNPAY',
            'vnp_TransactionStatus' => 'Mã phản hồi kết quả thanh toán',
        ];
    }
}

?>
