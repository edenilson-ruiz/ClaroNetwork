<?php

namespace App\Http\Controllers;

use DB;
use App\Datapoint;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Illuminate\Support\Collection;



class NetworkMonitorController extends Controller
{

	public function index()
	{
		$data = $this->getData("nm3g");
		$tipoRed = "3G";
		$stats = $this->selectDatapoints();

		return view('monitor.map',compact(['data','tipoRed','stats']));
	}

	public function showData2g()
	{
		$data = $this->getData("nm2g");
		$tipoRed = "2G";
    	$stats = $this->selectDatapoints();

		return view('monitor.map',compact(['data','tipoRed','stats']));
	}

	public function showDataLte()
	{
		$data = $this->getData("nmlte");
		$tipoRed = "LTE";
    	$stats = $this->selectDatapoints();

		return view('monitor.map',compact(['data','tipoRed','stats']));
	}

	public function showDataWifi()
	{
		$data = $this->getData("nmwifi");
		$tipoRed = "WiFi";
		$stats = $this->selectDatapoints();
		
		return view('monitor.map',compact(['data','tipoRed','stats']));
	}

	public function selectDatapoints()
	{
		$stmt = Datapoint::select(
					'departamento',
					'municipio',
					'mobile_data_network_type',
					DB::raw('count(*) as count'),
					DB::raw('AVG(cell_signal_strength) as avg_bars'),
					DB::raw('AVG(cell_signal_strength_dbm) as avg_signal')
				)->groupBy('departamento','municipio','mobile_data_network_type')
		     	 ->distinct()
				 ->get();

		return $stmt;
	}

	public  function getData($network_type)
	{
		$factory = (new Factory)
		    ->withServiceAccount( __DIR__ . '/firebaseService.json')
		    // The following line is optional if the project id in your credentials file
		    // is identical to the subdomain of your Firebase project. If you need it,
		    // make sure to replace the URL with the URL of your project.
		    ->withDatabaseUri('https://networkmonitor-93124.firebaseio.com/');

		$database = $factory->createDatabase();

		$ref = $database->getReference($network_type);
      /*->orderByKey()
      ->limitToFirst(100)
      ->getSnapshot();*/

		$networkValues = $ref->getValue();

    //dd($networkValues);

		$all_rows = [];
		$datapoint = new Datapoint();
		$datapoint->truncate();

		$records = [];
		$keys_array = [];
		$search_array = array(
						'cell_asu_level' => 1,
						'cell_signal_strength' => 2,
						'cell_signal_strength_dbm' => 3,
						'data_activity' => 4,
						'data_state' => 5,
						'detailed_state' => 6,
						'device_latitude' => 7,
						'device_longitude' => 8,
						'device_position_accuracy' => 9,
						'device_speed' => 10,
						'download_speed' => 11,
						'extra_info' => 12,
						'http_connection_test' => 13,
						'is_available' => 14,
						'is_connected' => 15,
						'is_failover' => 16,
						'is_network_metered' => 17,
						'is_roaming' => 18,
						'mobile_data_network_type' => 19,
						'network_mcc' => 20,
						'network_mnc' => 21,
						'network_operator' => 22,
						'network_type' => 23,
						'service_state' => 24,
						'sim_mcc' => 25,
						'sim_mnc' => 26,
						'sim_operator' => 27,
						'sim_state' => 28,
						'socket_connection_test' => 29,
						'timestamp' => 30,
						'uid' => 31,
						'upload_speed' => 32
						);

		foreach ($networkValues as $value) {

			$all_values[] = $value;

			//$address_geocoding = $this->getAddress($value['device_latitude'],$value['device_longitude']);
			$address_geocoding = 'La Libertad, Santa Tecla';

			$address_parts = explode(',', $address_geocoding, 2);

			$departamento = $address_parts[0];
			$municipio = $address_parts[1];			

			if(array_key_exists('cell_asu_level', $value) &&
			   array_key_exists('cell_signal_strength', $value) &&
			   array_key_exists('cell_signal_strength_dbm', $value) &&
			   array_key_exists('data_activity', $value) &&
			   array_key_exists('data_state', $value) &&
			   array_key_exists('detailed_state', $value) &&
			   array_key_exists('device_latitude', $value) &&
			   array_key_exists('device_longitude', $value) &&
			   array_key_exists('download_speed', $value) &&
			   array_key_exists('extra_info', $value) &&
			   array_key_exists('is_available', $value) &&
			   array_key_exists('is_connected', $value) &&
			   array_key_exists('is_failover', $value) &&
			   array_key_exists('is_network_metered', $value) &&
			   array_key_exists('is_roaming', $value) &&
			   array_key_exists('mobile_data_network_type', $value) &&
			   array_key_exists('network_mcc', $value) &&
			   array_key_exists('network_mnc', $value) &&
			   array_key_exists('network_operator', $value) &&
			   array_key_exists('network_type', $value) &&
			   array_key_exists('sim_mcc', $value) &&
			   array_key_exists('sim_mnc', $value) &&
			   array_key_exists('sim_operator', $value) &&
			   array_key_exists('sim_state', $value) &&
			   array_key_exists('timestamp', $value) &&
			   array_key_exists('uid', $value)
		    ) {

				$cell_signal_strength_dbm = $value['cell_signal_strength_dbm'];

				if ( $cell_signal_strength_dbm == '' ||  is_null($cell_signal_strength_dbm) )
				{
					$cell_signal_strength_dbm = 0;
				}


				$records[] = [
					'cell_asu_level' => $value['cell_asu_level'],
					'cell_signal_strength' => $value['cell_signal_strength'],
					'cell_signal_strength_dbm' => $cell_signal_strength_dbm,
					//'data_activity' => $value['data_activity'],
					//'data_state' => $value['data_state'],
					//'detailed_state' => $value['detailed_state'],
					'device_latitude' => round($value['device_latitude'],7),
					'device_longitude' => round($value['device_longitude'],7),
					///'device_position_accuracy' => $value['device_position_accuracy'],
					///'device_speed' => $value['device_speed'],
					'download_speed' => $value['download_speed'],
					//'extra_info' => $value['extra_info'],
					///'http_connection_test' => $value['http_connection_test'],
					//'is_available' => $value['is_available'],
					//'is_connected' => $value['is_connected'],
					//'is_failover' => $value['is_failover'],
					//'is_network_metered' => $value['is_network_metered'],
					//'is_roaming' => $value['is_roaming'],
					'mobile_data_network_type' => $value['mobile_data_network_type'],
					//'network_mcc' => $value['network_mcc'],
					//'network_mnc' => $value['network_mnc'],
					//'network_operator' => $value['network_operator'],
					//'network_type' => $value['network_type'],
					///'service_state' => $value['service_state'],
					//'sim_mcc' => $value['sim_mcc'],
					//'sim_mnc' => $value['sim_mnc'],
					//'sim_operator' => $value['sim_operator'],
					//'sim_state' => $value['sim_state'],
					///'socket_connection_test' => $value['socket_connection_test'],
					//'timestamp' => $value['timestamp'],
					//'uid' => $value['uid'],
					///'upload_speed' => $value['upload_speed'],
					//'address_location' => $address_geocoding,
					'departamento' => $departamento,
					'municipio' => $municipio,
					'created_at' => Carbon::now()
				];			
			}			
		}		
		
		// Se insertan masivamente los registros, provenientes de Firebase
		Datapoint::insert($records);		
		$data = $all_values;		

		return $data;
	}

	public function getAddress($lat,$lng)
	{
		//$url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=13.869596,-89.850800&key=AIzaSyCEACLgCogxJS3XzgQ4JqiOGIyqzKJ2Ybw';
		$url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=".trim($lat).",".trim($lng)."&key=AIzaSyCEACLgCogxJS3XzgQ4JqiOGIyqzKJ2Ybw";
	    $json = @file_get_contents($url);
	    $data = json_decode($json);
	    $status = $data->status;
	    if($status == "OK")
	    {
	      if ($data->results[0]->address_components[2]->types[0] == 'administrative_area_level_2')
	      {
	      	$direccion = $data->results[0]->address_components[3]->long_name.", ". $data->results[0]->address_components[2]->long_name;
	      }
	      else
	      {
	      	$direccion = $data->results[0]->address_components[2]->long_name.", ". $data->results[0]->address_components[2]->long_name;
	      }
	      return $direccion;
	    }
	    else
	    {
	      return false;
	    }
	}

}
