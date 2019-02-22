<? 
header("Access-Control-Allow-Origin: *"); // <--delete in production !!!!
//UPD 2.22.2019 <--This is old code!!:
require_once('sql.php');
$errs = [];
if(isset($_GET['Year'])  && strlen($_GET['Year'])==4)$Year =$_GET['Year']; else $errs[] = 'Year';
if(isset($_GET['Month']) && strlen($_GET['Month'])<3)$Month=$_GET['Month'];else	$errs[] = 'Month';	
if(isset($_GET['back'])  && strlen($_GET['back']) <2)$back =$_GET['back']; else	$errs[] = 'Years';
if(isset($_GET['dets'])  && strlen($_GET['dets'])==1)$dets =$_GET['dets']; else $errs[] = 'Detentions';
if(empty($errs)) { exit('Wrong request'); }



//DECLARE @From DATE = DATEADD(day,1,(dateadd(year,-1,@To)))

if(strlen($Month)<2){$Month = "0".$Month;}
 $from = $Year-$back.$Month;
$SQL = " 
DECLARE @To0 	DATE = '".$Year.$Month."10'
DECLARE @To 	DATE = dateadd(day,-DAY(@To0),DATEADD(month,1,@To0)) 
DECLARE @From DATE = DATEADD(day,1,(dateadd(year,-$back,@To)))
DECLARE @MOU tinyint = 3
DECLARE @dets tinyint = ".$dets."
DECLARE @detention tinyint = 1";

$SQL1 = ";with tt1 as
(
SELECT COUNT (*) Det
      ,(select top(1) t11.UID
        from [Inspections] t11
        INNER JOIN [Ships] t12 ON t11.ShipUID=t12.UID
        where t2.HUID=t12.HUID
		AND MOU=@MoU AND Detention=@detention
        AND DateOfInspect BETWEEN @From AND @To
        order by t11.DateOfInspect desc
       ) 'UID'
  FROM [Inspections] t1  
	INNER JOIN [Ships] t2 ON t1.ShipUID=t2.UID 
  WHERE DateOfInspect BETWEEN @From AND @To 
	AND MOU=@MoU
	AND Detention=@detention
  GROUP BY t2.HUID
  HAVING COUNT (*)>=@dets
)

SELECT tt1.Det
	, t2.Name 'Name'
	, t2.IMO  'IMO'
	, t3.Name 'Flag' 
	, t4.Name 'CompanyName'
	, t4.IMO  'CompanyIMO'
    , t5.ShipStatus
 
  FROM tt1
  inner join [Inspections] t1 on tt1.UID=t1.UID
  INNER JOIN [Ships] t2 ON t1.ShipUID=t2.UID
  INNER JOIN [Countries] t3 ON t3.ID=t2.FlagCode
  INNER JOIN [Companies] t4 ON t4.UID=t1.CompanyUID
  left  join [LloydShips] t5 on t2.IMO= t5.IMO
  ORDER BY Det DESC,name ";

 $SQL2 = " 
  
  SELECT t2.IMO IMO_No
		,t2.Callsign
		,t7.Dets 
		,t2.Name ShipName
		,DateOfInspect InspDate
		,t4.Name + ', ' + t3.Name InspPlaceAndAuth
		,t5.Name ShipFlag
		,t6.Name Company
		,t6.IMO  ExternalID
		,isnull(t8.Name,'') 'ISM DOC issuing RO' 
		,t2.HUID  HUID
  FROM [Inspections] t1 INNER JOIN [Ships] t2 ON t1.ShipUID = t2.UID 
										 INNER JOIN [Countries]   t3 ON t1.AuthCode  = t3.ID 
										 INNER JOIN [Ports]   t4 ON t1.PortCode   = t4.ID  
										 INNER JOIN [Countries]   t5 ON t2.FlagCode  = t5.ID  
										 INNER JOIN [Companies]   t6 ON t1.CompanyUID   = t6.UID
										 INNER JOIN (SELECT t2.IMO,COUNT(*) Dets 
										             FROM [Inspections] t1 INNER JOIN [Ships]  t2 ON t1.ShipUID = t2.UID 
                                                     WHERE MOU=@mou AND DateOfInspect BETWEEN @From AND @To AND Detention = @detention
                                                     GROUP BY t2.IMO) t7 on t2.IMO=t7.IMO
                                         left join (SELECT distinct tt8.InspectionUID
                                                          ,CASE WHEN tt9.IssuedRoCode=173 THEN tt10.Name+' ('+CAST(tt10.Code AS VARCHAR(10))+')/'+ISNULL(tt9.IssuedRoName,'') 
                                                                ELSE tt10.Name+' ('+CAST(tt10.Code AS VARCHAR(10))+')'  END 'Name'
                                                    FROM [InspectionsCertificates] tt8           
                                                    inner join  [Certificates] tt9 on tt8.CertificateUID=tt9.UID and tt9.Code=509
                                                    inner join  [Classes] tt10 on tt9.IssuedRoCode=tt10.Code
                                                      ) t8 on t1.UID=t8.InspectionUID
  WHERE MOU=@MoU 
	  AND DateOfInspect BETWEEN @From AND @To
	  AND Detention = @detention 
	  AND t2.HUID IN (
	  	  SELECT t2.HUID
		  FROM [Inspections] t1 
			INNER JOIN [Ships]  t2 ON t1.ShipUID = t2.UID 
		  WHERE MOU=@MoU  
			  AND DateOfInspect BETWEEN @From AND @To 
			  AND Detention = @detention
		  GROUP BY t2.HUID 
		  HAVING COUNT(*)>=@dets
	  )
   ORDER BY IMO_No, InspDate";
   
 $sql_1 = $SQL . $SQL1;
 $sql_2 = $SQL . $SQL2;
 

 $watch_list=[];
 
 $stmt = getSql($sql_1);

 while ($row = $stmt->fetch()) {
	 array_push($watch_list,[
			"Det"  		 						=> $row["Det"],
			"Name" 		 						=> $row["Name"],
			"IMO" 		 	 					=> $row["IMO"],
			"Flag" 		 						=> $row["Flag"],
			"CompanyName"					=> $row["CompanyName"],
			"CompanyIMO" 					=> $row["CompanyIMO"],
			"ShipStatus"					=> $row["ShipStatus"] 
	 ]);
 } 
 $perf_list=[];
 
 $stmt = getSql($sql_2);

	while ($row = $stmt->fetch()) {
	 array_push($perf_list,[
			"IMO_No"  		 				=> $row["IMO_No"],
			"Callsign" 		 				=> $row["Callsign"],
			"Dets" 		 	 					=> $row["Dets"],
			"ShipName" 		 				=> $row["ShipName"],
			"InspDate"		 				=> $row["InspDate"],
			"InspPlaceAndAuth"		=> $row["InspPlaceAndAuth"],
			"Company" 						=> $row["Company"] ,
			"ExternalID" 					=> $row["ExternalID"] ,
			"ShipFlag" 						=> $row["ShipFlag"] ,
			"ISM_DOC_issuing_RO"	=> $row["ISM_DOC_issuing_RO"] ,
			"HUID" 								=> $row["HUID"] 
	 ]);
 } 
	 echo json_encode(["watch_list"=>$watch_list, "perf_list"=>$perf_list]);
 ?>