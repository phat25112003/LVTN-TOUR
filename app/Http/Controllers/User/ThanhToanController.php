<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ThanhToanController extends Controller
{
    public function thanhtoan(Request $request)
    {
        $phuongthuc = $request->phuongThuc;
        $tongGia    = $request->tongGia;
        $maDatCho   = $request->maDatCho;


        $datCho = DB::table('datcho')->where('maDatCho', $maDatCho)->first();

        if (!$datCho) {
            return redirect()->route('user.thongtinuser')
                ->with('error', 'Đơn đặt chỗ không tồn tại.');
        }

        if (
            $datCho->xacNhan == -1 ||
            ($datCho->ngayhethan && now()->greaterThan($datCho->ngayhethan))
        ) {
            return redirect()->route('user.thongtinuser')
                ->with('error', 'Đơn đặt tour này đã hết hạn, không thể thanh toán.');
        }
        
        session(['maDatCho' => $maDatCho]);

        if ($phuongthuc === 'momo') {
            return $this->momopayment($tongGia);
        }

        if ($phuongthuc === 'vnpay') {
            return $this->vnpay_payment($tongGia, $maDatCho);
        }

        if ($phuongthuc === 'tại văn phòng') {
            return redirect()->route('lienhe');
        }
    }


    public function execPostRequest($url, $data)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data))
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }
    
    public function momopayment($tongGia)
    {
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";

        $partnerCode = 'MOMOBKUN20180529';
        $accessKey = 'klm05TvNBzhg7h7j';
        $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';

        $orderInfo = "Thanh toán qua MoMo";
        $amount = $tongGia;
        $orderId = time() ."";
        $redirectUrl = route('user.momo.return');
        $ipnUrl = route('user.momo.return');
        $extraData = "";

        $requestId = time() . "";
        $requestType = "payWithATM";
        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = array('partnerCode' => $partnerCode,
        'partnerName' => "Test",
        "storeId" => "MomoTestStore",
        'requestId' => $requestId,
        'amount' => $amount,
        'orderId' => $orderId,
        'orderInfo' => $orderInfo,
        'redirectUrl' => $redirectUrl,
        'ipnUrl' => $ipnUrl,
        'lang' => 'vi',
        'extraData' => $extraData,
        'requestType' => $requestType,
        'signature' => $signature);

        $result = $this->execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);  // decode json

        return redirect()->to($jsonResult['payUrl']);
    }
    
    public function momoReturn(Request $request)
    {
        // Nếu MoMo trả về không thành công
        if ($request->resultCode != 0) {
            return redirect()->route('user.thongtinuser')
                            ->with('error', 'Thanh toán thất bại!');
        }

        // Lấy dữ liệu từ query string
        $maDatCho   = session('maDatCho');   // lưu trong session khi tạo request thanh toán
        $maNguoiDung = auth()->id();
        $soTien     = $request->amount;
        $maGiaoDich = $request->transId;

        // Lưu vào DB
        DB::table('thanhtoan')->where('maDatCho',$maDatCho)->update([
            'maNguoiDung'          => $maNguoiDung,
            'phuongThucThanhToan'  => 'momo',
            'soTien'               => $soTien,
            'tinhTrangThanhToan'   => 'Đã thanh toán',
            'maGiaoDich'           => $maGiaoDich,
            'ngayThanhToan'        => now(),
        ]);

        // Cập nhật trạng thái datcho nếu cần
        DB::table('datcho')
            ->where('maDatCho', $maDatCho)
            ->update([
                'xacNhan'   => 1,
                'ngayhethan' => null   
            ]);



        return redirect()->route('user.thongtinuser')
                        ->with('success', 'Thanh toán thành công!');
    }

    public function vnpay_payment($tongGia)
    {
    $code_cart = rand(00, 9999);
    $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
    $vnp_Returnurl = route('user.vnpay.return');
    $vnp_TmnCode = "BM9C7JMF"; //Mã website tại VNPAY 
    $vnp_HashSecret = "MK2D89N46AN2MN6QVJG9SAAEB79CEESD"; //Chuỗi bí mật

    $vnp_TxnRef = $code_cart; //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
    $vnp_OrderInfo = 'thanhtoanvnpay';
    $vnp_OrderType = 'other';
    $vnp_Amount = $tongGia * 100;
    $vnp_Locale = 'vn';
    // $vnp_BankCode = 'NCB';
    $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

    $inputData = array(
      "vnp_Version" => "2.1.0",
      "vnp_Command" => "pay",
      "vnp_TmnCode" => $vnp_TmnCode,
      "vnp_Amount" => $vnp_Amount,
      "vnp_CreateDate" => date('YmdHis'),
      "vnp_CurrCode" => "VND",
      "vnp_IpAddr" => $vnp_IpAddr,
      "vnp_Locale" => $vnp_Locale,
      "vnp_OrderInfo" => $vnp_OrderInfo,
      "vnp_OrderType" => $vnp_OrderType,
      "vnp_ReturnUrl" => $vnp_Returnurl,
      "vnp_TxnRef" => $vnp_TxnRef,
    );

    if (isset($vnp_BankCode) && $vnp_BankCode != "") {
      $inputData['vnp_BankCode'] = $vnp_BankCode;
    }
    // if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
    //   $inputData['vnp_Bill_State'] = $vnp_Bill_State;
    // }

    //var_dump($inputData);
    ksort($inputData);
    $query = "";
    $i = 0;
    $hashdata = "";
    foreach ($inputData as $key => $value) {
      if ($i == 1) {
        $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
      } else {
        $hashdata .= urlencode($key) . "=" . urlencode($value);
        $i = 1;
      }
      $query .= urlencode($key) . "=" . urlencode($value) . '&';
    }

    $vnp_Url = $vnp_Url . "?" . $query;
    if (isset($vnp_HashSecret)) {
      $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
      $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
    }
    return redirect()->to($vnp_Url);
    }

    public function vnpayReturn(Request $request)
    {
        // ❌ Thanh toán thất bại
        if ($request->vnp_ResponseCode != '00') {
            return redirect()->route('user.thongtinuser')
                ->with('error', 'Thanh toán VNPay thất bại!');
        }

        // 🔐 VERIFY CHỮ KÝ
        $vnp_HashSecret = "MK2D89N46AN2MN6QVJG9SAAEB79CEESD";
        $inputData = [];

        foreach ($request->query() as $key => $value) {
            if ($key !== 'vnp_SecureHash' && $key !== 'vnp_SecureHashType') {
                $inputData[$key] = $value;
            }
        }

        ksort($inputData);
        $hashData = "";
        $i = 0;

        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . $key . "=" . $value;
            } else {
                $hashData .= $key . "=" . $value;
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        if ($secureHash !== $request->vnp_SecureHash) {
            return redirect()->route('user.thongtinuser')
                ->with('error', 'Sai chữ ký VNPay!');
        }

        // ✅ Thanh toán hợp lệ
        $maDatCho    = session('maDatCho');
        $maNguoiDung = auth()->id();
        $soTien      = $request->vnp_Amount / 100;
        $maGiaoDich  = $request->vnp_TransactionNo;

        DB::table('thanhtoan')->updateOrInsert(
            ['maDatCho' => $maDatCho],
            [
                'maNguoiDung'         => $maNguoiDung,
                'phuongThucThanhToan' => 'vnpay',
                'soTien'              => $soTien,
                'tinhTrangThanhToan'  => 'Đã thanh toán',
                'maGiaoDich'          => $maGiaoDich,
                'ngayThanhToan'       => now(),
            ]
        );


        DB::table('datcho')
            ->where('maDatCho', $maDatCho)
            ->update([
                'xacNhan'    => 1,
                'ngayhethan' => null,
            ]);

        return redirect()->route('user.thongtinuser')
            ->with('success', 'Thanh toán VNPay thành công!');
    }


}
