<?php
function getfeedback($id)
{
    $serivce = get('service_order', 'job_number', $id);
    $data = [];
    foreach ($serivce as $value) 
	{
        $rate = get('rate', 'order_id', $value['order_id']);
        if ($rate) 
		{
            $rate = $rate[0];
            $order = get('consumed_order', 'order_id', $value['order_id']);
            if ($order) 
			{
                $order = $order[0];
                $user = get('customer', 'ID', $order['user_id']);
                if ($user) 
				{
                    if ($rate['bad'] == 1) 
					{
                        $user = $user[0];
                        $head = '../photo/defult.jpg';
                        if ($user['head'] != '') 
						{
                            $head = $user['head'];
                        }
                        $price = 0;
                        if ($value['service_type'] == 1) 
						{
                            $serivcetype = get('service_type', 'ID', $rate['service_id']);
                            if ($serivcetype) 
							{
                                $price = $serivcetype[0]['price'];
                            }
                        } 
						else if ($value['servicetype'] == 2) 
						{
                            $serivcetype = get('item_type', 'ID', $rate['service_id']);
                            if ($serivcetype) 
							{
                                $price = $serivcetype[0]['price'];
                            }
                        } 
						else 
						{
                            $price = $value['price'];
                        }

                        $item = [
                            'name' => $user['name'],
                            'head' => 'border-radius: 50%;background-color: #ffcc99;background-image: url("' . $head . '");background-size:cover',
                            'rate' => $rate['score'],
                            'text' => $rate['comment'],
                            'serviceid' => $value['order_id'],
                            'price' => $price,
                        ];
                        array_push($data, $item);
                    }
                }
            }
        }
    }
    return $data;
}
