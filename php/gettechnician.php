<?php
function gettechnician($id,$tm)
{
    $technician=get('technician','job_number',$id);

    if($technician)
	{
        $rate = sql_str("select Avg(score) as score from rate where job_number = '$id'");
       
        $technician=$technician[0];

        $skill='';
        $skilllist=[];
        $skills=get('skill','job_number',$id);
		
        foreach($skills as $value)
		{
            $service=get('service_type','ID',$value['service_id']);
            if($service)
			{
                $skill.=$service[0]['name'].' ';
                array_push($skilllist,['name'=>$service[0]['name'],'id'=>$service[0]['ID']]);
            }
        }
        $service=get('service_order','job_number',$id);
        $photo=get('technician_photo','job_number',$id);
        $photodata=[];
        if(count($photo)>=4)
		{
            for($i=0;$i<4;$i++)
			{
                array_push($photodata,$photo[$i]['img']);
            }
        }
		else
		{
            foreach($photo as $value)
			{
                array_push($photodata,$value['img']);
            }
        }
        
        $entrytime='';
        $date=(time()-strtotime($technician['entry_date']));
        if($date>365*24*60*60)
		{
            $entrytime=round($date/(365*24*60*60),1).'年';
        }
		else if($date>(30*24*60*60))
		{
            $entrytime=round($date/(30*24*60*60),1).'月';
        }
		else if($date>24*60*60)
		{
            $entrytime=round($date/(24*60*60)).'天';
        }
		else
		{
            $entrytime='新人';
        }
        $gender='';
        if($technician['gender']==1)
		{
            $gender='他';
        }
		else if($technician['gender']==2)
		{
            $gender='她';
        }
        $data=[
            'name'=>$technician['name'],
            'description'=>$technician['description'],
            'jobnumber'=>$id,
            'head'=>$technician['photo'],
            'rate'=>$rate[0],
            'skill'=>$skill,
            'service'=>count($service),
            'photo'=>$photodata,
            'favoate'=>0,
            'entrytime'=>$entrytime,
            'skills'=>$skilllist,
            'gender'=>$gender,
            'vcr'=>$technician['vcr'],
            'level'=>$technician['level'],
//            'busy'=>$busy
        ];
        return $data;
    }
}