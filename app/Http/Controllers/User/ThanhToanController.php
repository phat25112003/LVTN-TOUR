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
        $tongGia = $request->tongGia;
        $maDatCho = $request->maDatCho;

        session(['maDatCho' => $maDatCho]);
        
        if($phuongthuc === 'momo'){
            return $this->momopayment($tongGia);
        }
        
        if ($phuongthuc === 'paypal') {
            return $this->thanhToanPaypal($tongGia, $maDatCho);
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
            ->update(['xacNhan' => 1]);



        return redirect()->route('user.thongtinuser')
                        ->with('success', 'Thanh toán thành công!');
    }

}
