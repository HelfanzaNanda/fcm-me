<?php

namespace App\Http\Controllers;

use App\Fcm;
use App\Notification;
use Illuminate\Http\Request;
use LaravelFCM\Facades\FCM as LaravelFCM;
use LaravelFCM\Message\{OptionsBuilder, PayloadDataBuilder, PayloadNotificationBuilder};

class ApiController extends Controller
{
    public function createToken(Request $request)
	{
		$fcm = $request->fcm_token;
		$model = Fcm::where('fcm_token', $fcm)->first();
		if (!$model) {
			Fcm::create([
				'fcm_token' => $fcm
			]);
		}

		return response()->json([
			'status' => true,
			'message' => 'success',
			'error' => [],
			'data' => []
		]);
	}


	public function sendNotif(Request $request)
	{
		$fcm = $request->fcm_token;
		$tokens = Fcm::where('fcm_token', '!=', $fcm)->get()->pluck('fcm_token')->toArray();

		$message = 'Bahaya';
		$optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20);
        $notificationBuilder = new PayloadNotificationBuilder('Fcm');
        $notificationBuilder->setBody($message)->setSound('default');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['a_data' => 'my_data']);
        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();
        
        LaravelFCM::sendTo($tokens, $option, $notification, $data);

		$fcm = Fcm::where('fcm_token', $fcm)->first();
		Notification::create([
			'fcm_id' => $fcm->id,
			'body' => '#'.$fcm->fcm_token . ' Menekan Tombol Bahay'
		]);

		return response()->json([
			'status' => true,
			'message' => 'success',
			'error' => [],
			'data' => []
		]);
	}

	public function history()
	{
		return response()->json([
			'status' => true,
			'message' => 'success',
			'error' => [],
			'data' => Notification::get()
		]);
		
	}
}
