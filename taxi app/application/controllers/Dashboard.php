<?php
class Dashboard extends CI_Controller

{
	function __construct()
	{
		parent::__construct();

		// ob_start();
		// error_reporting(E_ALL); ini_set('display_errors', 1);

		$this->load->model('Admin_model', '', TRUE);
		// $this->load->model('User_model', '', TRUE);
		$this->load->model('User_model_new','User_model');
		$this->load->library('session');
		/*$methodList = get_class_methods($this);

		// print_r($methodList);die;

		foreach ($methodList as $key => $value) {
		if ($value!="__construct"&&$value!="get_instance"&&$value!="index"&&$value!="add_permission"&&$value!="add_manager"&&$value!="add_driver"&&$value!="add_holidays"&&$value!="add_vehicle"&&$value!="Add_promo") {
		$this->Admin_model->add_permission(array('permission_name'=>$value));
		}
		}

		die;*/
		$this->load->helper(array(
			'form',
			'url'
		));
		$this->load->helper('csv');
		$session_data = $this->session->userdata('logged_in');
		if (!$session_data)
		{
			redirect('Login');
		}

		// $this->router->fetch_class(); // class = controller

		$methodName = $this->router->fetch_method();
		$id = $this->Admin_model->methodId($methodName);

		// print_r($id->permission_id);

		if ($id != "")
		{
			$check_permission = $this->Admin_model->check_permission($session_data['department_id'], $id->permission_id);
			if ($check_permission == "")
			{
				redirect('Dashboard/error');
			}
		}

		// prevent the server from timing out
		/*set_time_limit(0);

		// include the web sockets server script (the server is started at the far bottom of this file)
		$this->load->library('PHPWebSocket');			
		$this->load->library('BefrestAuth');	*/		
	}

	public

	function error()
	{
		$this->load->view('errors/403');
	}

	function index()
	{
		// echo "<pre>";
		// var_dump($this);die;
		// start the server
		/*$Server = new PHPWebSocket();
		$Server->bind('message', 'wsOnMessage');
		$Server->bind('open', 'wsOnOpen');
		$Server->bind('close', 'wsOnClose');
		// for other computers to connect, you will probably need to change this to your LAN IP or external IP,
		// alternatively use: gethostbyaddr(gethostbyname($_SERVER['SERVER_NAME']))
		$ip = gethostbyaddr(gethostbyname($_SERVER['SERVER_NAME']));
		echo "$ip";
		$Server->wsStartServer($ip, 9300);	*/
		// error_reporting(E_ALL); ini_set('display_errors', 1);
		// $this->Admin_model->autoDeactivate();
		$data = $this->Admin_model->dashboard_details();

		// echo "<pre>"; print_r($data); echo "</pre>";

		$data['AvailableDrivers'] = $this->Admin_model->logedInDrivers();
		$this->allview("dashboard_view", $data);
	}

	public

	function allview($view, $data = NULL)
	{
		// echo"<pre>";print_r($data);echo"</pre>";die;
		$this->load->view('template/header', $data);
		$this->load->view('header');
		$this->load->view('left-sidebar');
		$this->load->view($view);
		$this->load->view('footer');
	}

	public

	function unbookedRides()
	{
		error_reporting(E_ALL);
		ini_set('display_errors',1);
		$data['unbook'] = $this->Admin_model->unbook();
		// echo count($data['unbook']);
		// echo "<pre>";
		// print_r($data);die();

		$this->allview("unbookTable", $data);

		// $this->load->view('footer');

	}

	public function deffer()
	{
		$this->load->view('deffer');
	}

	public function server_processing()
	{
		// print_r($_GET);die;

		$draw = $_GET['draw'];
		$conditions = array();
		$conditions['order'] = $_GET['order'][0]['dir'];
		
		switch ($_GET['order'][0]['column']) {
			case 0:
				$conditions['order_by'] = 'tbl_priceQuoteLog.id';
				break;
			case 1:
				$conditions['order_by'] = 'tbl_users.first_name';
				break;
			case 2:
				$conditions['order_by'] = 'tbl_priceQuoteLog.end_address';
				break;
			case 2:
				$conditions['order_by'] = 'tbl_users.first_name';
				break;
			case 2:
				$conditions['order_by'] = 'tbl_users.first_name';
				break;
			case 2:
				$conditions['order_by'] = 'tbl_priceQuoteLog.addedOn';
				break;
			default:
				$conditions['order_by'] = 'tbl_priceQuoteLog.id';
				break;
		}
		
		$conditions['limit'] = $_GET['length'];
		$conditions['offset'] = $_GET['start'];
		$conditions['search'] = $_GET['search']['value'];

		// print_r($conditions['search']);die;
		$table_row_count = $this->db->count_all('tbl_priceQuoteLog');
		$data = $this->Admin_model->unbookdata($conditions);

		if (!empty($conditions['search'])) {
			$recordsFiltered = $data['filteredRows'];
		} else {
			$recordsFiltered = $table_row_count;
		}
		for ($i=0; $i < count($data)-1; $i++) { 
			$result[$i][] = $data[$i]->id;
			$result[$i][] = $data[$i]->first_name;
			$result[$i][] = $data[$i]->end_address;
			$result[$i][] = $data[$i]->first_name;
			$result[$i][] = $data[$i]->first_name;
			$result[$i][] = $data[$i]->addedOn;
			$result[$i][] = $data[$i]->estimated_price;
		}
		$res = '{"draw":'.$draw.',
			"recordsTotal":'.$table_row_count.',
			"recordsFiltered":'.$recordsFiltered.',
			"data":'.json_encode($result).'}';
		echo "$res";
	}

	public

	function bookedRides()
	{
		$data['book'] = $this->Admin_model->booked();
		// echo count($data['book']);
		// echo "<pre>";
		// print_r($data);die();

		$this->allview("bookTable", $data);

		// $this->load->view('footer');
	}

	public

	function completedRides()
	{
		$status = 4;
		$data['archive'] = $this->Admin_model->archive($status);

		// $data['driver'] = $this->Admin_model->driver_list();

		$this->allview("archive", $data);
	}

	public

	function getPickupLocation()
	{
		if (isset($_POST['pickup_loc']))
		{
			$url = "http://snapp-backup.api.cedarmaps.com/v1/geocode/cedarmaps.streets/" . $_POST['pickup_loc'] . ".json";

			// print_r($url);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Authorization: Bearer d96796e1685505169078c14169b7a89fe9f56f6e'
			));
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$response = curl_exec($ch);
			curl_close($ch);
			$data1 = json_decode($response);
			$name = $data1->results[0]->name_en;
			$latlng = $data1->results[0]->location->center;
			$address = $data1->results[0]->address;
			$explodeData = explode(",", $latlng);
			$latitude = $explodeData[0];
			$longitude = $explodeData[1];
			foreach($data1->results as $values)
			{

				// print_r($data->name_en);
				// echo "<option value ='$values->name_en' onClick='selectCountry('$values->name_en')>$values->name_en</option>";

				$locations[] = $values;
			}

			echo "<select id='suggesstion-test'>";
			foreach($locations as $locationsvalue)
			{
				echo "<option value='" . $locationsvalue->name_en . "'>" . $locationsvalue->name_en . "</option>";
			}

			echo "</select>";

			// echo json_encode(array ("vars"=>$locations));

		}
	}

	public

	function getDropLocation()
	{
		if (isset($_POST['dropoff_loc']))
		{
			$url1 = "http://snapp-backup.api.cedarmaps.com/v1/geocode/cedarmaps.streets/" . $_POST['dropoff_loc'] . ".json";
			$ch1 = curl_init();
			curl_setopt($ch1, CURLOPT_URL, $url1);
			curl_setopt($ch1, CURLOPT_HTTPHEADER, array(
				'Authorization: Bearer d96796e1685505169078c14169b7a89fe9f56f6e'
			));
			curl_setopt($ch1, CURLOPT_CONNECTTIMEOUT, 10);
			curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
			$responses = curl_exec($ch1);
			curl_close($ch1);
			$data = json_decode($responses);
			$name1 = $data->results[0]->name_en;
			$latlng1 = $data->results[0]->location->center;
			$address1 = $data->results[0]->address;
			$explodeData1 = explode(",", $latlng1);
			$latitudeabc = $explodeData1[0];
			$longitudeabc = $explodeData1[1];

			// echo "<select id='country-list1'>";

			foreach($data->results as $values)
			{

				// print_r($data->name_en);
				// echo "<option value ='$values->name_en' onClick='selectCountry('$values->name_en')>$values->name_en</option>";

				$locations[] = $values;
			}

			echo "<select id='suggesstion-test2'>";
			foreach($locations as $locationsvalue)
			{
				echo "<option  value='" . $locationsvalue->name_en . "'>" . $locationsvalue->name_en . "</option>";
			}

			echo "</select>";

			// echo json_encode(array ("vars"=>$locations));

		}
	}

	public

	function estimateCalculator()
	{

		// print_r($_POST);die();
		// $data1 = $_POST['pickup_location'];
		// $data2 = $_POST['dropoff_location'];
		// print_r($data2);die();

		$vehicle_info = $this->User_model->selectdata('tbl_vehicle', '*', array(
			'vehicle_status' => 0
		));

		// echo "<pre>";
		// print_r($vehicle_info);

		$info['car_type'] = $vehicle_info;

		// print_r($info);die();

		$dataS = array(
			'pickup_location' => $this->input->post('pickup_locations') ,
			'dropoff_location' => $this->input->post('dropoff_locations')
		);
		if (isset($_POST['submit']))
		{

			// print_r($_POST);die();

			if (isset($_POST['dropoff_locations']))
			{
				$url1 = "http://snapp-backup.api.cedarmaps.com/v1/geocode/cedarmaps.streets/" . $_POST['dropoff_locations'] . ".json";
				$ch1 = curl_init();
				curl_setopt($ch1, CURLOPT_URL, $url1);
				curl_setopt($ch1, CURLOPT_HTTPHEADER, array(
					'Authorization: Bearer d96796e1685505169078c14169b7a89fe9f56f6e'
				));
				curl_setopt($ch1, CURLOPT_CONNECTTIMEOUT, 10);
				curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
				$responses = curl_exec($ch1);
				curl_close($ch1);
				$data = json_decode($responses);
				$name1 = $data->results[0]->name_en;
				$latlng1 = $data->results[0]->location->center;
				$address1 = $data->results[0]->address;
				$explodeData1 = explode(",", $latlng1);
				$latitudeabc = $explodeData1[0];
				$longitudeabc = $explodeData1[1];
			}

			if (isset($_POST['pickup_locations']))
			{
				$url = "http://snapp-backup.api.cedarmaps.com/v1/geocode/cedarmaps.streets/" . $_POST['pickup_locations'] . ".json";

				// print_r($url);

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					'Authorization: Bearer d96796e1685505169078c14169b7a89fe9f56f6e'
				));
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$response = curl_exec($ch);
				curl_close($ch);
				$data1 = json_decode($response);
				$name = $data1->results[0]->name_en;
				$latlng = $data1->results[0]->location->center;
				$address = $data1->results[0]->address;
				$explodeData = explode(",", $latlng);
				$latitude = $explodeData[0];
				$longitude = $explodeData[1];
			}

			$url2 = "http://173.248.157.82/admin/api/User/directions?pickup_lat=" . $latitude . "&pickup_long=" . $longitude . "&dropoff_lat=" . $latitudeabc . "&dropoff_long=" . $longitudeabc . "";

			// print_r($url2);die();

			$ch3 = curl_init();
			curl_setopt($ch3, CURLOPT_URL, $url2);
			curl_setopt($ch3, CURLOPT_CONNECTTIMEOUT, 10);
			curl_setopt($ch3, CURLOPT_RETURNTRANSFER, 1);
			$responses = curl_exec($ch3);
			curl_close($ch3);
			$dataResponse = json_decode($responses);
			$vehicle_type = $this->input->post('data');
			$vehicle_info = $this->User_model->selectdata('tbl_vehicle', '*', array(
				'vehicle_status' => 0
			));

			// print_r($vehicle_info);die();

			$minFare = $vehicle_info[0]->minimum_fare;
			$per_km = $vehicle_info[0]->per_km;
			$traffic_cahrges_permin = $vehicle_info[0]->traffic_charges;
			$distance = $dataResponse->routes[0]->legs[0]->distance->value;

			// print_r($distance);die;

			if ($distance < 1000)
			{
				$info['estimated_fare'] = $minFare;
			}
			elseif ($distance >= 1000 && $distance <= 7900)
			{
				$distanceData = $distance / 1000;
				$distanceRate = ($distanceData - 1) * $per_km;
				$info['estimated_fare'] = round(($distanceRate + $minFare) / 500) * 500;

				// $info['estimated_fare'] = $minFare;
				// print_r($info);die;

			}
			else
			{
				$newDistance = round(($distance - 1000) / 1000);
				$duration = $data->routes[0]->legs[0]->duration->value;
				$duration_in_traffic = $data->routes[0]->legs[0]->duration_in_traffic->value;
				$divisor_for_minutes = $duration % (60 * 60);
				$duration = floor($divisor_for_minutes / 60);
				$divisor_for_minutes = $duration_in_traffic % (60 * 60);
				$duration_in_traffic = floor($divisor_for_minutes / 60);
				if ($duration_in_traffic > $duration)
				{
					$extraTime = $duration_in_traffic - $duration;
				}
				else
				{
					$extraTime = 0;
				}

				// echo "$extraTime";die;

				$traffic_cahrges = $extraTime * $traffic_cahrges_permin;
				$fare = $minFare + ($newDistance * $per_km) + $traffic_cahrges;

				// //////////////////////////////////////////////////////////////////

				$newData = $distance / 1000;
				$newDis = $newData - 8;
				$calc = $per_km * 7;
				$newCal = ($newDis * 625) + $calc;
				$info['estimated_fare'] = round(($newCal + $traffic_cahrgess + $minFare) / 500) * 500;

				// print_r($info['estimated_fare']);die;

				$fare1 = $minFare + ($newDistance * $per_km);
				$info['normal_fare'] = round($fare1 / 500) * 500;
				$info['pickup_location'] = $this->input->post("pickup_locations");
				$info['dropoff_location'] = $this->input->post("dropoff_locations");

				$info['duration'] = $dataResponse->routes[0]->legs[0]->duration->text;
				$info['distance'] = $dataResponse->routes[0]->legs[0]->distance->text;
			}
		}

		$this->allview("estimateCalculator", $info);
	}

	public

	function driverCancelledRides()
	{
		$status = 52;
		$data['archive'] = $this->Admin_model->archive($status);

		// $data['driver'] = $this->Admin_model->driver_list();

		$this->allview("archive", $data);
	}

	public

	function clientCancelledRides()
	{
		$status = 50;
		$data['archive'] = $this->Admin_model->archive($status);

		// $data['driver'] = $this->Admin_model->driver_list();

		$this->allview("archive", $data);
	}

	public function requestCancelled()
	{
		$data['requestCancelled'] = $this->Admin_model->requestCancelled();
		$this->allview('requestCancelled',$data);
	}

	public

	function closedRides()
	{
		$status = 6;
		$data['archive'] = $this->Admin_model->archive($status);

		// $data['driver'] = $this->Admin_model->driver_list();

		$this->allview("archive", $data);
	}

	public

	function detailUnbookData($id)
	{

		// print_r($id);die();

		$data['userInfo'] = $this->Admin_model->userInfo($id);

		// echo "<pre>";
		//  print_r($data);die;

		$this->allview("detailUnbookData", $data);
	}

	public

	function edit_cupon($id)
	{
		$data['edit_data'] = $this->Admin_model->userDatas($id);

		// print_r($datas);die;

		if (isset($_POST['submit']))
		{
			$data1 = array(
				'start_date' => $this->input->post('start_date') ,
				'end_date' => $this->input->post('end_date') ,
			);
			$date = str_replace('/', '-', $data1['start_date']);
			$new_date = date('Y-m-d', strtotime(str_replace("-", "/", $data1['start_date'])));

			// $date1 = str_replace('/', '-', $data1['end_date']);
			// $new_date1= date('m-d-Y', strtotime($date1));

			if (!empty($data['end_date']))
			{
				$DueDate = date('Y-m-d', strtotime(str_replace("-", "/", $data1['end_date'])));
			}
			else
			{
				$DueDate = date('Y-m-d', strtotime(str_replace("-", "/", '2098-10-10')));
			}

			$data = array(
				'promo_code' => $this->input->post('promo_code') ,
				'start_date' => $new_date,
				'end_date' => $DueDate,
				'amt_type' => $this->input->post('msg') ,
				'disc_amt' => $this->input->post('dis_amt') ,
				'disc_percent' => $this->input->post('dis_percent') ,
			);

			// print_r($data); die;

			$this->load->library('form_validation');
			$this->form_validation->set_rules('start_date', 'start date', 'required');

			// $this->form_validation->set_rules('promo_value','promo value','required');

			if ($this->form_validation->run() == FALSE)
			{
				$this->allview("edit_cupon", $data);
			}
			else
			{
				if ($data['amt_type'] == '1')
				{
					$data['disc_percent'] = 0;
				}
				else
				{
					$data['disc_amt'] = 0;
				}

				if (!isset($_POST['end_date']))
				{
					$data['end_date'] = date('Y-m-d', strtotime(str_replace("-", "/", '2098-10-17')));
				}

				$this->db->where('id', $id);
				$this->db->update('tbl_coupon', $data);
				$data['edit_data'] = $this->Admin_model->userDatas($id);
			}
		}

		$this->allview("edit_promo", $data);
	}

	public

	function detailBookData($id)
	{

		// print_r($id);die();

		$data['userBookedInfo'] = $this->Admin_model->userBookedInfo($id);
		if (isset($_POST['submit']))
		{
			$data1 = array(
				'arrived_datetime' => $this->input->post('driverLocationTime') ,
				'job_start_datetime' => $this->input->post('inCar_time') ,
				'job_completed_datetime' => $this->input->post('dropoff_time') ,
				'payment_method' => $this->input->post('status1')
			);
			$this->db->where('id', $this->input->post('hidden_id'));
			$query = $this->db->update('tbl_jobs', $data1);
			$data['userBookedInfo'] = $this->Admin_model->userBookedInfo($id);
		}

		// echo "<pre>";
		// print_r($data);die;

		$this->allview("detailBookData", $data);
	}

	public

	function trip_info($id)
	{

		// print_r($id);die();

		$data['ride_info'] = $this->Admin_model->trip_info($id);

		// echo "<pre>";
		// print_r($data);echo "<pre/>";die;

		$this->allview("ride_info", $data);
	}

	public

	function add_department()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('department_name', 'department Name', 'required|min_length[1]|max_length[125]|is_unique[tbl_department.department_name]');

		// $datetime = date("Y-m-d H:i:s");

		$data = array(
			'department_name' => $this->input->post('department_name') ,
			'department_description' => $this->input->post('department_description')
		);
		if ($this->form_validation->run() == TRUE)
		{
			$this->Admin_model->add_department($data);
			$this->session->set_flashdata('msg', 'Department Added Successfully');
		}

		$data['department_list'] = $this->Admin_model->select("tbl_department", "*");

		// echo "<pre>"; print_r($data);echo "</pre>";die;

		$this->load->view('template/header');
		$this->load->view('header');
		$this->load->view('left-sidebar');
		$this->load->view('add_department', $data);
		$this->load->view('footer');
	}

	public

	function department_list()
	{
		$data['department_list'] = $this->Admin_model->select("tbl_department", "*");

		// print_r($data);die;

		$this->allview("department_list", $data);
	}

	public

	function department_details($id)
	{
		$where = array(
			'department_id' => $id,
		);
		$data['department_details'] = $this->Admin_model->get_where("tbl_department", $where);

		// echo "<pre>"; print_r($data);echo "</pre>";die;

		$this->allview("department_details", $data);
	}

	public

	function edit_department($id)
	{
		$where = array(
			'department_id' => $id,
		);
		if (isset($_POST['submit']))
		{
			$data['data'] = array(
				'department_name' => $this->input->post('department_name') ,
				'department_description' => $this->input->post('department_description')
			);
			$data['edit_department'] = $this->Admin_model->updateWhere("tbl_department", $where, $data['data']);
		}

		$data['department_details'] = $this->Admin_model->get_where("tbl_department", $where);
		$this->allview("edit_department", $data);
	}

	public

	function add_permission()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('permission_name', 'permission Name', 'required|min_length[1]|max_length[125]|is_unique[tbl_permission.permission_name]');

		// $datetime = date("Y-m-d H:i:s");

		$data = array(
			'permission_name' => $this->input->post('permission_name') ,
			'permission_description' => $this->input->post('permission_description')
		);
		if ($this->form_validation->run() == FALSE)
		{
			$data['permission_list'] = $this->Admin_model->select("tbl_permission", "*");

			// echo "<pre>"; print_r($data);echo "</pre>";die;

			$this->load->view('template/header');
			$this->load->view('header');
			$this->load->view('left-sidebar');
			$this->load->view('add_permission', $data);
			$this->load->view('footer');
		}
		else
		{
			$this->Admin_model->add_permission($data);
			$this->session->set_flashdata('msg', 'Permission added Successfully');
			$data['permission_list'] = $this->Admin_model->select("tbl_permission", "*");

			// echo "<pre>"; print_r($data);echo "</pre>";die;

			$this->load->view('template/header');
			$this->load->view('header');
			$this->load->view('left-sidebar');
			$this->load->view('add_permission', $data);
			$this->load->view('footer');
		}
	}

	public

	function user_role()
	{
		$data['department'] = $this->db->get('tbl_department')->result();
		$data['permission'] = $this->db->get('tbl_permission')->result();
		$data['department_permission'] = $this->db->get('tbl_department_permission')->result();
		$data['users_department'] = $this->db->get('tbl_users_department')->result();

		// print_r($data);die;

		$this->allview("user_role", $data);
	}

	public

	function department_permission($dept_id)
	{
		$data['department_id'] = $dept_id;
		$department_details = $this->Admin_model->selectWhere("tbl_department", "department_name", array(
			'department_id' => $dept_id
		));
		$data['department_name'] = $department_details[0]->department_name;
		$department_permissions = $this->Admin_model->selectWhere("tbl_department_permission", "permission_id", array(
			'department_id' => $dept_id
		));
		$data['department_permission'] = array();
		foreach($department_permissions as $key => $value)
		{
			array_push($data['department_permission'], $value->permission_id);
		}

		$data['permission'] = $this->db->query("SELECT * FROM `tbl_permission` ORDER BY permission_description ASC ")->result();

		// $key = array_search("2", array_column($data['permission'], 'permission_id'));
		// echo "<pre>"; print_r($data); echo "</pre>"; die;

		$this->allview('department_permission', $data);
	}

	public

	function assign_permition()
	{

		// echo "<pre>"; print_r($this->input->get()); echo "</pre>";die;

		$this->Admin_model->delete(array(
			'department_id' => $this->input->get('department_id')
		) , "tbl_department_permission");
		foreach($this->input->get() as $key => $value)
		{
			if ($key != "department_id" && $key != "submit" && $key != "department_name")
			{
				$this->Admin_model->insert("tbl_department_permission", array(
					'department_id' => $this->input->get('department_id') ,
					'permission_id' => $value
				));
			}
		}

		$department_permissions = $this->Admin_model->selectWhere("tbl_department_permission", "permission_id", array(
			'department_id' => $this->input->get('department_id')
		));

		// print_r($department_permissions);

		$have_perm = array();
		foreach($department_permissions as $key => $value)
		{
			array_push($have_perm, $value->permission_id);
		}

		$data['department_id'] = $this->input->get('department_id');
		$data['department_permission'] = $have_perm;

		// echo "<pre>"; print_r($data); echo "</pre>";die;

		$data['permission'] = $this->db->get('tbl_permission')->result();
		$data['department_name'] = $this->input->get('department_name');
		$this->allview('department_permission', $data);
	}

	public

	function vehicle_type_list()
	{
		$data['vehicle_type_list'] = $this->Admin_model->select("tbl_vehicle", "*");

		// print_r($data);die;

		$this->allview("vehicle_type_list", $data);
	}

	public

	function vehicle_type_details($id)
	{
		$where = array(
			'id' => $id,
		);
		$data['vehicle_type_details'] = $this->Admin_model->get_where("tbl_vehicle", $where);

		// echo "<pre>"; print_r($data);echo "</pre>";die;

		$this->allview("vehicle_type_details", $data);
	}

	public

	function edit_vehicle_type($id)
	{
		if (isset($_POST['submit']))
		{

			// print_r($_POST);die;

			$data['data'] = array(
				"vehicle_type" => $this->input->post('vehicle_type') ,
				"base_rate" => $this->input->post('base_rate') ,
				"per_km" => $this->input->post('per_km') ,
				"per_min" => $this->input->post('per_min') ,
				"waiting_charge" => $this->input->post('waiting_charge') ,
				"traffic_charges" => $this->input->post('traffic_charges') ,
				"restriction" => $this->input->post('restriction')
			);
			$data['edit_vehicle_type'] = $this->Admin_model->update("tbl_vehicle", $id, $data['data']);
			$this->session->set_flashdata('msg', 'Vehicle Type Updated Successfully');
		}

		$where = array(
			'id' => $id,
		);
		$data['vehicle_type_details'] = $this->Admin_model->get_where("tbl_vehicle", $where);
		$this->allview("edit_vehicle_type", $data);
	}

	function add_vehicle()
	{
		$data = array();
		$this->load->library('form_validation');
		$this->form_validation->set_rules('vehicle_type', 'Vehicle Type', 'required|min_length[1]|max_length[125]|is_unique[tbl_vehicle.vehicle_type]');
		$this->form_validation->set_rules('capacity', 'Capacity', 'required|numeric');
		$this->form_validation->set_rules('per_km', 'Per km', 'required|numeric');
		$this->form_validation->set_rules('per_min', 'Per Minute', 'numeric');
		$this->form_validation->set_rules('base_rate', 'Base Rate', 'numeric');
		$this->form_validation->set_rules('waiting_charge', 'Waiting Charge', 'numeric');
		$this->form_validation->set_rules('traffic_charges', 'Traffic Charges', 'numeric');
		$data['vehicle_type'] = $this->Admin_model->get_vehicle_list();

		// print_r($data);die;

		if ($this->form_validation->run() == FALSE)
		{

			// $this->session->set_flashdata('msg', 'Error Adding Vehicle');
			// $this->allview("add_vehicle",$data);

		}
		else
		{
			$this->Admin_model->add_vehicle_type();
			$this->session->set_flashdata('msg', 'Vehicle Added Successfully');

			// redirect("Dashboard/add_vehicle");
		}

		$this->allview("add_vehicle", $data);
	}

	public

	function delete_vehicle_type($id)
	{
		$this->Admin_model->record_delete("tbl_vehicle", $id);
		redirect('Dashboard/vehicle_type_list');
	}

	public

	function holiday_list()
	{
		$data['holiday_list'] = $this->Admin_model->select("tbl_holidays", "*", 'holiday', 'desc');

		// print_r($data);die;

		$this->allview("holiday_list", $data);
	}

	public

	function add_holidays()
	{
		$data = array(
			'holiday' => date('Y-m-d', strtotime($this->input->get('add_holiday'))) ,
		);

		// print_r($data);die;

		$this->load->library('form_validation');
		$this->form_validation->set_rules('add_holiday', 'holiday date', 'required');
		$this->Admin_model->add_holiday($data);
		$this->session->set_flashdata('msg', 'Holiday Date Added Successfully');
		if ($this->form_validation->run() == FALSE)
		{
			$this->session->set_flashdata('msg', 'Something went Wrong');
			$this->allview("add_holidays");
		}
	}

	public

	function edit_holiday($id)
	{
		if (isset($_POST['submit']))
		{
			$data['data'] = array(
				'holiday' => date('Y-m-d', strtotime($this->input->post('holiday'))) ,
			);
			$data['edit_holiday'] = $this->Admin_model->update("tbl_holidays", $id, $data['data']);
		}

		$where = array(
			'id' => $id,
		);
		$data['holiday_details'] = $this->Admin_model->get_where("tbl_holidays", $where);
		$this->allview("edit_holiday", $data);
	}

	public

	function user_cancelled_trips()
	{
		$data['cancelled_trips'] = $this->Admin_model->user_cancelled_trips();
		$this->allview("user_cancelled_trips", $data);
	}

	public

	function user_cancelled_trips_detail($id)
	{

		// print_r($user_id);die;

		$data['cancelled_trips_detail'] = $this->Admin_model->user_cancelled_trips_detail($id);
		$this->allview("user_cancelled_trips_detail", $data);
	}

	public

	function driver_cancelled_trips()
	{
		$data['cancelled_trips'] = $this->Admin_model->driver_cancelled_trips();
		$this->allview("driver_cancelled_trips", $data);
	}

	public

	function driver_cancelled_trips_detail($id)
	{

		// print_r($user_id);die;

		$data['cancelled_trips_detail'] = $this->Admin_model->driver_cancelled_trips_detail($id);
		$this->allview("driver_cancelled_trips_detail", $data);
	}

	public

	function low_rating()
	{
		$data['low_rating'] = $this->Admin_model->low_rating();

		// echo"<pre>";print_r($data);echo "<pre/>";die;

		$this->allview("low_rating", $data);
	}

	public

	function low_overall_rating()
	{
		$data['low_overall_rating'] = $this->Admin_model->low_overall_rating();

		// echo"<pre>";print_r($data);echo "<pre/>";die;

		$this->allview("low_overall_rating", $data);
	}

	public

	function alert()
	{
		// $data['driver_list'] = $this->Admin_model->users_list("2");
		$data['result'] = $this->Admin_model->exp_date();
		$this->allview("alert", $data);
	}

	public

	function autoDeactivate()
	{

		// echo "string";die;

		$this->Admin_model->autoDeactivate();
	}

	public

	function driver_refuses($id = NULL)
	{
		$data['all'] = $this->Admin_model->driver_refuses($id);

		// echo"<pre>";print_r($data);echo"</pre>";die;

		$count = 0;
		$array = array();
		foreach($data['all'] as $value)
		{
			if ($value->is_cancel == '2')
			{
				$count++;
				if ($count > 0)
				{
					array_push($array, array(
						'driver_id' => $value->driver_id,
						'job_id' => $value->id,
						'count' => $count,
						'name' => $value->name,
						'first_name' => $value->first_name,
						'last_name' => $value->last_name,
						'email' => $value->email,
						'phone' => $value->phone,
						'profile_pic' => $value->profile_pic,
						'date_created' => $value->date_created,
						'client_id' => $value->user_id
					));
				}
			}
			else
			{
				$count = 0;
			}
		}

		$length = count($array);
		$data['all_cancelled'] = $array;

		// echo"<pre>";print_r($array);echo"</pre>";die;

		$baln_array = array(); /* $baln_array contains row cancelled in row >2 times*/
		$cntr = 0;
		for ($i = 0; $i < $length; $i++)
		{
			if ($array[$i + 1]['count'] > $array[$i]['count'])
			{
				$baln_array[$cntr][] = $array[$i];
			}

			if ($array[$i + 1]['count'] < $array[$i]['count'])
			{
				$baln_array[$cntr][] = $array[$i];
				$cntr++;
			}
		}

		/* $in_array contain job cacelled >= three times, in row only*/
		$index = 0;
		$in_array = array();
		foreach($baln_array as $value)
		{

			// echo(count($value))."<br />";

			if (count($value) >= 3)
			{

				// echo "<pre>"; print_r($value); echo "</prer>";

				$in_array[$index] = $value;

				// echo "<pre>"; print_r($in_array); echo "</prer>";

				$index++;
			}
		}

		// die;

		$data['in_row'] = $in_array;

		// echo"<pre>";print_r($baln_array);echo"</pre>";die;

		$l = count($baln_array);
		for ($i = 0; $i < $l; $i++)
		{
			$tot = max($baln_array[$i]);
			if ($tot['count'] >= 3)
			{
				$data['total'][$i] = max($baln_array[$i]);
			}
		}

		// $data['total'] = max($baln_array[0]);
		// echo"<pre>";print_r($data['total']);echo"</pre>";die;

		if ($id != NULL)
		{
			$this->allview("driver_refuses_details", $data);
		}
		else
		{
			$this->allview("driver_refuses", $data);
		}
	}

	public

	function payments()
	{
		$data['payments'] = $this->Admin_model->select("tbl_payment", "*", "id", "desc");

		// echo"<pre>";print_r($data);echo"</pre>";die;

		$this->allview("payments", $data);

		// print_r($data);die;

	}

	public

	function googlemaps()
	{
		$data['polygon1'] = $this->Admin_model->get("tbl_polygon1");
		$data['polygon2'] = $this->Admin_model->get("tbl_polygon2");

		// print_r($data);die;
		// print_r($data['markerClient']);die;

		$this->load->view('template/header');
		$this->load->view('header');
		$this->load->view('left-sidebar');
		$this->load->view("all_map", $data);
		$this->load->view('footer');

		// $this->allview("convex");

	}

	public

	function AvailableDrivers()
	{
		$data['polygon1'] = $this->Admin_model->get("tbl_polygon1");
		$data['polygon2'] = $this->Admin_model->get("tbl_polygon2");
		$data['mapNotifications'] = $this->Admin_model->mapNotificationsAll();

		// echo "<pre>";
		// print_r($data['mapNotifications']);die;
		// $this->db->query("select * from tbl_vehicle where id = ".$data['mapNotifications'])->result();
		// print_r($data['markerClient']);die;

		$this->load->view('template/header');
		$this->load->view('header');
		$this->load->view('left-sidebar');
		$this->load->view("AvailableDrivers", $data);
		$this->load->view('footer');

		// $this->allview("convex");

	}

	public

	function OngoingTrips()
	{
		$data['polygon1'] = $this->Admin_model->get("tbl_polygon1");
		$data['polygon2'] = $this->Admin_model->get("tbl_polygon2");
		$data['mapNotifications'] = $this->Admin_model->mapNotificationsAll();

		// print_r($data);die;
		// print_r($data['markerClient']);die;

		$this->load->view('template/header');
		$this->load->view('header');
		$this->load->view('left-sidebar');
		$this->load->view("OngoingTrips", $data);
		$this->load->view('footer');

		// $this->allview("convex");

	}

	public

	function NewRequests()
	{
		$data['polygon1'] = $this->Admin_model->get("tbl_polygon1");
		$data['polygon2'] = $this->Admin_model->get("tbl_polygon2");
		$data['mapNotifications'] = $this->Admin_model->mapNotificationsAll();

		// print_r($data);die;
		// print_r($data['markerClient']);die;

		$this->load->view('template/header');
		$this->load->view('header');
		$this->load->view('left-sidebar');
		$this->load->view("NewRequests", $data);
		$this->load->view('footer');

		// $this->allview("convex");

	}

	public

	function convexmap()
	{

		$this->load->view("convex");

	}

	public

	function marker($flag)
	{

		// Get the co-ordinates from the database using our model

		if ($flag == 1)
		{
			$markerFree = $this->Admin_model->get_coordinates("1");
			echo json_encode($markerFree);
			exit();
		}
		elseif ($flag == 2)
		{
			$markerOnTrip = $this->Admin_model->get_coordinates("2");
			echo json_encode($markerOnTrip);
			exit();
		}
		elseif ($flag == 3)
		{
			$markerRequesting = $this->Admin_model->get_coordinatesClient();
			echo json_encode($markerRequesting);
			exit();
		}
		else
		{
			$markerAll = $this->Admin_model->get_coordinates("0");
			echo json_encode($markerAll);
			exit();
		}
	}

	public

	function pushMessage()
	{
		$to = $this->input->get('to');

		// print_r($to);

		$user_login_data = $this->Admin_model->getUserLogin($to);

		// print_r($user_login_data);die;

		$count = count($user_login_data);
		foreach($user_login_data as $value)
		{
			if ($value->token_id != "")
			{

				// $push = $this->Admin_model->android($value->token_id, $this->input->get('message'));

			}
		}

		// print_r($_GET['message']);

		echo "Message Sent..!!! " . "To: " . "$count" . " Users";
	}

	public

	function series()
	{
		for ($i = 1; $i <= 5; $i++)
		{
			for ($z = 1; $z <= $i; $z++)
			{
				echo "$z";
			}

			echo "<br />";
		}

		$y = 0;
		$j = 1;
		echo $y . " , " . $j;
		while ($counter <= 20)
		{
			$z = $y + $j;
			echo " , " . $z;
			$y = $j;
			$j = $z;
			$counter++;
		}
	}

	public

	function getCoords()
	{
		$n = $this->Admin_model->getCoords();

		// $newCoords = $n[0]->latitude;
		// print_r($newCoords);die;

		return $n;
	}

	public

	function new_map()
	{

		// $this->load->helper('file');
		// $this->allview('test');

		$data = "";
		$data['information'] = $this->Admin_model->get_coordinates($data);
		$this->load->view('template/header');
		$this->load->view('header');
		$this->load->view('left-sidebar');
		$this->load->view('sort', $data);
	}

	// public function update_promo(){
	// $data1 = array(
	// 	'start_date'=>$this->input->post('start_date'),
	// 	'end_date'=>$this->input->post('end_date'),
	// 	);
	// $date = str_replace('/', '-', $data1['start_date']);
	// $new_date= date('Y-d-m', strtotime($date));
	// $date1 = str_replace('/', '-', $data1['end_date']);
	// $new_date1= date('Y-d-m', strtotime($date1));
	// $data = array(
	// 		'promo_type'=>$this->input->post('yes'),
	// 		'promo_code' => $this->input->post('promo_code'),
	// 		'user_id' => $_SESSION['logged_in']['id'],
	// 		'start_date'=>$new_date,
	// 		'end_date'=>$new_date1,
	// 		'promo_use'=>$this->input->post('myCheck'),
	// 		'amt_type'=>$this->input->post('msg'),
	// 		'disc_amt'=>$this->input->post('dis_amt'),
	// 		'disc_percent'=>$this->input->post('dis_percent'),
	// 		'code_usage' =>$this->input->post('code_usage'),
	// 		);
	// //print_r($data);die;
	// $this->load->library('form_validation');
	// $this->form_validation->set_rules('start_date','start date','required');
	// // $this->form_validation->set_rules('promo_value','promo value','required');
	// if($this->form_validation->run() == FALSE){
	// $this->allview("add_promo",$data);
	// }else{
	// if($data['promo_type'] == '1' /*&& !empty($data['code_usage'])*/){
	// if($data['promo_use'] != '1'){
	// unset($data['promo_use']);
	// }
	// if($data['amt_type'] == '1'){
	// unset($data['disc_percent']);
	// }
	// else{
	// unset($data['disc_amt']);
	// }
	// $result= $this->Admin_model->add_promo($data);
	// }
	// if($data['promo_type'] == '2' && !empty($data['code_usage'])){
	// for($i =1; $i <= $data['code_usage']; $i++ ){
	// 		$arr[]= uniqid('MASIR');
	// }
	// if(empty($data['disc_percent'])){
	// foreach($arr as $arr1){
	// $query = $this->db->query("INSERT INTO tbl_coupon (promo_type,promo_code,user_id,start_date,end_date,amt_type,disc_amt,disc_percent) VALUES ($data[promo_type],'$arr1',$data[user_id],'$new_date','$new_date1',$data[amt_type],$data[disc_amt],0)");
	// 	}
	// }
	// if(empty($data['disc_amt'])){
	// foreach($arr as $arr1){
	// $query = $this->db->query("INSERT INTO tbl_coupon (promo_type,promo_code,user_id,start_date,end_date,amt_type,disc_amt,disc_percent) VALUES ($data[promo_type],'$arr1',$data[user_id],'$new_date','$new_date1',$data[amt_type],0,$data[disc_percent])");
	// 		}
	// 	}
	// }
	// 	$this->allview("add_promo");
	// 	}
	// }

	public

	function Add_promo()
	{
		$data1 = array(
			'start_date' => $this->input->post('start_date') ,
			'end_date' => $this->input->post('end_date') ,
		);
		$date = str_replace('/', '-', $data1['start_date']);
		$new_date = date('Y-m-d', strtotime(str_replace("-", "/", $data1['start_date'])));

		// $date1 = str_replace('/', '-', $data1['end_date']);
		// $new_date1= date('m-d-Y', strtotime($date1));

		if (!empty($data1['end_date']))
		{
			$DueDate = date('Y-m-d', strtotime(str_replace("-", "/", $data1['end_date'])));
		}
		else
		{
			$DueDate = date('Y-m-d', strtotime(str_replace("-", "/", '2098-10-10')));
		}

		$data = array(
			'promo_type' => $this->input->post('yes'),
			'promo_code' => $this->input->post('promo_code'),
			'user_id' => $_SESSION['logged_in']['id'],
			'start_date' => $new_date,
			'end_date' => $DueDate,
			'promo_use' => $this->input->post('myCheck'),
			'amt_type' => $this->input->post('msg'),
			'disc_amt' => $this->input->post('dis_amt'),
			'disc_percent' => $this->input->post('dis_percent'),
			'code_usage' => $this->input->post('code_usage'),
		);

		// if(isset($_POST['submit'])){
		// print_r($data);die;}

		$this->load->library('form_validation');
		$this->form_validation->set_rules('start_date', 'start date', 'required');

		// $this->form_validation->set_rules('promo_value','promo value','required');

		if ($this->form_validation->run() == FALSE)
		{
			$this->allview("add_promo", $data);
		}
		else
		{
			if ($data['promo_type'] == '1' /*&& !empty($data['code_usage'])*/)
			{
				if ($data['promo_use'] != '1')
				{
					unset($data['promo_use']);
				}

				if ($data['amt_type'] == '1')
				{
					unset($data['disc_percent']);
				}
				else
				{
					unset($data['disc_amt']);
				}

				if (!isset($_POST['end_date']))
				{
					$data['end_date'] = date('Y-m-d', strtotime(str_replace("-", "/", '2098-10-17')));
				}

				$result = $this->Admin_model->add_promo($data);
			}

			if ($data['promo_type'] == '2' && !empty($data['code_usage']))
			{
				for ($i = 1; $i <= $data['code_usage']; $i++)
				{
					$arr[] = uniqid('MASIR');
				}

				if (empty($data['disc_percent']))
				{
					foreach($arr as $arr1)
					{
						$query = $this->db->query("INSERT INTO tbl_coupon (promo_type,promo_code,user_id,start_date,end_date,amt_type,disc_amt,disc_percent) VALUES ($data[promo_type],'$arr1',$data[user_id],'$new_date','$DueDate',$data[amt_type],$data[disc_amt],0)");
					}
				}

				if (empty($data['disc_amt']))
				{
					foreach($arr as $arr1)
					{
						$query = $this->db->query("INSERT INTO tbl_coupon (promo_type,promo_code,user_id,start_date,end_date,amt_type,disc_amt,disc_percent) VALUES ($data[promo_type],'$arr1',$data[user_id],'$new_date','$DueDate',$data[amt_type],0,$data[disc_percent])");
					}
				}
			}

			$this->allview("add_promo");
		}
	}

	public

	function cupon_list()
	{
		$data['cupon_list'] = $this->Admin_model->cupon_list();
		if (isset($_POST['submit']))
		{
			$this->Admin_model->ExportCSV();
		}

		$this->allview("cupon_list", $data);
	}

	public

	function reports($dayIndex)
	{
			$data['driver_list'] = $this->Admin_model->getReports($dayIndex);
			$this->allview('reports', $data);
	}

	public

	function addSubtractFund($userType)
	{

		// print_r($_POST);

		if ($userType == 2)
		{
			$data['driver_list'] = $this->Admin_model->users_list("2");
			$data['userType'] = $userType;
			$this->allview('addSubtractFund', $data);
		}
		else
		{
			$data['driver_list'] = $this->Admin_model->users_list("0");
			$data['userType'] = 0;
			$this->allview('addSubtractFund', $data);
		}
	}

	public

	function addSubtractFundEdit($id)
	{

		// print_r($_POST);

		if (isset($_POST['submit']))
		{
			$data['update_users'] = $this->Admin_model->update_users($id);
		}

		$data['one_user_info'] = $this->Admin_model->one_user_info($id);
		$this->load->view('template/header');
		$this->load->view('header');
		$this->load->view('left-sidebar');
		$this->load->view('addSubtractFundEdit', $data);
		$this->load->view('footer');
	}

	public

	function add_vehicle_model()
	{

		// print_r("expression");die;

		$this->load->library('form_validation');
		$this->form_validation->set_rules('model_name', 'Model Name', 'required|min_length[1]|max_length[125]|is_unique[tbl_vehicle_model.model_name]');

		// $datetime = date("Y-m-d H:i:s");

		$data = array(
			'model_name' => $this->input->post('model_name')
		);
		if ($this->form_validation->run() == TRUE)
		{
			$this->Admin_model->insert("tbl_vehicle_model", $data);
			$this->session->set_flashdata('msg', 'Vehicle Model Added Successfully');
		}
		else
		{
		}

		$data['model_list'] = $this->Admin_model->select("tbl_vehicle_model", "*");

		// echo "<pre>"; print_r($data);echo "</pre>";die;

		$this->allview('add_vehicle_model', $data);
	}

	public

	function traffic()
	{
		$db_result['tehran'] = $this->Admin_model->get('tbl_tehran');

		// echo "<pre>";
		// print_r($db_result);die;

		$this->allview('traffic', $db_result);
	}

	public

	function update_traffic()
	{

		// print_r($_POST);

		$update = $this->Admin_model->update('tbl_tehran', $_POST['location_id'], array(
			'traffic_level' => $_POST['traffic_level']
		));
		if ($update)
		{
			echo true;
		}
		else
		{
			echo false;
		}
	}

	public

	function mapNotifications()
	{

		// $this->db->query("UPDATE tbl_jobs set modified_on=now()");

		$db_result = $this->Admin_model->mapNotificationsAll();

		// echo "<pre>";
		// print_r($db_result);
		// die();

		echo json_encode($db_result);
	}

	public

	function assign_department($id)
	{
		$data['manager_list'] = $this->Admin_model->managerInfo();
		$data['department_list'] = $this->Admin_model->select('tbl_department', '*');

		// echo "<pre>"; print_r($data); echo "</pre>";die;

		$this->allview('assign_department', $data);
	}

	public

	function update_department()
	{

		// print_r($_POST);die;

		$this->Admin_model->delete(array(
			'user_id' => $_POST['user_id']
		) , 'tbl_users_department');
		$update = $this->Admin_model->insert('tbl_users_department', $_POST);
		if ($update)
		{
			echo true;
		}
		else
		{
			echo false;
		}
	}

	public

	function profile($id)
	{
		
		$data['user_info'] = $this->Admin_model->one_user_info($id);

		// print_r($data);die;

		$this->allview('profile', $data);
	}

	public

	function mprofile($id)
	{
		$data['user_info'] = $this->Admin_model->one_mngr_info($id);
		

		// print_r($data);die;

		$this->allview('profile', $data);
	}

	public

	function logedInDrivers()
	{
		if (isset($_POST['logout']))
		{

			// print_r($_POST);

			$this->Admin_model->update('tbl_login', $_POST['logout'], array(
				'status' => 0
			));

			$logData = array('user_id' => $_POST['user_id'],'logedOutBy'=>'admin','logedOutOn'=>date("Y-m-d H:i:s") );
            $this->Admin_model->insert('tbl_logout', $logData);
		}

		$db_result['logedInDrivers'] = $this->Admin_model->logedInDrivers();

		// echo "<pre>";
		// print_r($db_result);

		$this->allview('logedInDrivers', $db_result);
	}

	public

	function callingMode()
	{

		// print_r($_GET);

		if (isset($_POST['sub']))
		{
			$date = date("Y-m-d H:i:s");
			$this->Admin_model->update('tbl_callingMode', 1, array(
				'callingMode' => $_POST['callingMode'],
				'number' => $_POST['call'],
				'CallTime' => 60*$_POST['CallTime'],
				'BeepTime' => (60*$_POST['BeepTimeMin'])+$_POST['BeepTimeSec'],
				'modifiedOn' => $date
			));
		}

		$db_result = $this->Admin_model->selectWhere('tbl_callingMode', '*', array(
			'id' => 1
		));

		// print_r($db_result);

		$data['callingMode'] = $db_result[0];

		// print_r($data);

		// $data['number'] = $db_result[0]->number;
		$this->allview('callingMode', $data);
	}

	public

	function idleRides()
	{
		$data['idleRides'] = $this->Admin_model->idleRides();
		$this->allview("idleRides", $data);
	}

	public

	function fare_cal($data)
	{
		$vehicle_info = $this->User_model->fare_cal($data);
		$job_id = array(
			'job_id' => $data['job_id'],
		);
		$way_points = $this->User_model->get_points($job_id);

		// print_r($vehicle_info);die;
		// print_r($way_points);die;

		/*foreach ($way_points as $key => $value) {
		print_r($value);
		}die;*/
		$where = array(
			'id' => $data['job_id'],
		);
		$waiting_time_array = $this->User_model->selectdata("tbl_jobs", "estimate,destination_changed,waiting_time", $where);
		$waiting_time = $waiting_time_array[0]->waiting_time;

		// $to_time = strtotime($waiting_time);
		// $waiting_time = '21:30:30';

		$parsed = date_parse($waiting_time);

		// $seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];

		$minutes = $parsed['hour'] * 60 + $parsed['minute'] + $parsed['second'] / 60;
		$minutes = round($minutes);

		// print_r($minutes);die;
		// $from_time = 4;
		// echo round(abs($to_time - $from_time) / 60,2). " minute";
		// print_r($minutes);die;

		$l = count($way_points);

		// print_r($l);

		$distance = 0.0;
		$dist = 0.0;
		$unit = "K";
		for ($i = 0; $i <= $l - 2; $i++)
		{
			$lat1 = $way_points[$i]->latitude;
			$lon1 = $way_points[$i]->longitude;
			$lat2 = $way_points[$i + 1]->latitude;
			$lon2 = $way_points[$i + 1]->longitude;

			// print_r($lat1);

			$result = $this->User_model->distance($lat1, $lon1, $lat2, $lon2, $unit);
			if ($result > 0)
			{
				$dist+= $result;
			}

			// echo "\n";

		}

		$distance = round($dist, 3);
		$data['distance'] = (is_nan($distance)) ? '0' : "$distance";

		// print_r($dist);
		// die;
		// echo "$distance"."<br />";
		// echo "$minutes";
		// die;
		// print_r($waiting_time_array);die;

		if ($waiting_time_array[0]->destination_changed != 1 && $minutes <= 4)
		{ // for estimate as final fare
			$est = explode(',', $waiting_time_array[0]->estimate);
			$data['fare'] = $est[2];

			// print_r($est);die;

		}
		else
		{ // if destination changed
			$check_trafficarray = array();

			// $tehran = $this->User_model->selectdata('tbl_tehran','latitude,longitude');

			foreach($way_points as $key => $value)
			{

				// print_r($value->latitude);

				$latitude = $value->latitude;
				$longitude = $value->longitude;
				$check_traffic = $this->db->query("SELECT ( 6371 * acos ( cos ( radians($latitude) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians($longitude) ) + sin ( radians($latitude) ) * sin( radians( latitude ) ) ) ) AS `distance`,`traffic_level` FROM tbl_tehran having `distance` <= 0.100 order by distance")->row();
				if (!empty($check_traffic))
				{
					$check_trafficarray[] = $check_traffic->traffic_level;
				}

				// print_r($check_traffic);

				/*foreach ($tehran as $key => $value) {

				// print_r($value);

				}*/
			}

			// die;
			// print_r($check_trafficarray);die;

			$c = array_count_values($check_trafficarray);
			$val = array_search(max($c) , $c);
			if ($val == 1)
			{
				$per_km = 950;
			}
			elseif ($val == 2)
			{
				$per_km = 850;
			}
			elseif ($val == 3)
			{
				$per_km = 800;
			}
			else
			{
				$per_km = 700;
			}

			if ($distance <= 1)
			{
				if ($minutes <= 4)
				{
					$fare = $vehicle_info->minimum_fare;
				}
				else
				{
					$newTime = $minutes - 4;
					$fare = $vehicle_info->minimum_fare + ($vehicle_info->waiting_charge * $newTime);
				}
			}
			else
			{
				$newDistance = $distance - 1;
				if ($minutes <= 4)
				{
					$fare = ($newDistance * $per_km) + $vehicle_info->minimum_fare;
				}
				else
				{
					$newTime = $minutes - 4;
					$fare = ($newDistance * $per_km) + $vehicle_info->minimum_fare + ($vehicle_info->waiting_charge * $newTime);
				}
			}

			// print_r($fare);die;
			// $data['fare'] = $fare;
			// print_r($vehicle_info->minimum_fare);die;

			$fare = (is_nan($fare)) ? '0' : "$fare";

			// print_r($fare);
			// print_r($data);
			// die;

			$data['fare'] = round($fare / 500) * 500;
		}

		return $data;
	}

	// public

	// function edit_ride_info_old()
	// {
	// 	$admin_share = 13;
	// 	$driver_share = 87;

	// 	//    print_r($data['admin_id']); die;

	// 	if (isset($_POST['submit']))
	// 	{

	// 		// echo "<pre>";
	// 		// print_r($_POST);
	// 		// echo "</pre>";die;

	// 		if (isset($_POST['status']))
	// 		{
	// 			$data = array(
	// 				'user_id' => $_POST['driver_id'],
	// 				'job_id' => $_POST['ride_id'],
	// 				'client_id' => $_POST['user_id'],
	// 			);
	// 			$data1 = array(
	// 				'client_id' => $_POST['user_id'],
	// 				'job_id' => $_POST['ride_id'],
	// 			);
	// 			$data['admin_id'] = $_SESSION['logged_in']['id'];
	// 			if (isset($_POST['status1']) && $_POST['status1'] == 1)
	// 			{
	// 				$data['fare_cal'] = $this->fare_cal($data);

	// 				// print_r($data);die;

	// 				$result = $this->User_model->finish_ride($data);
	// 				$date1 = $result->job_start_datetime;
	// 				$date2 = $result->job_completed_datetime;
	// 				$result->payment_method = 1;

	// 				// print_r($result->payment_method);d

	// 				$start_date = new DateTime($date1);
	// 				$end_date = new DateTime($date2);
	// 				$interval = $start_date->diff($end_date);
	// 				$time_taken = $interval->y . " years, " . $interval->m . " months, " . $interval->d . " days, " . $interval->h . ":" . $interval->i . ":" . $interval->s;
	// 				$minutes = ($interval->y * 365 * 24 * 60) + ($interval->m * 30 * 24 * 60) + ($interval->d * 24 * 60) + ($interval->h * 60) + $interval->i;
	// 				$respo = array(
	// 					"job_id" => $data['job_id'],
	// 					"driver_id" => $data['user_id'],
	// 					"distance" => $data['fare_cal']['distance'],
	// 					"time taken" => $time_taken,
	// 					"minutes" => $minutes,
	// 					"fare" => $data['fare_cal']['fare'],
	// 					"payable_amount" => "",
	// 					"payment_method" => $result->payment_method,
	// 					"info" => $result
	// 				);

	// 				// print_r($respo); die;

	// 				/*payment method card(zarinpal)*/
	// 				$amount_to_pay = $_POST['Fare'];
	// 				$driverAmount = (($_POST['fare']) * $driver_share) / 100;
	// 				$driver_id = $_POST['driver_id'];

	// 				// print_r($driver_id);die;

	// 				$this->User_model->add_wallet('tbl_users',$driver_id, $driverAmount);
	// 				$adminAmount = $driverAmount;
	// 				$admin_id = $_SESSION['logged_in']['id'];
	// 				$this->User_model->sub_wallet('tbl_managers',$admin_id, $driverAmount);
	// 				$respo['payable_amount'] = $amount_to_pay;

	// 				//   //    /*----------ENTRY IN PAYMENT TABLE-------------------*/

	// 				$admin_pay_type = "2";
	// 				$driver_pay_type = "1";
	// 				$aRefID = $data['user_id'];
	// 				$dRefID = $data['admin_id'];
	// 				$method = $result->payment_method;

	// 				// print_r($method);die;

	// 				$datetime = date("Y-m-d H:i:s");

	// 				// print_r($result);
	// 				// print_r($method);die;

	// 				$adminWallet = $this->User_model->selectdata("tbl_users", "wallet_balance", array(
	// 					'id' => $data['admin_id']
	// 				));

	// 				// print_r($data['admin_id']);die;

	// 				$adminWalletBal = $adminWallet[0]->wallet_balance;
	// 				$driverWallet = $this->User_model->selectdata("tbl_users", "wallet_balance", array(
	// 					'id' => $data['user_id']
	// 				));
	// 				$driverWalletBal = $driverWallet[0]->wallet_balance;
	// 				if ($adminAmount > 0)
	// 				{
	// 					$admin_transaction_data = array(
	// 						'job_id' => $data['job_id'],
	// 						'payment_type' => $admin_pay_type,
	// 						'payment_method' => $method,
	// 						'payment_status' => '100',
	// 						'payment_RefID' => $aRefID,
	// 						'user_id' => $data['admin_id'],
	// 						'amount' => $adminAmount,
	// 						'wallet_balance' => $adminWalletBal,
	// 						'date_created' => $datetime
	// 					);
	// 					$this->User_model->insertdata("tbl_payment", $admin_transaction_data);
	// 				}

	// 				$driver_transaction_data = array(
	// 					'job_id' => $data['job_id'],
	// 					'payment_type' => $driver_pay_type,
	// 					'payment_method' => $method,
	// 					'payment_status' => '100',
	// 					'payment_RefID' => $dRefID,
	// 					'user_id' => $data['user_id'],
	// 					'amount' => $driverAmount,
	// 					'wallet_balance' => $driverAmount,
	// 					'date_created' => $datetime
	// 				);
	// 				$this->User_model->insertdata("tbl_payment", $driver_transaction_data);
	// 				/*-------------Entry In job Table-----------*/
	// 				$message = "Ride has been finished";
	// 				$action = "finish_ride";
	// 				$driver_info = $this->User_model->driver_info($data);
	// 				$this->User_model->updatedata("tbl_jobs", array(
	// 					'id' => $data['job_id']
	// 				) , array(
	// 					'payable_amount' => $respo['payable_amount'],
	// 					'status' => 4
	// 				));
	// 				if ($respo['payable_amount'] == 0)
	// 				{
	// 					$this->User_model->updatedata("tbl_jobs", array(
	// 						'id' => $data['job_id']
	// 					) , array(
	// 						'payment_status' => 100
	// 					));
	// 				}

	// 				/*------------Push notification------------------*/
	// 				foreach($user_login_data as $value)
	// 				{
	// 					if ($value->mb_device_id == 1)
	// 					{
	// 						$push = $this->User_model->ios($value->mb_token_id, $message, "", $action, $data['job_id'], "", "", "", "", "", $respo['fare']);
	// 					}
	// 				}
	// 			}

	// 			if (isset($_POST['status1']) && $_POST['status1'] == 2)
	// 			{ /*Payment method wallet*/
	// 				$where = array(
	// 					'id' => $data['client_id']
	// 				);
	// 				$user_details = $this->User_model->selectdata("tbl_users", "payment_method,wallet_balance", $where);
	// 				$client_wallet_bal = $user_details[0]->wallet_balance;

	// 				// $respo['payment_method'] = $result->payment_method;

	// 				$amount_to_pay = $data['fare_cal']['fare'] - $coupon_value;
	// 				if ($client_wallet_bal >= $amount_to_pay)
	// 				{ /* having enough balance */
	// 					$rideAmount = $amount_to_pay;
	// 					$this->User_model->sub_wallet('tbl_users',$data['client_id'], $rideAmount);
	// 					$adminAmount_old = ($data['fare_cal']['fare'] * $admin_share) / 100;
	// 					if ($adminAmount_old > $coupon_value)
	// 					{
	// 						$adminAmount = $adminAmount_old - $coupon_value;
	// 						$this->User_model->add_wallet('tbl_managers',$data['admin_id'], $adminAmount);
	// 						$admin_pay_type = "1";
	// 						$aRefID = $data['client_id'];
	// 					}
	// 					elseif ($adminAmount_old < $coupon_value)
	// 					{
	// 						$adminAmount = $coupon_value - $adminAmount_old;
	// 						$this->User_model->sub_wallet('tbl_managers',$data['admin_id'], $adminAmount);
	// 						$admin_pay_type = "2";
	// 						$aRefID = $data['client_id'];
	// 					}
	// 					else
	// 					{
	// 						$adminAmount = 0;
	// 					}

	// 					// $this->User_model->add_wallet('tbl_managers',$data['admin_id'],$adminAmount);

	// 					$driverAmount = ($data['fare_cal']['fare'] * $driver_share) / 100;
	// 					$this->User_model->add_wallet('tbl_users',$data['user_id'], $driverAmount);
	// 					$respo['payable_amount'] = 0;
	// 				}
	// 				elseif ($client_wallet_bal < $amount_to_pay)
	// 				{ /* $amount to pay > client wallet bal */
	// 					$respo['payable_amount'] = $amount_to_pay;
	// 					$driverAmount_old = ($data['fare_cal']['fare'] * $driver_share) / 100;
	// 					$cash = $amount_to_pay - $client_wallet_bal;

	// 					// $adminAmount = ($cash*$admin_share)/100;

	// 					$driverAmount = $driverAmount_old - $cash;
	// 					$this->User_model->add_wallet('tbl_users',$data['user_id'], $driverAmount);
	// 					if ($client_wallet_bal > $driverAmount)
	// 					{
	// 						$adminAmount = $client_wallet_bal - $driverAmount;
	// 						$this->User_model->add_wallet('tbl_managers',$data['admin_id'], $adminAmount);
	// 					}
	// 					else
	// 					{
	// 						$adminAmount = $driverAmount - $client_wallet_bal;
	// 						$this->User_model->sub_wallet('tbl_managers',$data['admin_id'], $adminAmount);
	// 					}

	// 					$this->User_model->sub_wallet('tbl_users',$data['client_id'], $client_wallet_bal);
	// 				}
	// 			}

	// 			if (isset($_POST['status1']) && $_POST['status1'] == 0)
	// 			{
	// 				if ($coupon_value > 0)
	// 				{
	// 					$amount_to_pay = $data['fare_cal']['fare'] - $coupon_value;
	// 					$driverAmount = ($data['fare_cal']['fare'] * $driver_share) / 100;
	// 					$respo['payable_amount'] = $amount_to_pay;
	// 					if ($amount_to_pay > $driverAmount)
	// 					{
	// 						$adminAmount = $amount_to_pay - $driverAmount;
	// 						$this->User_model->sub_wallet('tbl_users',$data['user_id'], $adminAmount);
	// 						$this->User_model->add_wallet('tbl_managers',$data['admin_id'], $adminAmount);
	// 						$admin_pay_type = "1";
	// 						$client_pay_type = "2";
	// 						$driver_pay_type = "1";
	// 						$aRefID = $data['user_id'];
	// 						$cRefID = $data['user_id'];
	// 						$dRefID = $data['client_id'];
	// 					}
	// 					else
	// 					{
	// 						$adminAmount = $driverAmount - $amount_to_pay;
	// 						$this->User_model->sub_wallet('tbl_managers',$data['admin_id'], $adminAmount);
	// 						$this->User_model->add_wallet('tbl_users',$data['user_id'], $adminAmount);
	// 						$admin_pay_type = "2";
	// 						$client_pay_type = "2";
	// 						$driver_pay_type = "1";
	// 						$aRefID = $data['user_id'];
	// 						$cRefID = $data['user_id'];
	// 						$dRefID = $data['client_id'];
	// 					}
	// 				}
	// 				else
	// 				{
	// 					$admin_pay_type = "1";
	// 					$client_pay_type = "2";
	// 					$driver_pay_type = "1";
	// 					$adminAmount = ($data['fare_cal']['fare'] * $admin_share) / 100;
	// 					$this->User_model->sub_wallet('tbl_users',$data['user_id'], $adminAmount);
	// 					$this->User_model->add_wallet('tbl_managers',$data['admin_id'], $adminAmount);
	// 					$driverAmount = ($data['fare_cal']['fare'] * $driver_share) / 100;
	// 					$respo['payable_amount'] = $data['fare_cal']['fare'];
	// 					$aRefID = $data['user_id'];
	// 					$cRefID = $data['user_id'];
	// 					$dRefID = $data['client_id'];
	// 				}

	// 				// print_r($driverAmount);die;

	// 			}

	// 			if (isset($_POST['status1']) && $_POST['status1'] == 3)
	// 			{
	// 				/*Payment method jring*/

	// 				// print_r("expression");die;

	// 				$amount_to_pay = $data['fare_cal']['fare'] - $coupon_value;
	// 				$driverAmount = ($data['fare_cal']['fare'] * $driver_share) / 100;
	// 				$this->User_model->add_wallet('tbl_users',$data['user_id'], $driverAmount);
	// 				$adminAmount = $driverAmount;
	// 				$this->User_model->sub_wallet('tbl_managers',$data['admin_id'], $driverAmount);
	// 				$respo['payable_amount'] = $amount_to_pay;
	// 				/*----------ENTRY IN PAYMENT TABLE-------------------*/
	// 				$admin_pay_type = "2";
	// 				$driver_pay_type = "1";
	// 				$aRefID = $data['user_id'];
	// 				$dRefID = $data['admin_id'];
	// 				$method = $_POST['status1'];

	// 				// print_r($method);

	// 				$datetime = date("Y-m-d H:i:s");
	// 				$adminWallet = $this->User_model->selectdata("tbl_users", "wallet_balance", array(
	// 					'id' => $data['admin_id']
	// 				));

	// 				// print_r($adminWallet[0]->wallet_balance);die;

	// 				$adminWalletBal = $adminWallet[0]->wallet_balance;
	// 				$driverWallet = $this->User_model->selectdata("tbl_users", "wallet_balance", array(
	// 					'id' => $data['user_id']
	// 				));
	// 				$driverWalletBal = $driverWallet[0]->wallet_balance;

	// 				// print_r($driverWalletBal);die;

	// 				if ($adminAmount > 0)
	// 				{
	// 					$admin_transaction_data = array(
	// 						'job_id' => $data['job_id'],
	// 						'payment_type' => $admin_pay_type,
	// 						'payment_method' => $method,
	// 						'payment_status' => '100',
	// 						'payment_RefID' => $aRefID,
	// 						'user_id' => $data['admin_id'],
	// 						'amount' => $adminAmount,
	// 						'wallet_balance' => $adminWalletBal,
	// 						'date_created' => $datetime
	// 					);
	// 					$this->User_model->insertdata("tbl_payment", $admin_transaction_data);
	// 				}

	// 				$driver_transaction_data = array(
	// 					'job_id' => $data['job_id'],
	// 					'payment_type' => $driver_pay_type,
	// 					'payment_method' => $method,
	// 					'payment_status' => '100',
	// 					'payment_RefID' => $dRefID,
	// 					'user_id' => $data['user_id'],
	// 					'amount' => $driverAmount,
	// 					'wallet_balance' => $driverAmount,
	// 					'date_created' => $datetime
	// 				);
	// 				$this->User_model->insertdata("tbl_payment", $driver_transaction_data);
	// 				/*-------------Entry In job Table-----------*/
	// 				$message = "Ride has been finished";
	// 				$action = "finish_ride";
	// 				$driver_info = $this->User_model->driver_info($data);
	// 				$this->User_model->updatedata("tbl_jobs", array(
	// 					'id' => $data['job_id']
	// 				) , array(
	// 					'payable_amount' => $respo['payable_amount'],
	// 					'status' => 4
	// 				));
	// 				if ($respo['payable_amount'] == 0)
	// 				{
	// 					$this->User_model->updatedata("tbl_jobs", array(
	// 						'id' => $data['job_id']
	// 					) , array(
	// 						'payment_status' => 100
	// 					));
	// 				}

	// 				/*------------Push notification------------------*/
	// 				foreach($user_login_data as $value)
	// 				{
	// 					if ($value->mb_device_id == 1)
	// 					{
	// 						$push = $this->User_model->ios($value->mb_token_id, $message, "", $action, $data['job_id'], "", "", "", "", "", $respo['fare']);
	// 					}
	// 				}
	// 			}

	// 			if (isset($_POST['status']) && ($_POST['status'] == 51))
	// 			{
	// 				$result = $this->Admin_model->cancel_ride($data1);
	// 			}
	// 		}

	// 		// echo "<pre>";
	// 		// print_r($_POST);
	// 		// echo "</pre>";die;

	// 		$newData = array(
	// 			'pickup_location' => $_POST['pickup_location'],
	// 			'dropoff_location' => $_POST['dropoff_location'],
	// 			'status' => $_POST['status'],
	// 			'accept_datetime' => $_POST['accept_datetime'],
	// 			'arrived_datetime' => $_POST['arrived_datetime'],
	// 			'job_start_datetime' => $_POST['job_start_datetime'],
	// 			'job_completed_datetime' => $_POST['job_completed_datetime'],
	// 			'fare' => $_POST['fare'],
	// 			'payment_method' => $_POST['status1'],
	// 			'payable_amount' => $_POST['payable_amount'],
	// 			'payment_status' => $_POST['payment_status'],
	// 			'payment_RefID' => $_POST['payment_RefID'],
	// 		);
	// 		$this->Admin_model->update('tbl_jobs', $_GET['rideId'], $newData);
	// 		redirect('Dashboard/idleRides');
	// 	}

	// 	$data['ride_info'] = $this->Admin_model->trip_info($_GET['rideId']);

	// 	// echo "<pre>";
	// 	// print_r($data);echo "<pre/>";die;

	// 	$this->allview("edit_ride_info", $data);
	// }

	public

	function edit_ride_info()
	{
		
		if (isset($_POST['submit']))
		{

			// echo "<pre>";
			// print_r($_POST);
			// echo "</pre>";die;

			if (isset($_POST['status']))
			{
				$data = array(
					'user_id' => $_POST['driver_id'],
					'job_id' => $_POST['ride_id'],
					'client_id' => $_POST['user_id'],
				);
				$data1 = array(
					'client_id' => $_POST['user_id'],
					'job_id' => $_POST['ride_id'],
				);
				$data['admin_id'] = $_SESSION['logged_in']['id'];
				if (isset($_POST['status1']) && $_POST['status1'] == 1)
				{
					$data['fare_cal'] = $this->fare_cal($data);

					// print_r($data);die;

					$result = $this->User_model->finish_ride($data);
					$date1 = $result->job_start_datetime;
					$date2 = $result->job_completed_datetime;
					$result->payment_method = 1;

					// print_r($result->payment_method);d

					$start_date = new DateTime($date1);
					$end_date = new DateTime($date2);
					$interval = $start_date->diff($end_date);
					$time_taken = $interval->y . " years, " . $interval->m . " months, " . $interval->d . " days, " . $interval->h . ":" . $interval->i . ":" . $interval->s;
					$minutes = ($interval->y * 365 * 24 * 60) + ($interval->m * 30 * 24 * 60) + ($interval->d * 24 * 60) + ($interval->h * 60) + $interval->i;
					$respo = array(
						"job_id" => $data['job_id'],
						"driver_id" => $data['user_id'],
						"distance" => $data['fare_cal']['distance'],
						"time taken" => $time_taken,
						"minutes" => $minutes,
						"fare" => $data['fare_cal']['fare'],
						"payable_amount" => "",
						"payment_method" => $result->payment_method,
						"info" => $result
					);

					// print_r($respo); die;

					/*payment method card(zarinpal)*/
					$amount_to_pay = $_POST['Fare'];
					$driverAmount = (($_POST['fare']) * $driver_share) / 100;
					$driver_id = $_POST['driver_id'];

					// print_r($driver_id);die;

					$this->User_model->add_wallet('tbl_users',$driver_id, $driverAmount);
					$adminAmount = $driverAmount;
					$admin_id = $_SESSION['logged_in']['id'];
					$this->User_model->sub_wallet('tbl_managers',$admin_id, $driverAmount);
					$respo['payable_amount'] = $amount_to_pay;

					//   //    /*----------ENTRY IN PAYMENT TABLE-------------------*/

					$admin_pay_type = "2";
					$driver_pay_type = "1";
					$aRefID = $data['user_id'];
					$dRefID = $data['admin_id'];
					$method = $result->payment_method;

					// print_r($method);die;

					$datetime = date("Y-m-d H:i:s");

					// print_r($result);
					// print_r($method);die;

					$adminWallet = $this->User_model->selectdata("tbl_users", "wallet_balance", array(
						'id' => $data['admin_id']
					));

					// print_r($data['admin_id']);die;

					$adminWalletBal = $adminWallet[0]->wallet_balance;
					$driverWallet = $this->User_model->selectdata("tbl_users", "wallet_balance", array(
						'id' => $data['user_id']
					));
					$driverWalletBal = $driverWallet[0]->wallet_balance;
					if ($adminAmount > 0)
					{
						$admin_transaction_data = array(
							'job_id' => $data['job_id'],
							'payment_type' => $admin_pay_type,
							'payment_method' => $method,
							'payment_status' => '100',
							'payment_RefID' => $aRefID,
							'user_id' => $data['admin_id'],
							'amount' => $adminAmount,
							'wallet_balance' => $adminWalletBal,
							'date_created' => $datetime
						);
						$this->User_model->insertdata("tbl_payment", $admin_transaction_data);
					}

					$driver_transaction_data = array(
						'job_id' => $data['job_id'],
						'payment_type' => $driver_pay_type,
						'payment_method' => $method,
						'payment_status' => '100',
						'payment_RefID' => $dRefID,
						'user_id' => $data['user_id'],
						'amount' => $driverAmount,
						'wallet_balance' => $driverAmount,
						'date_created' => $datetime
					);
					$this->User_model->insertdata("tbl_payment", $driver_transaction_data);
					/*-------------Entry In job Table-----------*/
					$message = "Ride has been finished";
					$action = "finish_ride";
					$driver_info = $this->User_model->driver_info($data);
					$this->User_model->updatedata("tbl_jobs", array(
						'id' => $data['job_id']
					) , array(
						'payable_amount' => $respo['payable_amount'],
						'status' => 4
					));
					if ($respo['payable_amount'] == 0)
					{
						$this->User_model->updatedata("tbl_jobs", array(
							'id' => $data['job_id']
						) , array(
							'payment_status' => 100
						));
					}

					/*------------Push notification------------------*/
					foreach($user_login_data as $value)
					{
						if ($value->mb_device_id == 1)
						{
							$push = $this->User_model->ios($value->mb_token_id, $message, "", $action, $data['job_id'], "", "", "", "", "", $respo['fare']);
						}
					}
				}

				if (isset($_POST['status1']) && $_POST['status1'] == 2)
				{ /*Payment method wallet*/
					$where = array(
						'id' => $data['client_id']
					);
					$user_details = $this->User_model->selectdata("tbl_users", "payment_method,wallet_balance", $where);
					$client_wallet_bal = $user_details[0]->wallet_balance;

					// $respo['payment_method'] = $result->payment_method;

					$amount_to_pay = $data['fare_cal']['fare'] - $coupon_value;
					if ($client_wallet_bal >= $amount_to_pay)
					{ /* having enough balance */
						$rideAmount = $amount_to_pay;
						$this->User_model->sub_wallet('tbl_users',$data['client_id'], $rideAmount);
						$adminAmount_old = ($data['fare_cal']['fare'] * $admin_share) / 100;
						if ($adminAmount_old > $coupon_value)
						{
							$adminAmount = $adminAmount_old - $coupon_value;
							$this->User_model->add_wallet('tbl_managers',$data['admin_id'], $adminAmount);
							$admin_pay_type = "1";
							$aRefID = $data['client_id'];
						}
						elseif ($adminAmount_old < $coupon_value)
						{
							$adminAmount = $coupon_value - $adminAmount_old;
							$this->User_model->sub_wallet('tbl_managers',$data['admin_id'], $adminAmount);
							$admin_pay_type = "2";
							$aRefID = $data['client_id'];
						}
						else
						{
							$adminAmount = 0;
						}

						// $this->User_model->add_wallet('tbl_managers',$data['admin_id'],$adminAmount);

						$driverAmount = ($data['fare_cal']['fare'] * $driver_share) / 100;
						$this->User_model->add_wallet('tbl_users',$data['user_id'], $driverAmount);
						$respo['payable_amount'] = 0;
					}
					elseif ($client_wallet_bal < $amount_to_pay)
					{ /* $amount to pay > client wallet bal */
						$respo['payable_amount'] = $amount_to_pay;
						$driverAmount_old = ($data['fare_cal']['fare'] * $driver_share) / 100;
						$cash = $amount_to_pay - $client_wallet_bal;

						// $adminAmount = ($cash*$admin_share)/100;

						$driverAmount = $driverAmount_old - $cash;
						$this->User_model->add_wallet('tbl_users',$data['user_id'], $driverAmount);
						if ($client_wallet_bal > $driverAmount)
						{
							$adminAmount = $client_wallet_bal - $driverAmount;
							$this->User_model->add_wallet('tbl_managers',$data['admin_id'], $adminAmount);
						}
						else
						{
							$adminAmount = $driverAmount - $client_wallet_bal;
							$this->User_model->sub_wallet('tbl_managers',$data['admin_id'], $adminAmount);
						}

						$this->User_model->sub_wallet('tbl_users',$data['client_id'], $client_wallet_bal);
					}
				}

				if (isset($_POST['status1']) && $_POST['status1'] == 0)
				{
					if ($coupon_value > 0)
					{
						$amount_to_pay = $data['fare_cal']['fare'] - $coupon_value;
						$driverAmount = ($data['fare_cal']['fare'] * $driver_share) / 100;
						$respo['payable_amount'] = $amount_to_pay;
						if ($amount_to_pay > $driverAmount)
						{
							$adminAmount = $amount_to_pay - $driverAmount;
							$this->User_model->sub_wallet('tbl_users',$data['user_id'], $adminAmount);
							$this->User_model->add_wallet('tbl_managers',$data['admin_id'], $adminAmount);
							$admin_pay_type = "1";
							$client_pay_type = "2";
							$driver_pay_type = "1";
							$aRefID = $data['user_id'];
							$cRefID = $data['user_id'];
							$dRefID = $data['client_id'];
						}
						else
						{
							$adminAmount = $driverAmount - $amount_to_pay;
							$this->User_model->sub_wallet('tbl_managers',$data['admin_id'], $adminAmount);
							$this->User_model->add_wallet('tbl_users',$data['user_id'], $adminAmount);
							$admin_pay_type = "2";
							$client_pay_type = "2";
							$driver_pay_type = "1";
							$aRefID = $data['user_id'];
							$cRefID = $data['user_id'];
							$dRefID = $data['client_id'];
						}
					}
					else
					{
						$admin_pay_type = "1";
						$client_pay_type = "2";
						$driver_pay_type = "1";
						$adminAmount = ($data['fare_cal']['fare'] * $admin_share) / 100;
						$this->User_model->sub_wallet('tbl_users',$data['user_id'], $adminAmount);
						$this->User_model->add_wallet('tbl_managers',$data['admin_id'], $adminAmount);
						$driverAmount = ($data['fare_cal']['fare'] * $driver_share) / 100;
						$respo['payable_amount'] = $data['fare_cal']['fare'];
						$aRefID = $data['user_id'];
						$cRefID = $data['user_id'];
						$dRefID = $data['client_id'];
					}

					// print_r($driverAmount);die;

				}

				if (isset($_POST['status1']) && $_POST['status1'] == 3)
				{
					/*Payment method jring*/

					// print_r("expression");die;

					$amount_to_pay = $data['fare_cal']['fare'] - $coupon_value;
					$driverAmount = ($data['fare_cal']['fare'] * $driver_share) / 100;
					$this->User_model->add_wallet('tbl_users',$data['user_id'], $driverAmount);
					$adminAmount = $driverAmount;
					$this->User_model->sub_wallet('tbl_managers',$data['admin_id'], $driverAmount);
					$respo['payable_amount'] = $amount_to_pay;
					/*----------ENTRY IN PAYMENT TABLE-------------------*/
					$admin_pay_type = "2";
					$driver_pay_type = "1";
					$aRefID = $data['user_id'];
					$dRefID = $data['admin_id'];
					$method = $_POST['status1'];

					// print_r($method);

					$datetime = date("Y-m-d H:i:s");
					$adminWallet = $this->User_model->selectdata("tbl_users", "wallet_balance", array(
						'id' => $data['admin_id']
					));

					// print_r($adminWallet[0]->wallet_balance);die;

					$adminWalletBal = $adminWallet[0]->wallet_balance;
					$driverWallet = $this->User_model->selectdata("tbl_users", "wallet_balance", array(
						'id' => $data['user_id']
					));
					$driverWalletBal = $driverWallet[0]->wallet_balance;

					// print_r($driverWalletBal);die;

					if ($adminAmount > 0)
					{
						$admin_transaction_data = array(
							'job_id' => $data['job_id'],
							'payment_type' => $admin_pay_type,
							'payment_method' => $method,
							'payment_status' => '100',
							'payment_RefID' => $aRefID,
							'user_id' => $data['admin_id'],
							'amount' => $adminAmount,
							'wallet_balance' => $adminWalletBal,
							'date_created' => $datetime
						);
						$this->User_model->insertdata("tbl_payment", $admin_transaction_data);
					}

					$driver_transaction_data = array(
						'job_id' => $data['job_id'],
						'payment_type' => $driver_pay_type,
						'payment_method' => $method,
						'payment_status' => '100',
						'payment_RefID' => $dRefID,
						'user_id' => $data['user_id'],
						'amount' => $driverAmount,
						'wallet_balance' => $driverAmount,
						'date_created' => $datetime
					);
					$this->User_model->insertdata("tbl_payment", $driver_transaction_data);
					/*-------------Entry In job Table-----------*/
					$message = "Ride has been finished";
					$action = "finish_ride";
					$driver_info = $this->User_model->driver_info($data);
					$this->User_model->updatedata("tbl_jobs", array(
						'id' => $data['job_id']
					) , array(
						'payable_amount' => $respo['payable_amount'],
						'status' => 4
					));
					if ($respo['payable_amount'] == 0)
					{
						$this->User_model->updatedata("tbl_jobs", array(
							'id' => $data['job_id']
						) , array(
							'payment_status' => 100
						));
					}

					/*------------Push notification------------------*/
					foreach($user_login_data as $value)
					{
						if ($value->mb_device_id == 1)
						{
							$push = $this->User_model->ios($value->mb_token_id, $message, "", $action, $data['job_id'], "", "", "", "", "", $respo['fare']);
						}
					}
				}

				if (isset($_POST['status']) && ($_POST['status'] == 51))
				{
					$result = $this->Admin_model->cancel_ride($data1);
				}
			}

			// echo "<pre>";
			// print_r($_POST);
			// echo "</pre>";die;

			$newData = array(
				'pickup_location' => $_POST['pickup_location'],
				'dropoff_location' => $_POST['dropoff_location'],
				'status' => $_POST['status'],
				'accept_datetime' => $_POST['accept_datetime'],
				'arrived_datetime' => $_POST['arrived_datetime'],
				'job_start_datetime' => $_POST['job_start_datetime'],
				'job_completed_datetime' => $_POST['job_completed_datetime'],
				'fare' => $_POST['fare'],
				'payment_method' => $_POST['status1'],
				'payable_amount' => $_POST['payable_amount'],
				'payment_status' => $_POST['payment_status'],
				'payment_RefID' => $_POST['payment_RefID'],
			);
			$this->Admin_model->update('tbl_jobs', $_GET['rideId'], $newData);
			redirect('Dashboard/idleRides');
		}

		$data['ride_info'] = $this->Admin_model->trip_info($_GET['rideId']);

		// echo "<pre>";
		// print_r($data);echo "<pre/>";die;

		$this->allview("edit_ride_info", $data);
	}

	public

	function jiring()
	{
		$this->allview('rideinfo');
	}

	public

	function driverStats()
	{
		$data['driverStats'] = $this->Admin_model->driverStats();

		// echo "<pre>";
		// print_r($driverStats);

		$this->allview('driverStats', $data);
	}

	public

	function rzone_time()
	{
		if (isset($_POST['Submit']))
		{

			// echo "<pre>";
			// print_r($_POST[end][1]);die;

			$data1 = $_POST['start'];
			foreach($data1 as $k => $v)
			{
				$this->db->query("update tbl_rzone_time set start_time = '$v', end_time = '" . $_POST['end'][$k] . "' WHERE id = $k");
			}
		}

		$data['rzone_time'] = $this->Admin_model->select('tbl_rzone_time', '*');
		$this->allview('rzone_time', $data);

		//
		// print_r($data);

	}

	public

	function setCommission()
	{

		// print_r($_POST);

		if (isset($_POST['submit']))
		{
			$newData = array(
				'commission' => $_POST['commission'],
				'commission_from' => $_POST['commission_from'],
				'commission_to' => $_POST['commission_to'],
				'date_modified' => date("Y-m-d H:i:s")
			);
			$this->Admin_model->updateWhere('tbl_commission', array(
				'driver_id' => 0
			) , $newData);
			$this->session->set_flashdata('msg', 'Commission Details Updated Successfully');
		}
		elseif (isset($_POST['submitDefault']))
		{
			$findDefault = $this->Admin_model->selectWhere('tbl_commission', 'commission', array(
				'driver_id' => 'default'
			));
			$newData = array(
				'commission' => $_POST['commission'],
				'driver_id' => 'default',
				'date_modified' => date("Y-m-d H:i:s")
			);
			if (empty($findDefault))
			{
				$this->Admin_model->insert('tbl_commission', $newData);
			}
			else
			{
				$this->Admin_model->updateWhere('tbl_commission', array(
					'driver_id' => 'default'
				) , $newData);
			}

			$this->session->set_flashdata('msg2', 'Commission Details Updated Successfully');
		}

		$commission = $this->Admin_model->selectWhere('tbl_commission', '*', array(
			'driver_id' => 0
		));
		$findDefault = $this->Admin_model->selectWhere('tbl_commission', '*', array(
			'driver_id' => 'default'
		));

		// print_r($commission[0]);die;

		$data['record'] = $commission[0];
		$data['default'] = $findDefault[0];
		$this->allview('setCommission', $data);
	}

	public

	function notificationList()
	{
		$data['notificationList'] = $this->Admin_model->select('tbl_notifications','*');

		$this->allview('notification_list',$data);
	}

	public

	function notificationSend()
	{
		$img_url = "";
			// print_r($_FILES);die;
	        if (isset($_FILES['notifImage'])) {
	            $config['upload_path'] =  'public/notifImage/';
	            $config['allowed_types'] = 'gif|jpg|png|jpeg';
	            $config['max_size'] = 500000;
	            $config['max_width'] = 10240;
	            $config['max_height'] = 7680;
				$this->load->library('upload');
	            $this->upload->initialize($config);

	            if (!$this->upload->do_upload('notifImage')) {
	                $error = array('error' => $this->upload->display_errors());
	                $img_url = "";
	                // print_r($error);die;
	            } else {
	                $data = $this->upload->data();
	                $fullPath = base_url() .$config['upload_path']. $data['file_name'];
	                $img_url = $fullPath;
	            }
	        }
			// print_r($img_url);die;
		if (isset($_POST['Send']))
		{
		// print_r($_POST);die;
	        // print_r($img_url);die;
			if (isset($_POST['alluser']))
			{
				$notification_type = $_POST['alluser'];
				$notificationData = array(
					'notification_type' => $notification_type,
					'message' => $_POST['message'],
					'description' => $_POST['description'],
					'date_created' => date("Y-m-d H:i:s")
				);
				if ($img_url!="") {
					$notificationData['img_url'] = $img_url;
				}
				$this->Admin_model->insert('tbl_notifications', $notificationData);
			}

			elseif (isset($_POST['allmanagers']))
			{
				$notification_type = $_POST['allmanagers'];
				$notificationData = array(
					'notification_type' => $notification_type,
					'message' => $_POST['message'],
					'description' => $_POST['description'],
					'date_created' => date("Y-m-d H:i:s")
				);
				if ($img_url!="") {
					$notificationData['img_url'] = $img_url;
				}
				$this->Admin_model->insert('tbl_notifications', $notificationData);
			}

			elseif (isset($_POST['alldrivers']))
			{
				$notification_type = $_POST['alldrivers'];
				$notificationData = array(
					'notification_type' => $notification_type,
					'message' => $_POST['message'],
					'description' => $_POST['description'],
					'date_created' => date("Y-m-d H:i:s")
				);
				if ($img_url!="") {
					$notificationData['img_url'] = $img_url;
				}
				$this->Admin_model->insert('tbl_notifications', $notificationData);
			}

			elseif (isset($_POST['allpassengers']))
			{
				$notification_type = $_POST['allpassengers'];
				$notificationData = array(
					'notification_type' => $notification_type,
					'message' => $_POST['message'],
					'description' => $_POST['description'],
					'date_created' => date("Y-m-d H:i:s")
				);
				if ($img_url!="") {
					$notificationData['img_url'] = $img_url;
				}
				$this->Admin_model->insert('tbl_notifications', $notificationData);
			}

			elseif (isset($_POST['user']))
			{
				$user_id = $_POST['user'];
				$notificationData = array(
					'notification_type' => 3,
					'user_id' => $user_id,
					'message' => $_POST['message'],
					'description' => $_POST['description'],
					'date_created' => date("Y-m-d H:i:s")
				);
				if ($img_url!="") {
					$notificationData['img_url'] = $img_url;
				}
				$this->Admin_model->insert('tbl_notifications', $notificationData);
			}else{
				$this->session->set_flashdata('err', 'Select atleast one option.');
				$this->allview('notificationSend');
				return false;
			}

			$this->session->set_flashdata('msg', 'Message Sent Successfully');
		}
			

		// print_r($this->db->last_query());

		$this->allview('notificationSend');
	}

	public function zone_setting()
	{
		// print_r($_POST);
		$table_name = 'tbl_zone_setting';
		if (isset($_POST['submit'])) {
			$isExist = $this->Admin_model->get_where($table_name,array('id'=>1));
			$zoneData = array('scenario'=>$_POST['scenario'],'start_date'=>$_POST['start_date'],'end_date'=>$_POST['end_date'],'date_modified'=>date("Y-m-d H:i:s"));
			if (empty($isExist)) {
				$this->Admin_model->insert($table_name,$zoneData);
			}else{
				$this->Admin_model->update($table_name,1,$zoneData);
			}
		}
		$isExist = $this->Admin_model->get_where($table_name,array('id'=>1));
		$data['record'] = $isExist[0];
		$this->allview('zone_setting',$data);
	}


	public function set_coupon_value()
	{
		if (isset($_POST['submit'])) {
			// print_r($_POST);
			$this->Admin_model->updateWhere('tbl_cupon_value',array('cupon_type'=>1),array('value'=>$_POST['passenger_refferal']));
			$this->Admin_model->updateWhere('tbl_cupon_value',array('cupon_type'=>3),array('value'=>$_POST['driver_refferal']));
		}
		$data['coupon_value'] = $this->Admin_model->select('tbl_cupon_value','*');
		// echo "<pre>";
		// print_r($data['coupon_value']);
		// echo "</pre>";
		// die;
		$this->allview('set_coupon_value',$data);
	}

	public function ChangeUserStatus()
	{
		$this->Admin_model->updateWhere('tbl_users',array('id'=>$_POST['id']),array('status'=>$_POST['statusid']));
		$res = $this->Admin_model->selectWhere('tbl_users','status',array('id'=>$_POST['id']));
		echo $res[0]->status;
	}

	public function driverNearbyRange()
	{
		// echo "string";
		$table_name = 'tbl_driverNearbyRange';

		if (isset($_POST['submit'])) {
			$driverNearbyRange = $this->Admin_model->select($table_name,'range');
			
			$input = array('range' => $_POST['range'],'date_modified' => date("Y-m-d H:i:s") );
			if (empty($driverNearbyRange)) {
				$this->Admin_model->insert($table_name,$input);
		// print_r($this->db->last_query());die;
			} else {
				$this->Admin_model->update($table_name,1,$input);
			}
		}

		$dnr = $this->Admin_model->select($table_name,'range');
		$data['driverNearbyRange'] = $dnr[0];
		$this->allview('driverNearbyRange',$data);
	}

	public function settings()
	{
		$data['settings'] = $this->Admin_model->select('tbl_settings','*');
		$this->allview('settings',$data);
	}

	public function ChangeSettingStatus()
	{
		$this->Admin_model->updateWhere('tbl_settings',array('id'=>$_POST['id']),array('status'=>$_POST['status'],'modifiedOn'=>date("Y-m-d H:i:s")));
		$res = $this->Admin_model->selectWhere('tbl_settings','status',array('id'=>$_POST['id']));
		echo($res[0]->status);
	}

	public function refferralValues()
	{
		if (isset($_POST['value'])) {
			$this->Admin_model->updateWhere('tbl_cupon_value',array('id'=>$_POST['id']),array('value'=>$_POST['value']));
			echo $_POST['value'];
			return false;
		}
		$data['refferralValues'] = $this->Admin_model->select('tbl_cupon_value','*');
		$this->allview('refferralValues',$data);
	}

	public function appSettings()
	{
		if (isset($_POST['value'])) {
			$this->Admin_model->updateWhere('tbl_appSettings',array('id'=>$_POST['id']),array('value'=>$_POST['value']));
			echo $_POST['value'];
			return false;
		}
		$data['appSettings'] = $this->Admin_model->select('tbl_appSettings','*');
		$this->allview('appSettings',$data);
	}

	public function commissionLevels()
	{
		if (isset($_POST['value'])) {
			$this->Admin_model->updateWhere('tbl_commissionLevels',array('id'=>$_POST['id']),array('commissionPrcnt'=>$_POST['value']));
			echo $_POST['value'];
			return false;
		}
		$data['commissionLevels'] = $this->Admin_model->select('tbl_commissionLevels','*');
		$this->allview('commissionLevels',$data);
	}

	public function addCommLevel()
	{
		$this->Admin_model->insert("tbl_commissionLevels", array(
					'commissionLevel' => $this->input->post('commissionLevel') ,
					'commissionPrcnt' => $this->input->post('commissionPrcnt'),
					'addedOn'=>date("Y-m-d H:i:s")
				));
		$last_id = $this->db->insert_id();
		$commissionLevels = $this->Admin_model->selectWhere('tbl_commissionLevels','*',array('id'=>$last_id));
	
		echo(json_encode($commissionLevels[0]));
	}

	public function dash()
	{
		$this->load->view("dash.php");
	}

	public function findUser()
	{
		if(!empty($_POST["keyword"])) {
			$users = $this->Admin_model->findUser($_POST['keyword']); 
			// print_r($users); 
			?>
			<div class="controls">
				<ul id="country-list">
			<?php
			foreach ($users as $value) { 
			?>
				<li onClick="selectCountry('<?php echo "$value->phone";?>',<?php echo "$value->id";?>)"><?php echo "$value->phone";?></li>
			<?php 
			} 
			?>
				</ul>
			</div>
			<?php
		}
	}

	function ongoingRide($driver_id)
	{

		// $this->db->query("UPDATE tbl_jobs set modified_on=now()");

		$db_result = $this->Admin_model->ongoingRide($driver_id);

		// echo "<pre>";
		// print_r($db_result);
		// die();

		echo json_encode($db_result);
	}

	public function trackUser($id)
	{
		$data['user_id'] = $id;
		$data['polygon1'] = $this->Admin_model->get("tbl_polygon1");
		$data['polygon2'] = $this->Admin_model->get("tbl_polygon2");
		$data['mapNotifications'] = $this->Admin_model->ongoingRideAll($id);

		$this->allview("trackUser", $data);
	}

	public

	function markerUser($user_id)
	{

		// Get the co-ordinates from the database using our model

		$markerFree = $this->Admin_model->get_latlng($user_id);
		echo json_encode($markerFree);
		exit();
	}

	// when a client sends data to the server
	function wsOnMessage($clientID, $message, $messageLength, $binary) {
		global $Server;
		$ip = long2ip( $Server->wsClients[$clientID][6] );

		// check if message length is 0
		if ($messageLength == 0) {
			$Server->wsClose($clientID);
			return;
		}

		//The speaker is the only person in the room. Don't let them feel lonely.
		if ( sizeof($Server->wsClients) == 1 )
			$Server->wsSend($clientID, "There isn't anyone else in the room, but I'll still listen to you. --Your Trusty Server");
		else
			//Send the message to everyone but the person who said it
			foreach ( $Server->wsClients as $id => $client )
				if ( $id != $clientID )
					$Server->wsSend($id, "Visitor $clientID ($ip) said \"$message\"");
	}

	// when a client connects
	function wsOnOpen($clientID)
	{
		global $Server;
		$ip = long2ip( $Server->wsClients[$clientID][6] );

		$Server->log( "$ip ($clientID) has connected." );

		//Send a join notice to everyone but the person who joined
		foreach ( $Server->wsClients as $id => $client )
			if ( $id != $clientID )
				$Server->wsSend($id, "Visitor $clientID ($ip) has joined the room.");
	}

	// when a client closes or lost connection
	function wsOnClose($clientID, $status) {
		global $Server;
		$ip = long2ip( $Server->wsClients[$clientID][6] );

		$Server->log( "$ip ($clientID) has disconnected." );

		//Send a user left notice to everyone in the room
		foreach ( $Server->wsClients as $id => $client )
			$Server->wsSend($id, "Visitor $clientID ($ip) has left the room.");
	}

	function clientChat()
	{
		$this->load->view('clientchat');
	}
	
    /*public static function publish($uid, $chid, $auth, $message) {
    	error_reporting(E_ALL); ini_set('display_errors', 1);
    	private static $ENDPOINT = 'https://api.bef.rest/xapi/1/publish/%d/%s';
        $headers = array(
            sprintf('X-BF-AUTH: %s', $auth)
        );

        $curl = curl_init(sprintf(self::$ENDPOINT, $uid, $chid));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $message);
        curl_exec($curl);
    }*/
}

?>