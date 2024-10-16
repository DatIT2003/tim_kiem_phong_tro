<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Motelroom;
use App\Categories;
use App\Reports;
use App\Payment;
use Illuminate\Support\Facades\DB;
class MotelController extends Controller
{
// 	public function SearchMotelAjax(Request $request){
// 		$getmotel = Motelroom::where([
// 			['district_id',$request->id_ditrict],
// 			['price','>=',$request->min_price],
// 			['price','<=',$request->max_price],
// 			['category_id',$request->id_category],
// 			['approve',1]])->get();
// 		$arr_result_search = array();
// 		foreach ($getmotel as $room) {
// 			$arrlatlng = json_decode($room->latlng,true);
// 			$arrImg = json_decode($room->images,true);
// 			$arr_result_search[] = ["id" =>$room->id,"lat"=> $arrlatlng[0],"lng"=> $arrlatlng[1],"title"=>$room->title,"address"=> $room->address,"image"=>$arrImg[0],"phone"=>$room->phone];
// 		}
// 		return json_encode($arr_result_search);
	// }


public function SearchMotel(Request $request) {
    // Lấy dữ liệu từ request
    $districtId = $request->id_district;
    $categoryId = $request->id_category;
    $minPrice = $request->min_price;
    $maxPrice = $request->max_price;

    // Tạo query tìm kiếm
    $query = Motelroom::where('approve', 1);

    // Kiểm tra điều kiện quận/huyện
    if (!empty($districtId)) {
        $query->where('district_id', $districtId);
    }

    // Kiểm tra điều kiện loại nhà trọ
    if (!empty($categoryId)) {
        $query->where('category_id', $categoryId);
    }

    // Kiểm tra khoảng giá
    if (!empty($minPrice)) {
        $query->where('price', '>=', $minPrice);
    }

    if (!empty($maxPrice)) {
        $query->where('price', '<=', $maxPrice);
    }

    // Thực thi query và lấy kết quả
    $search_motel = $query->get();

    // Fetch categories from the database (adjust table names if needed)
    $categories = DB::table('categories')->where('name', 'slug')->orderBy('id')->get();

    // Hiển thị kết quả tìm kiếm trên trang view
    return view('home.search_results')
        ->with('search_motel', $search_motel)
        ->with('categories', $categories);
}

	
    // Hiển thị kết quả tìm kiếm trên trang view

	
	
	public function user_del_motel($id){
		if (!Auth::check()) {
			return redirect('user/login');
		}
		else 
		{
			$getmotel = Motelroom::find($id);
			if(Auth::id() != $getmotel->user_id )
				return redirect('user/profile')->with('thongbao','Bạn không có quyền xóa bài đăng không phải là của bạn!');
			else
			{
				$getmotel->delete();
				return redirect('user/profile')->with('thongbao','Đã xóa bài đăng của bạn');
			}
		}
	}

	public function getMotelByCategoryId($id){
		$getmotel = Motelroom::where([['category_id',$id],['approve',1]])->paginate(3);
		$Categories = Categories::all();
		return view('home.category',['listmotel'=>$getmotel,'categories'=>$Categories]);
	}

	public function userReport($id,Request $request){
		$ipaddress = '';
	    if (getenv('HTTP_CLIENT_IP'))
	        $ipaddress = getenv('HTTP_CLIENT_IP');
	    else if(getenv('HTTP_X_FORWARDED_FOR'))
	        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	    else if(getenv('HTTP_X_FORWARDED'))
	        $ipaddress = getenv('HTTP_X_FORWARDED');
	    else if(getenv('HTTP_FORWARDED_FOR'))
	        $ipaddress = getenv('HTTP_FORWARDED_FOR');
	    else if(getenv('HTTP_FORWARDED'))
	       $ipaddress = getenv('HTTP_FORWARDED');
	    else if(getenv('REMOTE_ADDR'))
	        $ipaddress = getenv('REMOTE_ADDR');
	    else
	        $ipaddress = 'UNKNOWN';
	    $report = new Reports;
	    $report->ip_address = $ipaddress;
	    $report->id_motelroom = $id;
	    $report->status = $request->baocao;
	    $report->save();
	    $getmotel = Motelroom::find($id);
		return redirect('phongtro/'.$getmotel->slug)->with('thongbao','Cảm ơn bạn đã báo cáo, đội ngũ chúng tôi sẽ xem xét');
	}
	public function getRentedRooms()
    {
        $rented_rooms = Motelroom::where('is_rented', 1)->get();
        return view('admin.motelroom.rented', ['rented_rooms' => $rented_rooms]);
    }

    public function getAvailableRooms()
    {
        $available_rooms = Motelroom::where('is_rented', 0)->get();
        return view('admin.motelroom.available', ['available_rooms' => $available_rooms]);
    }
// 	public function index()
// {
//     // Fetch rented rooms
//     $rentedRooms = MotelRoom::where('is_rented', 1)->get();

//     // Fetch available rooms
//     $availableRooms = MotelRoom::where('is_rented', 0)->get();

//     // Pass the data to the view
//     return view('admin.motelrooms', compact('rentedRooms', 'availableRooms'));
// }

}
