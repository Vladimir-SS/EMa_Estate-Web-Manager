<?php

class WeatherApiController extends Controller
{
  private $appid = "43312c12c795ff35ad87b974d38f0092";
  public function get_weather_data(Request $request)
  {
    header('Content-Type: application/json; charset=UTF-8');
    $data = $this->get_data_from_api($request->get_body()['lat'], $request->get_body()['lon']);
    echo json_encode($data);
  }
  private function get_data_from_api($lat, $lon)
  {

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "http://api.openweathermap.org/data/2.5/air_pollution?lat=$lat&lon=$lon&appid=" . $this->appid,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "Accept: */*",
        "Cache-Control: no-cache",
        "Connection: keep-alive",
        "Content-Type: application/json",
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    $decoded_response_object = json_decode($response);

    curl_close($curl);

    return $decoded_response_object;
  }
}
